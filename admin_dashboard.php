<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kehadiran";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}

// Dapatkan senarai staf untuk dropdown
$sql_staff = "SELECT id, nama, ic FROM staff ORDER BY nama";
$result_staff = $conn->query($sql_staff);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin_dashboard.css">
    <script>
        function toggleInfoBox() {
            var infoBox = document.getElementById('info-box');
            if (infoBox.style.display === 'none' || infoBox.style.display === '') {
                infoBox.style.display = 'block';
            } else {
                infoBox.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <img src="../kehadiranstaff/css/logo2.png" height="150px" id="img2" alt="Logo">
        <h1>PUSAT TUSYEN FAIZA JAYA SEMENYIH 1</h1>
        <img src="../kehadiranstaff/css/info.png" height="80px" id="info-icon" alt="Info Icon" onclick="toggleInfoBox()">
    </div>
    <div class="info-box" id="info-box">
        <h4>PERBEZAAN (REKOD KEHADIRAN) DAN (REKOD KEHADIRAN OT) </h4>
        <ul>
        <li><p><strong>Rekod Kehadiran :</strong> Bahagian ini merekodkan kehadiran biasa staff.</p></li>
       <li> <p><strong>Rekod Kehadiran OT :</strong> Bahagian ini merekodkan kehadiran kerja lebih masa staff.</p></li>
    </ul>
    </div>

    <div class="container">
        <h1>ADMIN DASHBOARD</h1>
        <h2>REKOD KEHADIRAN STAFF</h2>
        <img src="../kehadiranstaff/css/kehadiran.png" height="270px" id="img1">

        <!-- Form untuk memilih rekod -->
        <form method="get" class="dashboard-form">
            <label for="staff_id">PILIH NAMA STAFF :</label>
            <select name="staff_id" id="staff_id" required>
                <option value="">-- Sila pilih --</option>
                <?php
                if ($result_staff->num_rows > 0) {
                    while($row_staff = $result_staff->fetch_assoc()) {
                        echo "<option value='" . $row_staff['id'] . "'>" . $row_staff['nama'] . " (" . $row_staff['ic'] . ")</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" name="view" value="attendance">LIHAT REKOD KEHADIRAN</button>
            <button type="submit" name="view" value="overtime">LIHAT REKOD KEHADIRAN OT</button>
        </form>
        
        <?php
        if (isset($_GET['view']) && isset($_GET['staff_id'])) {
            $staff_id = $_GET['staff_id'];
            $view = $_GET['view'];

            if ($view == 'attendance') {
                header("Location: rekod_kehadiran.php?staff_id=$staff_id");
                exit();
            } elseif ($view == 'overtime') {
                header("Location: rekod_ot.php?staff_id=$staff_id");
                exit();
            } else {
                echo "<p>Sila pilih pilihan yang betul.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
