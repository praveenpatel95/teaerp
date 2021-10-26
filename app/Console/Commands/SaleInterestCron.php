<?php

namespace App\Console\Commands;

use App\Http\Controllers\admin\SaleInterestController;
use App\Models\InterestSetting;
use App\Models\Route;
use App\Models\Sale;
use App\Models\SaleInterest;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class SaleInterestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SaleInterest:cron';

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
        //interest setting data
        $setting_data = InterestSetting::first();

        //for getting set date by days
        $setting_date = Carbon::now()
            ->subDays($setting_data->days);

        //sale data
        $sale_data = Sale::leftjoin(DB::raw('(select sale_id, SUM(pay_amount) paid
                                from sale_payments GROUP BY sale_id) as a'), function ($sale_data) {
            $sale_data->on('a.sale_id', 'sales.id');
        })
            ->where('sales.sale_date', '<=', $setting_date)
            ->where('sales.is_interest', 0)
            ->where(DB::raw('sales.total_amount - paid  '), '>', 0)
            ->select('sales.*', DB::raw('sales.total_amount - paid as due'))
            ->get();

        foreach ($sale_data as $row) {
            //calculating interest
            $interest = $row->due * $setting_data->percent * 0.01;

            //storing interest data
            $store = new SaleInterest();
            $store->sale_id = $row->id;
            $store->amount = $row->due;
            $store->percentage = $setting_data->percent;
            $store->interest_amount = $interest;
            $store->interest_date = date('Y-m-d');
            $store->save();

            //update sale is_interest Status to 1 for recursive interest
            $update = Sale::find($row->id);
            $update->is_interest = 1;
            $update->last_recursive_date = date('Y-m-d');
            $update->save();

        }
    }
}
