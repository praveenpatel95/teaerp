<?php

namespace App\Http\Controllers\salesman;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoice_no()
    {
        $invoice_no = Sale::orderby('id', 'desc')->count();
        if ($invoice_no) {
            $invoice_no = $invoice_no + 1;
        } else {
            $invoice_no = 1;
        }
        return str_pad($invoice_no, 3, "0", STR_PAD_LEFT);
    }

    public function index($id)
    {
        $invoice_no =$this->invoice_no();

        $sale = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')->where('sales.id', $id)->select('sales.*', 'customers.customer_name','customers.address','customers.mobile_no')->first();

        $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')->where('sale_products.sale_id', $id)->select('sale_products.*', 'products.product_name', 'products.unite')->get();

        $sale_pay = SalePayment::where('sale_id', $id)->get();
        $sale_total_amount = SaleProduct::where('sale_id', $sale->id)->sum('total_price');
        $pay_amount = SalePayment::where('sale_id', $sale->id)->sum('pay_amount');

        return view('salesman.invoice.invoice',compact('invoice_no','sale','sale_product','sale_pay','sale_total_amount','pay_amount'));

    }
}
