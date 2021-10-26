<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdvancePayment;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AdvancePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();
        return view('admin.advancepayment.index', compact('salesman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();
        return view('admin.advancepayment.create', compact('salesman'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'salesman_id' => 'required',
            'amount' => 'required',
            'adv_date' => 'required',
        ]);

        $store = new AdvancePayment();
        $store->salesman_id = $request->salesman_id;
        $store->amount = $request->amount;
        $store->adv_date = date('Y-m-d H:i:s', strtotime($request->adv_date));
        $store->remark = $request->remark;
        $store->save();
        session()->flash('success_msg', 'एडवांस पेमेंट  सफलतापूर्वक जोड़ा गया');
        return redirect()->route('advancepayment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = AdvancePayment::find($id);
        $salesman = Salesman::pluck('name', 'id')->toarray();

        return view('admin.advancepayment.edit',compact('data','salesman'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'salesman_id' => 'required',
            'amount' => 'required',
            'adv_date' => 'required',
        ]);

        $update = AdvancePayment::find($id);
        $update->salesman_id = $request->salesman_id;
        $update->amount = $request->amount;
        $update->adv_date = date('Y-m-d H:i:s', strtotime($request->adv_date));
        $update->remark = $request->remark;
        $update->save();
        session()->flash('success_msg', 'एडवांस पेमेंट  सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('advancepayment.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = AdvancePayment::find($id);
        $delete->delete();
        return response()->json('success');
    }

    public function getData(Request $request)
    {

        $data = AdvancePayment::leftjoin('salesmen','salesmen.id','advance_payments.salesman_id')
            ->select('advance_payments.*','salesmen.name');


        if($request->salesman_id){
            $data->where('salesmen.id',$request->salesman_id);
        }
        $data =   $data->get();

       $total_amount = $data->sum(DB::raw('amount'));

        return DataTables::of($data)
            ->addIndexColumn()
            ->with('total_amount',$total_amount)
            ->editColumn('adv_date', function ($datatables) {
                return date('j M, Y h:ia', strtotime($datatables->adv_date));
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('advancepayment.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action'])->make(true);



    }
}
