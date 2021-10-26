<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AssignStock;
use App\Models\ReturnStock;
use App\Models\Product;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ReturnStockController extends Controller
{
    public function index()
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();
        return view('admin.returnstock.index', compact('salesman'));
    }

    public function create()
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();
        $product = Product::pluck('product_name', 'id')->toarray();
        return view('admin.returnstock.create', compact('salesman', 'product'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'salesman_id' => 'required|integer',
            'return_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $salesman_id = $request->salesman_id;
            $product_id = $request->product_id;
            $qty = $request->qty;
            $return_date = $request->return_date;
            foreach ($product_id as $key => $value) {
                $store = new ReturnStock();
                $store->salesman_id = $salesman_id;
                $store->product_id = $product_id[$key];
                $store->qty = $qty[$key];
                $store->return_date = date('Y-m-d', strtotime($return_date));

                $this->returninstockStore($product_id[$key], $qty[$key]);
                $store->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('danger_msg', $e->getMessage());
            return redirect()->route('returnstock.index');
            //dd($e->getMessage());
        }

        session()->flash('success_msg', 'स्टॉक सफलतापूर्वक लौटाया  गया');
        if ($request->get('submit') == '1') {
            return redirect()->route('returnstock.index');
        }
        if ($request->get('submit') == '2') {
            return redirect()->route('returnstock.create');
        }

    }

    public function returninstockStore($product_id, $qty)
    {
        $in_stock = Product::where('id',$product_id)->first();
        $in_stock->in_stock += $qty;
        $in_stock->save();
    }

    public function edit($id)
    {

        $salesman = Salesman::pluck('name', 'id')->toarray();
        $product = Product::pluck('product_name', 'id')->toarray();
        $return_stock = ReturnStock::find($id);

        return view('admin.returnstock.edit', compact('return_stock', 'salesman', 'product'));


    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'salesman_id' => 'required|integer',
            'product_id' => 'required|integer',
            'qty' => 'required',
            'return_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {

            $update = ReturnStock::find($id);
            $update->salesman_id = $request->salesman_id;
            if ($update->product_id != $request->product_id) {

                $TiData = Product::find($request->product_id);
                $TiData->in_stock += $request->qty;
                $TiData->save();

                $TiData1 = Product::find($update->product_id);
                $TiData1->in_stock -= $update->qty;
                $TiData1->save();

                $update = ReturnStock::find($id);
                $update->product_id = $request->product_id;
                $update->qty = $request->qty;
                $update->save();
            }
            $update->product_id = $request->product_id;
            $update->qty = $request->qty;
            $update->return_date = date('Y-m-d', strtotime($request->return_date));
            $this->returninstockUpdate($request->product_id, $request->qty);
            $update->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('danger_msg', $e->getMessage());
            return redirect()->route('returnstock.index');
            //dd($e->getMessage());
        }
        session()->flash('success_msg', 'सफलतापूर्वक अपडेट किया गया');

        return redirect()->route('returnstock.index');

    }

    public function returninstockUpdate($product_id, $qty)
    {
        $return_stock = ReturnStock::where('product_id', $product_id)->first();
        $in_stock = Product::where('id', $product_id)->first();

        if ($return_stock->in_stock == $qty) {

            $in_stock->save();
        } elseif ($return_stock->qty > $qty) {
            $in_stock->in_stock = $in_stock->in_stock + $qty - $return_stock->qty;

            $in_stock->save();
        } elseif ($return_stock->qty < $qty) {
            $in_stock->in_stock = $in_stock->in_stock + $qty - $return_stock->qty;
            $in_stock->save();
        }


    }

    public function destroy($id)
    {

        $delete = ReturnStock::find($id);
        $RStockDelId = $delete->product_id;
        $this->returninstockDelete($RStockDelId);
        $delete->delete();
        return response()->json('success');
    }

    public function returninstockDelete($RStockDelId)
    {

        $return_stock = ReturnStock::where('product_id', $RStockDelId)->first();
        $in_stock = Product::where('id', $RStockDelId)->first();

        $in_stock->in_stock = $in_stock->in_stock - $return_stock->qty;
        $in_stock->save();

    }


    public function getData(Request $request)
    {
        $salesman_id = $request->salesman_id;
        $query = ReturnStock::leftjoin('products', 'products.id', 'return_stocks.product_id')
            ->leftjoin('salesmen', 'salesmen.id', 'return_stocks.salesman_id')
            ->orderBy('return_stocks.return_date', 'desc')
            ->select('return_stocks.*', 'products.product_name', 'products.in_stock', 'products.weight', 'salesmen.name');
        if ($salesman_id) {
            $query->where('return_stocks.salesman_id', $salesman_id);
        }
        $query = $query->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->addColumn('total_weight', function ($datatables) {
                return $datatables->qty * $datatables->weight . ' Kg';
            })
            ->editColumn('return_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->return_date));
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('returnstock.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action'])->make(true);
    }

}
