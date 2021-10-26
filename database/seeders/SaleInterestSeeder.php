<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleInterestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('interest_settings')->updateOrInsert([
            'id' => '1',
            'days' => '30',
            'percent' => '5.5',
        ]);
    }
}
