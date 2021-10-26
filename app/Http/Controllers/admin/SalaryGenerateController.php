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
use function Symfony\Component\String\s;

class SalaryGenerateController extends Controller
{

   public function salesmanSalary()
    {

        return view('admin.salary.generatedSalary');
    }


    public function salesmanSalaryGetdata(Request $request)
    {
        $date = date('Y-m', strtotime($request->month));


        $data = SalesmanSalary::leftjoin('salesmen', 'salesmen.id', 'salesman_salaries.salesman_id')
            ->where('salary_month', $date)

            ->select('salesman_salaries.*', 'salesmen.name')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->editColumn('due_amount', function ($datatables) {
                return $datatables->total_amount - $datatables->advance_amount;
            })
            ->addColumn('action', function ($datatables) {
                if($datatables->pay_status ==0)
                    return '<button onclick="PayIt(' . $datatables->id . ')" class="btn btn-primary btn-sm " title="Pay">  Pay</button>
';
                else
                    return '<button  class="btn btn-success  btn-sm"> Paid</button>';
            })
            ->rawColumns(['action'])->make(true);
    }


    public function getSalarayData(Request $request)
    {

        $month = date('m', strtotime($request->mnth));

        $year = date('Y', strtotime($request->mnth));

        $salesmanData = Salesman::get();

        $totalAmount = Attendance::whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->sum('salary');

        $saleCommission = Attendance::whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->sum('commission');

        $paymentCommission = Sale::whereMonth('sale_date', $month)
            ->whereYear('sale_date', $year)
            ->sum('sale_commission');

        $totalAmount = $totalAmount + $saleCommission + $paymentCommission;

        return view('admin.salary.salaryData', compact('salesmanData', 'totalAmount', 'month', 'year'));
    }

    public function salaryStore(Request $request)
    {

        $salary_month = SalesmanSalary::where('salary_month', date('Y-m', strtotime($request->month)))->pluck('salary_month')->toarray();

        if ($salary_month) {
            session()->flash('danger_msg',$request->month. 'का वेतन पहले से ही Generated हे');
            return redirect()->back();
        }

        else {
            $salesman_id = $request->salesman_id;
            $salary_amount = $request->salary_amount;
            $work_hours = $request->work_hours;
            $total_salary = $request->salary;
            $commission = $request->commission;
            $sale_commission = $request->sale_commission;
            $total_amount = $request->total_amount;
            $advance_amount = $request->advance_amount;
            foreach ($salesman_id as $key => $value) {
                $salaryStore = new SalesmanSalary();
                $salaryStore->salesman_id = $salesman_id[$key];
                $salaryStore->salary_amount = $salary_amount[$key];
                $salaryStore->work_hours = $work_hours[$key];
                $salaryStore->total_salary = $total_salary[$key];
                $salaryStore->commission = $commission[$key];
                $salaryStore->sale_commission = $sale_commission[$key];
                $salaryStore->total_amount = $total_amount[$key];
                $salaryStore->advance_amount = $advance_amount[$key];
                $salaryStore->salary_month = date('Y-m', strtotime($request->month));

                $salaryStore->save();
            }
            session()->flash('success_msg',$request->month.'का वेतन Generate किया गया,');
        }
        return redirect()->route('salesmanSalary.index');
    }

    public function salarypay($id)
    {
        $update_pay_status = SalesmanSalary::find($id);
        $update_pay_status->pay_status = 1;
        $update_pay_status->save();

        return redirect()->back();
    }


}
