<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Gift;
use App\Models\LineRoute;
use App\Models\User;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    /*Customer list*/
    public function getData(Request $request)
    {
        $user = User::find($request->user_id);
        $customerData = $user->salesman;
        $route = LineRoute::where('line_id', $customerData->line_id)->pluck('route_id')->toArray();
        $customer = Customer::whereIn('route_id', $route)->get();
        return response()->json($customer);
    }

    /* Customer Store*/
    public function order_no()
    {
        $order_no = Customer::orderby('id', 'desc')->count();
        if ($order_no) {
            $order_no = $order_no + 1;
        } else {
            $order_no = 1;
        }
        return str_pad($order_no, 1, "0", STR_PAD_LEFT);
    }

    public function Store(Request $request)
    {

        $rules = [
            'route_id' => 'required|integer',
            'customer_name' => 'required|string',
            'father_name' => 'required|string',
            'status' => 'required',
            'customer_type' => 'required'
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $store = new Customer();
        $store->route_id = $request->route_id;
        $store->customer_name = $request->customer_name;
        $store->father_name = $request->father_name;
        $store->mobile_no = $request->mobile_no;
        $store->adhar_no = $request->adhar_no;
        $store->due_balance = $request->old_due_balance;
        $store->old_due_balance = $request->old_due_balance;
        $store->address = $request->address;
        if ($request->order_no) {
            $store->order_no = $request->order_no;
        } else {
            $store->order_no = $this->order_no();
        }
        $store->status = $request->status;
        $store->customer_type = $request->customer_type;
        if ($request->hasFile('customer_photo')) {
            $file = $request->file('customer_photo');
            $fileName = 'customer_photo' . rand(999, 9999) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('customer_photo', $fileName);
            $store->customer_photo = $path;
        }
        $store->save();
        return response()->json('success', 200);
    }

    /*Customer Gift*/
    public function customerGiftData(Request $request)
    {
        $user = User::find($request->user_id);
        $customerData = $user->salesman;
        $route = LineRoute::where('line_id', $customerData->line_id)->pluck('route_id')->toArray();
        $customer = Customer::whereIn('route_id', $route)->pluck('id')->toArray();
        $gift = Gift::whereIn('customer_id', $customer)->get();
        return response()->json($gift);
    }

    /*Customer Gift Update*/
    public function giftUpdate(Request $request, $id)
    {
        $rules = [
            'gift_status' => 'required',
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $updateGift = Gift::find($id);
        $updateGift->gift_status = $request->gift_status;
        $updateGift->save();
        return response()->json('success', 200);
    }

}
