<?php

namespace Database\Seeders;

use App\Models\User; // es para no poner: \App\Models\User::create([
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::create([
            'full_name' => 'Test User09',
            'email' => 'test09@example.com',
            'password' => Hash::make('0123456789'),
        ]);

        User::create([
            'full_name' => 'Test User19',
            'email' => 'test19@example.com',
            'password' => Hash::make('123456789'),
        ]);

        User::create([
            'full_name' => 'Test User29',
            'email' => 'test29@example.com',
            'password' => Hash::make('23456789'),
        ]);

        User::factory(10)->create();
    }
}
