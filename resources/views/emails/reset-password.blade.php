<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Tahoma, sans-serif; background-color: #f3f4f6; padding: 20px; line-height: 1.6;">
    <div style="max-w: 600px; margin: 0 auto; background: white; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); overflow: hidden;">
        
        <div style="background-color: #2563eb; height: 6px; width: 100%;"></div>

        <div style="padding: 30px;">
            <h2 style="color: #1e40af; margin-top: 0; border-bottom: 2px solid #eff6ff; padding-bottom: 10px;">
                🔑 แจ้งเตือนการตั้งรหัสผ่านใหม่
            </h2>
            
            <p style="color: #374151; font-size: 16px;">สวัสดีคุณ <strong>{{ $user->name }}</strong>,</p>
            
            <p style="color: #4b5563;">
                คุณได้รับอีเมลฉบับนี้เนื่องจากระบบได้รับคำขอ <strong>"รีเซ็ตรหัสผ่าน"</strong> สำหรับบัญชีของคุณ หากคุณเป็นผู้ทำรายการนี้ กรุณาคลิกที่ปุ่มด้านล่างเพื่อตั้งรหัสผ่านใหม่ครับ:
            </p>

            <div style="text-align: center; margin-top: 35px; margin-bottom: 35px;">
                <a href="{{ $url }}" style="background-color: #2563eb; color: white; padding: 14px 28px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; font-size: 16px; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);">
                    👉 คลิกที่นี่เพื่อตั้งรหัสผ่านใหม่
                </a>
            </div>

            <div style="background-color: #fefce8; border-left: 4px solid #eab308; padding: 15px; margin-bottom: 20px; border-radius: 0 8px 8px 0;">
                <p style="margin: 0; color: #854d0e; font-size: 14px;">
                    <strong>ข้อควรระวัง:</strong> ลิงก์นี้จะมีอายุการใช้งานเพียง <strong>60 นาที</strong> เท่านั้น หากคุณไม่ได้เป็นผู้ขอรีเซ็ตรหัสผ่าน กรุณาเพิกเฉยต่ออีเมลฉบับนี้ บัญชีของคุณจะยังคงปลอดภัยครับ
                </p>
            </div>
            
            <div style="margin-top: 40px; border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center;">
                <p style="font-size: 13px; color: #6b7280; margin-bottom: 8px;">
                    หากพบปัญหากดปุ่มไม่ได้ กรุณาก๊อปปี้ลิงก์ด้านล่างไปวางในเบราว์เซอร์:<br>
                    <a href="{{ $url }}" style="color: #3b82f6; word-break: break-all;">{{ $url }}</a>
                </p>
                
                <p style="font-size: 15px; color: #000000; font-weight: bold; margin-top: 20px; background-color: #fee2e2; display: inline-block; padding: 6px 16px; border-radius: 20px;">
                    ⚠️ กรุณาอย่าตอบกลับอีเมลนี้
                </p>
            </div>
        </div>
    </div>
</body>
</html>