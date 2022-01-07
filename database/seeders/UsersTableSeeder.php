<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Utility;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminUser = User::create([
                                      'name' => 'Admin',
                                      'email' => 'admin@example.com',
                                      'password' => Hash::make('1234'),
                                      'type' => 'admin',
                                  ]);

        Utility::add_landing_page_data();
    }
}
