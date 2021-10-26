<?php

namespace App\Http\Controllers\admin;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Line;
use App\Models\LineRoute;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\Salesman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Hub;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{

    public function paidpaymentindex()
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();

        return view('admin.payment.paidpayment', compact('salesman'));
    }

    public function getPaidPaymentData(Request $request)
    {
        $salesman_id = $request->salesman_id;

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->join('sale_payments', 'sale_payments.sale_id', 'sales.id')->select('customers.*', 'customers.id as customer_id', 'sales.sale_date', 'sales.sale_no','sales.total_amount' ,  'sale_payments.*', 'sale_payments.id as payment_id');
        $total = $query->sum('sale_payments.pay_amount');

        if($request->startDate && $request->endDate)
        {
            $query->whereBetween('sale_payments.pay_date', [$startdate, $enddate]);
        }
        if ($salesman_id) {
            $query->where('sale_payments.salesman_id', $salesman_id);
        }
        $query = $query->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->with('pay_amount', $total)
            ->editColumn('pay_amount', function ($datatables) {
                return "<label class='text-success'>$datatables->pay_amount</label>";
            })
            ->editColumn('sale_date', function ($datatables) {
                return date('j M, Y h:ia', strtotime($datatables->sale_date));
            })
            ->editColumn('pay_date', function ($datatables) {
                return date('j M, Y h:ia', strtotime($datatables->pay_date));
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
                return '<a href="' . route('paymentReceipt', $datatables->payment_id) . '" class="btn btn-default btn-success " title="Edit"> <i class="zmdi zmdi-print"></i></a>';
            })
            ->rawColumns(['action', 'pay_amount', 'sale_date', 'pay_amount'])->make(true);
    }

    public function duepaymentindex()
    {
        $line = Line::pluck('line_name', 'id')->toarray();
        $salesman = Salesman::pluck('name', 'id')->toarray();

        return view('admin.payment.duepayment', compact('line', 'salesman'));

    }

    public function getduePaymentData(Request $request)
    {
        //for  filter by line
        $line_id = $request->line;
        $data = LineRoute::where('line_id', $line_id)->pluck('route_id')->toarray();

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->leftjoin(DB::raw('(select sale_id, SUM(pay_amount) paid
                                from sale_payments GROUP BY sale_id) as a'), function ($query) {
                $query->on('a.sale_id', 'sales.id');
            })
            ->leftjoin(DB::raw('(select sale_id, SUM(total_price) total
                                from sale_products GROUP BY sale_id) as b'), function ($query) {
                $query->on('b.sale_id', 'sales.id');
            })
            ->where(DB::raw('paid'), '<', DB::raw('total'));

        //for filter by line
        if ($line_id) {
            $query->whereIn('customers.route_id', $data);
        }

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
                if ($date_diff <= 30)
                    return "<label class='text-success'>$due</label>";
                elseif (60 >= $date_diff)
                    return "<label class='text-info'>$due</label>";
                elseif (90 >= $date_diff)
                    return "<label class='text-warning'>$due</label>";
                elseif ($date_diff >= 90)
                    return "<label class='text-danger'>$due</label>";

            })
            ->editColumn('customer_id', function ($query) {

                $todays = date('Y-m-d');
                $sale_date = Carbon::parse($query->sale_date);
                $today = Carbon::parse($todays);
                $date_diff = $sale_date->diffInDays($today);

                if ($date_diff <= 30)
                    return "<button class='btn bgm-green btn-icon btn' >$query->customer_id</button>";
                elseif (60 >= $date_diff)
                    return "<button class='btn bgm-lightblue btn-icon btn' >$query->customer_id</button>";
                elseif (90 >= $date_diff)
                    return "<button class='btn bgm-amber btn-icon btn' >$query->customer_id</button>";
                elseif ($date_diff >= 90)
                    return "<button class='btn bgm-red btn-icon btn' >$query->customer_id</button>";

            })
            ->addColumn("DT_RowIndex", '')
            ->editColumn('sale_date', function ($datatables) {
                return date('j M, Y h:ia', strtotime($datatables->sale_date));
            })
            ->editColumn('sale_days', function ($query) {
                $todays = date('Y-m-d');
                $sale_date = Carbon::parse($query->sale_date);
                $today = Carbon::parse($todays);
                $date_diff = $sale_date->diffInDays($today);
                return $date_diff;
            })
            ->rawColumns(['due_amount', 'sale_date', 'sale_days', 'customer_id'])->make(true);
    }

    public function PaidPaymentExporttToExcel(Request $request)
    {
        $salesman_id = $request->salesman;

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->join('sale_payments', 'sale_payments.sale_id', 'sales.id')
            ->select('customers.id', 'customers.customer_name', 'customers.father_name', 'customers.mobile_no', 'sales.sale_date','sales.sale_no',
                DB::raw('(CASE WHEN sale_payments.paymode = "1" THEN "Cash" WHEN sale_payments.paymode = "2" THEN "Account" WHEN sale_payments.paymode = "3" THEN "Paytm" WHEN sale_payments.paymode = "4" THEN "Phone Pay"  ELSE "adhar card"  END) AS paymodes'), 'sale_payments.pay_date', 'sales.total_amount','sale_payments.pay_amount');
        if ($salesman_id) {
            $query->where('sale_payments.salesman_id', $salesman_id);
        }
        if ($request->startDate && $request->endDate) {
            $query->whereBetween('sale_payments.pay_date', [$startdate, $enddate]);
        }
        $data = $query->get();

        $headings = [
            'ग्राहक आईडी',
            'ग्राहक का नाम',
            'पिता का नाम ',
            'मोबाइल नंबर',
            'सेल की तिथी',
            'पेमेंट का तरीका',
            'पेमेंट की तिथि',
            'कुल राशि',
            'जमा राशि',
        ];
        return Excel::download(new ReportExport($data, $headings), 'PaidPaymentReport.xlsx');
    }

    public function DuePaymentExporttToExcel(Request $request)
    {
        //for  filter by line
        $line_id = $request->line;
        $data = LineRoute::where('line_id', $line_id)->pluck('route_id')->toarray();

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->leftjoin(DB::raw('(select sale_id, SUM(pay_amount) paid
                                from sale_payments GROUP BY sale_id) as a'), function ($query) {
                $query->on('a.sale_id', 'sales.id');
            })
            ->leftjoin(DB::raw('(select sale_id, SUM(total_price) total
                                from sale_products GROUP BY sale_id) as b'), function ($query) {
                $query->on('b.sale_id', 'sales.id');
            })
            ->where(DB::raw('paid'), '<', DB::raw('total'))
            ->select('customers.id as customer_id', 'customers.customer_name', 'customers.father_name','customers.mobile_no', 'sales.sale_date','sales.total_amount','paid', DB::raw('(sales.total_amount - paid) as due '));

        //for filter by line
        if ($line_id) {
            $query->whereIn('customers.route_id', $data);
        }
        $query = $query->get();

        $headings = [
            'ग्राहक आईडी',
            'ग्राहक का नाम',
            'पिता का नाम ',
            'मोबाइल नंबर',
            'सेल की तिथी',
            'कुल राशि',
            'जमा राशि',
            'बकाया राशि',
        ];
        return Excel::download(new ReportExport($query, $headings), 'DuePaymentReport.xlsx');
    }
}
