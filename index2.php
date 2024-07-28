<!DOCTYPE html>
<html>
<head>
    <title>Sistem Kehadiran Staff</title>
    <link rel="stylesheet" type="text/css" href="index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../kehadiranstaff/css/image1.jpg'); /* Replace with the path to your image */
            background-size: cover;
            background-position: 40%;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 200px auto;
            margin-top: 5px;
            background-color: #000000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            display: flex; /* Use flexbox for horizontal alignment */
            padding: 20px; /* Add some padding */
            align-items: center; /* Center items vertically */
        }

        #img2 {
            margin-right: 0px; /* Space between image and text */
            /* Ensure the image size fits well */
        }

        h1 {
            margin: 0; /* Remove default margin */
            font-size: 28px; /* Adjust font size as needed */
            color: #000000; /* Premium gold color */
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            background-color: rgba(255, 227, 69, 0.2);
        }

        h2 {
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            text-align: center;
            color: #e2dbdb;
            margin-bottom: 1%;
        }

        h3 {
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            text-align: center;
            color: #e2dbdb;
            font-size: 150%;
            margin-top: 0%;
        }

        #img1 {
            display: block;
            margin: 0 auto; /* Center the image horizontally */
            margin-bottom: 40px;
            border-radius: 4px;
            padding: 5px;
            width: 200px; /* Adjust width as needed */
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-weight: bold;
            color: #cccccc;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        button {
            padding: 10px;
            background-color: #000000;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            border: 2px solid rgb(255, 221, 30);
        }

        button:hover {
            background-color: #b3a100;
        }

        p {
            color: #f7f4f4;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-weight: bold;
        }

        .message {
            font-size: 1em;
            padding: 10px;
            margin-top: 20px;
            border-radius: 4px;
        }

        .warning {
            color: #41dc3e;
        }

        .warning-today {
            color: #48ff00;
        }

        .info-btn {
            padding: 15px 25px;
            font-family: 'Times New Roman', Times, serif;
            font-size: 18px;
            background-color: #f5b224;
            color: #000000;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bolder;
            border: 2px solid rgb(16, 16, 14);
            position: absolute;
            top: 30px;
            right: 80px;
        }

        .phone-icon {
            position: absolute;
            top: 20px;
            right: 5px;
            cursor: pointer;
            padding: 10px 10px;
            margin-left: 1%;
        }

        .phone-number {
            display: none;
            background-color: rgba(13, 10, 10, 0.95);
            color: #333333;
            padding: 15px;
            border-radius: 8px;
            position: absolute;
            top: 70px;
            right: 20px;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .phone-number p {
            margin: 0;
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        .info-btn:hover {
            background-color: #b3a100;
        }

        .info-box {
            display: none;
            background-color: rgba(255, 255, 255, 0.95);
            color: #333333;
            padding: 20px;
            border-radius: 8px;
            position: fixed;
            top: 40px;
            right: 20px;
            max-width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .info-content {
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        .info-box h4 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .info-box ul {
            list-style-type: disc;
            padding-left: 20px;
            margin: 0;
        }

        .info-box ul li {
            margin-bottom: 10px;
            font-family: Arial, sans-serif;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #100707;
        }

        .header-links {
    display: flex;
    align-items: center; /* Align items vertically in the center */
    justify-content: space-between; /* Distribute space between elements */
}

.header-links button.info-btn {
    margin-right: 10px; /* Add some space between button and other elements */
}

.header-links .phone-icon {
    margin: 0 10px; /* Add space around the phone icon */
}

.header-links .link-image {
    margin: 0 10px; /* Add space around the link images */
}

.header-links img {
    display: block; /* Ensure images do not have extra space around them */
}

        
        
    </style>
</head>
<body>
    <div class="header">
        <img src="../kehadiranstaff/css/logo2.png" height="150px" id="img2" alt="Logo">
        <h1>PUSAT TUSYEN FAIZA JAYA SEMENYIH 1</h1>
    </div>

    <div class="header-links">
    <!-- Button to toggle the info box -->
    <button onclick="toggleInfoBox()" class="info-btn">Maklumat Lanjut</button>

    <!-- Icon for phone call -->
    <div class="phone-icon" onclick="togglePhoneNumber()">
        <img src="../kehadiranstaff/css/phoneicon.png" alt="Call" height="50px">
    </div>

    <!-- Clickable images linking to other pages -->
    <div class="link-image">
        <a href="overtime.php">
            <img src="../kehadiranstaff/css/ot.png" alt="Overtime">
        </a>
    </div>
    <div class="link-image">
        <a href="adminlogin.php">
            <img src="../kehadiranstaff/css/admin.png" alt="Admin Login">
        </a>
    </div>
</div>


    <!-- Info box -->
    <div id="infoBox" class="info-box">
        <div class="info-content">
            <span onclick="closeInfoBox()" class="close-btn">&times;</span>
            <h4>SISTEM KEHADIRAN STAFF:</h4>
            <ul>
                <li>Sistem ini hanya menerima submit kehadiran sehari sekali.</li>
                <li>Jika staff masuk sebelum 4:30 PM maka masa masuk akan direkodkan sebagai 4:30 PM.</li>
                <li>Waktu keluar tetap pada 10:30 PM.</li>
                <li>Maklumat ini membantu dalam pengiraan gaji bulanan.</li>
                <li>Hubungi admin jika mempunyai sebarang masalah.</li>
            </ul>
        </div>
    </div>

    <!-- Phone number section -->
    <div id="phoneNumber" class="phone-number">
        <p>Hubungi Admin: </p>
        <p>+60 123 456 789 (Cikgu Naz)</p>
    </div>

    <div class="container">
        <h2>SISTEM KEHADIRAN STAFF</h2>
        <h3>PUSAT TUSYEN FAIZA JAYA</h3>

        <img src="../kehadiranstaff/css/logo.png" id="img1" alt="Logo">

        <form action="submit_attendance.php" method="post">
            <label for="ic">No. Kad Pengenalan:</label>
            <input type="text" id="ic" name="ic" required>

            <button type="submit">Hadir</button>
        </form>
    </div>

    <div class="footer">
        <p>Hak Cipta &copy; 2023 Pusat Tusyen Faiza Jaya. Semua Hak Cipta Terpelihara.</p>
    </div>

    <script>
        function toggleInfoBox() {
            var infoBox = document.getElementById("infoBox");
            if (infoBox.style.display === "none" || infoBox.style.display === "") {
                infoBox.style.display = "block";
            } else {
                infoBox.style.display = "none";
            }
        }

        function closeInfoBox() {
            document.getElementById("infoBox").style.display = "none";
        }

        function togglePhoneNumber() {
            var phoneNumber = document.getElementById("phoneNumber");
            if (phoneNumber.style.display === "none" || phoneNumber.style.display === "") {
                phoneNumber.style.display = "block";
            } else {
                phoneNumber.style.display = "none";
            }
        }
    </script>
</body>
</html>
