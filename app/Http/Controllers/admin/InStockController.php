<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Yajra\DataTables\DataTables;

class InStockController extends Controller
{
    public function index(){

        return view('admin.instock.index');

    }

    public function getData(){

        $query = Product::select('products.product_name','products.in_stock','products.weight')->where('in_stock', '>=' ,0 )->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('total_weight',function ($datatables)
            {
                return $datatables->in_stock * $datatables->weight.' Kg';
            })
            ->addColumn("DT_RowIndex", '')->make(true);

    }
}
