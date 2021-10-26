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

class PaymentController extends Controller
{
    /*Payment List*/
    public function getData(Request $request)
    {
        $salesmanData = Salesman::where('user_id', Auth::id())->first();
        $salesPayment = SalePayment::where('salesman_id', $salesmanData->id)->get();
        return response()->json($salesPayment);
    }

    /*Payment Store*/
    public function Store(Request $request)
    {
        $rules = [
            'paymode' => 'required',
            'pay_amount' => 'required',
            'pay_date' => 'required',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $sale = Sale::find($request->sale_id);

        $store = new SalePayment();
        $store->sale_id = $request->sale_id;
        $store->salesman_id = $sale->salesman_id;
        $store->paymode = $request->paymode;
        $store->pay_amount = $request->pay_amount;
        $store->pay_date = date('Y-m-d h:i:s', strtotime($request->pay_date));
        $store->rec_no = $this->rec_no();
        $store->remark = $request->remark;
        $store->save();
        $this->CustomerDueStorePayTime($request->sale_id, $store->pay_amount);

        $sale = Sale::find($store->sale_id);
        $this->Commisssion($sale->salesman_id, $sale->id);

        return response()->json('success', 200);

    }

    public function CustomerDueStorePayTime($sale_id, $pay_amount)
    {
        $sale = Sale::find($sale_id);

        $due_amount_update = Customer::find($sale->customer_id);
        $due_amount_update->due_balance -= $pay_amount;
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

}
