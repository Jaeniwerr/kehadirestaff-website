<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengiraan Gaji Staff</title>
    <link rel="stylesheet" href="gaji_bulanan.css">
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

        // Semak jika parameter staff_id, start_date, dan end_date telah diterima dari URL
        if (isset($_GET['staff_id']) && isset($_GET['start_date']) && isset($_GET['end_date'])) {
            $staff_id = $_GET['staff_id'];
            $start_date = $_GET['start_date'];
            $end_date = $_GET['end_date'];

            // Query untuk mendapatkan nama staff berdasarkan staff_id
            $staff_query = "SELECT nama FROM staff WHERE id = $staff_id";
            $staff_result = $conn->query($staff_query);

            if ($staff_result->num_rows > 0) {
                $staff_row = $staff_result->fetch_assoc();
                $nama_staff = $staff_row['nama'];

                // Ambil bulan terkini dengan huruf besar untuk "July"
                $bulan_terkini = date('F Y');
                $bulan_terkini = strtoupper($bulan_terkini); // Mengubah "July" menjadi "JULY"

                // Query untuk mendapatkan semua rekod kehadiran staf dari start_date hingga end_date
                $sql = "SELECT k.tarikh, k.masa_masuk, k.masa_keluar, DAYNAME(k.tarikh) AS hari
                        FROM kehadiran k
                        WHERE k.staff_id = $staff_id
                        AND k.tarikh BETWEEN '$start_date' AND '$end_date'";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Array untuk menyimpan tarikh-tarikh kerja
                    $dates = array();
                    $total_gaji = 0;

                    while($row = $result->fetch_assoc()) {
                        $tarikh = $row['tarikh'];
                        $masa_masuk = $row['masa_masuk'];
                        $masa_keluar = $row['masa_keluar'];
                        $hari = $row['hari'];

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

                        // Calculate the number of full hours worked
                        $jam_bekerja = floor($minit_bekerja / 60);
                        $minit_sisa = $minit_bekerja % 60;

                        // Calculate salary based on full hours
                        $gaji_tarikh = ($minit_bekerja / 60) * 7;
                        $total_gaji += $gaji_tarikh; // Accumulate total salary

                        // Format the salary to RM0.00
                        $gaji_format = number_format($gaji_tarikh, 2);

                        // Store date, day, entry time, exit time, and salary in the array
                        $dates[] = array(
                            'tarikh' => $tarikh,
                            'hari' => $hari,
                            'masa_masuk' => $masa_masuk,
                            'masa_keluar' => $masa_keluar,
                            'jam' => $jam_bekerja,
                            'minit' => $minit_sisa,
                            'gaji' => "$gaji_format"
                        );
                    }

                    // Paparan nama staff, tarikh-tarikh kerja, dan jumlah gaji
                    echo "<h2>JUMLAH GAJI $nama_staff PADA BULAN $bulan_terkini</h2>";
                    echo "<table>";
                    echo "<tr><th>Tarikh</th><th>Hari</th><th>Masa Masuk</th><th>Masa Keluar</th><th>Jumlah Jam Bekerja</th><th>Gaji (RM)</th></tr>";

                    foreach ($dates as $date) {
                        echo "<tr>";
                        echo "<td>" . $date['tarikh'] . "</td>";
                        echo "<td>" . $date['hari'] . "</td>";
                        echo "<td>" . $date['masa_masuk'] . "</td>";
                        echo "<td>" . $date['masa_keluar'] . "</td>";
                        echo "<td>" . $date['jam'] . " jam " . $date['minit'] . " minit</td>";
                        echo "<td>RM " . $date['gaji'] . "</td>";
                        echo "</tr>";
                    }

                    echo "<tr class='total'><th>Jumlah Keseluruhan</th><td colspan='5'>RM " . number_format($total_gaji, 2) . "</td></tr>";
                    echo "</table>";
                    echo "<br>";
                    echo "<button onclick='window.print()'>Print</button>"; // Tombol Print
                } else {
                    echo "<p>Tiada rekod kehadiran untuk staf ini dari $start_date hingga $end_date.</p>";
                }
            } else {
                echo "<p>Tiada rekod staf dengan ID $staff_id.</p>";
            }
        } else {
            echo "<p>Tiada parameter staff_id, start_date, atau end_date diterima.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
