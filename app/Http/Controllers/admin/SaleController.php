<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalePayment;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $salesman = Salesman::pluck('name', 'id')->toarray();
        return view('admin.sale.index', compact('salesman'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'salesman_id' => 'required',
            'sale_date' => 'required|date',
            'sale_no' => 'required',
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'total_price' => 'required',
            'total_amount' => 'required',
        ]);

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
        session()->flash('success_msg', 'सफलतापूर्वक जोड़ा गया');
        if ($request->general)
            return redirect()->route('customer.show', $request->customer_id);
        else
            return redirect()->route('sale.index');
    }

    public function CustomerDueStore($customer_id, $total_price, $pay_amount)
    {
        $due_amount_update = Customer::find($customer_id);
        $due_amount_update->due_balance += $total_price - $pay_amount;
        $due_amount_update->save();

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
        $sale = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->where('sales.id', $id)
            ->select('sales.*', 'customers.customer_name')
            ->first();

        $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
            ->where('sale_products.sale_id', $id)
            ->select('sale_products.*', 'products.product_name', 'products.unite')
            ->get();

        $sale_pay = SalePayment::where('sale_id', $id)->get();
        $sale_total_amount = SaleProduct::where('sale_id', $sale->id)->where('status', 0)->sum('total_price');
        $pay_amount = SalePayment::where('sale_id', $sale->id)->sum('pay_amount');

        return view('admin.sale.view', compact('sale', 'sale_product', 'sale_pay', 'product', 'sale_total_amount', 'pay_amount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salesman = Salesman::pluck('name', 'id')->toarray();
        $product = Product::pluck('product_name', 'id')->toarray();

        $sale = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->where('sales.id', $id)
            ->select('customers.*', 'sales.*', 'customers.id as customer_id')
            ->first();

        $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
            ->where('sale_products.sale_id', $id)
            ->select('sale_products.*', 'products.product_name', 'products.unite')
            ->get();
        $sale_total_amount = SaleProduct::where('sale_id', $id)->where('status', 0)
            ->sum('total_price');

        return view('admin.sale.edit', compact('sale', 'sale_product', 'product', 'sale_total_amount', 'salesman'));

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
            'salesman_id' => 'required',
            'sale_date' => 'required|date',
            'product_id' => 'required',
            'qty' => 'required',
            'price' => 'required',
            'total_price' => 'required',
        ]);

        $update = Sale::find($id);
        $update->salesman_id = $request->salesman_id;
        $update->sale_date = date('Y-m-d', strtotime($request->sale_date));
        $update->remark = $request->remark;
        $this->CustomerDueUpdateSaleEditTime($update->customer_id, $request->total_amount, $update->total_amount);
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

        //for Update return date
        $customer = Customer::find($request->customer_id);
        $customer->return_date = date('Y-m-d', strtotime($request->return_date));
        $customer->save();
        $this->Commisssion($update->salesman_id, $update->id);

        session()->flash('success_msg', 'सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('sale.index');
    }

    public function CustomerDueUpdateSaleEditTime($customer_id, $req_total_amount, $str_total_price)
    {
        if ($req_total_amount != $str_total_price) {
            $due_amount_update = Customer::find($customer_id);
            $due_amount_update->due_balance += $req_total_amount - $str_total_price;
            $due_amount_update->save();
        }
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

        $total_pay = SalePayment::where('sale_id', $id)->sum('pay_amount');
        $this->dueUpdateSaleDeleteTime($delete->total_amount, $delete->customer_id, $total_pay);
        $delete->delete();
        return response()->json('success');
    }


    public function dueUpdateSaleDeleteTime($total_amount, $customerId, $total_pay)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $customer->due_balance -= $total_amount - $total_pay;
            $customer->save();
        }
    }

    public function SaleProductDelete($id)
    {

        $delete = SaleProduct::find($id);
        $delete->delete();

        $sale = Sale::find($delete->sale_id);
        $this->Commisssion($sale->salesman_id, $delete->sale_id);
        return redirect()->back();

    }


    public function getdata(Request $request)
    {
        $salesman_id = $request->salesman_id;
        $customer_id = $request->customer_id;

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        $query = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->leftjoin(DB::raw('(select sale_id, SUM(pay_amount) paid
                                from sale_payments GROUP BY sale_id) as a'), function ($query) {
                $query->on('a.sale_id', 'sales.id');
            })

            ->orderBy('sales.sale_date', 'desc')
            ->select('sales.*', 'customers.customer_name', 'customers.father_name', 'paid');

        if ($request->startDate && $request->endDate) {
            $query->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startdate, $enddate]);
        }
        if ($salesman_id) {
            $query->where('sales.salesman_id', $salesman_id);
        }
        if ($customer_id) {
            $query->where('customers.id', $customer_id);
        }
        $query = $query->get();

        $total_amount = $query->sum('total_amount');
        $pay_amount = $query->sum(DB::raw('paid'));
        /*$sale_total_amount = SaleProduct::sum('total_price');*/
        $due = $total_amount - $pay_amount;

        $commission = $query->sum(DB::raw('sale_commission'));

        return DataTables::of($query)
            ->addIndexColumn()
            ->with('total_amount', $total_amount)
            ->with('pay_amount', $pay_amount)
            ->with('due', $due)
            ->with('sale_commission', $commission)
            ->editColumn('sale_date', function ($datatables) {
                return date('j M, Y h:ia', strtotime($datatables->sale_date));
            })
            ->editColumn('total_amount', function ($datatables) {
                $sale_total_amount = SaleProduct::where('sale_id', $datatables->id)->where('status',0)->sum('total_price');
                return $sale_total_amount;
            })
            ->editColumn('pay_amount', function ($datatables) {
                $pay_amount = SalePayment::where('sale_id', $datatables->id)->sum('pay_amount');
                return $pay_amount;
            })
            ->addColumn("due_amount", function ($datatables) {
                $pay_amount = SalePayment::where('sale_id', $datatables->id)->sum('pay_amount');
                $sale_total_amount = SaleProduct::where('sale_id', $datatables->id)->where('status',0)->sum('total_price');
                $due = $sale_total_amount - $pay_amount;
                if ($due > 0) {
                    return "<label class='text-danger'>$due</label>";
                }
                return "<label class='text-success'>$due</label>";
            })
            ->addColumn('action', function ($datatables) {
                if ($datatables->status == 0) {
                    return '<a href="' . route('sale.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
                <a href="' . route('sale.show', $datatables->id) . '" class="btn btn-success " title="View"> <i class="zmdi zmdi-eye"></i></a>
                <a href="' . route('invoice', $datatables->id) . '" class="btn btn-info " title="Invoice"> <i class="zmdi zmdi-print"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
                }
                return '<a href="' . route('sale.show', $datatables->id) . '" class="btn btn-success " title="View"> <i class="zmdi zmdi-eye"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->rawColumns(['action', 'sale_date', 'due_amount', 'status'])->make(true);
    }

    public function getProductData(Request $request)
    {
        $product_id = $request->product_id;
        $data = Product::find($product_id);
        return $data;
    }

    public function EditSaleProduct(Request $request)
    {
        $sale_product = SaleProduct::leftjoin('products', 'products.id', 'sale_products.product_id')
            ->where('sale_products.id', $request->id)
            ->select('sale_products.*', 'products.product_name', 'products.unite')
            ->first();

        return $sale_product;
    }

    public function saleproductupdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'price' => 'required',
            'total_price' => 'required',
        ]);
        $update = SaleProduct::where('id', $request->sale_product_id)->first();
        $update->product_id = $request->product_id;
        $update->qty = $request->qty;
        $update->price = $request->price;
        $this->TotalAmountUpdate($request->total_price, $update->total_price, $update->sale_id);
        $this->CustomerDueUpdate($update->sale_id, $request->total_price, $update->total_price);
        $update->total_price = $request->total_price;
        $update->save();

        $this->Commisssion($update->salesman_id, $update->sale_id);
        session()->flash('success_msg', 'Product Updated successfully');
        return redirect()->back();
    }


    public function TotalAmountUpdate($req_total_price, $store_total_price, $sale_id)
    {

        if ($store_total_price != $req_total_price) {
            $data = Sale::find($sale_id);
            $data->total_amount -= $store_total_price - $req_total_price;
            $data->save();
        }
    }


    public function Saleaddpayment(Request $request)
    {

        $request->validate([
            'paymode' => 'required',
            'pay_amount' => 'required',
            'pay_date' => 'required',
        ]);

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
        session()->flash('success_msg', 'Payment Added Successfully');
        return redirect()->back();
    }

    public function CustomerDueStorePayTime($sale_id, $pay_amount)
    {
        $sale = Sale::find($sale_id);

        $due_amount_update = Customer::find($sale->customer_id);
        $due_amount_update->due_balance -= $pay_amount;
        $due_amount_update->save();
    }

    public function deletepayment($id)
    {
        $delete = SalePayment::find($id);
        $this->CustomerDueUpdatePaymentDeleteTime($delete->sale_id, $delete->pay_amount);
        $this->Commisssion($delete->salesman_id, $delete->sale_id);
        $delete->delete();


        /*  $sale = Sale::find($delete->sale_id);

          $this->Commisssion($sale->salesman_id, $delete->sale_id);*/
        return redirect()->back();
    }

    public function CustomerDueUpdatePaymentDeleteTime($sale_id, $pay_amount)
    {

        $sale = Sale::find($sale_id);

        $customer = Customer::find($sale->customer_id);
        $customer->due_balance -= $pay_amount;
        $customer->save();
    }

    public function EditSalePayment(Request $request)
    {

        $data = SalePayment::find($request->id);
        $pay_date = date('d-M-Y H:i:s', strtotime($data->pay_date));

        $max_pay = Sale::find($data->sale_id);

        return response()->json(['data' => $data, 'pay_date' => $pay_date, 'max_pay' => $max_pay]);
    }

    public function updatepayment(Request $request)
    {
        $request->validate([
            'paymode' => 'required',
            'pay_amount' => 'required',
            'pay_date' => 'required',
        ]);

        $update = SalePayment::find($request->pay_id);
        $update->paymode = $request->paymode;
        $update->pay_amount = $request->pay_amount;
        $update->pay_date = date('Y-m-d H:i:s', strtotime($request->pay_date));
        $update->remark = $request->remark;
        $this->CustomerDueUpdatePayTime($request->pay_id, $request->pay_amount, $update->sale_id);
        $update->save();
        $sale = Sale::find($update->sale_id);
        $this->Commisssion($sale->salesman_id, $update->sale_id);
        session()->flash('success_msg', 'Payment Updated Successfully');
        return redirect()->back();
    }

    public function CustomerDueUpdatePayTime($pay_id, $pay_amount, $sale_id)
    {
        $sale = Sale::find($sale_id);
        $total_pay_amount = SalePayment::find($pay_id);

        if ($pay_amount != $total_pay_amount->pay_amount) {
            $due_amount_update = Customer::find($sale->customer_id);
            $due_amount_update->due_balance += $total_pay_amount->pay_amount - $pay_amount;
            $due_amount_update->save();
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

    public function paymentReceipt($id)
    {

        $sale_payment = SalePayment::find($id);
        $sale = Sale::where('sales.id', $sale_payment->sale_id)->first();
        $customer = Customer::where('customers.id', $sale->customer_id)->first();

        return view('admin.paymentreceipt.paymentreceipt', compact('sale_payment', 'sale', 'customer'));
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

    public function ReturnReceiptNo()
    {
        $rec_no = SaleProduct::orderby('id', 'desc')->where('status',1)->value('return_rec_no');
        if ($rec_no) {
            $rec_no = $rec_no + 1;
        } else {
            $rec_no = 1;
        }
        return str_pad($rec_no, 3, "0", STR_PAD_LEFT);
    }

    public function salereturn($id)
    {
        $sale_product = SaleProduct::find($id);
        $sale_product->status = 1;
        $sale_product->return_rec_no = $this->ReturnReceiptNo();
        $sale_product->save();

        $sale = Sale::find($sale_product->sale_id);
        $sale->total_amount -= $sale_product->total_price;
        $sale->save();

        $sale_payment = SalePayment::where('sale_id', $sale_product->sale_id)->first();
        if ($sale_payment->pay_amount > $sale_product->total_price) {
            $sale_payment->pay_amount -= $sale_product->total_price;
        }
        else{
            $sale_payment->pay_amount = 0;
        }
        $sale_payment->save();

        $customer_due = Customer::find($sale->customer_id);
        if ($customer_due->due_balance - $customer_due->old_due_balance > $sale_product->total_price) {
            $customer_due->due_balance -= $sale_product->total_price;
        }
        else{
            $customer_due->due_balance = $customer_due->old_due_balance;
        }
        $customer_due->save();

        return redirect()->back();
    }

    public function CustomerDueUpdate($sale_id, $req_total_price, $str_total_price)
    {
        $sale = Sale::find($sale_id);

        if ($req_total_price != $str_total_price)
        {
            $due_amount_update = Customer::find($sale->customer_id);
            $due_amount_update->due_balance += $req_total_price - $str_total_price;
            $due_amount_update->save();
        }
    }

    public function ReturnReceipt($id)
    {
        $sale_product = SaleProduct::leftjoin('products','products.id','sale_products.product_id')
            ->select('sale_products.*','products.product_name')->where('sale_products.id',$id)->first();

        $sale = Sale::where('sales.id', $sale_product->sale_id)->first();
        $customer = Customer::where('customers.id', $sale->customer_id)->first();

        return view('admin.returnreceipt.returnreceipt', compact('sale_product', 'sale', 'customer'));
    }
}
