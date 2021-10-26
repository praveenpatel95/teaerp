<?php

namespace App\Http\Controllers\salesman;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\LineRoute;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Egulias\EmailValidator\Warning\CFWSNearAt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){

        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->first();

        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();
        //for current salesman s customer
        $data = Customer::whereIn('route_id',$routes)->pluck('id')->toarray();

        $sale_id = Sale::whereIn('customer_id',$data)->pluck('id')->toarray();


        $current_month = date('m');

        //for totol sale
        $total_sale =SaleProduct::whereIn('sale_id',$sale_id)->sum('total_price');
        $total_sale_by_month =SaleProduct::whereIn('sale_id',$sale_id)->whereMonth('created_at',$current_month)->sum('total_price');

        //for due payment
        $pay_amount = SalePayment::whereIn('sale_id',$sale_id)->sum('pay_amount');
        $due_payment = $total_sale - $pay_amount;
        $pay_amount_by_month =SalePayment::whereIn('sale_id',$sale_id)->whereMonth('pay_date',$current_month)->sum('pay_amount');
        $due_payment_by_month =$total_sale_by_month - $pay_amount_by_month;

        //for total customer
        $total_customer = Customer::whereIn('route_id',$routes)->count('id');
        $total_customer_by_month = Customer::whereIn('route_id',$routes)->whereMonth('created_at',$current_month)->count('id');



        //for latest 5 sale
        $latest_sale = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')->whereIn('sales.customer_id',$data)->select('sales.*', 'customers.customer_name')->orderby('sales.id','desc')->take(5)->get();

        //for latest 5 payments
        $latest_payment = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')->join('sale_payments', 'sale_payments.sale_id', 'sales.id')->whereIn('sales.customer_id',$data)->select('customers.*', 'customers.id as customer_id', 'sales.sale_date', 'sale_payments.*')->orderby('sale_payments.id','desc')->take(5)->get();


        return view('salesman.home',compact('total_sale','total_sale_by_month','due_payment','due_payment_by_month','total_customer','total_customer_by_month','latest_sale','latest_payment','salemsan'));
    }
}
