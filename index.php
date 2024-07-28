<?php
// Sambungan ke pangkalan data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kehadiran";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}

// Initialize variables
$message = ""; // Ensure $message is initialized
$nama = "";
$masa_masuk = "";
$tarikh = "";
$hari = "";
$ic = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ic = $_POST['ic'];

    if (isset($_POST['search'])) {
        // Semak IC dalam pangkalan data
        $sql = "SELECT * FROM staff WHERE ic = '$ic'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $staff = $result->fetch_assoc();
            $nama = $staff['nama'];
        } else {
            $message = "Nombor IC tidak didaftarkan sebagai staff.";
            $message_class = "error";
        }
    }

    if (isset($_POST['submit'])) {
        // Semak IC dalam pangkalan data
        $sql = "SELECT * FROM staff WHERE ic = '$ic'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $staff = $result->fetch_assoc();
            $nama = $staff['nama'];
            $staff_id = $staff['id'];

            // Semak jika sudah submit hari ini
            date_default_timezone_set('Asia/Kuala_Lumpur');
            $tarikh = date("Y-m-d");
            $hari = date("l");
            $masa_masuk = date("H:i:s");
            $masa_keluar = "22:30:00"; // Tetapkan masa keluar

            $sql = "SELECT * FROM kehadiran WHERE staff_id = $staff_id AND tarikh = '$tarikh'";
            $result = $conn->query($sql);

            if ($result->num_rows == 0) {
                // Masukkan rekod kehadiran
                $sql = "INSERT INTO kehadiran (staff_id, tarikh, masa_masuk, masa_keluar) VALUES ($staff_id, '$tarikh', '$masa_masuk', '$masa_keluar')";
                if ($conn->query($sql) === TRUE) {
                    $message = "Anda sudah submit.";
                    $message_class = "success";
                } else {
                    $message = "Ralat: " . $conn->error;
                    $message_class = "error";
                }
            } else {
                $message = "Anda sudah submit hari ini.";
                $message_class = "warning";
            }
        } else {
            $message = "Nombor kad pengenalan tidak dijumpai.";
            $message_class = "error";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Kehadiran Staff</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <div class="header">
        <img src="../kehadiranstaff/css/logo2.png" height="150px" id="img2" alt="Logo">
        <h1>PUSAT TUSYEN FAIZA JAYA SEMENYIH 1</h1>

        <div class="link-image">
            <a href="overtime.php">
                <img src="../kehadiranstaff/css/ott.png" alt="Overtime" id="img4">
            </a>
        </div>
        <div class="link-image">
            <a href="admin_login.php">
                <img src="../kehadiranstaff/css/admin.png" alt="Admin Login" id="img3">
            </a>
        </div>
    </div>
    </div>

    <!-- Button to toggle the info box -->
    <button onclick="toggleInfoBox()" class="info-btn">Maklumat Lanjut</button>

    <!-- Icon for phone call -->
    <div class="phone-icon" onclick="togglePhoneNumber()">
        <img src="../kehadiranstaff/css/phoneicon.png" alt="Call" height="150px">
    </div>

    <!-- Info box -->
    <div id="infoBox" class="info-box">
        <div class="info-content">
            <span onclick="closeInfoBox()" class="close-btn">&times;</span>
            <h4>SISTEM KEHADIRAN STAFF : </h4>
            <ul>
                <li>Sistem ini hanya menerima submit kehadiran sehari sekali.</li>
                <li>Jika staff masuk sebelum 4:30 PM maka masa masuk akan direkodkan sebagai 4:30 PM.</li>
                <li>Waktu keluar tetap pada 10:30 PM.</li>
                <li>Maklumat ini membantu dalam pengiraan gaji bulanan.</li>
                <li>Hubungi admin jika mempunyai sebarang masalah.</li>
            </ul>
        </div>
    </div>

    <!-- Phone number section -->
    <div id="phoneNumber" class="phone-number" style="display: none;">
        <p>Hubungi Admin: </p>
        <p>+60 123 456 789 (Cikgu Naz)</p>
    </div>

    <div class="container">
        <h2>SISTEM KEHADIRAN STAFF</h2>
        <h3>PUSAT TUSYEN FAIZA JAYA</h3>

        <img src="../kehadiranstaff/css/logo.png" height="200px" id="img1">
        <form method="post">
            <label for="ic">MASUKKAN NOMBOR KAD PENGENALAN ANDA : </label>
            <input type="text" id="ic" name="ic" value="<?php echo htmlspecialchars($ic, ENT_QUOTES, 'UTF-8'); ?>" required>
            <button type="submit" name="search">CARI</button>
        </form>
        <?php if (!empty($nama)) { ?>
            <p>NAMA : <?php echo htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?></p>
            <form method="post">
                <input type="hidden" name="ic" value="<?php echo htmlspecialchars($ic, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" name="submit">SUBMIT KEHADIRAN</button>
            </form>
        <?php } ?>
        <p class="message <?php echo $message_class; ?>">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>
        <?php if (isset($_POST['submit']) && !empty($message) && $message_class === "success") { ?>
            <p>TARIKH: <?php echo htmlspecialchars($tarikh, ENT_QUOTES, 'UTF-8'); ?></p>
            <p>HARI: <?php echo htmlspecialchars($hari, ENT_QUOTES, 'UTF-8'); ?></p>
            <p>MASA: <?php echo htmlspecialchars($masa_masuk, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </div>

    <script>
        function toggleInfoBox() {
            var infoBox = document.getElementById('infoBox');
            infoBox.style.display = infoBox.style.display === 'none' || infoBox.style.display === '' ? 'block' : 'none';
        }

        function closeInfoBox() {
            document.getElementById('infoBox').style.display = 'none';
        }

        function togglePhoneNumber() {
            var phoneNumber = document.getElementById('phoneNumber');
            phoneNumber.style.display = phoneNumber.style.display === 'none' || phoneNumber.style.display === '' ? 'block' : 'none';
        }
    </script>

    <div class="footer">
        © 2024 Sistem Kehadiran
    </div>
</body>
</html>
