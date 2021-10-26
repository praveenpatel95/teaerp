<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Salesman;
use Illuminate\Http\Request;

class ExtraWorkCommissionController extends Controller
{
    public function extraWorkSalesmanGetData()
    {
        $today = date('Y-m-d');

        $data = Attendance::where('work_hours', '>', 8)
            ->where('attendance_date',$today)
            ->get();



    }
}
