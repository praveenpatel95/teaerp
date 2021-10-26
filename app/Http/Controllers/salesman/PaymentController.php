<?php

namespace App\Http\Controllers\salesman;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\LineRoute;
use App\Models\Sale;
use App\Models\Salesman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{
    public function paidpaymentindex()
    {

        return view('salesman.payment.paidpayment');
    }

    public function getPaidPaymentData(Request $request)
    {
        $getdate = explode('-', $request->daterange);
        $startdate = date('Y-m-d', strtotime($getdate[0]));
        $enddate = date('Y-m-d', strtotime($getdate[1]));

        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->join('sale_payments', 'sale_payments.sale_id', 'sales.id')->whereIn('customers.route_id',$routes)->select('customers.*', 'customers.id as customer_id', 'sales.sale_date', 'sale_payments.*','sale_payments.id as payment_id')
            ->whereBetween('sale_payments.pay_date', [$startdate, $enddate]);
        $total = $query->sum('sale_payments.pay_amount');
        $query->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->with('pay_amount', $total)
            ->editColumn('pay_amount', function ($datatables) {
                return "<label class='text-success'>$datatables->pay_amount</label>";
            })
            ->editColumn('sale_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->sale_date));
            })
            ->editColumn('pay_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->pay_date));
            })
            ->editColumn('paymode', function ($datatables) {
                if ($datatables->paymode == 0)
                    return 'Cash';
                elseif ($datatables->paymode == 1)
                    return 'Account';
                elseif ($datatables->paymode == 2)
                    return 'Paytm';
                elseif ($datatables->paymode == 3)
                    return 'Phone Pay';
                elseif ($datatables->paymode == 4)
                    return 'Google Pay';
                else
                    return 'Adhar Card';
            })
            ->addColumn("DT_RowIndex", '')
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('paymentReceipts', $datatables->payment_id) . '" class="btn btn-default btn-primary " title="Edit"> <i class="zmdi zmdi-print"></i></a>';
            })
            ->rawColumns(['action', 'pay_amount', 'sale_date', 'pay_amount'])->make(true);
    }

    public function duepaymentindex()
    {
        $line = Line::pluck('line_name', 'id')->toarray();
        $salesman = Salesman::pluck('name', 'id')->toarray();

        return view('salesman.payment.duepayment', compact('line', 'salesman'));

    }

    public function getduePaymentData()
    {
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->leftjoin(DB::raw('(select sale_id, SUM(pay_amount) paid
                                from sale_payments GROUP BY sale_id) as a'), function ($query) {
                $query->on('a.sale_id', 'sales.id');
            })
            ->leftjoin(DB::raw('(select sale_id, SUM(total_price) total
                                from sale_products GROUP BY sale_id) as b'), function ($query) {
                $query->on('b.sale_id', 'sales.id');
            })
            ->where(DB::raw('paid'), '<', DB::raw('total'))->whereIn('customers.route_id',$routes);


        $query = $query->get();

        $total = $query->sum(DB::raw('total')) - $query->sum(DB::raw('paid'));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->with('due_amount', $total)
            ->editColumn('due_amount', function ($query) {
                $todays = date('Y-m-d');
                $sale_date = Carbon::parse($query->sale_date);
                $today = Carbon::parse($todays);
                $date_diff = $sale_date->diffInDays($today);

                $due = $query->total - $query->paid;
                if ($date_diff < 30)
                    return "<label class='text-info'>$due</label>";
                elseif ($date_diff > 30)
                    return "<label class='text-warning'>$due</label>";
                elseif ($date_diff > 60)
                    return "<label class='text-danger'>$due</label>";

            })
            ->addColumn("DT_RowIndex", '')
            ->editColumn('sale_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->sale_date));
            })
            ->editColumn('sale_days', function ($query) {
                $todays = date('Y-m-d');
                $sale_date = Carbon::parse($query->sale_date);
                $today = Carbon::parse($todays);
                $date_diff = $sale_date->diffInDays($today);
                return $date_diff;
            })

            ->rawColumns([ 'due_amount', 'sale_date', 'sale_days'])->make(true);

    }
}
