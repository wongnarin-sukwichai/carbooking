/**
 * ไฟล์: public/js/btn-loading.js
 * ใช้สำหรับเปลี่ยนสถานะปุ่ม Submit เป็น Loading spinner หลังจากกดส่งฟอร์มแล้ว
 */

function showLoading(form, loadingText) {
    // หาปุ่ม Submit ในฟอร์มนั้นๆ (อาจจะหาจาก id=submitBtn หรือหา type=submit ก็ได้)
    const btn = form.querySelector('#submitBtn') || form.querySelector('button[type="submit"]');
    
    if (btn) {
        // ปิดการกดซ้ำ
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        
        // ใส่ SVG Spinner เข้าไปแทนเนื้อหาเดิม
        btn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg> 
            ${loadingText || 'กำลังประมวลผล...'}
        `;
    }
    
    // คืนค่า true เพื่อให้ฟอร์มทำงาน submit ต่อไป
    return true;
}