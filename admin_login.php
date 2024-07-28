<?php
session_start();

$message = ""; // Initialize the $message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace these with your actual admin credentials
    $admin_username = "admin";
    $admin_password = "123"; // In real applications, use hashed passwords

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['loggedin'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $message = "Nama pengguna atau kata laluan tidak sah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="adminlogin.css">
</head>
<body>
<div class="header">
    <img src="../kehadiranstaff/css/logo2.png" height="150px" id="img2" alt="Logo">
    <h1>PUSAT TUSYEN FAIZA JAYA SEMENYIH 1</h1>
</div>
<img src="../kehadiranstaff/css/info.png" height="80px" id="info-icon" alt="Info Icon" onclick="toggleInfoBox()">
<div class="info-box" id="info-box">
    <h2>ADMIN LOGIN SYSTEM</h2>
    <ul>
                <li>Sistem ini hanya boleh diakses oleh admin sahaja untuk melihat rekod kehadiran staff.</li>
                <li>Sila masukkan nama pengguna dan kata laluan.</li>
                <li>Jika terlupa kata laluan boleh hubungi admin pengurusan sistem ini.</li>
            </ul>
</div>
<div class="container">
    <img src="../kehadiranstaff/css/adminlogin.png" height="300px" alt="Admin Login">
    <form method="post" class="login-form">
        <p><label for="username">USERNAME :</label></p>
        <p><input type="text" id="username" name="username" required></p>
        <p><label for="password">PASSWORD :</label></p>
        <p><input type="password" id="password" name="password" required></p>
        <button type="submit">LOGIN</button>
    </form>
    <p class="message"><?php echo $message; ?></p>
</div>
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
</body>
</html>
