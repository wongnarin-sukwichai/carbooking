<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CarController extends Controller
{
    // 1. หน้าแสดงรายการรถ
    public function index(Request $request)
    {
        $query = Car::query();

        // 🔍 ตรวจสอบว่ามีการพิมพ์คำค้นหา (search) เข้ามาหรือไม่
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('car_name', 'LIKE', "%{$search}%")
                  ->orWhere('license_plate', 'LIKE', "%{$search}%")
                  ->orWhere('brand', 'LIKE', "%{$search}%");
            });
        }

        // ✨ ดึงข้อมูลทีละ 10 รายการ เรียงลำดับตามชื่อรถ (เรียงภาษาไทยถูกต้องเป๊ะ 100%)
        $cars = $query->orderByRaw("CONVERT(car_name USING tis620) ASC")->paginate(10); 
        
        return view('admin.cars.index', compact('cars'));
    }

    // 2. หน้าฟอร์มเพิ่มรถ
    public function create()
    {
        return view('admin.cars.create');
    }

    // 3. บันทึกข้อมูลรถใหม่ (Store)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'car_name' => 'required|string|max:255',
            'license_plate' => 'required|string|unique:cars,license_plate|max:255',
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'status' => 'required|in:available,maintenance',
            'pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'license_plate.unique' => 'ทะเบียนรถนี้มีอยู่ในระบบแล้ว' // เพิ่มข้อความแจ้งเตือนภาษาไทย
        ]);

        // ถ้ามีการอัปโหลดรูปรถมา
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            // ตั้งชื่อไฟล์ใหม่ ป้องกันชื่อซ้ำ (ใช้เวลาปัจจุบัน + ชื่อไฟล์เดิม)
            $filename = time() . '_' . $file->getClientOriginalName();
            // ย้ายไฟล์ไปไว้ในโฟลเดอร์ public/img
            $file->move(public_path('img'), $filename);
            // เก็บแค่ "ชื่อไฟล์" ลงใน Database
            $validated['pic'] = $filename;
        }

        Car::create($validated);

        return redirect()->route('admin.cars.index')->with('success', 'เพิ่มรถยนต์สำเร็จ!');
    }
    
    // หน้าฟอร์มแก้ไขรถ (Edit)
    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    // 4. บันทึกการแก้ไขข้อมูลรถ (Update)
    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'car_name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:255|unique:cars,license_plate,' . $car->id,
            'brand' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'status' => 'required|in:available,maintenance',
            'pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'license_plate.unique' => 'ทะเบียนรถนี้มีอยู่ในระบบแล้ว' // เพิ่มข้อความแจ้งเตือนภาษาไทย
        ]);

        // ถ้ามีการอัปโหลดรูปใหม่มาทับ
        if ($request->hasFile('pic')) {
            // 1. ลบรูปเก่าทิ้งก่อน (ถ้ามีไฟล์เก่าอยู่จริง)
            if ($car->pic && File::exists(public_path('img/' . $car->pic))) {
                File::delete(public_path('img/' . $car->pic));
            }
            
            // 2. เซฟรูปใหม่
            $file = $request->file('pic');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $validated['pic'] = $filename;
        }

        $car->update($validated);

        return redirect()->route('admin.cars.index')->with('success', 'อัปเดตข้อมูลรถยนต์สำเร็จ!');
    }

    // 5. ลบข้อมูลรถ (Destroy)
    public function destroy(Car $car)
    {
        // 🛡️ เช็กก่อนว่ารถคันนี้เคยมีประวัติถูกจองหรือไม่
        $hasBookings = \App\Models\Booking::where('car_id', $car->id)->exists();
        if ($hasBookings) {
            return redirect()->route('admin.cars.index')->with('error', 'ไม่อนุญาตให้ลบ! รถคันนี้มีประวัติการถูกจองอยู่ในระบบ แนะนำให้เปลี่ยนสถานะเป็น "ส่งซ่อม / บำรุงรักษา" แทนครับ');
        }

        // เช็กว่ามีรูปไหม ถ้ามีให้ตามไปลบไฟล์ในโฟลเดอร์ img ทิ้งด้วย
        if ($car->pic && File::exists(public_path('img/' . $car->pic))) {
            File::delete(public_path('img/' . $car->pic));
        }

        $car->delete();
        
        return redirect()->route('admin.cars.index')->with('success', 'ลบข้อมูลรถยนต์สำเร็จ!');
    }
}