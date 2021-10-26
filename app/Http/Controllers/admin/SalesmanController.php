<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AdvancePayment;
use App\Models\AssignStock;
use App\Models\Attendance;
use App\Models\Line;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\Salesman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SalesmanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.salesman.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $line = Line::pluck('line_name', 'id')->toarray();
        return view('admin.salesman.create', compact('line'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'father_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
            'salary_amount' => 'required',
            'salary_type' => 'required',
            'joining_date' => 'required|date',
            'status' => 'required',
        ]);

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
        session()->flash('success_msg', 'सेल्समैन सफलतापूर्वक जोड़ा गया');

        return redirect()->route('salesman.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $salesman = Salesman::leftjoin('lines', 'lines.id', 'salesmen.line_id')
            ->leftjoin('users', 'users.id', 'salesmen.user_id')
            ->where('salesmen.id', $id)
            ->select('salesmen.*', 'users.email', 'users.status', 'lines.line_name')
            ->first();

        $commission = Attendance::where('salesman_id', $id)->sum('commission');

        $payment_commission = Sale::where('salesman_id', $id)->sum('sale_commission');

        //salesman sale product
        $productData = SaleProduct::leftjoin('products', 'sale_products.product_id', 'products.id')
            ->join('sales', 'sales.id', 'sale_products.sale_id')
            ->leftjoin('salesmen', 'salesmen.id', 'sales.salesman_id')
            ->leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->where('sale_products.status', 0)
            ->where('sales.salesman_id', $id)
            ->get();

        $today = date('Y-m-d');

        //today total tea sale quantity
        $today_tea = SaleProduct::join('sales', 'sales.id', 'sale_products.sale_id')
            ->leftjoin('salesmen', 'salesmen.id', 'sales.salesman_id')
            ->where('sale_products.status', 0)
            ->where(DB::raw('DATE(sales.sale_date)'), $today)
            ->where('sales.salesman_id', $id)
            ->sum('sale_products.qty');

        //total tea sale quantity
        $total_tea = SaleProduct::join('sales', 'sales.id', 'sale_products.sale_id')
            ->leftjoin('salesmen', 'salesmen.id', 'sales.salesman_id')
            ->where('sale_products.status', 0)
            ->where('sales.salesman_id', $id)
            ->sum('sale_products.qty');

        //Today total assign tea quantity
        $assign_tea = AssignStock::leftjoin('salesmen', 'salesmen.id', 'assign_stocks.salesman_id')
            ->where('assign_stocks.salesman_id', $id)
            ->where('assign_stocks.assign_date', $today)
            ->sum('assign_stocks.qty');

        //customer return product
        $salesman_product_return = SaleProduct::leftjoin('products', 'sale_products.product_id', 'products.id')
            ->join('sales', 'sales.id', 'sale_products.sale_id')
            ->leftjoin('salesmen', 'salesmen.id', 'sales.salesman_id')
            ->leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->where('sale_products.status', 1)
            ->where('sales.salesman_id', $id)
            ->get();


        //Today Total Payment
        $total_payment = Sale::join('sale_payments', 'sale_payments.sale_id', 'sales.id')
            ->where('sales.salesman_id', $id)
            ->where(DB::raw('DATE(sales.sale_date)'), $today)
            ->sum('pay_amount');

        $paymentData = Sale::leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->join('sale_payments', 'sale_payments.sale_id', 'sales.id')
            ->select('customers.*', 'customers.id as customer_id', 'sales.sale_date', 'sales.sale_no', 'sale_payments.*', 'sale_payments.id as payment_id')
            ->where('sales.salesman_id', $id)
            ->get();

        return view('admin.salesman.view', compact('salesman', 'productData', 'paymentData', 'commission', 'salesman_product_return', 'today_tea', 'total_tea', 'assign_tea', 'total_payment','payment_commission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $salesman = Salesman::find($id);
        $user = User::find($salesman->user_id);
        $line = Line::pluck('line_name', 'id')->toarray();

        return view('admin.salesman.edit', compact('salesman', 'line', 'user'));
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
            'name' => 'required',
            'father_name' => 'required',
            'salary_amount' => 'required',
            'salary_type' => 'required',
            'joining_date' => 'required|date',
        ]);

        $update = Salesman::find($id);
        $update->line_id = $request->line_id;
        $update->name = $request->name;
        $update->father_name = $request->father_name;
        $update->mobile_no = $request->mobile_no;
        $update->address = $request->address;
        $update->adhar_no = $request->adhar_no;
        $update->salary_type = $request->salary_type;
        $update->salary_amount = $request->salary_amount;
        $update->commission = $request->commission;
        $update->joining_date = date('Y-m-d', strtotime($request->joining_date));
        $update->save();

        $user_update = User::find($update->user_id);
        $user_update->status = $request->status;
        $user_update->save();

        session()->flash('success_msg', 'सेल्समैन सफलतापूर्वक अपडेट किया गया');
        return redirect()->route('salesman.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Salesman::find($id);
        $this->salesmanUserDelete($delete->user_id);
        $delete->delete();
        session()->flash('success_msg', 'सेल्समैन सफलतापूर्वक Delete किया गया');
        return response()->json('success');
    }

    public function salesmanUserDelete($user_id)
    {
        $user = User::find($user_id);
        $user->delete();
        return response()->json('success');
    }

    public function getData()
    {
        $query = Salesman::leftjoin('users', 'users.id', 'salesmen.user_id')
            ->leftjoin('lines', 'lines.id', 'salesmen.line_id')
            ->orderBy('salesmen.created_at', 'desc')
            ->select('salesmen.*', 'users.status', 'lines.line_name')
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn("DT_RowIndex", '')
            ->addColumn('action', function ($datatables) {
                return '<a href="' . route('salesman.edit', $datatables->id) . '" class="btn btn-primary " title="Edit"> <i class="zmdi zmdi-edit"></i></a>
         <a href="' . route('salesman.show', $datatables->id) . '" class="btn btn-success " title="View"> <i class="zmdi zmdi-eye"></i></a>
                <button onclick="deleteIt(' . $datatables->id . ')" class="btn btn-danger " title="Delete"><i class="zmdi zmdi-delete"></i> </button>';
            })
            ->addColumn('status', function ($datatables) {
                if ($datatables->status == 0) {
                    return "<i class='zmdi zmdi-lock' style='font-size: 20px; color:red'></i> ";
                } elseif ($datatables->status == 1) {
                    return "<i class='zmdi zmdi-lock-open' style='font-size: 20px; color:green'></i>";
                }
            })
            ->editColumn('joining_date', function ($datatables) {
                return date('d-M-Y', strtotime($datatables->joining_date));
            })
            ->rawColumns(['action', 'status', 'joining_date'])->make(true);
    }

    public function SalesmanPasswordUpdate(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
        ]);

        $salesman = Salesman::find($id);
        $salesman->password = $request->password;

        $salesman->save();

        $user = User::where('id', $salesman->user_id)->first();;
        $user->password = bcrypt($request->password);
        $user->save();
        session()->flash('success_msg', 'Password Updated successfully');

        return redirect()->back();
    }




}
