<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * เปิดหน้าแก้ไขข้อมูลส่วนตัว
     */
    public function edit()
    {
        // ส่งตัวแปร user (ดึงข้อมูลของคนที่ล็อกอินอยู่) ไปที่หน้า view
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * อัปเดตข้อมูลส่วนตัว (ชื่อ, อีเมล)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. ตรวจสอบข้อมูล (Validation) - ตัด phone ออก
        $request->validate([
            'name' => 'required|string|max:255',
            // ตรวจสอบรูปแบบอีเมล และต้องไม่ซ้ำในระบบ (ยกเว้นไอดีของตัวเอง)
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้มีผู้ใช้งานแล้วในระบบ',
        ]);

        // 2. อัปเดตข้อมูลลง Database
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // 3. กลับไปหน้าเดิมพร้อมข้อความสำเร็จ
        return back()->with('success', 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }

    /**
     * อัปเดตรหัสผ่านใหม่
     */
    public function updatePassword(Request $request)
    {
        // 1. ตรวจสอบข้อมูลรหัสผ่าน
        $request->validate([
            'current_password' => 'required|current_password', // current_password จะเช็กกับรหัสเดิมในระบบให้อัตโนมัติ
            'password' => 'required|min:8|confirmed', // confirmed จะบังคับให้ต้องส่ง password_confirmation มาและต้องตรงกัน
        ], [
            'current_password.required' => 'กรุณากรอกรหัสผ่านปัจจุบัน',
            'current_password.current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min' => 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
        ]);

        // 2. เข้ารหัสรหัสผ่านใหม่ (Hash) และอัปเดตลง Database
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        // 3. กลับไปหน้าเดิมพร้อมข้อความสำเร็จ
        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }
}