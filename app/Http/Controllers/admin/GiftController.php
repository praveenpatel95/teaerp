<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Gift;
use App\Models\GiftProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GiftController extends Controller
{

    public function index()
    {
        $gift_product = GiftProduct::first();


        return view('admin.gift.index', compact('gift_product'));
    }

    public function giftAssign()
    {
        $gift_product = GiftProduct::first();

        $startDate = Carbon::today()->subMonth(6);
        $endDate = Carbon::today();

        $customer = Customer::where('customer_type', 0)
            ->where('due_balance', '<=', 0)
            ->orwhere('due_balance', null)
            ->get();

        foreach ($customer as $row) {

            $gift_assign = Gift::where('customer_id', $row->id)
                ->latest('gift_date')
                ->first();

            $sale = Sale::where('customer_id', $row->id)->pluck('id')->toarray();
            $sale_product = SaleProduct::whereIn('sale_id', $sale)->sum('qty');


            if ($gift_assign) {
                // if gift assign time is more than 6 month then gift assign
                $req_date = date('Y-m-d', strtotime($gift_assign->gift_date . ' + 6 month'));

                if ($sale_product >= $gift_product->gift_qty) {
                    $gift_store = Sale::join('sale_products', 'sale_products.sale_id', 'sales.id')
                        ->leftjoin('gifts', 'gifts.customer_id', 'sales.customer_id')
                        ->leftjoin(DB::raw('(select sale_id,SUM(qty) SaleQty
                    from sale_products GROUP BY sale_id) as b'),
                            function ($gift_store) {
                                $gift_store->on('b.sale_id', 'sales.id');
                            })
                        ->where('sales.customer_id', $row->id)
                        ->wheredate('sales.sale_date', '>=', $req_date)
                        ->whereBetween('sales.sale_date', [$startDate, $endDate])
                        ->where('SaleQty', '>=', $gift_product->gift_qty)
                        ->latest('gifts.gift_date')
                        ->first();


                    if ($gift_store) {

                        $gift_assign = new Gift();
                        $gift_assign->customer_id = $gift_store->customer_id;
                        $gift_assign->gift_product = $gift_product->product_name;
                        $gift_assign->price = $gift_product->price;
                        $gift_assign->gift_date = date('Y-m-d');
                        $gift_assign->save();
                    }
                }

            } else {


                if ($sale_product >= $gift_product->gift_qty) {

                    $gift_new = Sale::join('sale_products', 'sale_products.sale_id', 'sales.id')
                        ->where('sales.customer_id', $row->id)
                        ->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startDate, $endDate])
                        ->first();

                    $gift_assign = new Gift();
                    $gift_assign->customer_id = $gift_new->customer_id;
                    $gift_assign->gift_product = $gift_product->product_name;
                    $gift_assign->price = $gift_product->price;
                    $gift_assign->gift_date = date('Y-m-d');
                    $gift_assign->save();
                }
            }
        }
    }

    public function giftCustomer()
    {
        $query = Gift::leftjoin('customers', 'customers.id', 'gifts.customer_id')
            ->get();

        $total_gift_amount = $query->sum('price');

        return DataTables::of($query)
            ->addIndexColumn()
            ->with('total_gift_amount',$total_gift_amount)
            ->editColumn('gift_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->gift_date));
            })
            ->editColumn('status', function ($datatables) {
                if ($datatables->gift_status == 1) {
                    return "<label style='color: green'>Dispatched</label>";
                }
                else {
                    return "<label style='color: red'>Pending</label>";
                }
            })
            ->addColumn("DT_RowIndex", '')
            ->rawColumns(['status'])->make(true);
    }

    public function giftProdcutUpdate(Request $request)
    {
        $update = GiftProduct::first();
        $update->product_name = $request->product_name;
        $update->price = $request->price;
        $update->gift_qty = $request->gift_qty;
        $update->save();
        session()->flash('success_msg', 'गिफ्ट प्रोडक्ट सफलतापूर्वक अपडेट किया गया');
        return redirect()->back();

    }
}

