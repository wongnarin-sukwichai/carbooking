<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Tahoma, sans-serif; background-color: #f3f4f6; padding: 20px; line-height: 1.6;">

    @php
        $isApproved = $booking->status === 'approved';
        $themeColor = $isApproved ? '#10b981' : '#ef4444'; 
        $statusText = $isApproved ? 'อนุมัติให้ใช้รถยนต์ได้' : 'ไม่อนุมัติให้ใช้รถยนต์';
        $bgColor = $isApproved ? '#f0fdf4' : '#fef2f2';
    @endphp

    <div style="max-w: 600px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden;">
        
        <div style="background-color: {{ $themeColor }}; height: 6px; width: 100%;"></div>

        <div style="padding: 30px;">
            <h2 style="color: {{ $themeColor }}; margin-top: 0; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">
                {{ $isApproved ? '✅ แจ้งผลการพิจารณา (อนุมัติ)' : '❌ แจ้งผลการพิจารณา (ไม่อนุมัติ)' }}
            </h2>
            
            <p style="color: #374151; font-size: 16px;">เรียน คุณ <strong>{{ $booking->user->name }}</strong>,</p>
            
            <p style="color: #4b5563;">
                คำขออนุญาตใช้รถยนต์ของคุณได้รับการพิจารณาเรียบร้อยแล้ว โดยมีรายละเอียดดังนี้:
            </p>

            <div style="background-color: {{ $bgColor }}; padding: 20px; border-left: 5px solid {{ $themeColor }}; margin: 25px 0; border-radius: 0 8px 8px 0;">
                <p style="margin: 8px 0; font-size: 16px;">
                    <strong>สถานะ:</strong> 
                    <span style="color: {{ $themeColor }}; font-weight: bold; padding: 4px 8px; background: white; border-radius: 4px;">
                        {{ $statusText }}
                    </span>
                </p>
                <hr style="border: none; border-top: 1px dashed #d1d5db; margin: 15px 0;">
                <p style="margin: 8px 0; color: #1f2937;"><strong>รถที่จอง:</strong> {{ $booking->car->car_name ?? '-' }}</p>
                
                <p style="margin: 8px 0; color: #1f2937;"><strong>คนขับรถ:</strong> {{ $booking->driver ? $booking->driver->first_name . ' ' . $booking->driver->last_name : '- ไม่ระบุ / ขับเอง -' }}</p>
                
                <p style="margin: 8px 0; color: #1f2937;"><strong>ปลายทาง:</strong> {{ $booking->destination }}</p>
                <p style="margin: 8px 0; color: #4b5563; font-size: 14px;">
                    <strong>เริ่ม:</strong> {{ \Carbon\Carbon::parse($booking->start_time)->addYears(543)->format('d/m/Y H:i') }} น.<br>
                    <strong>ถึง:</strong> {{ \Carbon\Carbon::parse($booking->end_time)->addYears(543)->format('d/m/Y H:i') }} น.
                </p>
            </div>

            <div style="margin-top: 20px; padding: 15px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px;">
                <p style="margin: 0 0 5px 0; color: #64748b; font-size: 13px; font-weight: bold;">📝 หมายเหตุจากผู้พิจารณา:</p>
                <p style="margin: 0; color: #1e293b; font-size: 15px;">
                    {!! $booking->head_remark ? nl2br(e($booking->head_remark)) : '<span style="color: #94a3b8; font-style: italic;">- ไม่มีหมายเหตุเพิ่มเติม -</span>' !!}
                </p>
            </div>

            <!-- ✨ ส่วนที่ปรับแก้: เปลี่ยนลิงก์ปุ่มสีน้ำเงินให้ไปที่หน้ารายละเอียดเต็มรูปแบบ -->
            <div style="text-align: center; margin-top: 35px; margin-bottom: 10px;">
                <a href="{{ route('main') }}" style="background-color: #ffffff; color: #4b5563; padding: 12px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; border: 1px solid #d1d5db; margin: 0 5px 10px 5px;">
                    📅 ดูปฏิทินหน้าหลัก
                </a>
                
                {{-- ✨ เปลี่ยนลิงก์เป็น bookings.show พร้อมส่ง ID คิวรถไปด้วย --}}
                <a href="{{ route('bookings.show', $booking->id) }}" style="background-color: #2563eb; color: white; padding: 12px 20px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; border: 1px solid #2563eb; margin: 0 5px 10px 5px;">
                    🖨️ ดูรายละเอียด / พิมพ์เอกสาร
                </a>
            </div>
            
            <div style="margin-top: 30px; text-align: center;">
                <p style="font-size: 13px; color: #9ca3af; margin-top: 20px; border-top: 1px solid #f3f4f6; padding-top: 15px;">
                    ระบบจองรถยนต์ สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม<br>กรุณาอย่าตอบกลับอีเมลนี้
                </p>
            </div>
        </div>
    </div>
</body>
</html>