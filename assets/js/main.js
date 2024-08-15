function setting() {
    Swal.fire({
        title: 'กรุณาใส่รหัสผ่าน',
        input: 'password',
        inputAttributes: {
            maxlength: 6,
            autocapitalize: 'off',
            autocorrect: 'off'
        },
        inputValidator: (value) => {
            if (value !== '109158') {
                return 'รหัสผ่านไม่ถูกต้อง!'
            }
        },
        showCancelButton: true,
        confirmButtonText: 'ตกลง',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed && result.value === '109158') {
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'รหัสผ่านถูกต้อง',
                icon: 'success',
                timer: 1000,
                timerProgressBar: true,
                showConfirmButton: false,
                willClose: () => {
                    window.location.href = 'setting.php';
                }
            });
        }
    });
}

function logout() {
    Swal.fire({
        title: 'คุณต้องการออกจากระบบใช่หรือไม่?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../api/logout_db.php';
        }
    });
}