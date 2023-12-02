<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::firstOrCreate(
            [
                "email" => "misujon01@gmail.com"
            ],
            [
                "name" => "Admin",
                "username" => "admin",
                "email" => "misujon01@gmail.com",
                "password" => \Hash::make("12345678"),
                "pin" => \Hash::make("1144"),
                "role_type" => 1,
            ]
        );
    }
}
