<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }

        $users = $query->orderByRaw("FIELD(role, 'admin', 'staff_head', 'staff')")
                       ->orderByRaw("CONVERT(name USING tis620) ASC")
                       ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|ends_with:@msu.ac.th',
            'role'  => 'required|in:admin,staff_head,staff',
        ], [
            'email.ends_with' => 'อีเมลต้องเป็นบัญชีองค์กร @msu.ac.th เท่านั้น',
        ]);

        User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'เพิ่มผู้ใช้งานสำเร็จ');
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'ไม่สามารถลบบัญชีของตนเองได้');
        }

        $hasBookings = \App\Models\Booking::where('user_id', $user->id)->exists();

        if ($hasBookings) {
            return redirect()->route('admin.users.index')->with('error', 'ไม่อนุญาตให้ลบ! เนื่องจากผู้ใช้งานนี้มี "ประวัติการจองรถ" อยู่ในระบบ');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'ลบผู้ใช้งานสำเร็จ');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . '|ends_with:@msu.ac.th',
            'role'  => 'required|in:admin,staff_head,staff',
        ], [
            'email.ends_with' => 'อีเมลต้องเป็นบัญชีองค์กร @msu.ac.th เท่านั้น',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'อัปเดตข้อมูลผู้ใช้งานสำเร็จ');
    }
}
