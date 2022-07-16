<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'phone' => '0987654321',
                'name' => 'Admin',
                'password' => Hash::make('123123123'),
                'salary' => 0,
                'idNumber' => '19283463732',
                'address' => 'Ninh Bình',
                'role_id' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
            ]);
    }
}
