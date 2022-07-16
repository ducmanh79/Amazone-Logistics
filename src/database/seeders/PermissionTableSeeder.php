<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
            ['name' => 'create_order'],
            ['name' => 'edit_order'],
            ['name' => 'delete_order'],
            ['name' => 'add_goods'],
            ['name' => 'edit_goods'],
            ['name' => 'delete_goods'],
            ['name' => 'create_carrental'],
            ['name' => 'edit_carrental'],
            ['name' => 'create_car'],
            ['name' => 'edit_car'],
            ['name' => 'confirm_goods'],
            ['name' => 'cancel_confirm_goods'],
            ['name' => 'load_goods_on_car'],
            ['name' => 'edit_fare_of_car'],
            ['name' => 'cancel_goods_on_the_car'],
            ['name' => 'manage_inventory'],
            ['name' => 'manage_goods_in_car'],
            ['name' => 'create_cost_of_car'],
            ['name' => 'edit_cost_of_car'],
            ['name' => 'delete_cost_of_car'],
            ['name' => 'confirm_collected_money_from_car'],
            ['name' => 'cancel_confirm_collected_money_from_car'],
        ]);
    }
}
