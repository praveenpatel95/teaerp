<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Gift;
use App\Models\GiftProduct;
use App\Models\Sale;
use App\Models\SaleProduct;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GiftAssignCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GiftAssign:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gift_product = GiftProduct::first();

        $startDate = Carbon::today()->subMonth(6);
        $endDate = Carbon::today();

        $customer = Customer::where('customer_type', 0)
            ->where('due_balance', '<=', 0)
            ->orwhere('due_balance', null)
            ->get();

        foreach ($customer as $row) {

            $gift_assign = Gift::where('customer_id', $row->id)
                ->latest('gift_date')
                ->first();

            $sale = Sale::where('customer_id', $row->id)->pluck('id')->toarray();
            $sale_product = SaleProduct::whereIn('sale_id', $sale)->sum('qty');


            if ($gift_assign) {
                // if gift assign time is more than 6 month then gift assign
                $req_date = date('Y-m-d', strtotime($gift_assign->gift_date . ' + 6 month'));

                if ($sale_product >= $gift_product->gift_qty) {
                    $gift_store = Sale::join('sale_products', 'sale_products.sale_id', 'sales.id')
                        ->leftjoin('gifts', 'gifts.customer_id', 'sales.customer_id')
                        ->leftjoin(DB::raw('(select sale_id,SUM(qty) SaleQty
                    from sale_products GROUP BY sale_id) as b'),
                            function ($gift_store) {
                                $gift_store->on('b.sale_id', 'sales.id');
                            })
                        ->where('sales.customer_id', $row->id)
                        ->wheredate('sales.sale_date', '>=', $req_date)
                        ->whereBetween('sales.sale_date', [$startDate, $endDate])
                        ->where('SaleQty', '>=', $gift_product->gift_qty)
                        ->latest('gifts.gift_date')
                        ->first();


                    if ($gift_store) {

                        $gift_assign = new Gift();
                        $gift_assign->customer_id = $gift_store->customer_id;
                        $gift_assign->gift_product = $gift_product->product_name;
                        $gift_assign->price = $gift_product->price;
                        $gift_assign->gift_date = date('Y-m-d');
                        $gift_assign->save();
                    }
                }

            } else {

                if ($sale_product >= $gift_product->gift_qty) {

                    $gift_new = Sale::join('sale_products', 'sale_products.sale_id', 'sales.id')
                        ->where('sales.customer_id', $row->id)
                        ->whereBetween(DB::raw('DATE(sales.sale_date)'), [$startDate, $endDate])
                        ->first();

                    $gift_assign = new Gift();
                    $gift_assign->customer_id = $gift_new->customer_id;
                    $gift_assign->gift_product = $gift_product->product_name;
                    $gift_assign->price = $gift_product->price;
                    $gift_assign->gift_date = date('Y-m-d');
                    $gift_assign->save();
                }
            }
        }
    }
}
