{{-- ไฟล์: resources/views/components/alerts.blade.php --}}

@if(session('success') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // เช็กก่อนว่าหน้าเว็บนี้มีการเรียกใช้ SweetAlert2 หรือไม่
            if (typeof Swal !== 'undefined') {
                
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ!',
                        text: '{!! session('success') !!}', // ใช้ {!! !!} เผื่อมีข้อความขึ้นบรรทัดใหม่
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: { popup: 'rounded-2xl' }
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด!',
                        text: '{!! session('error') !!}',
                        confirmButtonColor: '#3b82f6',
                        customClass: { popup: 'rounded-2xl' }
                    });
                @endif

            }
        });
    </script>
@endif