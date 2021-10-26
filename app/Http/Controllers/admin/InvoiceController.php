<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function index($id)
    {
        $sale = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')->where('sales.id', $id)->select('sales.*', 'customers.customer_name','customers.address','customers.mobile_no')->first();

        $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')->where('sale_products.sale_id', $id)->where('sale_products.status',0)->select('sale_products.*', 'products.product_name', 'products.unite')->get();

        $sale_pay = SalePayment::where('sale_id', $id)->get();
        $sale_total_amount = SaleProduct::where('sale_id', $sale->id)->where('status',0)->sum('total_price');
        $pay_amount = SalePayment::where('sale_id', $sale->id)->sum('pay_amount');

        return view('admin.invoice.invoice',compact('sale','sale_product','sale_pay','sale_total_amount','pay_amount'));

    }
}
