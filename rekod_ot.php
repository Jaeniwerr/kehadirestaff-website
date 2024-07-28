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
if (isset($_GET['staff_id']) && is_numeric($_GET['staff_id'])) {
    $staff_id = (int)$_GET['staff_id'];

    // Query untuk mendapatkan rekod OT staf berdasarkan staff_id
    $stmt = $conn->prepare("SELECT o.id, o.tarikh, o.masa_masuk, o.masa_keluar, DAYNAME(o.tarikh) AS hari FROM overtime o WHERE o.staff_id = ? ORDER BY o.tarikh DESC");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Simpan hasil ke dalam array untuk digunakan di HTML
        $records = [];
        while ($row = $result->fetch_assoc()) {
            // Convert times to Unix timestamps
            $timestamp_masuk = strtotime($row['masa_masuk']);
            $timestamp_keluar = strtotime($row['masa_keluar']);

            // Calculate the difference in seconds
            $seconds_difference = $timestamp_keluar - $timestamp_masuk;

            // Convert the difference to minutes
            $minit_bekerja = (int)($seconds_difference / 60);

            // Calculate hours and minutes
            $jam = floor($minit_bekerja / 60);
            $minit = $minit_bekerja % 60;

            // Calculate salary based on total minutes
            $salary = ($minit_bekerja / 60) * 6; // RM6 per hour

            // Format the output
            $jam_bekerja_format = "$jam jam $minit minit";

            // Add to records array
            $records[] = [
                'tarikh' => $row['tarikh'],
                'hari' => $row['hari'],
                'masa_masuk' => $row['masa_masuk'],
                'masa_keluar' => $row['masa_keluar'],
                'jam_bekerja' => $jam_bekerja_format,
                'id' => $row['id'],
                'gaji' => number_format($salary, 2) // Format gaji ke dalam RM0.00
            ];
        }
    } else {
        $message = "Tiada rekod OT untuk staf ini.";
    }
    $stmt->close();
} else {
    $message = "Tiada parameter staff_id diterima.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekod OT Staf</title>
    <link rel="stylesheet" href="rekod_ot.css"> <!-- Hubungkan dengan file CSS -->
          
</head>
<body>
<div class="header-container">
    <img src="../kehadiranstaff/css/kehadiran.png" id="img2" alt="Kehadiran Logo">
    <h1>REKOD OT STAFF UNTUK PAPARAN ADMIN</h1>
</div>

<div class="container">
    <h2>REKOD KEHADIRAN OT STAFF</h2>
    
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
                <th>Pengiraan Gaji</th>
            </tr>
            <?php foreach ($records as $record) : ?>
                <tr>
                    <td><?php echo $record['tarikh']; ?></td>
                    <td><?php echo $record['hari']; ?></td>
                    <td><?php echo $record['masa_masuk']; ?></td>
                    <td><?php echo $record['masa_keluar']; ?></td>
                    <td><?php echo $record['jam_bekerja']; ?></td>
                    <td>RM <?php echo $record['gaji']; ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="6" align="center">
                    <?php
                    $first_day_of_month = date('Y-m-01');
                    $last_day_of_month = date('Y-m-t');
                    ?>
                    <a href="gaji_bulanan_ot.php?staff_id=<?php echo $staff_id; ?>&start_date=<?php echo $first_day_of_month; ?>&end_date=<?php echo $last_day_of_month; ?>">Paparkan jumlah gaji OT staff untuk bulan ini</a>
                </td>
            </tr>
        </table>
    <?php else : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</div>
</body>
</html>
