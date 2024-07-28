<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kehadiran";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}

if (isset($_POST['search'])) {
    $ic = $_POST['ic'];

    $sql = "SELECT id, nama FROM staff WHERE ic = '$ic'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $staff_id = $row['id'];
        $staff_nama = $row['nama'];

        $current_date = date("Y-m-d");
        $check_sql = "SELECT * FROM kehadiran WHERE staff_id = $staff_id AND tarikh = '$current_date'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            echo "<div class='container'>";
            echo "<h2>Anda sudah submit kehadiran untuk hari ini.</h2>";
            echo "</div>";
        } else {
            $current_time = date("H:i:s");
            $default_entry_time = "16:30:00";
            $entry_time = (strtotime($current_time) < strtotime($default_entry_time)) ? $default_entry_time : $current_time;

            $insert_sql = "INSERT INTO kehadiran (staff_id, tarikh, masa_masuk) VALUES ($staff_id, '$current_date', '$entry_time')";
            
            if ($conn->query($insert_sql) === TRUE) {
                echo "<div class='container'>";
                echo "<h2>Rekod kehadiran berjaya dimasukkan untuk $staff_nama.</h2>";
                echo "<p>Masa masuk: $entry_time</p>";
                echo "</div>";
            } else {
                echo "Ralat: " . $conn->error;
            }
        }
    } else {
        echo "<div class='container'>";
        echo "<h2>Maaf, tiada maklumat staf dengan IC tersebut.</h2>";
        echo "</div>";
    }
}

$conn->close();
?>
