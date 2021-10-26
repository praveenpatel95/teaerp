<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Salesman::select('name', 'id')->get();

        return view('admin.attendance.index', compact('attendance'));
    }

    public function store(Request $request)
    {
        $request->validate([
           'salesman_id'=>'required',
           'work_hours'=>'required',
           'attendance_date' =>'required'
        ]);

        $salary = Salesman::pluck('salary_amount')->toArray();

        $salesman = $request->salesman_id;
        $work_hours = $request->work_hours;
        $type = $request->type;
        $attendance_date = date('Y-m-d', strtotime($request->attendance_date));
        $commission = $request->commission;
        foreach ($salesman as $key => $value) {
            $data = Attendance::where(['attendance_date'=>$attendance_date, 'salesman_id'=>$salesman[$key]])->first();

            if (!$data)
            {
                $data = new Attendance;
                $data->salesman_id = $salesman[$key];
                $data->work_hours = $work_hours[$key];
                $data->commission = $commission[$key];
                $data->salary = $salary[$key]*$work_hours[$key];

                if ($type[$key] ?? null) {
                    $data->type = $type[$key];
                }
                else {
                    $data->type = 0;
                }
            }
            else
                {
                $data->work_hours = $work_hours[$key];
                $data->commission = $commission[$key];
                $data->salary = $salary[$key]*$work_hours[$key];
                if ($type[$key] ?? null) {
                    $data->type = $type[$key];
                }
            }
            $data->attendance_date = date('Y-m-d', strtotime($attendance_date));
            $data->save();
        }
        session()->flash('success_msg', 'अटेंडेंस सफलतापूर्वक जोड़ी गई');
        return redirect()->route('attendance.index');
    }

    public function attendanceData(Request $request)
    {
        $attendance_date = date('Y-m-d', strtotime($request->attendance_date));
        $isData = Attendance::where('attendance_date',$attendance_date)->first();

        if ($isData){
            $data = Salesman::leftjoin('attendances','attendances.salesman_id','salesmen.id')
                ->where('attendance_date',$attendance_date)->orwhere('attendance_date',null)
                ->selectRaw('sum(mprs.mprquantity) AS totalmpr')
                ->select('salesmen.*','salesmen.id as sId','attendances.*')
                ->get();
        }
        else{
            $data = Salesman::select('salesmen.id as sId','salesmen.name')
                ->get();
        }

        return view('admin.attendance.attendanceData', compact('data'));
    }

    public function attendanceviewData(Request $request){

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        $attendance_view = Salesman::leftjoin('attendances','attendances.salesman_id','salesmen.id')
            ->select('salesmen.*','salesmen.id as sId','attendances.*')
            ->whereBetween('attendances.attendance_date',[$startdate,$enddate])
            ->get();

        return DataTables::of($attendance_view)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->editColumn('attendance_date',function ($datatables){
                return date('d-M-Y',strtotime($datatables->attendance_date));
            })
            ->editColumn('type',function ($datatables){
                if($datatables->type==0)
                    return "<label class='text-primary'>उपस्थित</label>";
                elseif($datatables->type==1)
                    return "<label class='text-danger'>अनुपस्थित </label>";
                elseif($datatables->type==2)
                    return "<label class='text-warning'>छुट्टी</label>";
            })
            ->rawColumns(['action','attendance_date','type'])->make(true);
    }

}
