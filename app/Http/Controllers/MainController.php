<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Booking;
use App\Models\User; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✨ เปลี่ยนจาก Cache มาเรียกใช้ DB

class MainController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================
        // 🌟 1. ระบบนับสถิติผู้เข้าชมเว็บไซต์ (เก็บลง Database ถาวร)
        // ==========================================
        $todayDate = date('Y-m-d');
        $thisMonth = date('m');
        $thisYear = date('Y');

        // เช็กว่า Session นี้เคยถูกนับไปแล้วหรือยัง (ป้องกันคนกด F5 รัวๆ)
        if (!$request->session()->has('has_visited_today')) {
            // ถ้ายืนยันว่าเพิ่งเข้าเว็บครั้งแรก ให้มาร์ค Session ไว้
            $request->session()->put('has_visited_today', true);

            // ✨ ค้นหาว่ามีข้อมูลของ "วันนี้" ในฐานข้อมูลหรือยัง
            $stat = DB::table('visitor_stats')->where('date', $todayDate)->first();

            if ($stat) {
                // ถ้ามีข้อมูลของวันนี้แล้ว -> ให้บวกยอดเข้าชมเพิ่ม 1
                DB::table('visitor_stats')->where('date', $todayDate)->increment('views');
            } else {
                // ถ้ายังไม่มี (คุณคือคนแรกของวันนี้) -> ให้สร้างแถวใหม่และตั้งค่าเป็น 1
                DB::table('visitor_stats')->insert([
                    'date' => $todayDate,
                    'views' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // ✨ ดึงข้อมูลสถิติจาก Database มาแสดงผล
        $daily = DB::table('visitor_stats')->where('date', $todayDate)->value('views');
        $monthly = DB::table('visitor_stats')->whereMonth('date', $thisMonth)->whereYear('date', $thisYear)->sum('views');
        $yearly = DB::table('visitor_stats')->whereYear('date', $thisYear)->sum('views');
        $total = DB::table('visitor_stats')->sum('views');

        $visitorStats = [
            'daily' => $daily ?? 0,
            'monthly' => $monthly ?? 0,
            'yearly' => $yearly ?? 0,
            'total' => $total ?? 0,
        ];

        // ==========================================
        // 📊 2. ดึงข้อมูลสถิติระบบจองรถยนต์
        // ==========================================
        $stats = [
            // 1. รถพร้อมให้บริการ
            'cars' => Car::where('status', 'available')->count(),
            
            // 2. รายการอนุมัติแล้ว
            'bookings' => Booking::where('status', 'approved')->count(),
            
            // 3. รออนุมัติ (คิวที่รอหัวหน้า/แอดมินพิจารณา)
            'pending' => Booking::where('status', 'pending')->count(),
            
            // 4. ผู้ใช้งานระบบทั้งหมด
            'users' => User::count(), 
        ];

        // ✨ ส่งทั้งตัวแปร $stats และ $visitorStats ไปที่หน้า main.blade.php
        return view('main', compact('stats', 'visitorStats'));
    }
}