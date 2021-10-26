<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Line;
use App\Models\LineRoute;
use App\Models\Route;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.line.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $assigned_route = LineRoute::pluck('route_id')->toarray();

        $route = Route::whereNotIn('id',$assigned_route)->pluck('route_name','id')->toarray();

        return view('admin.line.create',compact('route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
           'line_name'=>'required',
           'route_id'=>'required'
        ]);

        $store = new Line();
        $store->line_name = $request->line_name;
        $store->save();

        $route_id = $request->route_id;

        foreach ($route_id as $keys => $value) {
            $data2 = new LineRoute;
            $data2->line_id = $store->id;
            $data2->route_id = $route_id[$keys];
            $data2->save();
        }
        session()->flash('success_msg', 'लाइन सफलतापूर्वक जोड़ी गई');
        return redirect()->route('line.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {/*
        $allLineRoute = LineRoute::pluck('route_id')->toarray();
        $assigned = LineRoute::where('line_id', $id)->pluck('route_id')->toarray();

        $assigned_route = Route::whereIn('id',$assigned)->pluck('route_name','id')->toarray();

        $notAssigned_route = Route::whereNotIn('id',$allLineRoute)->pluck('route_name','id')->toarray();

        $route = array_merge($assigned_route, $notAssigned_route);

        $line = Line::find($id);
        $line_route =  LineRoute::where('line_id',$line->id)->join('routes','routes.id','line_routes.route_id')->get();

        foreach($line_route as $zoneCity)
        {
            $zoneCityIds[] = $zoneCity->id;
        }*/

        $route = Route::pluck('route_name','id')->toarray();
        $line = Line::find($id);
//        $line_route =  LineRoute::where('line_id',$line->id)
//            ->join('routes','routes.id','line_routes.route_id')->get();

        $AssignRoutes =  LineRoute::where('line_id', '!=', $id)->get('route_id');

        $line_route =  Route::leftjoin('line_routes','line_routes.route_id','routes.id')
            ->whereNotIn('line_routes.route_id',$AssignRoutes)->orWhereNull('route_id')
            ->select('routes.*')->pluck('route_name','id')->toArray();

        $selected = LineRoute::where('line_id', $id)->pluck('route_id')->all();


        return view('admin.line.edit',compact('line','line_route','route','selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $store = Line::find($id);
        $store->line_name = $request->line_name;
        $store->save();

        $route_id = $request->route_id;
        foreach ($store->lineRoute as $row){
            $row->delete();
        }
        foreach ($route_id as $keys => $value) {
            $data2 = new LineRoute;
            $data2->line_id = $store->id;
            $data2->route_id = $route_id[$keys];
            $data2->save();
        }

        session()->flash('success_msg', 'लाइन को सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('line.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Line::find($id);
        $delete->delete();
        return response()->json('success');
    }

    public function getData()
    {
        $data = Line::get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('line.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })

            ->rawColumns(['action'])->make(true);
    }
}
