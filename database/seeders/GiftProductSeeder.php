<?php

namespace Database\Seeders;

use App\Models\GiftProduct;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GiftProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gift_products')->updateOrInsert([
            'id' => '1',
            'product_name' => 'Samsumng j7',
            'price' => '12000',
            'gift_qty' => '5',
        ]);

    }
}
