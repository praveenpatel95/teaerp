<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Gift;
use App\Models\LineRoute;
use App\Models\Product;
use App\Models\Route;
use App\Models\Sale;
use App\Models\SaleInterest;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $route = Route::pluck('route_name', 'id')->toarray();

        return view('admin.customer.index', compact('route'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer_order = Customer::select("order_no", DB::raw("CONCAT(customers.order_no,' ',customers.customer_name) as customer_order"))
            ->pluck('customer_order', 'order_no')->toarray();
        $route = Route::pluck('route_name', 'id')->toarray();

        return view('admin.customer.create', compact('route', 'customer_order'));
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
            'status' => 'required',
            'customer_type' => 'required'
        ]);

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

        session()->flash('success_msg', 'ग्राहक सफलतापूर्वक जोड़ा गया');


        return redirect()->route('customer.index');

    }


    public function edit($id)
    {
        $customer_order = Customer::select("order_no", DB::raw("CONCAT(customers.order_no,' ',customers.customer_name) as customer_order"))
            ->pluck('customer_order', 'order_no')->toarray();
        $route = Route::pluck('route_name', 'id')->toarray();
        $customer = Customer::find($id);
        return view('admin.customer.edit', compact('customer', 'route', 'customer_order'));
    }


    public function show($id)
    {
        $product = Product::pluck('product_name', 'id')->toarray();
        $data = Sale::where('customer_id', $id)->first();
        if ($data) {
            //for customer data
            $customer = Customer::leftjoin('routes', 'routes.id', 'customers.route_id')->where('customers.id', $id)->select('customers.*', 'routes.route_name')->first();

            //for sale id which have same customer
            $sale = Sale::where('customer_id', $id)->pluck('id')->toarray();
            //for sale product  & payment which have same sale_id
            $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
                ->leftjoin('sales', 'sales.id', 'sale_products.sale_id')
                ->where('sale_products.status', 0)
                ->whereIn('sale_products.sale_id', $sale)
                ->orderBy('sale_products.created_at', 'desc')
                ->select('sale_products.*', 'products.product_name', 'products.unite', 'sales.sale_no')
                ->get();

            $sale_product_return = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
                ->leftjoin('sales', 'sales.id', 'sale_products.sale_id')
                ->where('sale_products.status', 1)
                ->whereIn('sale_products.sale_id', $sale)
                ->orderBy('sale_products.updated_at', 'desc')
                ->select('sale_products.*', 'products.product_name', 'products.unite', 'sales.sale_no')
                ->get();

            $sale_pay = SalePayment::leftjoin('sales', 'sales.id', 'sale_payments.sale_id')
                ->whereIn('sale_id', $sale)
                ->select('sale_payments.*', 'sales.sale_no')
                ->get();
            $sale_total_amount = SaleProduct::whereIn('sale_id', $sale)->where('status', 0)->sum('total_price');
            $pay_amount = SalePayment::whereIn('sale_id', $sale)->sum('pay_amount');
            //for sale data
            $saledata = Sale::leftjoin(DB::raw('(select sale_id, SUM(total_price) total
                                from sale_products GROUP BY sale_id) as a'), function ($query) {
                $query->on('a.sale_id', 'sales.id');
            })
                ->leftjoin(DB::raw('(select sale_id, SUM(pay_amount) PayAmount
                                from sale_payments GROUP BY sale_id) as b'), function ($query) {
                    $query->on('b.sale_id', 'sales.id');
                })
                ->orderBy('sale_date', 'desc')
                ->where('customer_id', $id)->get();

            $interest_data = SaleInterest::join('sales', 'sales.id', 'sale_interests.sale_id')
                ->leftjoin('customers', 'customers.id', 'sales.customer_id')
                ->where('customers.id',$id)
                ->select('sale_interests.*', 'customers.customer_name', 'sales.sale_no')
                ->get();

            $gift_data = Gift::where('customer_id', $id)
                ->get();
            return view('admin.customer.view', compact('customer', 'sale_product', 'sale_pay', 'sale_total_amount', 'pay_amount', 'id', 'data', 'saledata', 'product', 'sale_product_return', 'interest_data', 'gift_data'));
        } else {
            $customer = Customer::leftjoin('routes', 'routes.id', 'customers.route_id')
                ->where('customers.id', $id)
                ->select('customers.*', 'routes.route_name')
                ->first();

            return view('admin.customer.view', compact('customer', 'id', 'data', 'product'));
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
            'status' => 'required',
            'customer_type' => 'required'
        ]);

        $update = Customer::find($id);
        $update->route_id = $request->route_id;
        $update->customer_name = $request->customer_name;
        $update->father_name = $request->father_name;
        $update->mobile_no = $request->mobile_no;
        $update->adhar_no = $request->adhar_no;
        $update->due_balance += $request->old_due_balance - $update->old_due_balance;
        $update->old_due_balance = $request->old_due_balance;
        $update->address = $request->address;
        $update->status = $request->status;
        $update->customer_type = $request->customer_type;
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
        $update->save();
        session()->flash('success_msg', 'ग्राहक सफलतापूर्वक अपडेट किया गया');


        return redirect()->route('customer.index');
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

    public function getData(Request $request)
    {
        $route_id = $request->route;
        $data = Customer::leftjoin('routes', 'routes.id', 'customers.route_id')->orderby('order_no', 'asc')->select('customers.*', 'routes.route_name');
        if ($route_id) {
            $data->where('customers.route_id', $route_id);
        }

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
            ->editColumn('return_date', function ($datatables) {

                return $datatables->return_date != '' && $datatables->return_date != '1970-01-01' && $datatables->return_date != '0000-00-00' ? date('d-M-Y', strtotime($datatables->return_date)) : "";
            })
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('customer.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <a href="' . route('customer.show', $datatables->id) . '" class="btn btn-success " title="View"> <i class="zmdi zmdi-eye"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>
                <a href="' . route('customersale', $datatables->id) . '" class="btn btn-info "  title="सेल जोड़ें"><i class="zmdi zmdi-shopping-cart-add"> </i> </a>';
            })
            ->rawColumns(['action', 'status'])->make(true);
    }

    public function Sale_no()
    {
        $sale_no = Sale::orderby('id', 'desc')->value('sale_no');
        if ($sale_no) {
            $sale_no = $sale_no + 1;
        } else {
            $sale_no = 1;
        }
        return str_pad($sale_no, 3, "0", STR_PAD_LEFT);
    }

    public function customerSale($id)
    {
        $sale_no = $this->Sale_no();
        $product = Product::pluck('product_name', 'id')->toarray();

        $data = Customer::find($id);

        $line_route = LineRoute::where('route_id', $data->route_id)->select('line_id')->first();

        $salesmans = Salesman::pluck('name', 'id')->toArray();
        $salesman = Salesman::where('line_id', $line_route->line_id)->select('name', 'id')->first();

        //for gift data
        $gift_data = Gift::where('customer_id', $id)->first();
        return view('admin.customer.customersale', compact('data', 'sale_no', 'product', 'salesman', 'salesmans', 'gift_data'));
    }


    public function getoldDuedata(Request $request)
    {
        $customer_id = $request->customer_id;

        $data = Customer::find($customer_id);
        return $data;
    }

    public function getGiftData(Request $request)
    {
        $customer_id = $request->customer_id;

        $gift_data = Gift::where('customer_id', $customer_id)->first();

        return view('admin.sale.giftStatusData', compact('gift_data'));
    }

    public function GiftStatusUpdate(Request $request)
    {
        $customer_id = $request->customer_id;
        $status = $request->status_id;

        $update = Gift::where('customer_id', $customer_id)->first();
        $update->gift_status = $status;
        $update->save();
        session()->flash('success_msg', 'गिफ्ट  Dispatched  किया गया');
    }


    public function olddueGet(Request $request)
    {
        $data = Customer::find($request->id);

        return $data;
    }

    public function olddueAdd(Request $request)
    {
        $customer_id = $request->customer_id;
        $pay = $request->pay_amount;

        $update = Customer::find($customer_id);
        $update->old_due_balance -= $pay;
        $update->due_balance -= $pay;
        $update->save();
        return redirect()->back();

    }

    public function maxPayCustomer(Request $request)
    {
        $sale_total = Sale::find($request->id);

        $pay = SalePayment::where('sale_id', $request->id)->sum('pay_amount');

        $data = $sale_total->total_amount - $pay;

        return $data;

    }

}
