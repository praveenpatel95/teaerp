<?php

namespace App\Http\Controllers\salesman;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LineRoute;
use App\Models\Product;
use App\Models\Route;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        return view('salesman.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $route = Route::whereIn('id',$routes)->pluck('route_name', 'id')->toarray();
        $customer_order = Customer::select("order_no", DB::raw("CONCAT(customers.order_no,' ',customers.customer_name) as customer_order"))->
            whereIn('customers.route_id',$routes)->pluck('customer_order', 'order_no')->toarray();


        return view('salesman.customer.create', compact('route', 'customer_order'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


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

    public function store(Request $request)
    {
        $request->validate([
            'route_id' => 'required|integer',
            'customer_name' => 'required|string',
            'father_name' => 'required|string',
            'opening_balance' => 'required',
            'status' => 'required'
        ]);

        $store = new Customer();
        $store->route_id = $request->route_id;
        $store->customer_name = $request->customer_name;
        $store->father_name = $request->father_name;
        $store->mobile_no = $request->mobile_no;
        $store->adhar_no = $request->adhar_no;
        $store->opening_balance = $request->opening_balance;
        $store->address = $request->address;
        if ($request->order_no) {
            $store->order_no = $request->order_no;
        } else {
            $store->order_no = $this->order_no();
        }

        $store->status = $request->status;

        if ($request->hasFile('customer_photo')) {
            $file = $request->file('customer_photo');
            $fileName = 'customer_photo' . rand(999, 9999) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('customer_photo', $fileName);
            $store->customer_photo = $path;
        }

        session()->flash('success_msg', 'Added successfully');
        $store->save();

        return redirect()->route('salesmancustomer.index');

    }


    public function edit($id)
    {
        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $customer_order = Customer::whereIn('route_id',$routes)->pluck('customer_name', 'id')->toarray();
        $route = Route::whereIn('id',$routes)->pluck('route_name', 'id')->toarray();
        $customer = Customer::find($id);
        return view('salesman.customer.edit', compact('customer', 'route', 'customer_order'));
    }


    public function show($id)
    {
        $product = Product::pluck('product_name', 'id')->toarray();
        $data = Sale::where('customer_id', $id)->first();
        if ($data) {

            //for customer data
            $customer = Customer::find($id);
            //for sale id which have same customer
            $sale = Sale::where('customer_id', $id)->pluck('id')->toarray();
            //for sale product  & payment which have same sale_id
            $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')->whereIn('sale_products.sale_id', $sale)->select('sale_products.*', 'products.product_name', 'products.unite')->get();
            $sale_pay = SalePayment::whereIn('sale_id', $sale)->get();
            $sale_total_amount = SaleProduct::whereIn('sale_id', $sale)->sum('total_price');
            $pay_amount = SalePayment::whereIn('sale_id', $sale)->sum('pay_amount');
            //for sale data
            $saledata = Sale::leftjoin(DB::raw('(select sale_id, SUM(total_price) total
                                from sale_products GROUP BY sale_id) as a'), function ($query) {
                $query->on('a.sale_id', 'sales.id');
            })->where('customer_id',$id)->get();
            return view('salesman.customer.view', compact('customer', 'sale_product', 'sale_pay', 'sale_total_amount', 'pay_amount', 'id','data','saledata','product'));
        } else {
            $customer = Customer::find($id);

            return view('salesman.customer.view', compact('customer', 'id','data','product'));

        }
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
            'route_id' => 'required|integer',
            'customer_name' => 'required|string',
            'father_name' => 'required|string',
            'opening_balance' => 'required',

            'status' => 'required'
        ]);

        $update = Customer::find($id);
        $update->route_id = $request->route_id;
        $update->customer_name = $request->customer_name;
        $update->father_name = $request->father_name;
        $update->mobile_no = $request->mobile_no;
        $update->adhar_no = $request->adhar_no;
        $update->opening_balance = $request->opening_balance;
        $update->address = $request->address;
        $update->status = $request->status;
        if ($request->order_no) {
            $update->order_no = $request->order_no;
        } else {
            $update->order_no = $this->order_no();
        }
        if ($request->hasFile('customer_photo')) {
            $file = $request->file('customer_photo');
            $fileName = 'customer_photo' . rand(999, 9999) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('customer_photo', $fileName);
            $update->customer_photo = $path;
        }
        session()->flash('success_msg', 'Updated successfully');
        $update->save();

        return redirect()->route('salesmancustomer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Customer::find($id);
        $delete->delete();

        return response()->json('success');
    }

    public function getData()
    {
        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $data = Customer::leftjoin('routes', 'routes.id', 'customers.route_id')->orderby('order_no', 'asc')->whereIn('customers.route_id',$routes)->select('customers.*', 'routes.route_name')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->addColumn('status', function ($datatables) {
                if ($datatables->status == 0) {
                    return "<i class='zmdi zmdi-lock' style='font-size: 20px; color:red'></i> ";
                } elseif ($datatables->status == 1) {
                    return "<i class='zmdi zmdi-lock-open' style='font-size: 20px; color:green'></i>";
                }
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('salesmancustomer.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <a href="' . route('salesmancustomer.show', $datatables->id) . '" class="btn btn-success " title="View"> <i class="zmdi zmdi-eye"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action', 'status'])->make(true);
    }

    public function customerSales($id)
    {
        $sale_no = $this->Sale_no();
        $product = Product::pluck('product_name', 'id')->toarray();

        $data = Customer::where('id', $id)->first();

        return view('salesman.customer.customersale', compact('data','sale_no','product'));
    }

    public function Sale_no()
    {
        $sale_no = Sale::orderby('id', 'desc')->count();
        if ($sale_no) {
            $sale_no = $sale_no + 1;
        } else {
            $sale_no = 1;
        }
        return str_pad($sale_no, 3, "0", STR_PAD_LEFT);
    }


}
