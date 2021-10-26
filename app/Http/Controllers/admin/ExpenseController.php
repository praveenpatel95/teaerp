<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salesman = Salesman::pluck('name','id')->toarray();
        return view('admin.expense.index',compact('salesman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $salesman = Salesman::pluck('name','id')->toarray();
        return view('admin.expense.create',compact('salesman'));
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
           'expense_name'=>'required',
           'amount'=>'required',
            'expense_date'=>'required',
        ]);

        $store = new Expense();
        $store->expense_name = $request->expense_name;
        $store->salesman_id = $request->salesman_id;
        $store->amount = $request->amount;
        $store->expense_date = date('Y-m-d',strtotime($request->expense_date));
        $store->detail = $request->detail;
        $store->save();
        session()->flash('success_msg','खर्च सफलतापूर्वक जोड़ा गया');
        return redirect()->route('expense.index');
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
        $salesman = Salesman::pluck('name','id')->toarray();
        $expense = Expense::find($id);
        return view('admin.expense.edit',compact('expense','salesman'));
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
        $request->validate([
            'expense_name'=>'required',
            'amount'=>'required',
            'expense_date'=>'required',
        ]);

        $update = Expense::find($id);
        $update->expense_name = $request->expense_name;
        $update->salesman_id = $request->salesman_id;
        $update->amount = $request->amount;
        $update->expense_date = date('Y-m-d',strtotime($request->expense_date));
        $update->detail = $request->detail;
        $update->save();
        session()->flash('success_msg','खर्च सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('expense.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Expense::find($id);
        $delete->delete();
        return response()->json('success');
    }

    public function getExpenseData(Request $request)
    {
        $salesman_id  = $request->salesman_id;

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));
        $data = Expense::leftjoin('salesmen', 'salesmen.id', 'expenses.salesman_id')
            ->select('expenses.*', 'salesmen.name');

        if ($salesman_id) {
            $data->where('expenses.salesman_id',$salesman_id);
        }
        if ($request->startDate && $request->endDate) {
            $data->whereBetween('expenses.expense_date', [$startdate, $enddate]);
        }
        $data = $data->get();


        $total = $data->sum(DB::raw('amount'));


        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->with('total_expense', $total)
            ->editColumn('expense_date', function ($datatables){
              return date('d-M-Y',strtotime($datatables->expense_date));
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('expense.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action','expense_date'])->make(true);
    }
}
