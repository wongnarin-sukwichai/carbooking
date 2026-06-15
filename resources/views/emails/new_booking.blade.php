<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Tahoma, sans-serif; background-color: #f3f4f6; padding: 20px; line-height: 1.6;">
    <div style="max-w: 600px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); overflow: hidden;">
        
        <div style="background-color: #2563eb; height: 6px; width: 100%;"></div>

        <div style="padding: 30px;">
            <h2 style="color: #1e40af; margin-top: 0; border-bottom: 2px solid #eff6ff; padding-bottom: 10px;">🚗 มีคำขอใช้รถยนต์รอพิจารณา</h2>
            <p style="color: #374151; font-size: 16px;">เรียน ผู้พิจารณาคิวรถ,</p>
            <p style="color: #4b5563;">ระบบได้รับคำขออนุญาตใช้รถยนต์ใหม่ กรุณาตรวจสอบรายละเอียดดังนี้:</p>
            
            <div style="background-color: #fefce8; padding: 20px; border-left: 5px solid #eab308; margin: 25px 0; border-radius: 0 8px 8px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <p style="margin: 8px 0; color: #1f2937;"><strong>ผู้จอง:</strong> <span style="color: #2563eb; font-weight: bold;">{{ $booking->user->name ?? 'ไม่ทราบชื่อ' }}</span></p>
                <p style="margin: 8px 0; color: #1f2937;"><strong>รถที่ต้องการ:</strong> <span style="color: #059669; font-weight: bold;">{{ $booking->car->car_name ?? '-' }}</span> (ทะเบียน: {{ $booking->car->license_plate ?? '-' }})</p>
                
                <p style="margin: 8px 0; color: #1f2937;"><strong>คนขับรถ:</strong> <span style="color: #0284c7; font-weight: bold;">{{ $booking->driver ? $booking->driver->first_name . ' ' . $booking->driver->last_name : '- ไม่ระบุ / ขับเอง -' }}</span></p>
                
                <p style="margin: 8px 0; color: #1f2937;"><strong>ปลายทาง:</strong> {{ $booking->destination }}</p>
                <p style="margin: 8px 0; color: #1f2937;"><strong>วัตถุประสงค์:</strong> {{ $booking->purpose }}</p>
                <p style="margin: 8px 0; color: #1f2937;"><strong>ผู้เดินทาง:</strong> {{ $booking->passenger_count }} ท่าน</p>
                
                <hr style="border: none; border-top: 1px dashed #ca8a04; margin: 15px 0;">
                
                <p style="margin: 5px 0; color: #4b5563; font-size: 15px;">
                    <strong>🕒 เริ่ม:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->addYears(543)->format('d/m/Y H:i') }} น.<br>
                    <strong>🏁 ถึง:</strong> {{ \Carbon\Carbon::parse($booking->end_time)->addYears(543)->format('d/m/Y H:i') }} น.
                </p>
            </div>

            <!-- ✨ แก้ไข Route เป็น smart.pending แล้ว -->
            <div style="text-align: center; margin-top: 35px; margin-bottom: 10px;">
                <a href="{{ route('smart.pending') }}" style="background-color: #059669; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.3);">
                    👉 เข้าสู่ระบบเพื่อพิจารณาคำขอ
                </a>
            </div>
            
            <div style="margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center;">
                <p style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">
                    อีเมลฉบับนี้เป็นการแจ้งเตือนอัตโนมัติจากระบบจองรถยนต์ สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม
                </p>
                
                <p style="font-size: 15px; color: #000000; font-weight: bold; margin-top: 5px; background-color: #fee2e2; display: inline-block; padding: 6px 16px; border-radius: 20px;">
                    ⚠️ กรุณาอย่าตอบกลับอีเมลนี้
                </p>
            </div>
        </div>
    </div>
</body>
</html>