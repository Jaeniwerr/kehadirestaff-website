<?php
// Sambungan ke pangkalan data
$conn = new mysqli("localhost", "username", "YES", "kehadiranstaff");

// Semak sambungan
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dapatkan IC dari borang
$ic = $_POST['ic'];

// Query untuk mendapatkan maklumat staff berdasarkan IC
$sql = "SELECT id, nama FROM staff WHERE ic = '$ic'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Papar borang untuk submit kehadiran
    while($row = $result->fetch_assoc()) {
        echo "Nama: " . $row["nama"]. "<br>";
        echo '<form action="submit_kehadiran.php" method="POST">';
        echo '<input type="hidden" name="staff_id" value="' . $row["id"] . '">';
        echo '<button type="submit" name="submit_kehadiran">Submit Kehadiran</button>';
        echo '</form>';
    }
} else {
    echo "Tiada rekod staff.";
}
$conn->close();
?>
