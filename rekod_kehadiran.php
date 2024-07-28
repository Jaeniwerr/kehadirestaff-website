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
                   DAYNAME(k.tarikh) AS hari
            FROM kehadiran k
            WHERE k.staff_id = $staff_id
            ORDER BY k.tarikh DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Simpan hasil ke dalam array untuk digunakan di HTML
        $records = [];
        while($row = $result->fetch_assoc()) {
            // Konversi masa_masuk jika sebelum waktu default
            $default_entry_time = "16:30:00";
            if (strtotime($row['masa_masuk']) < strtotime($default_entry_time)) {
                $row['masa_masuk'] = $default_entry_time;
            }

            // Convert times to Unix timestamps
            $timestamp_masuk = strtotime($row['masa_masuk']);
            $timestamp_keluar = strtotime($row['masa_keluar']);

            // Calculate the difference in seconds
            $seconds_difference = $timestamp_keluar - $timestamp_masuk;

            // Convert the difference to minutes
            $minit_bekerja = (int)($seconds_difference / 60); // Explicit conversion to integer

            // Calculate hours and minutes
            $jam = floor($minit_bekerja / 60);
            $minit = $minit_bekerja % 60;

            // Calculate salary based on full hours
            $salary = $jam * 7; // RM7 per hour

            // Format the output
            $jam_bekerja_format = "$jam jam $minit minit";

            // Add to records array
            $records[] = [
                'tarikh' => $row['tarikh'],
                'hari' => $row['hari'],
                'masa_masuk' => $row['masa_masuk'],
                'masa_keluar' => $row['masa_keluar'],
                'jam_bekerja' => $jam_bekerja_format,
                'id' => $row['id']
            ];
        }
    } else {
        $message = "Tiada rekod kehadiran untuk staf ini.";
    }
} else {
    $message = "Tiada parameter staff_id diterima.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekod Kehadiran Staf</title>
    <link rel="stylesheet" href="styles.css"> <!-- Hubungkan dengan file CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>

<link rel="stylesheet" type="text/css" href="rekod_kehadiran.css">

</head>
<body>
<div class="header-container">
    <img src="../kehadiranstaff/css/kehadiran.png" height="150px" id="img2" alt="Kehadiran Logo">
    <h1> REKOD KEHADIRAN STAFF UNTUK PAPARAN ADMIN </h1>
</div>

    <div class="container">
    <h2>REKOD KEHADIRAN STAFF </h2>
        
        <?php if (isset($message)) : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <?php if (!empty($records)) : ?>
            <table>
                <tr>
                    <th>Tarikh</th>
                    <th>Hari</th>
                    <th>Masa Masuk</th>
                    <th>Masa Keluar</th>
                    <th>Jumlah Jam Bekerja</th>
                </tr>
                <?php foreach ($records as $record) : ?>
                    <tr>
                        <td><?php echo $record['tarikh']; ?></td>
                        <td><?php echo $record['hari']; ?></td>
                        <td><?php echo $record['masa_masuk']; ?></td>
                        <td><?php echo $record['masa_keluar']; ?></td>
                        <td><?php echo $record['jam_bekerja']; ?></td>
                      
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="6" align="center">
                        <?php
                        $first_day_of_month = date('Y-m-01');
                        $last_day_of_month = date('Y-m-t');
                        ?>
                        <a href="gaji_bulanan.php?staff_id=<?php echo $staff_id; ?>&start_date=<?php echo $first_day_of_month; ?>&end_date=<?php echo $last_day_of_month; ?>">Paparkan jumlah gaji staff untuk bulan ini</a>
                    </td>
                </tr>
            </table>
        <?php else : ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
