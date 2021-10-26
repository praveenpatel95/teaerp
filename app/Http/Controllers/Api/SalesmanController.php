<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\LineRoute;
use App\Models\Route;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesmanController extends Controller
{

    /*Salesmen Create*/
    public function SalesmanStore(Request $request)
    {
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = bcrypt($request->password);
        $data->status = $request->status;
        $data->assignRole('salesman');
        $data->save();

        $store = new Salesman();
        $store->user_id = $data->id;
        $store->line_id = $request->line_id;
        $store->name = $request->name;
        $store->father_name = $request->father_name;
        $store->mobile_no = $request->mobile_no;
        $store->address = $request->address;
        $store->adhar_no = $request->adhar_no;
        $store->salary_type = $request->salary_type;
        $store->salary_amount = $request->salary_amount;
        $store->commission = $request->commission;
        $store->joining_date = date('Y-m-d', strtotime($request->joining_date));
        $store->password = $request->password;
        $store->save();
    }

    /*Salesman List*/
    public function getData(Request $request)
    {
        $salesmanData = User::with('salesman')->find($request->user_id);
        return response()->json($salesmanData);
    }

    /*Sales Create*/
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

    public function SalesStore(Request $request)
    {
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
            $sale_payment->remark = $request->remark;
            $sale_payment->save();
        }
        $this->Commisssion($request->salesman_id, $store->id);
        if ($request->pay_amount) {
            $this->CustomerDueStore($request->customer_id, $store->total_amount, $sale_payment->pay_amount);
        } else {
            $this->CustomerDueStore($request->customer_id, $store->total_amount, 0);
        }

    }

    /*Sale List*/
    public function salesmanSalesData(Request $request)
    {
        $salesmanData = Salesman::where('user_id', $request->user_id)->first();
        $sales = Sale::where('salesman_id', $salesmanData->id)->get();
        return response()->json($sales);
    }

    /*Payment List*/
    public function salesmanPaymentData(Request $request)
    {
        $salesmanData = Salesman::where('user_id', $request->user_id)->first();
        $salesPayment = SalePayment::where('salesman_id', $salesmanData->id)->get();
        return response()->json($salesPayment);
    }

    /*Attendance List*/
    public function salesmanAttendanceData(Request $request)
    {
        $salesmanData = Salesman::where('user_id', $request->user_id)->first();
        $salesAttendance = Attendance::where('salesman_id', $salesmanData->id)->get();
        return response()->json($salesAttendance);
    }

    /*Route List*/
    public function salesmanRouteData(Request $request)
    {
        $salesmanData = Salesman::where('user_id', $request->user_id)->first();
        $lineRoute = LineRoute::where('line_id', $salesmanData->line_id)->pluck('route_id')->toArray();
        $route = Route::whereIn('id', $lineRoute)->get();
        return response()->json($route);
    }
}
