<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ดึงสถิติการจอง "เฉพาะของพนักงานคนที่ล็อกอินอยู่"
        $totalCount = Booking::where('user_id', $userId)->count();
        $pendingCount = Booking::where('user_id', $userId)->where('status', 'pending')->count();
        $approvedCount = Booking::where('user_id', $userId)->where('status', 'approved')->count();
        $rejectedCount = Booking::where('user_id', $userId)->where('status', 'rejected')->count();

        // ดึงประวัติล่าสุด 5 รายการ
        $recentBookings = Booking::with('car')
                            ->where('user_id', $userId)
                            ->latest()
                            ->take(5)
                            ->get();

        return view('staff.dashboard', compact(
            'totalCount', 'pendingCount', 'approvedCount', 'rejectedCount', 'recentBookings'
        ));
    }
}