<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.routes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.routes.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'route_name'=>'required'
        ]);

        $store = new Route();
        $store->route_name = $request->route_name;
        $store->save();
        session()->flash('success_msg', 'रूट सफलतापूर्वक जोड़ा गया');
        return redirect()->route('routes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $route = Route::find($id);
        return view('admin.routes.edit',compact('route'));
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
        $update = Route::find($id);
        $update->route_name = $request->route_name;
        $update->save();
        session()->flash('success_msg', 'रूट सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('routes.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Route::find($id);

        $delete->delete();
        return response()->json('success');
    }

    public function getData()
    {
        $data = Route::get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('routes.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })

            ->rawColumns(['action'])->make(true);
    }
}
