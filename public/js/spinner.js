// ไฟล์: public/js/spinner.js

function showLoading(formElement, loadingText = 'กำลังดำเนินการ...') {
    // 1. ปิดการทำงานของปุ่ม Submit ทันที เพื่อกันคนกดเบิ้ล
    const btn = formElement.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    // 2. ตรวจสอบว่าหน้าเว็บนี้มี SweetAlert ติดตั้งอยู่ไหม (ถ้ามีให้โชว์ Popup)
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: loadingText,
            html: '<span class="text-sm text-gray-500">กรุณารอสักครู่ ระบบกำลังประมวลผล...</span>',
            allowOutsideClick: false, // ห้ามคลิกข้างนอก
            allowEscapeKey: false,    // ห้ามกดปุ่ม ESC
            showConfirmButton: false, // ซ่อนปุ่ม "ตกลง"
            didOpen: () => {
                // สั่งให้แสดงไอคอนโหลดหมุนๆ
                Swal.showLoading();
            }
        });
    } else {
        // สำรองไว้เผื่อบางหน้าไม่มี SweetAlert ก็ให้เปลี่ยนข้อความที่ปุ่มแทน
        if (btn) btn.innerHTML = `⏳ ${loadingText}`;
    }

    // 3. อนุญาตให้ฟอร์มส่งข้อมูล (Submit) ต่อไปหลังบ้าน
    return true; 
}