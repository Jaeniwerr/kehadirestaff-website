<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kehadiran";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}

$message = "";
$overtime_records = [];
$selected_staff_id = "";

// Semak jika borang telah dihantar untuk carian rekod OT
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_record'])) {
    $staff_id = $_POST['staff_id'];

    // Semak jika staff_id diberikan
    if (!empty($staff_id)) {
        $selected_staff_id = $staff_id;

        // Dapatkan rekod OT untuk staff yang dipilih
        $sql = "SELECT * FROM overtime WHERE staff_id = $staff_id ORDER BY tarikh DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Calculate total hours and overtime pay
                $masa_masuk = new DateTime($row['masa_masuk']);
                $masa_keluar = new DateTime($row['masa_keluar']);
                $interval = $masa_masuk->diff($masa_keluar);
                $jam_bekerja = $interval->format('%h jam %i minit');
                $gaji_ot = ($interval->h + $interval->i / 60) * 6;

                $row['jam_bekerja'] = $jam_bekerja;
                $row['gaji_ot'] = number_format($gaji_ot, 2);

                $overtime_records[] = $row;
            }
        } else {
            $message = "Tiada rekod OT untuk staff ini.";
        }
    } else {
        $message = "Sila pilih nama staff.";
    }
}

// Dapatkan senarai staf untuk dropdown
$sql_staff = "SELECT id, nama, ic FROM staff ORDER BY nama";
$result_staff = $conn->query($sql_staff);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekod OT Staff - Admin</title>
    <link rel="stylesheet" type="text/css" href="../kehadiranstaff/admin.css">
    <style>
        /* Add custom styles for the page */
        .header-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-container img {
            height: 150px;
        }
        .header-container h1 {
            font-size: 24px;
            color: #333;
            font-family: 'Times New Roman', Times, serif;
        }
        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #000000;
            color: #e2dbdb;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .search-form {
            margin: 20px 0;
            text-align: center;
        }
        select, button {
            padding: 10px;
            font-size: 16px;
            margin-right: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #cccccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #333;
            color: #e2dbdb;
        }
        td {
            background-color: #444;
        }
        .message {
            color: #ff0000;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <img src="../kehadiranstaff/css/kehadiran.png" id="img2" alt="Kehadiran Logo">
        <h1>REKOD KEHADIRAN STAFF UNTUK PAPARAN ADMIN</h1>
    </div>

    <div class="container">
        <h2>REKOD KEHADIRAN STAFF</h2>

        <!-- Form untuk pemilihan staf -->
        <form method="post" class="search-form">
            <label for="staff_id">PILIH NAMA STAFF:</label>
            <select name="staff_id" id="staff_id" required>
                <option value="">-- Sila pilih --</option>
                <?php
                if ($result_staff->num_rows > 0) {
                    while ($row_staff = $result_staff->fetch_assoc()) {
                        $selected = ($row_staff['id'] == $selected_staff_id) ? 'selected' : '';
                        echo "<option value='" . $row_staff['id'] . "' $selected>" . $row_staff['nama'] . " (" . $row_staff['ic'] . ")</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" name="search_record">LIHAT REKOD OT</button>
        </form>

        <!-- Paparan rekod OT -->
        <?php if (!empty($overtime_records)) { ?>
            <table>
                <tr>
                    <th>Tarikh</th>
                    <th>Hari</th>
                    <th>Masa Masuk</th>
                    <th>Masa Keluar</th>
                    <th>Jumlah Jam Bekerja</th>
                    <th>Pengiraan Gaji</th>
                </tr>
                <?php foreach ($overtime_records as $record) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['tarikh'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['hari'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['masa_masuk'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['masa_keluar'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['jam_bekerja'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($record['gaji_ot'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td colspan="6" align="center">
                        <?php
                        $first_day_of_month = date('Y-m-01');
                        $last_day_of_month = date('Y-m-t');
                        ?>
                        <a href="gaji_bulanan.php?staff_id=<?php echo htmlspecialchars($selected_staff_id, ENT_QUOTES, 'UTF-8'); ?>&start_date=<?php echo htmlspecialchars($first_day_of_month, ENT_QUOTES, 'UTF-8'); ?>&end_date=<?php echo htmlspecialchars($last_day_of_month, ENT_QUOTES, 'UTF-8'); ?>">Paparkan jumlah gaji staff untuk bulan ini</a>
                    </td>
                </tr>
            </table>
        <?php } else { ?>
            <p class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
    </div>
</body>
</html>
