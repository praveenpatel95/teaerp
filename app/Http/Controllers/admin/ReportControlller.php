<?php

namespace App\Http\Controllers\admin;

use App\Exports\ReportExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\Salesman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ReportControlller extends Controller
{
    public function productReport()
    {
        $product = Product::pluck('product_name', 'id')->toarray();
        $salesman = Salesman::pluck('name', 'id')->toarray();
        return view('admin.report.productreport', compact('salesman', 'product'));
    }

    public function productReportGetdata(Request $request)
    {
        $salesman_id = $request->salesman_id;
        $product_id = $request->product_id;

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        $productData = SaleProduct::leftjoin('products', 'sale_products.product_id', 'products.id')
            ->join('sales', 'sales.id', 'sale_products.sale_id')
            ->leftjoin('salesmen', 'salesmen.id', 'sales.salesman_id')
            ->leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->orderBy('sale_products.id','desc')
            ->select('sale_products.*', 'sales.sale_date', 'sales.sale_no', 'salesmen.name', 'customers.customer_name', 'customers.id as customer_id', 'products.product_name');


        if ($request->startDate && $request->endDate) {
            $productData->whereBetween(DB::raw('DATE(sale_products.created_at)'), [$startdate, $enddate]);
        }

        if ($salesman_id) {
            $productData->where('sales.salesman_id', $salesman_id);
        }
        if ($product_id) {
            $productData->where('sale_products.product_id', $product_id);
        }

        $productData->get();

        $total_amount = $productData->sum(DB::raw('sale_products.total_price'));
        $total_price = $productData->sum(DB::raw('sale_products.price'));
        $total_qty = $productData->sum(DB::raw('sale_products.qty'));

        return DataTables::of($productData)
            ->addIndexColumn()
            ->with('total_amount', $total_amount)
            ->with('total_price', $total_price)
            ->with('total_qty', $total_qty)
            ->editColumn('qty', function ($datatables) {
                return "$datatables->qty $datatables->unite";
            })
            ->editColumn('sale_date', function ($datatables) {
                return date('j M, Y h:ia', strtotime($datatables->sale_date));
            })
            ->addColumn("DT_RowIndex", '')
            ->rawColumns([])
            ->make(true);
    }

    public function ProductReportExporttToExcel(Request $request)
    {
        $salesman_id = $request->salesman;
        $product_id = $request->product;

        $startdate = date('Y-m-d', strtotime($request->startDate));
        $enddate = date('Y-m-d', strtotime($request->endDate));

        $productData = SaleProduct::leftjoin('products', 'sale_products.product_id', 'products.id')
            ->join('sales', 'sales.id', 'sale_products.sale_id')
            ->leftjoin('salesmen', 'salesmen.id', 'sales.salesman_id')
            ->leftjoin('customers', 'customers.id', 'sales.customer_id')
            ->select('customers.id as customer_id', 'customers.customer_name', 'salesmen.name', 'products.product_name',
                'sale_products.qty', 'sale_products.price', 'sale_products.total_price');

        if ($request->startDate && $request->endDate) {
            $productData->whereBetween(DB::raw('DATE(sale_products.created_at)'), [$startdate, $enddate]);
        }

        if ($salesman_id) {
            $productData->where('sales.salesman_id', $salesman_id);
        }
        if ($product_id) {
            $productData->where('sale_products.product_id', $product_id);
        }
        $data = $productData->get();

        $headings = [
            'ग्राहक आईडी',
            'ग्राहक का नाम',
            'सेल्समेन',
            'प्रोडक्ट',
            'मात्रा',
            'कीमत',
            'कुल कीमत',
        ];
        return Excel::download(new ReportExport($data, $headings), 'ProductReport.xlsx');

    }


}
