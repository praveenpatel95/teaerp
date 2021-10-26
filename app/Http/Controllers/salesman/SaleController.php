<?php

namespace App\Http\Controllers\salesman;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LineRoute;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('salesman.sale.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $sale_no =  $this->Sale_no();
        $product = Product::pluck('product_name', 'id')->toarray();
        $customer = Customer::whereIn('route_id',$routes)->where('status',1)->pluck('customer_name', 'id')->toarray();

        return view('salesman.sale.create', compact('customer', 'sale_no', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function Sale_no()
    {
        $sale_no = Sale::orderby('id', 'desc')->count();
        if ($sale_no) {
            $sale_no = $sale_no + 1;
        }
        else {
            $sale_no = 1;
        }
        return str_pad($sale_no, 3, "0", STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'sale_date' => 'required|date',
            'sale_no' => 'required',
            'total_amount' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'total_price' => 'required',
        ]);
        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('id')->first();

        $store = new Sale();
        $store->customer_id = $request->customer_id;
        $store->salesman_id = $salemsan->id;
        $store->sale_date = date('Y-m-d', strtotime($request->sale_date));
        $store->sale_no = $request->sale_no;
        $store->total_amount = $request->total_amount;
        $store->save();

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
            $sale_payment->salesman_id = $salemsan->id;
            $sale_payment->rec_no = $this->rec_no();
            $sale_payment->paymode = $request->paymode;
            $sale_payment->pay_amount = $request->pay_amount;
            $sale_payment->pay_date = date('Y-m-d', strtotime($request->pay_date));
            $sale_payment->remark = $request->remark;
            $sale_payment->save();
        }

        session()->flash('success_msg', 'Added successfully');

        if ($request->general)
            return redirect()->route('salesmancustomer.show' ,$request->customer_id);
        else
            return redirect()->route('salesmansale.index');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::pluck('product_name', 'id')->toarray();
        $sale = Sale::leftjoin('customers','customers.id','sales.customer_id')->where('sales.id',$id)->select('sales.*','customers.customer_name')->first();

        $sale_product = SaleProduct::leftjoin('products','products.id','sale_products.product_id')->where('sale_products.sale_id', $id)->select('sale_products.*','products.product_name','products.unite')->get();

        $sale_pay =SalePayment::where('sale_id',$id)->get();
        $sale_total_amount = SaleProduct::where('sale_id', $sale->id)->sum('total_price');
        $pay_amount = SalePayment::where('sale_id', $sale->id)->sum('pay_amount');

        return view('salesman.sale.view', compact('sale','sale_product','sale_pay','product','sale_total_amount','pay_amount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $sale = Sale::find($id);
        $product = Product::pluck('product_name', 'id')->toarray();
        $customer = Customer::whereIn('route_id',$routes)->pluck('customer_name', 'id')->toarray();
        $sale_product = SaleProduct::leftjoin('products','products.id','sale_products.product_id')->where('sale_products.sale_id', $id)->select('sale_products.*','products.product_name','products.unite')->get();
        $sale_total_amount = SaleProduct::where('sale_id', $id)->sum('total_price');

        return view('salesman.sale.edit', compact('sale', 'customer', 'sale_product', 'product', 'sale_total_amount'));
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
            'customer_id' => 'required',
            'sale_date' => 'required|date',
            'total_amount' => 'required',
        ]);

        $update = Sale::find($id);
        $update->customer_id = $request->customer_id;
        $update->sale_date = date('Y-m-d', strtotime($request->sale_date));
        $update->total_amount = $request->total_amount;
        $update->save();

        $product_id = $request->product_id;
        $qty = $request->qty;
        $price = $request->price;
        $total_price = $request->total_price;
        $checkval = $request->checkval;

        foreach ($product_id as $key => $value) {
            if ($checkval[$key] == 'new') {
                $serdata = new SaleProduct();
                $serdata->sale_id = $update->id;
            } else {
                $serdata = SaleProduct::find($checkval[$key]);
            }
            $serdata->product_id = $product_id[$key];
            $serdata->qty = $qty[$key];
            $serdata->price = $price[$key];
            $serdata->total_price = $total_price[$key];
            $serdata->save();
        }
        session()->flash('success_msg', 'Updated successfully');
        return redirect()->route('salesmansale.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Sale::find($id);
        $delete->delete();
        return response()->json('success');
    }

    public function saleproductDelete($id)
    {
        $delete = SaleProduct::find($id);
        $delete->delete();
        return redirect()->back();
    }

    public function getdata()
    {
        //for salesman_id
        $user_id = Auth::user()->id;
        $salemsan = Salesman::where('user_id',$user_id)->select('line_id')->first();
        //for current salesman s route
        $routes = LineRoute::where('line_id',$salemsan->line_id)->pluck('route_id')->toarray();

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')->whereIn('customers.route_id',$routes)->select('sales.*', 'customers.customer_name')->get();
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->editColumn('sale_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->sale_date));
            })
            ->editColumn('total_amount', function ($datatables) {
                $sale_total_amount = SaleProduct::where('sale_id', $datatables->id)->sum('total_price');
                return $sale_total_amount;
            })
            ->editColumn('pay_amount',function ($datatables){
                $pay_amount = SalePayment::where('sale_id',$datatables->id)->sum('pay_amount');
                return $pay_amount;})
            ->addColumn("due_amount", function ($datatables) {
                $pay_amount = SalePayment::where('sale_id',$datatables->id)->sum('pay_amount');
                $sale_total_amount = SaleProduct::where('sale_id', $datatables->id)->sum('total_price');
                $due = $sale_total_amount - $pay_amount;
                if ($due > 0) {
                    return "<label class='text-danger'>$due</label>";
                }
                return "<label class='text-success'>$due</label>";})
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('salesmansale.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <a href="' . route('salesmansale.show', $datatables->id) . '" class="btn btn-success " title="View"> <i class="zmdi zmdi-eye"></i></a>
                <a href="' . route('invoices', $datatables->id) . '" class="btn btn-info " title="Invoice"> <i class="zmdi zmdi-print"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action', 'sale_date','due_amount'])->make(true);
    }

    public function getProductData(Request $request)
    {
        $product_id = $request->product_id;
        $data = Product::find($product_id);
        return $data;

    }

    public function SaleProductStore(Request $request)
    {
        $request->validate([
            'saleId' =>'required',
            'product_id' =>'required',
            'price' =>'required',
            'total_price' =>'required',
        ]);
        //for salesman_id
        $user_id = Auth::user()->id;
        $salesmsan = Salesman::where('user_id',$user_id)->select('id')->first();

        $store = new SaleProduct();
        $store->sale_id = $request->saleId;
        $store->product_id = $request->product_id;
        $store->salesman_id = $salesmsan->id;
        $store->qty = $request->qty;
        $store->price = $request->price;
        $store->total_price = $request->total_price;
        $store->save();
        session()->flash('success_msg','Product added successfully ');
        return redirect()->back();

    }

    public function EditSaleProduct(Request $request)
    {
        $sale_product = SaleProduct::leftjoin('products','products.id','sale_products.product_id')->where('sale_products.id',$request->id)->select('sale_products.*','products.product_name','products.unite')->first();
        return $sale_product;
    }


    public function saleproductupdate(Request $request)
    {
        $request->validate([
            'product_id' =>'required',
            'price' =>'required',
            'total_price' =>'required',
        ]);


        $update = SaleProduct::where('id',$request->saleId)->first();

        $update->product_id = $request->product_id;
        $update->qty = $request->qty;
        $update->price = $request->price;
        $update->total_price = $request->total_price;
        $update->save();
        session()->flash('success_msg','Product Updated successfully ');
        return redirect()->back();

    }

    public function saleviewproductDelete($id)
    {
        $delete = SaleProduct::find($id);
        $delete->delete();
        return redirect()->back();
    }

    public function Saleaddpayment(Request $request){

        $request->validate([
            'paymode' =>'required',
            'pay_amount' =>'required',
            'pay_date' =>'required',
        ]);

        //for salesman_id
        $user_id = Auth::user()->id;
        $salesmsan = Salesman::where('user_id',$user_id)->select('id')->first();

        $store = new SalePayment();
        $store->sale_id = $request->sale_id;
        $store->salesman_id = $salesmsan->id;
        $store->rec_no = $this->rec_no();
        $store->paymode = $request->paymode;
        $store->pay_amount = $request->pay_amount;
        $store->pay_date = date('Y-m-d',strtotime($request->pay_date));
        $store->remark = $request->remark;
        $store->save();
        session()->flash('success_msg','Payment Added Successfully');
        return redirect()->back();


    }

    public function deletepayment($id)
    {
        $delete = SalePayment::find($id);
        $delete->delete();
        return redirect()->back();
    }

    public function EditSalePayment(Request $request)
    {

        $data = SalePayment::find($request->id);
        $pay_date = date('d-M-Y', strtotime($data->pay_date));

        return response()->json(['data' => $data, 'pay_date' => $pay_date]);


    }

    public function updatepayment(Request $request){
        $request->validate([
            'paymode' =>'required',
            'pay_amount' =>'required',
            'pay_date' =>'required',
        ]);

        $update = SalePayment::find($request->pay_id);
        $update->paymode = $request->paymode;
        $update->pay_amount = $request->pay_amount;
        $update->pay_date = date('Y-m-d',strtotime($request->pay_date));
        $update->remark = $request->remark;
        $update->save();
        session()->flash('success_msg','Payment Updated Successfully');
        return redirect()->back();
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

    public function paymentReceipts($id){

        $sale_payment = SalePayment::find($id);
        $sale = Sale::where('sales.id',$sale_payment->sale_id)->first();
        $customer = Customer::where('customers.id',$sale->customer_id)->first();

        return view('salesman.paymentreceipt.paymentreceipt',compact('sale_payment','sale','customer'));

    }


}
