<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Instock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.product.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product.create');
    }

    public function code()
    {

        $barcode = Product::orderBy('id', 'desc')->value('barcode');

        if ($barcode) {
            $barcode = $barcode + 1;
        } else {
            $barcode = 1;
        }

        return $bar = str_pad($barcode, '6', '1', STR_PAD_LEFT);
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
            'product_name' => 'required',
            'weight' => 'required',
            'unite' => 'required',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'in_stock' => 'required|numeric'
        ]);

        $store = new Product();
        $store->barcode = $this->code();
        $store->product_name = $request->product_name;
        $store->weight = $request->weight;
        $store->unite = $request->unite;
        $store->purchase_price = $request->purchase_price;
        $store->sale_price = $request->sale_price;
        $store->in_stock = $request->in_stock;
        $store->save();

        session()->flash('success_msg', 'प्रोडक्ट  सफलतापूर्वक जोड़ा गया');
        return redirect()->route('product.index');
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
        $product = Product::find($id);

        return view('admin.product.edit', compact('product'));
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
            'product_name' => 'required',
            'unite' => 'required',
            'purchase_price' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'in_stock' => 'required|numeric'
        ]);

        $update = Product::find($id);
        $update->product_name = $request->product_name;
        $update->weight = $request->weight;
        $update->unite = $request->unite;
        $update->purchase_price = $request->purchase_price;
        $update->in_stock = $request->in_stock;
        $update->sale_price = $request->sale_price;
        $update->save();


        session()->flash('success_msg', 'प्रोडक्ट सफलतापूर्वक अपडेट किया गयाा');
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Product::find($id);
        $delete->delete();
        return response()->json('success');

    }

    public function getData()
    {
        $query = Product::all();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->editColumn('weight',function ($datatables){
                return $datatables->weight .' kg';
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('product.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
