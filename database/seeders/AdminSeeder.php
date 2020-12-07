<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(config('admin.admin_first_name')) {
            User::firstOrCreate(
                ['email' => config('admin.admin_email')], [
                    'first_name' => config('admin.admin_first_name'),
                    'middle_name' => config('admin.admin_middle_name'),
                    'last_name' => config('admin.admin_last_name'),
                    'phone_number' => config('admin.admin_phone_number'),
                    'password' =>  Hash::make(config('admin.admin_password')),
                    'role' => config('admin.admin_role'),
                ]
            );
        }
    }
}
