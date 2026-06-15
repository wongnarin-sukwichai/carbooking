<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    /**
     * 🌟 1. ระบบยืนยันการออกจากระบบ (Global Function)
     */
    function confirmLogout() {
        Swal.fire({
            title: 'ออกจากระบบ?',
            text: "คุณยืนยันที่จะออกจากระบบใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'ยืนยัน, ออกจากระบบ',
            cancelButtonText: 'ยกเลิก',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl px-6 py-2.5 text-sm font-bold',
                cancelButton: 'rounded-xl px-6 py-2.5 text-sm font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const logoutForm = document.getElementById('logout-form');
                if (logoutForm) {
                    logoutForm.submit();
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 🌟 2. ระบบแจ้งเตือน Success / Error อัตโนมัติ ---
        
        // กรณีทำงานสำเร็จ (Success)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: '{!! session('success') !!}',
                showConfirmButton: false,
                timer: 2000,
                customClass: { popup: 'rounded-2xl' }
            });
        @endif

        // ✨ กรณีเกิดข้อผิดพลาด (เช่น คิวรถ/คนขับชนกัน) 
        @if(session('error'))
            // ใช้ nl2br เพื่อแปลง \n เป็น <br> ให้อ่านง่ายขึ้น
            const errorMessage = {!! json_encode(nl2br(e(session('error')))) !!}; 
            
            Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถดำเนินการได้!',
                html: `
                    <div style="text-align: left; font-size: 0.95em; line-height: 1.6; margin-top: 12px; padding: 16px; background-color: rgba(239, 68, 68, 0.08); color: #b91c1c; border-radius: 12px; border: 1px solid rgba(239, 68, 68, 0.2);">
                        ${errorMessage}
                    </div>
                `,
                confirmButtonText: 'ตกลง, ฉันเข้าใจแล้ว',
                confirmButtonColor: '#ef4444',
                backdrop: `rgba(0,0,0,0.4)`,
                customClass: { 
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl font-bold px-6 py-2.5'
                }
            });
        @endif

        // --- 🗑️ 3. ระบบยืนยันการลบข้อมูล (Event Delegation) ---
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-delete');
            if (deleteBtn) {
                e.preventDefault();
                const form = deleteBtn.closest('form');
                
                Swal.fire({
                    title: 'ยืนยันการลบข้อมูล?',
                    text: "หากลบแล้วจะไม่สามารถกู้คืนได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#9ca3af',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก',
                    reverseButtons: true,
                    customClass: { popup: 'rounded-2xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                });
            }
        });

        // --- 📱 4. ระบบเปิด/ปิด Sidebar สำหรับหน้าจอมือถือ ---
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (mobileMenuBtn && sidebar) {
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
                if (overlay) overlay.classList.toggle('hidden');
            });

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }
        }
    });
</script>