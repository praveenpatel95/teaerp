<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{

    /*Sale List*/
    public function getData(Request $request)
    {
        $salesmanData = Salesman::where('user_id', Auth::id())->first();
        $sales = Sale::where('salesman_id', $salesmanData->id)->get();
        return response()->json($sales);
    }

    /*Sale Store*/
    public function Store(Request $request)
    {
        $rules = [
            'customer_id' => 'required',
            'salesman_id' => 'required',
            'sale_date' => 'required|date',
            'sale_no' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'total_price' => 'required',
            'total_amount' => 'required',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $store = new Sale();
        $store->customer_id = $request->customer_id;
        $store->salesman_id = $request->salesman_id;
        $store->sale_date = date('Y-m-d H:i:s', strtotime($request->sale_date));
        $store->sale_no = $request->sale_no;
        $store->remark = $request->remark;
        $store->total_amount = $request->total_amount;
        $store->save();

        //for store return date
        $customer = Customer::find($request->customer_id);
        $customer->return_date = date('Y-m-d', strtotime($request->return_date));
        $customer->save();

        //sale product store
        $product_id = $request->product_id;
        $qty = $request->qty;
        $price = $request->price;
        $total_price = $request->total_price;
        foreach ($product_id as $key => $value) {
            $data2 = new SaleProduct();
            $data2->sale_id = $store->id;
            $data2->product_id = $product_id[$key];
            $data2->qty = $qty[$key];
            $data2->price = $price[$key];
            $data2->total_price = $total_price[$key];
            $data2->save();
        }

        //sale payment store
        if ($request->pay_amount) {
            $sale_payment = new SalePayment();
            $sale_payment->sale_id = $store->id;
            $sale_payment->salesman_id = $request->salesman_id;;
            $sale_payment->rec_no = $this->rec_no();
            $sale_payment->paymode = $request->paymode;
            $sale_payment->pay_amount = $request->pay_amount;
            $sale_payment->pay_date = date('Y-m-d H:i:s', strtotime($request->pay_date));
            $sale_payment->remark = $request->remarks;
            $sale_payment->save();
        }
        $this->Commisssion($request->salesman_id, $store->id);
        if ($request->pay_amount) {
            $this->CustomerDueStore($request->customer_id, $store->total_amount, $sale_payment->pay_amount);
        } else {
            $this->CustomerDueStore($request->customer_id, $store->total_amount, 0);
        }
        return response()->json('success', 200);

    }

    public function CustomerDueStore($customer_id, $total_price, $pay_amount)
    {
        $due_amount_update = Customer::find($customer_id);
        $due_amount_update->due_balance += $total_price - $pay_amount;
        $due_amount_update->save();
    }

    public function Commisssion($salesman_id, $id)
    {
        $salesman = Salesman::where('id', $salesman_id)->select('commission')->first();
        $total_sale_price = SaleProduct::where('sale_id', $id)->sum('total_price');
        $total_purchase_price = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
            ->where('sale_products.sale_id', $id)
            ->sum(DB::raw('sale_products.qty*products.purchase_price'));
        $total_paid = SalePayment::where('sale_id', $id)->sum('pay_amount');
        //80 % of total sale
        $max_per = $total_sale_price * 80 * 0.01;
        //if 80%amount < to paid amount
        if ($total_paid >= $max_per) {
            $commission = ($total_sale_price - $total_purchase_price) * $salesman->commission * 0.01;
            $sale = Sale::find($id);
            $sale->sale_commission = $commission;
            $sale->commission_date = date('Y-m-d');
            $sale->save();
        } else {
            $sale_e = Sale::find($id);
            $sale_e->sale_commission = 0;
            $sale_e->save();
        }
    }

    public function rec_no()
    {
        $rec_no = SalePayment::orderby('id', 'desc')->count();
        if ($rec_no) {
            $rec_no = $rec_no + 1;
        } else {
            $rec_no = 1;
        }
        return str_pad($rec_no, 3, "0", STR_PAD_LEFT);
    }

    public function saleProduct()
    {
        $salesmanData = Salesman::where('user_id', Auth::User()->id)->first();


        $sale_id = Sale::where('salesman_id',$salesmanData->id)->pluck('id')->toArray();

        $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
            ->whereIn('sale_products.sale_id', $sale_id)
            ->select('sale_products.*', 'products.product_name', 'products.unite')
            ->get();


        return response()->json($sale_product);
    }

}
