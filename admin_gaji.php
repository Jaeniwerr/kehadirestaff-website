<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengiraan Gaji</title>
    <link rel="stylesheet" href="admin_gaji.css">
</head>
<body>
    <div class="container">
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

        // Semak jika parameter kehadiran_id telah diterima dari URL
        if (isset($_GET['kehadiran_id'])) {
            $kehadiran_id = $_GET['kehadiran_id'];

            // Query untuk mendapatkan maklumat kehadiran berdasarkan id kehadiran
            $sql = "SELECT k.id, k.tarikh, k.masa_masuk, k.masa_keluar, s.nama
                    FROM kehadiran k
                    JOIN staff s ON k.staff_id = s.id
                    WHERE k.id = $kehadiran_id";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Ambil maklumat kehadiran
                $row = $result->fetch_assoc();
                $nama = $row['nama'];
                $tarikh = $row['tarikh'];
                $masa_masuk = $row['masa_masuk'];
                $masa_keluar = $row['masa_keluar'];

                $default_entry_time = "16:30:00";
                if (strtotime($masa_masuk) < strtotime($default_entry_time)) {
                    $masa_masuk = $default_entry_time;
                }

                // Convert times to Unix timestamps
                $timestamp_masuk = strtotime($masa_masuk);
                $timestamp_keluar = strtotime($masa_keluar);

                // Calculate the difference in seconds
                $seconds_difference = $timestamp_keluar - $timestamp_masuk;

                // Convert the difference to minutes
                $minit_bekerja = (int)($seconds_difference / 60);

                // Calculate the number of full hours and minutes worked
                $jam_bekerja = floor($minit_bekerja / 60);
                $minit_sisa = $minit_bekerja % 60;

                // Calculate salary based on total minutes
                $gaji = ($minit_bekerja / 60) * 7; // Mengira gaji dengan kadar RM7/jam
                // Format gaji ke dalam RM0.00
                $gaji_format = number_format($gaji, 2);

                // Paparan maklumat kehadiran dalam bentuk table
                echo "<h2>JUMLAH GAJI HARIAN $nama</h2>";
                echo "<table>";
                echo "<tr><th>Tarikh</th><th>Masa Masuk</th><th>Masa Keluar</th><th>Jumlah Jam Bekerja</th><th>Gaji (RM7/jam)</th></tr>";
                echo "<tr>";
                echo "<td>$tarikh</td>";
                echo "<td>$masa_masuk</td>";
                echo "<td>$masa_keluar</td>";
                echo "<td>$jam_bekerja jam $minit_sisa minit</td>";
                echo "<td>RM $gaji_format</td>";
                echo "</tr>";
                echo "</table>";
            } else {
                echo "Tiada rekod kehadiran yang ditemui.";
            }
        }

        $conn->close();
        ?>
        <div class="footer">
            © 2024 Sistem Kehadiran
        </div>
    </div>
</body>
</html>
