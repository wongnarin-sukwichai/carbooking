<?php

namespace App\Http\Controllers\Head;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. ดึงข้อมูลคิวที่ "รอพิจารณา (pending)" มาโชว์ที่หน้าแรก (จำกัดแค่ 5 รายการล่าสุด)
        $pendingBookings = Booking::with(['user', 'car'])
                            ->where('status', 'pending')
                            ->latest()
                            ->take(5)
                            ->get();

        // 2. นับจำนวนสถิติต่างๆ เพื่อโชว์ในกล่องสรุป
        $stats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'total_this_month' => Booking::whereMonth('start_time', date('m'))->count(),
        ];

        // 3. ส่งข้อมูลไปที่หน้า View
        return view('head.dashboard', compact('pendingBookings', 'stats'));
    }
}