<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
        ['email' => 'admin@admin.com'],
        ['name' => 'مدير النظام', 'password' => bcrypt('password')]
        );
        \App\Models\User::firstOrCreate(
        ['email' => 'ahmed@example.com'],
        ['name' => 'أحمد المبيعات', 'password' => bcrypt('password')]
        );
        \App\Models\User::firstOrCreate(
        ['email' => 'sara@example.com'],
        ['name' => 'سارة مبيعات', 'password' => bcrypt('password')]
        );
        \App\Models\User::firstOrCreate(
        ['email' => 'khaled@example.com'],
        ['name' => 'خالد مبيعات', 'password' => bcrypt('password')]
        );
    }
}
