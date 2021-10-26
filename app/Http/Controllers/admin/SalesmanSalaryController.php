<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdvancePayment;
use App\Models\Attendance;
use App\Models\Sale;
use App\Models\Salesman;
use App\Models\SalesmanSalary;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SalesmanSalaryController extends Controller
{


      public function index(){

        return view('admin.salary.salary');
      }


    public function SalaryGetdata(Request $request)
    {
        $month = date('m', strtotime($request->month));

        $year = date('Y', strtotime($request->month));

        $attendance_view = Salesman::get();

        $total_amount = Attendance::whereMonth('attendance_date',$month)
            ->whereYear('attendance_date',$year)
            ->sum('salary');

        $sale_commission = Attendance::whereMonth('attendance_date',$month)
            ->whereYear('attendance_date',$year)
            ->sum('commission');

        $payment_commission = Sale::whereMonth('sale_date',$month)
            ->whereYear('sale_date',$year)
            ->sum('sale_commission');

        $total = $total_amount+$sale_commission+$payment_commission;

        return DataTables::of($attendance_view)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->with('total',$total)
            ->editColumn('work_hours', function ($datatables) use ($month,$year) {
                $work_hours = Attendance::where('salesman_id', $datatables->id)
                    ->whereMonth('attendance_date',$month)
                    ->whereYear('attendance_date',$year)
                    ->sum('work_hours');

                return $work_hours ;
            })
            ->editColumn('total_amount', function ($datatables) use ($month,$year) {
                $total_amount = Attendance::where('salesman_id', $datatables->id)
                    ->whereMonth('attendance_date',$month)
                    ->whereYear('attendance_date',$year)
                    ->sum('salary');

                return $total_amount ;
            })
            ->editColumn('sale_commission', function ($datatables) use ($month,$year) {
                $sale_commission = Attendance::where('salesman_id', $datatables->id)
                    ->whereMonth('attendance_date',$month)
                    ->whereYear('attendance_date',$year)
                    ->sum('commission');

                return $sale_commission ;
            })
            ->editColumn('payment_commission', function ($datatables) use ($month,$year) {
                $payment_commission = Sale::where('salesman_id', $datatables->id)
                    ->whereMonth('sale_date',$month)
                    ->whereYear('sale_date',$year)
                    ->sum('sale_commission');

                return $payment_commission ;
            })
            ->editColumn('total_salary', function ($datatables) use ($month,$year){
                $total_amount = Attendance::where('salesman_id', $datatables->id)
                    ->whereMonth('attendance_date',$month)
                    ->whereYear('attendance_date',$year)
                    ->sum('salary');

                $sale_commission = Attendance::where('salesman_id', $datatables->id)
                    ->whereMonth('attendance_date',$month)
                    ->whereYear('attendance_date',$year)
                    ->sum('commission');

                $payment_commission = Sale::where('salesman_id', $datatables->id)
                    ->whereMonth('sale_date',$month)
                    ->whereYear('sale_date',$year)
                    ->sum('sale_commission');

                return $total_amount + $sale_commission + $payment_commission ;

            })
            ->editColumn('advance_amount', function ($datatables) use ($month,$year){
                $advance_amount = AdvancePayment::where('salesman_id', $datatables->id)
                    ->whereMonth('adv_date',$month)
                    ->whereYear('adv_date',$year)
                    ->sum('amount');
                return $advance_amount ;
            })

            ->editColumn('due_amount', function ($datatables) use ($month,$year){

                $total_amount = Attendance::where('salesman_id', $datatables->id)
                    ->whereMonth('attendance_date',$month)
                    ->whereYear('attendance_date',$year)
                    ->sum('salary');

                $sale_commission = Attendance::where('salesman_id', $datatables->id)
                    ->whereMonth('attendance_date',$month)
                    ->whereYear('attendance_date',$year)
                    ->sum('commission');

                $payment_commission = Sale::where('salesman_id', $datatables->id)
                    ->whereMonth('sale_date',$month)
                    ->whereYear('sale_date',$year)
                    ->sum('sale_commission');

                $advance_amount = AdvancePayment::where('salesman_id', $datatables->id)
                    ->whereMonth('adv_date',$month)
                    ->whereYear('adv_date',$year)
                    ->sum('amount');
                return $total_amount + $sale_commission + $payment_commission - $advance_amount;
            })
            ->rawColumns(['action', 'attendance_date', 'type'])->make(true);
    }


}
