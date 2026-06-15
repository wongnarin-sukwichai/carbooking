// ไฟล์: public/js/booking-details.js

function showBookingDetails(booking) {
    // ฟังก์ชันช่วยแปลงเวลาให้เป็นภาษาไทย (เพิ่ม 543 ปี เป็น พ.ศ.)
    const formatThaiDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        date.setFullYear(date.getFullYear()); // แปลง ค.ศ. เป็น พ.ศ. (ถ้าเซิร์ฟเวอร์ส่งมาเป็น ค.ศ. อาจจะต้อง +543 ใน JS ด้วยครับ แต่ถ้าแก้ใน DB/Controller แล้วก็ไม่ต้อง)
        return date.toLocaleDateString('th-TH', {
            year: 'numeric', month: 'short', day: 'numeric',
            hour: '2-digit', minute: '2-digit'
        }) + ' น.';
    };

    // จัดการข้อมูลป้องกันค่า null
    const carName = booking.car ? booking.car.car_name : 'ไม่ระบุ/ถูกลบ';
    const licensePlate = booking.car ? booking.car.license_plate : '-';
    const userName = booking.user ? booking.user.name : 'ไม่ระบุชื่อ';
    
    // ✨ ดึงชื่อคนขับ
    const driverName = booking.driver ? `${booking.driver.first_name} ${booking.driver.last_name}` : '- ไม่ระบุ / ขับเอง -';
    
    const startTime = formatThaiDate(booking.start_time);
    const endTime = formatThaiDate(booking.end_time);

    // กำหนดสีและข้อความของสถานะ
    let statusHtml = '';
    if (booking.status === 'approved') {
        statusHtml = '<span class="px-3 py-1 bg-green-100 text-green-700 border border-green-200 rounded-full text-xs font-bold shadow-sm">🟢 อนุมัติแล้ว</span>';
    } else if (booking.status === 'rejected') {
        statusHtml = '<span class="px-3 py-1 bg-red-100 text-red-700 border border-red-200 rounded-full text-xs font-bold shadow-sm">🔴 ไม่อนุมัติ</span>';
    } else {
        statusHtml = '<span class="px-3 py-1 bg-yellow-100 text-yellow-700 border border-yellow-200 rounded-full text-xs font-bold shadow-sm">🟡 รอพิจารณา</span>';
    }

    // กล่องหมายเหตุ (โชว์เฉพาะเวลามีหมายเหตุจากหัวหน้า)
    const remarkHtml = booking.head_remark 
        ? `<div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg text-sm">
             <strong class="text-gray-700 text-xs">💬 หมายเหตุจากผู้พิจารณา:</strong>
             <p class="text-gray-600 italic mt-1">"${booking.head_remark}"</p>
           </div>` 
        : '';

    // เรียกใช้ SweetAlert2
    Swal.fire({
        html: `
            <div class="text-left mt-2">
                <div class="flex justify-between items-center bg-blue-50/50 p-4 rounded-xl border border-blue-100 mb-4">
                    <div>
                        <span class="block text-xs font-bold text-blue-400 tracking-wider uppercase mb-1">รถยนต์ที่จอง</span>
                        <span class="font-bold text-blue-700 text-xl">${carName}</span>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">ทะเบียน</span>
                        <span class="bg-white border border-gray-200 px-2 py-1 rounded text-sm font-mono font-bold text-gray-800 shadow-sm">${licensePlate}</span>
                    </div>
                </div>

                <div class="flex items-start gap-3 bg-gray-50 p-3 rounded-lg mb-4 border border-gray-100">
                    <span class="text-xl">🕒</span>
                    <div class="text-[13px] text-gray-700">
                        <p><strong class="text-gray-900">เริ่ม:</strong> ${startTime}</p>
                        <p class="mt-1"><strong class="text-gray-900">ถึง:</strong> ${endTime}</p>
                    </div>
                </div>

                <div class="space-y-3 text-[14px] text-gray-700 px-1">
                    <p class="flex items-start gap-2">
                        <span class="mt-0.5 text-gray-400">👤</span> 
                        <span><strong class="font-bold text-gray-900 mr-1">ผู้จอง:</strong> ${userName}</span>
                    </p>
                    
                    <p class="flex items-start gap-2">
                        <span class="mt-0.5 text-gray-400">👨‍✈️</span> 
                        <span><strong class="font-bold text-gray-900 mr-1">คนขับรถ:</strong> <span class="text-indigo-600 font-semibold">${driverName}</span></span>
                    </p>

                    <p class="flex items-start gap-2">
                        <span class="mt-0.5 text-gray-400">📍</span> 
                        <span><strong class="font-bold text-gray-900 mr-1">ปลายทาง:</strong> ${booking.destination} <span class="text-xs text-blue-600 font-bold ml-1 bg-blue-50 px-2 py-0.5 rounded">(${booking.passenger_count} ท่าน)</span></span>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="mt-0.5 text-gray-400">📝</span> 
                        <span><strong class="font-bold text-gray-900 mr-1">วัตถุประสงค์:</strong> ${booking.purpose}</span>
                    </p>
                </div>

                ${remarkHtml}

                <div class="border-t border-gray-100 pt-4 mt-5 flex items-center justify-between px-1">
                    <strong class="text-sm text-gray-500">สถานะคิวรถ:</strong>
                    ${statusHtml}
                </div>
            </div>
        `,
        showCloseButton: true,
        showConfirmButton: false,
        width: '420px',
        customClass: { popup: 'rounded-2xl' }
    });
}