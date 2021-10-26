<?php

namespace Database\Seeders;

use App\Http\Controllers\admin\SaleInterestController;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([RoleSeeder::class]);
        $this->call([UserSeeder::class]);
        $this->call([GiftProductSeeder::class]);
        $this->call([SaleInterestSeeder::class]);

    }
}
