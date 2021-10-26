<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\LineRoute;
use App\Models\Route;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LineHisabController extends Controller
{
    public function index()
    {
        $line_hisab = Line:: leftjoin(DB::raw('(select line_id, COUNT(route_id) TOTALROUTE
                                from line_routes GROUP BY line_id) as a'), function ($query) {
            $query->on('a.line_id', 'lines.id');
        })->get();


        $data = LineRoute::join('routes', 'routes.id', 'line_routes.route_id')
            ->leftjoin(DB::raw('(select route_id, COUNT(customer_name) TOTALCUSTOMER
                                from customers GROUP BY route_id) as a'), function ($query) {
                $query->on('a.route_id', 'routes.id');
            })->
            whereNotNull('TOTALCUSTOMER')->select('line_routes.route_id', 'routes.route_name', 'TOTALCUSTOMER')->get();


        return view('admin.linehisab.linehisab', compact('line_hisab', 'data'));
    }

    public function getLineHisabData()
    {
        $data = LineRoute::join('routes', 'routes.id', 'line_routes.route_id')
            ->leftjoin(DB::raw('(select route_id, COUNT(customer_name) TOTALCUSTOMER
                                from customers GROUP BY route_id) as a'), function ($query) {
                $query->on('a.route_id', 'routes.id');
            })->
            whereNotNull('TOTALCUSTOMER')->select('line_routes.route_id', 'routes.route_name', 'TOTALCUSTOMER');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->addColumn('due_amount', function ($datatables) {
                $value = \App\helper\CommonClass::getDuePaymentRouteWise($datatables->route_id);
                if ($value > 0)
                    return "<label style='color: red'> $value </label>";
                else
                    return "<label style='color: green'> $value </label>";
            })
            ->rawColumns(['due_amount'])->make(true);
    }


}


