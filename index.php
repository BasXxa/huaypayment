<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title -->
    <?php include 'config/config.php'; ?>
    <title><?php echo $title; ?></title>

    <!-- CSS and JS -->
    <link rel="stylesheet" href="assets/css/main.style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fade {
            opacity: 0;
            transition: opacity 0.5s;
            position: absolute;
            visibility: hidden;
        }

        .fade.show {
            opacity: 1;
            visibility: visible;
            position: relative;
        }

        select {
            width: 100%;
            height: 3.5em;
            padding: 12px 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        select:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(38, 143, 255, 0.25);
        }
    </style>
</head>

<body>
    <div class="flex flex-col justify-center items-center" style="height: 100vh;">
        <div id="login-form" class="form-container fade show">
            <div class="logo-container">
                HUAY | LOGIN (แอพผ่อน)
            </div>

            <form class="form" id="loginForm">
                <div class="form-group">
                    <label for="phone"><i class="fa-solid fa-phone"></i> เบอร์มือถือ</label>
                    <input type="text" id="login_phone" name="phone" placeholder="เบอร์มือถือ">
                </div>

                <button class="form-submit-btn" type="submit">LOGIN</button>
            </form>

            <p class="signup-link">
                <a href="#" class="signup-link link" onclick="showRegisterForm()"> สมัครสมาชิก</a>
            </p>
        </div>

        <div id="register-form" class="form-container fade">
            <div class="logo-container">
                HUAY | REGISTER (แอพผ่อน)
            </div>

            <form class="form" id="registerForm">
                <div class="form-group">
                    <label for="phone"><i class="fa-solid fa-phone"></i> เบอร์โทรเจ้าของตู้</label>
                    <input type="text" id="register_phone" name="phone" placeholder="เบอร์โทรเจ้าของตู้">
                </div>
                <div class="form-group">
                    <label for="sales_phone"><i class="fa-solid fa-phone"></i> เบอร์โทรเซลล์</label>
                    <input type="text" id="sales_phone" name="sales_phone" placeholder="เบอร์โทรเซลล์">
                </div>
                <div class="form-group">
                    <label for="admin_phone"><i class="fa-solid fa-phone"></i> เบอร์แอดมิน</label>
                    <input type="text" id="admin_phone" name="admin_phone" placeholder="เบอร์แอดมิน">
                </div>
                <div class="flex justify-between gap-2">
                    <div class="form-group">
                        <label for="firstname"><i class="fa-solid fa-user"></i> ชื่อ</label>
                        <input type="text" id="firstname" name="firstname" placeholder="ชื่อ">
                    </div>
                    <div class="form-group">
                        <label for="lastname"><i class="fa-solid fa-user"></i> นามสกุล</label>
                        <input type="text" id="lastname" name="lastname" placeholder="นามสกุล">
                    </div>
                </div>
                <div class="flex gap-2 justify-between">
                    <div class="form-group">
                        <label for="subdistrict"><i class="fa-solid fa-map-marker-alt"></i> ตำบล</label>
                        <input type="text" id="subdistrict" name="subdistrict" placeholder="ตำบล">
                    </div>
                    <div class="form-group">
                        <label for="district"><i class="fa-solid fa-map-marker-alt"></i> อำเภอ</label>
                        <input type="text" id="district" name="district" placeholder="อำเภอ">
                    </div>
                </div>
                <div class="flex justify-between gap-2">
                    <div class="form-group">
                        <label for="address"><i class="fa-solid fa-map-marker-alt"></i> ที่อยู่</label>
                        <input type="text" id="address" name="address" placeholder="ที่อยู่">
                    </div>
                    <div class="form-group">
                        <label for="province"><i class="fa-solid fa-map-marker-alt"></i> จังหวัด</label>
                        <select id="province" name="province"></select>
                    </div>
                </div>

                <button class="form-submit-btn" type="submit">REGISTER</button>
            </form>

            <p class="login-link">
                <a href="#" class="login-link link flex justify-center item-center" onclick="showLoginForm()"> กลับไปยังหน้าเข้าสู่ระบบ</a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener('submit', function(event) {
            event.preventDefault();
            const phone = document.getElementById("login_phone").value;
            if (!phone) {
                Swal.fire({
                    icon: 'error',
                    title: 'ทำรายการไม่สำเร็จ',
                    text: 'กรุณากรอกเบอร์มือถือ',
                    confirmButtonText: 'ตกลง'
                });
            } else {
                fetch('api/login_db.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `phone=${phone}`
                }).then(response => response.text()).then(data => {
                    if (data === 'เข้าสู่ระบบสำเร็จ') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ทำรายการสำเร็จ',
                            text: 'เข้าสู่ระบบสำเร็จ',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'page/main.php';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ทำรายการไม่สำเร็จ',
                            text: data,
                            confirmButtonText: 'ตกลง'
                        });
                    }
                });
            }
        });

        document.getElementById("registerForm").addEventListener('submit', function(event) {
            event.preventDefault();
            const phone = document.getElementById("register_phone").value;
            const salesphone = document.getElementById("sales_phone").value;
            const adminphone = document.getElementById("admin_phone").value;
            const firstname = document.getElementById("firstname").value;
            const lastname = document.getElementById("lastname").value;
            const subdistrict = document.getElementById("subdistrict").value;
            const district = document.getElementById("district").value;
            const address = document.getElementById("address").value;
            const province = document.getElementById("province").value;

            if (!phone || !salesphone || !adminphone || !firstname || !lastname || !subdistrict || !district || !address || !province) {
                Swal.fire({
                    icon: 'error',
                    title: 'ทำรายการไม่สำเร็จ',
                    text: 'กรุณากรอกข้อมูลให้ครบถ้วน',
                    confirmButtonText: 'ตกลง'
                });
            } else {
                fetch('api/register_db.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `phone=${phone}&salesphone=${salesphone}&adminphone=${adminphone}&firstname=${firstname}&lastname=${lastname}&subdistrict=${subdistrict}&district=${district}&address=${address}&province=${province}`
                }).then(response => response.text()).then(data => {
                    if (data === 'สมัครสมาชิกสำเร็จ') {
                        Swal.fire({
                            icon: 'success',
                            title: 'ทำรายการสำเร็จ',
                            text: data,
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        }).then(() => {
                            showLoginForm();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ทำรายการไม่สำเร็จ',
                            text: data,
                            confirmButtonText: 'ตกลง'
                        });
                    }
                });
            }
        });

        const provinces = [
            "กรุงเทพมหานคร", "กระบี่", "กาญจนบุรี", "กาฬสินธุ์", "กำแพงเพชร", "ขอนแก่น", "จันทบุรี", "ฉะเชิงเทรา",
            "ชลบุรี", "ชัยนาท", "ชัยภูมิ", "ชุมพร", "เชียงใหม่", "เชียงราย", "ตรัง", "ตราด", "ตาก", "นครนายก",
            "นครปฐม", "นครพนม", "นครราชสีมา", "นครศรีธรรมราช", "นครสวรรค์", "นนทบุรี", "นราธิวาส", "น่าน",
            "บึงกาฬ", "บุรีรัมย์", "ปทุมธานี", "ประจวบคีรีขันธ์", "ปราจีนบุรี", "ปัตตานี", "พระนครศรีอยุธยา",
            "พังงา", "พัทลุง", "พิจิตร", "พิษณุโลก", "เพชรบุรี", "เพชรบูรณ์", "แพร่", "พะเยา", "ภูเก็ต", "มหาสารคาม",
            "มุกดาหาร", "แม่ฮ่องสอน", "ยโสธร", "ยะลา", "ร้อยเอ็ด", "ระนอง", "ระยอง", "ราชบุรี", "ลพบุรี", "ลำปาง",
            "ลำพูน", "เลย", "ศรีสะเกษ", "สกลนคร", "สงขลา", "สตูล", "สมุทรปราการ", "สมุทรสงคราม", "สมุทรสาคร",
            "สระแก้ว", "สระบุรี", "สิงห์บุรี", "สุโขทัย", "สุพรรณบุรี", "สุราษฎร์ธานี", "สุรินทร์", "หนองคาย",
            "หนองบัวลำภู", "อ่างทอง", "อำนาจเจริญ", "อุดรธานี", "อุตรดิตถ์", "อุทัยธานี", "อุบลราชธานี"
        ];

        const provinceSelect = document.getElementById('province');
        provinces.forEach(province => {
            const option = document.createElement('option');
            option.value = province;
            option.textContent = province;
            provinceSelect.appendChild(option);
        });

        function showLoginForm() {
            document.getElementById('login-form').classList.add('show');
            document.getElementById('register-form').classList.remove('show');
        }

        function showRegisterForm() {
            document.getElementById('login-form').classList.remove('show');
            document.getElementById('register-form').classList.add('show');
        }
    </script>
</body>

</html>