<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. สร้างบัญชี Admin
        User::create([
            'name' => 'ผู้ดูแลระบบ',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'), // เข้ารหัสผ่านให้ถูกต้อง
            'role' => 'admin',
        ]);

        // 2. สร้างบัญชี หัวหน้า (Staff Head)
        User::create([
            'name' => 'หัวหน้าแผนก',
            'email' => 'siwakornssp@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'staff_head',
        ]);

        // 3. สร้างบัญชี พนักงานทั่วไป (Staff)
        User::create([
            'name' => 'พนักงาน ทั่วไป',
            'email' => 'staff@test.com',
            'password' => Hash::make('123456'),
            'role' => 'staff',
        ]);
    }
}