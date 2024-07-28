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

// Semak jika parameter staff_id telah diterima dari URL
if (isset($_GET['staff_id'])) {
    $staff_id = $_GET['staff_id'];

    // Query untuk mendapatkan rekod kehadiran staf berdasarkan staff_id
    $sql = "SELECT k.id, k.tarikh, k.masa_masuk, k.masa_keluar,
                   DAYNAME(k.tarikh) AS hari,
                   TIMESTAMPDIFF(HOUR, k.masa_masuk, k.masa_keluar) AS jam_bekerja
            FROM kehadiran k
            WHERE k.staff_id = $staff_id
            ORDER BY k.tarikh DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Paparan jadual rekod kehadiran
        echo "<h2>Rekod Kehadiran untuk Staf</h2>";
        echo "<table>";
        echo "<tr><th>Tarikh</th><th>Hari</th><th>Masa Masuk</th><th>Masa Keluar</th><th>Jumlah Jam Bekerja</th><th>Pengiraan Gaji</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['tarikh'] . "</td>";
            echo "<td>" . $row['hari'] . "</td>";
            echo "<td>" . $row['masa_masuk'] . "</td>";
            echo "<td>" . $row['masa_keluar'] . "</td>";
            echo "<td>" . $row['jam_bekerja'] . "</td>";
            // Button untuk pengiraan gaji dengan link ke halaman admin_gaji.php
            echo "<td><a href='admin_gaji.php?kehadiran_id=" . $row['id'] . "'>Pengiraan Gaji</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Tiada rekod kehadiran untuk staf ini.";
    }
} else {
    echo "Tiada parameter staff_id diterima.";
}

$conn->close();
?>
