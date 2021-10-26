<?php
namespace App\helper;

use App\Models\Customer;
use App\Models\LineRoute;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;


class CommonClass
{
    static function getTotalAmount($id)
    {
        $data = LineRoute::where('line_id',$id)->pluck('route_id')->toarray();

        $customer  = Customer::whereIn('customers.route_id',$data)->pluck('id')->toarray();

        $total_amount = Sale::join('sale_products','sale_products.sale_id','sales.id')
            ->where('sale_products.status',0)->whereIn('sales.customer_id',$customer)
            ->sum('sale_products.total_price');

        return $total_amount;
    }

    static function getDuePaymentRouteWise($id)
    {

        $route_id = LineRoute::where('route_id',$id)->pluck('route_id')->toarray();

        $due_payment  = Customer::whereIn('customers.route_id',$route_id)->sum('customers.due_balance');

       /* $sale_id =Sale::join('sale_products','sale_products.sale_id','sales.id')->where('sale_products.status',0)->wherein('customer_id',$customer_id)->pluck('sales.id')->toarray();

        $total_amount = Sale::join('sale_products','sale_products.sale_id','sales.id')
            ->where('sale_products.status',0)->whereIn('sales.customer_id',$customer_id)
            ->sum('sale_products.total_price');

        $pay_amount = SalePayment::whereIn('sale_id',$sale_id)->sum('pay_amount');
        $due_payment = $total_amount - $pay_amount;*/

        return $due_payment;
    }


}
