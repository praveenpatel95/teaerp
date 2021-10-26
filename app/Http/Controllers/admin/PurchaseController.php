<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\ReturnStock;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = Product::pluck('product_name', 'id')->toarray();

        return view('admin.purchase.create', compact('product'));
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
            'product_id' => 'required',
            'qty' => 'required',
            'purchase_price' => 'required',
            'purchase_date' => 'required|date',
        ]);
        $store = new Purchase();
        $store->product_id = $request->product_id;
        $store->qty = $request->qty;
        $store->purchase_price = $request->purchase_price;
        $store->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $store->remark = $request->remark;
        $this->PurchaseinstockStore($request->product_id, $request->qty);
        $store->save();
        session()->flash('success', 'सफलतापूर्वक जोड़ा गया');
        return redirect()->route('purchase.index');
    }

    public function PurchaseinstockStore($product_id, $qty)
    {

        $in_stock = Product::where('id', $product_id)->first();
        $in_stock->in_stock = $in_stock->in_stock + $qty;
        $in_stock->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::pluck('product_name', 'id')->toarray();
        $purchase = Purchase::find($id);

        return view('admin.purchase.edit', compact('purchase', 'product'));
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
            'product_id' => 'required',
            'qty' => 'required',
            'purchase_price' => 'required',
            'purchase_date' => 'required|date',
        ]);

        $update = Purchase::find($id);
        if ($update->product_id != $request->product_id) {

            $TiData = Product::find($request->product_id);
            $TiData->in_stock += $request->qty;
            $TiData->save();

            $TiData1 = Product::find($update->product_id);
            $TiData1->in_stock -= $update->qty;
            $TiData1->save();

            $update = Purchase::find($id);
            $update->product_id = $request->product_id;
            $update->qty = $request->qty;
            $update->save();
        }

        $update->product_id = $request->product_id;
        $update->qty = $request->qty;
        $update->purchase_price = $request->purchase_price;
        $update->purchase_date = date('Y-m-d', strtotime($request->purchase_date));
        $update->remark = $request->remark;
        $this->PurchaseinstockUpdate($request->product_id, $request->qty);
        $update->save();
        session()->flash('success', 'सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('purchase.index');

    }

    public function PurchaseinstockUpdate($product_id, $qty)
    {
        $purchase = Purchase::where('product_id', $product_id)->first();
        $in_stock = Product::where('id', $product_id)->first();


        if ($purchase->qty == $qty) {
            $in_stock->save();
        } elseif ($purchase->qty > $qty) {
            $in_stock->in_stock = $in_stock->in_stock + $qty - $purchase->qty;

            $in_stock->save();
        } elseif ($purchase->qty < $qty) {
            $in_stock->in_stock = $in_stock->in_stock + $qty - $purchase->qty;

            $in_stock->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Purchase::find($id);
        $PStockDelId = $delete->product_id;
        $this->PurchaseStockkDelete($PStockDelId);
        $delete->delete();
        return response()->json('successs');

    }

    public function PurchaseStockkDelete($PStockDelId)
    {
        $purchase_stock = Purchase::where('product_id', $PStockDelId)->first();
        $in_stock = Product::where('id', $PStockDelId)->first();
        $in_stock->in_stock = $in_stock->in_stock - $purchase_stock->qty;
        $in_stock->save();
    }

    public function getData()
    {
        $query = Purchase::leftjoin('products', 'products.id', 'purchases.product_id')
            ->orderBy('purchases.purchase_date','desc')
            ->select('purchases.*', 'products.product_name')
            ->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->editColumn('purchase_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->purchase_date));
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('purchase.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
