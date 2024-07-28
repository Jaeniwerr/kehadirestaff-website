<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengiraan Gaji OT</title>
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

        // Semak jika parameter overtime_id telah diterima dari URL
        if (isset($_GET['overtime_id'])) {
            $overtime_id = $_GET['overtime_id'];

            // Query untuk mendapatkan maklumat OT berdasarkan id overtime
            $sql = "SELECT o.id, o.tarikh, o.masa_masuk, o.masa_keluar, s.nama
                    FROM overtime o
                    JOIN staff s ON o.staff_id = s.id
                    WHERE o.id = $overtime_id";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Ambil maklumat OT
                $row = $result->fetch_assoc();
                $nama = $row['nama'];
                $tarikh = $row['tarikh'];
                $masa_masuk = $row['masa_masuk'];
                $masa_keluar = $row['masa_keluar'];

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

                // Calculate overtime salary based on total minutes
                $gaji = ($minit_bekerja / 60) * 6; // Mengira gaji dengan kadar RM6/jam
                // Format gaji ke dalam RM0.00
                $gaji_format = number_format($gaji, 2);

                // Paparan maklumat OT dalam bentuk table
                echo "<h2>JUMLAH GAJI OT HARIAN $nama</h2>";
                echo "<table>";
                echo "<tr><th>Tarikh</th><th>Masa Masuk</th><th>Masa Keluar</th><th>Jumlah Jam Bekerja</th><th>Gaji (RM6/jam)</th></tr>";
                echo "<tr>";
                echo "<td>$tarikh</td>";
                echo "<td>$masa_masuk</td>";
                echo "<td>$masa_keluar</td>";
                echo "<td>$jam_bekerja jam $minit_sisa minit</td>";
                echo "<td>RM $gaji_format</td>";
                echo "</tr>";
                echo "</table>";
            } else {
                echo "Tiada rekod OT yang ditemui.";
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
