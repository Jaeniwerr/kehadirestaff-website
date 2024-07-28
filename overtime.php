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
$message = "";
$message_class = "";
$nama = "";
$tarikh = "";
$hari = "";
$masa_masuk = "";
$masa_keluar = "";
$gaji_ot = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ic = isset($_POST['ic']) ? $_POST['ic'] : '';
    $tarikh = isset($_POST['tarikh']) ? $_POST['tarikh'] : '';
    $hari = isset($_POST['hari']) ? $_POST['hari'] : '';
    $masa_masuk = isset($_POST['masa_masuk']) ? $_POST['masa_masuk'] : '';
    $masa_keluar = isset($_POST['masa_keluar']) ? $_POST['masa_keluar'] : '';

    if (isset($_POST['submit'])) {
        // Semak IC dalam pangkalan data
        $sql = "SELECT * FROM staff WHERE ic = '$ic'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $staff = $result->fetch_assoc();
            $nama = $staff['nama'];
            $staff_id = $staff['id'];

            // Calculate hours worked
            $time_in = new DateTime($masa_masuk);
            $time_out = new DateTime($masa_keluar);
            $interval = $time_in->diff($time_out);
            $hours_worked = $interval->h + ($interval->i / 60); // Convert minutes to hours
            $gaji_ot = $hours_worked * 6; // RM6 per hour

            // Insert overtime record into database
            $sql = "INSERT INTO overtime (staff_id, tarikh, hari, masa_masuk, masa_keluar, gaji_ot) VALUES ($staff_id, '$tarikh', '$hari', '$masa_masuk', '$masa_keluar', $gaji_ot)";
            if ($conn->query($sql) === TRUE) {
                $message = "Rekod OT telah dimasukkan. Gaji OT: RM" . number_format($gaji_ot, 2);
                $message_class = "success";
            } else {
                $message = "Ralat: " . $conn->error;
                $message_class = "error";
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
    <title>Rekod OT Staff</title>
    <link rel="stylesheet" type="text/css" href="overtime.css">
    <style>
        .info-button-container {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .info-box {
            display: none;
            position: absolute;
            top: 50px;
            right: 10px;
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        .close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    cursor: pointer;
}

.info-box h4 {
    margin-top: 10px;
    margin-bottom: 10px;
}

.info-box ul {
    list-style-type: disc;
    padding-left: 20px;
    margin: 0;
}

.info-box ul li {
    margin-bottom: 10px;
    font-family: Arial, sans-serif;
}

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .container {
            position: relative;
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <!-- Info Button Container -->
    <div class="info-button-container">
        <button onclick="toggleInfoBox()">Maklumat Lanjut</button>

        <!-- Information Box -->
        <div class="info-box" id="infoBox">
            <h4>SISTEM KEHADIRAN OT : </h4>
            <ul>
                <li>Laman ini digunakan untuk merekod kehadiran kerja lebih masa (OT) untuk staff.</li>
                <li>Sila masukkan nombor kad pengenalan, tarikh, hari, masa masuk, dan masa keluar.</li>
                <li>Masukkan nombor kad pengenalan dengan betul.</li>
                <li>Gaji OT akan dikira secara automatik berdasarkan jumlah jam bekerja pada kadar RM6 per jam.</li>
            </ul>
        </div>
    </div>
    <div class="container">
        <h2>REKOD KEHADIRAN KERJA LEBIH MASA (OT)</h2>
        <img src="../kehadiranstaff/css/ot.png" height="270px" id="img1">

        <form method="post">
            <label for="ic">NOMBOR KAD PENGENALAN:</label>
            <input type="text" id="ic" name="ic" required>

            <label for="tarikh">TARIKH:</label>
            <input type="date" id="tarikh" name="tarikh" required>

            <label for="hari">HARI:</label>
            <input type="text" id="hari" name="hari" placeholder="Contoh: Isnin" required>

            <label for="masa_masuk">MASA MASUK:</label>
            <input type="time" id="masa_masuk" name="masa_masuk" required>

            <label for="masa_keluar">MASA KELUAR:</label>
            <input type="time" id="masa_keluar" name="masa_keluar" required>

            <button type="submit" name="submit">SUBMIT KEHADIRAN OT</button>
        </form>

        <p class="message <?php echo $message_class; ?>">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    </div>

    <script>
        function toggleInfoBox() {
            var infoBox = document.getElementById("infoBox");
            if (infoBox.style.display === "none" || infoBox.style.display === "") {
                infoBox.style.display = "block";
            } else {
                infoBox.style.display = "none";
            }
        }
    </script>
</body>
</html>
