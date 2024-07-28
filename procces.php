<?php
// Sambung ke pangkalan data MySQL
$servername = "localhost";
$username = "username"; // Gantikan dengan username pangkalan data anda
$password = "password"; // Gantikan dengan kata laluan pangkalan data anda
$dbname = "kehadiran"; // Gantikan dengan nama pangkalan data anda

// Cipta sambungan
$conn = new mysqli($servername, $username, $password, $dbname);

// Semak sambungan
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tangkap data dari borang
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ic_staff = $_POST['ic'];

    // Dapatkan nama staff dari pangkalan data berdasarkan No. IC
    $sql = "SELECT nama_staff FROM staff WHERE ic_staff = '$ic_staff'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Panggil nama staff
        $row = $result->fetch_assoc();
        $nama_staff = $row['nama_staff'];

        // Dapatkan tarikh dan masa masuk automatik
        $masuk = date('Y-m-d H:i:s');
        
        // Tetapkan masa keluar kepada 10:30 malam (22:30:00)
        $keluar = date('Y-m-d 22:30:00');

        // Semak jika rekod untuk hari ini sudah wujud
        $sql_check = "SELECT * FROM kehadiran WHERE ic_staff = '$ic_staff' AND DATE(masuk) = CURDATE()";
        $result_check = $conn->query($sql_check);

        if ($result_check->num_rows == 0) {
            // Jika belum wujud, masukkan rekod kehadiran ke dalam pangkalan data
            $sql_insert = "INSERT INTO kehadiran (ic_staff, nama_staff, masuk, keluar) VALUES ('$ic_staff', '$nama_staff', '$masuk', '$keluar')";
            
            if ($conn->query($sql_insert) === TRUE) {
                echo "<script>alert('Kehadiran anda telah direkodkan untuk hari ini. Terima kasih!');</script>";
            } else {
                echo "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            echo "<script>alert('Anda telah merekodkan kehadiran untuk hari ini sebelum ini. Anda hanya boleh submit sekali sehari.');</script>";
        }
    } else {
        echo "<script>alert('No. IC tidak sah. Sila cuba lagi.');</script>";
    }
}

$conn->close();
?>
