<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kehadiran";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}

$sql = "SELECT k.id, s.nama, k.tarikh, k.masa_masuk, k.masa_keluar,
               TIMESTAMPDIFF(MINUTE, k.masa_masuk, k.masa_keluar) AS minit_bekerja
        FROM kehadiran k
        JOIN staff s ON k.staff_id = s.id
        ORDER BY k.tarikh DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='container'>";
    echo "<h2>Rekod Kehadiran Staff</h2>";
    echo "<table class='attendance-table'>";
    echo "<tr><th>Nama Staff</th><th>Tarikh</th><th>Masa Masuk</th><th>Masa Keluar</th><th>Jumlah Jam Bekerja</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['nama'] . "</td>";
        echo "<td>" . $row['tarikh'] . "</td>";
        echo "<td>" . $row['masa_masuk'] . "</td>";
        echo "<td>" . $row['masa_keluar'] . "</td>";
        
        $minit_bekerja = $row['minit_bekerja'];
        $jam = floor($minit_bekerja / 60);
        $minit = $minit_bekerja % 60;
        echo "<td>$jam jam $minit minit</td>";

        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
} else {
    echo "<div class='container'>";
    echo "<h2>Tiada rekod kehadiran untuk dipaparkan.</h2>";
    echo "</div>";
}

$conn->close();
?>
