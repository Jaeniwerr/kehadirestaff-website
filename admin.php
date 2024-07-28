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

$message = "";

// Semak jika borang telah dihantar untuk carian rekod kehadiran
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_record'])) {
    $staff_id = $_POST['staff_id'];

    // Redirect ke halaman rekod_kehadiran.php dengan staff_id sebagai parameter
    header("Location: rekod_kehadiran.php?staff_id=$staff_id");
    exit();
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
    <title>Halaman Admin</title>
    <link rel="stylesheet" type="text/css" href="../kehadiranstaff/admin.css">
</head>
<body>
    <div class="container">
        <h1>REKOD KEHADIRAN STAFF</h1>
        <h2>UNTUK PAPARAN ADMIN</h2>
        <img src="../kehadiranstaff/css/kehadiran.png" height="270px" id="img1">
        
        <!-- Form untuk pemilihan staf -->
        <form method="post" class="search-form">
            <label for="staff_id">PILIH NAMA STAFF :</label>
            <select name="staff_id" id="staff_id">
                <?php
                if ($result_staff->num_rows > 0) {
                    while($row_staff = $result_staff->fetch_assoc()) {
                        echo "<option value='" . $row_staff['id'] . "'>" . $row_staff['nama'] . " (" . $row_staff['ic'] . ")</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" name="search_record">CARI</button>
        </form>

        <!-- Paparan mesej jika tiada rekod -->
        <p class="message"><?php echo $message; ?></p>
    </div>
</body>
</html>
