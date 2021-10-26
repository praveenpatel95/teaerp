<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AssignStock;
use App\Models\Attendance;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ReturnStock;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AssignStockController extends Controller
{
    public function index()
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();
        return view('admin.assignstock.index', compact('salesman'));
    }

    public function create()
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();
        $product = Product::pluck('product_name', 'id')->toarray();
        return view('admin.assignstock.create', compact('salesman', 'product'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'salesman_id' => 'required|integer',
            'assign_date' => 'required|date',
        ]);
        DB::beginTransaction();
        try {

            $salesman_id = $request->salesman_id;
            $product_id = $request->product_id;
            $qty = $request->qty;
            $assign_date = $request->assign_date;
            foreach ($product_id as $key => $value) {
                $store = new AssignStock();
                $store->salesman_id = $salesman_id;
                $store->product_id = $product_id[$key];
                $store->qty = $qty[$key];
                $store->assign_date = date('Y-m-d', strtotime($assign_date));
                $this->instockStore($product_id[$key], $qty[$key]);
                $store->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('danger_msg', $e->getMessage());
            return redirect()->route('assignstock.index');
            //dd($e->getMessage());
        }

        session()->flash('success_msg', 'स्टॉक सफलतापूर्वक असाइन किया गया');
        if ($request->get('submit') == '1') {
            return redirect()->route('assignstock.index');
        }
        if ($request->get('submit') == '2') {
            return redirect()->route('assignstock.create');
        }

    }

    public function instockStore($product_id, $qty)
    {
            $in_stock = Product::where('id',$product_id)->first();
            $in_stock->in_stock -= $qty;
            $in_stock->save();
    }

    public function edit($id)
    {

        $salesman = Salesman::pluck('name', 'id')->toarray();
        $product = Product::pluck('product_name', 'id')->toarray();
        $assign_stock = AssignStock::find($id);

        return view('admin.assignstock.edit', compact('assign_stock', 'salesman', 'product'));


    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'salesman_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty' => 'required',
            'assign_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {

            $update = AssignStock::find($id);
            $update->salesman_id = $request->salesman_id;
            if ($update->product_id != $request->product_id) {

                $TiData = Product::find($request->product_id);
                $TiData->in_stock -= $request->qty;
                $TiData->save();

                $TiData1 = Product::find($update->product_id);
                $TiData1->in_stock += $update->qty;
                $TiData1->save();

                $update = AssignStock::find($id);
                $update->product_id = $request->product_id;
                $update->qty = $request->qty;
                $update->save();
            }
            $update->product_id = $request->product_id;
            $update->qty = $request->qty;
            $update->assign_date = date('Y-m-d', strtotime($request->assign_date));
            $this->instockUpdate($request->product_id, $request->qty);
            $update->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('danger_msg', $e->getMessage());
            return redirect()->route('assignstock.index');
            /*  dd($e->getMessage());*/
        }
        session()->flash('success_msg', 'असाइन किया गया स्टॉक सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('assignstock.index');

    }

    public function instockUpdate($product_id, $qty)
    {
        $assign_stock = AssignStock::where('product_id', $product_id)->first();
        $in_stock = Product::where('id', $product_id)->first();

        if ($assign_stock->in_stock == $qty) {

            $in_stock->save();
        } elseif ($assign_stock->qty > $qty) {
            $in_stock->in_stock = $in_stock->in_stock + $assign_stock->qty - $qty;
            $in_stock->save();
        } elseif ($assign_stock->qty < $qty) {
            $in_stock->in_stock = $in_stock->in_stock + $assign_stock->qty - $qty;

            $in_stock->save();
        }
    }


    public function destroy($id)
    {
        $delete = AssignStock::find($id);
        $AStockDelId = $delete->product_id;
        $this->AssignStockkDelete($AStockDelId);
        $delete->delete();
        return response()->json('success');
    }

    public function AssignStockkDelete($AStockDelId)
    {
        $assign_stock = AssignStock::where('product_id', $AStockDelId)->first();
        $in_stock = Product::where('id', $AStockDelId)->first();
        $in_stock->in_stock += $assign_stock->qty;
        $in_stock->save();
    }

    public function getData(Request $request)
    {
        $salesman_id = $request->salesman_id;

        $query = AssignStock::leftjoin('products', 'products.id', 'assign_stocks.product_id')
            ->leftjoin('salesmen', 'salesmen.id', 'assign_stocks.salesman_id')
            ->orderBy('assign_stocks.assign_date', 'desc')
            ->select('assign_stocks.*', 'products.product_name', 'products.in_stock', 'products.weight', 'salesmen.name');

        if ($salesman_id) {
            $query->where('assign_stocks.salesman_id', $salesman_id);
        }
        $query = $query->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->editColumn('assign_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->assign_date));
            })
            ->addColumn('total_weight', function ($datatables) {
                return $datatables->qty * $datatables->weight . ' Kg';
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('assignstock.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function maxAssignStock(Request $request)
    {
        $product_id = $request->product_id;
        $in_stock = Product::where('id', $product_id)->select('in_stock')->first();


        return $in_stock;

    }

}
