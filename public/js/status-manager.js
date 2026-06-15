// ไฟล์: public/js/status-manager.js

function changeStatus(updateUrl, currentStatus, currentRemark) {
    const remarkValue = currentRemark && currentRemark !== 'null' ? currentRemark : '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: '<div class="text-xl font-bold">ปรับเปลี่ยนสถานะการจอง</div>',
        html: `
            <form id="statusForm" action="${updateUrl}" method="POST" class="text-left mt-4">
                <input type="hidden" name="_token" value="${csrfToken}">
                <input type="hidden" name="_method" value="PATCH">
                
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">เลือกสถานะใหม่:</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="pending" ${currentStatus === 'pending' ? 'selected' : ''}>🟡 รอพิจารณา (ย้อนกลับสถานะ)</option>
                        <option value="approved" ${currentStatus === 'approved' ? 'selected' : ''}>🟢 อนุมัติแล้ว</option>
                        <option value="rejected" ${currentStatus === 'rejected' ? 'selected' : ''}>🔴 ไม่อนุมัติ</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">หมายเหตุ (ถ้ามี):</label>
                    <textarea name="head_remark" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 resize-none" placeholder="ระบุเหตุผลการอนุมัติ/ไม่อนุมัติ...">${remarkValue}</textarea>
                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'บันทึกสถานะ',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        allowOutsideClick: false, 
        
        preConfirm: () => {
            // 1. เปลี่ยนปุ่มให้เป็นไอคอนหมุนๆ ทันที
            Swal.showLoading(); 
            
            // 2. สั่งเบราว์เซอร์ให้ส่งข้อมูล (วิ่งไปหลังบ้าน)
            document.getElementById('statusForm').submit();
            
            // 3. 🌟 ทริคใหม่: คืนค่า Promise ที่ไม่มีการแจ้งเตือนจบการทำงาน 
            // ทำให้ Pop-up หมุนค้างไว้แบบนั้น จนกว่าหน้าเว็บจะถูกรีเฟรชกลับมาเอง!
            return new Promise(() => {});
        }
    });
}