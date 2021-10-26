<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Gift;
use App\Models\GiftProduct;
use App\Models\InterestSetting;
use App\Models\Sale;
use App\Models\SaleInterest;
use App\Models\SaleProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SaleInterestController extends Controller
{
    public function index()
    {

        $data = InterestSetting::first();

        return view('admin.saleinterest.index', compact('data'));
    }

    public function saleinterestSettingUpdate(Request $request)
    {
        $update = InterestSetting::first();
        $update->days = $request->days;
        $update->percent = $request->percent;
        $update->save();
        session()->flash('success_msg', 'सेल ब्याज सेटिंग सफलतापूर्वक अपडेट किया गया');
        return redirect()->back();

    }

    public function AddSaleInterest()
    {
        //interest setting data
        $setting_data = InterestSetting::first();


        //for getting set date by days
        $setting_date = Carbon::now()
            ->subDays($setting_data->days);


        //sale data
        $sale_data = Sale::leftjoin(DB::raw('(select sale_id, SUM(pay_amount) paid
                                from sale_payments GROUP BY sale_id) as a'), function ($sale_data) {
            $sale_data->on('a.sale_id', 'sales.id');
        })
            ->where('sales.sale_date', '<=', $setting_date)
            ->where('sales.is_interest', 0)
            ->where(DB::raw('sales.total_amount - paid  '), '>', 0)
            ->select('sales.*', DB::raw('sales.total_amount - paid as due'))
            ->get();




        foreach ($sale_data as $row) {
            //calculating interest
            $interest = $row->due * $setting_data->percent * 0.01;

            //storina interest data
            $store = new SaleInterest();
            $store->sale_id = $row->id;
            $store->amount = $row->due;
            $store->percentage = $setting_data->percent;
            $store->interest_amount = $interest;
            $store->interest_date = date('Y-m-d');
            $store->save();

            //update sale is_interest Status to 1 for recursive interest
            $update = Sale::find($row->id);
            $update->is_interest = 1;
            $update->last_recursive_date = date('Y-m-d');
            $update->save();

        }
    }

    public function AddSaleInterestRecursive()
    {
        //interest setting data
        $setting_data = InterestSetting::first();

        //for getting last month date from current date
        $last_month = Carbon::now()->subMonth(1);

        //sale data
        $sale_data = Sale::leftjoin(DB::raw('(select sale_id, SUM(pay_amount) paid
                                from sale_payments GROUP BY sale_id) as a'), function ($sale_data) {
            $sale_data->on('a.sale_id', 'sales.id');
        })
            ->where('sales.last_recursive_date', '<=', $last_month)
            ->where(DB::raw('sales.total_amount - paid  '), '>', 0)
            ->where('sales.is_interest', 1)
            ->select('sales.*', DB::raw('sales.total_amount - paid as due'))
            ->get();


        foreach ($sale_data as $row) {

            //calculating interest
            $interest = $row->due * $setting_data->percent * 0.01;

            //storina interest data
            $store = new SaleInterest();
            $store->sale_id = $row->id;
            $store->amount = $row->due;
            $store->percentage = $setting_data->percent;
            $store->interest_amount = $interest;
            $store->interest_date = date('Y-m-d');
            $store->save();

            //update sale is_interest Status to 1 for recursive interest
            $update = Sale::find($row->id);
            $update->last_recursive_date = date('Y-m-d');
            $update->save();

        }
    }

    public function SaleInterestData()
    {
        $query = SaleInterest::leftjoin('sales', 'sales.id', 'sale_interests.sale_id')
            ->leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->select('sale_interests.*', 'customers.customer_name', 'sales.sale_no')
            ->get();
        $interest = $query->sum(DB::raw('interest_amount'));
        return DataTables::of($query)
            ->addIndexColumn()
            ->with('interest',$interest)
            ->editColumn('interest_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->interest_date));
            })
            ->addColumn("DT_RowIndex", '')
            ->make(true);
    }

    public function IntererstReceiptNo(){
        $rec_no = SaleInterest::orderby('id', 'desc')->where('status',1)->value('rec_no');
        if ($rec_no) {
            $rec_no = $rec_no + 1;
        } else {
            $rec_no = 1;
        }
        return str_pad($rec_no, 3, "0", STR_PAD_LEFT);
    }



    public function InterestSubmit($id)
    {

        $data = SaleInterest::find($id);
        $data->status = 1;
        $data->rec_no = $this->IntererstReceiptNo();
        $data->save();
        return redirect()->back();

    }

    public function SaleInterestReceipt($id)
    {
        $sale_interest = SaleInterest::where('status',1)->find($id);

        $sale = Sale::where('sales.id', $sale_interest->sale_id)->first();
        $customer = Customer::where('customers.id', $sale->customer_id)->first();

        return view('admin.saleinterestReceipt.index', compact('sale_interest', 'sale', 'customer'));
    }
}
