<?php
// Get data from query string
$stations = explode(',', $_GET['stations'] ?? '');
$start = $_GET['start'] ?? '';
$end = $_GET['end'] ?? '';

$stationCount = count($stations);
$pricePerStation = 500;
$totalPrice = $stationCount * $pricePerStation;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Booking Receipt</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #fff;
            color: #333;
            max-width: 700px;
            margin: 40px auto;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #8A2BE2;
        }

        .section {
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .btn {
            padding: 10px 15px;
            background-color: #8A2BE2;
            color: white;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #a14ef0;
        }

        .station-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .station-box {
            background: #eee;
            padding: 10px 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .glitch-section {
            margin-top: 50px;
            text-align: center;
            padding: 30px;
            background: #000;
            border-top: 3px solid #8A2BE2;
            border-bottom: 3px solid #8A2BE2;
            box-shadow: 0 0 20px #8A2BE2;
            border-radius: 15px;
            position: relative;
            overflow: hidden;
        }

        .glitch-text {
            font-size: 2em;
            font-weight: bold;
            color: #8A2BE2;
            text-shadow: 0 0 5px #8A2BE2, 0 0 10px #fff;
            position: relative;
            animation: glitch 1.5s infinite;
        }

        @keyframes glitch {
            0% {
                text-shadow: 2px 2px #ff00c1, -2px -2px #00fff9;
                transform: translate(0);
            }

            20% {
                text-shadow: -2px -2px #ff00c1, 2px 2px #00fff9;
                transform: translate(-1px, 1px);
            }

            40% {
                text-shadow: 2px -2px #ff00c1, -2px 2px #00fff9;
                transform: translate(1px, -1px);
            }

            60% {
                text-shadow: 0 0 10px #fff;
                transform: translate(0);
            }

            80% {
                text-shadow: 2px 2px #00fff9, -2px -2px #ff00c1;
                transform: translate(-1px, 1px);
            }

            100% {
                text-shadow: 0 0 5px #8A2BE2;
                transform: translate(0);
            }
        }

        .footer-logo {
            text-align: center;
            padding: 20px 0;
            margin-top: 30px;
            background-color: white;
            color: #888;
            font-size: 14px;
        }

        .footer-logo img {
            width: 80px;
            height: auto;
            opacity: 0.8;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <h1>🎟 Booking Receipt</h1>

    <div class="section">
        <span class="label">Stations Booked:</span>
        <div class="station-list">
            <?php foreach ($stations as $s): ?>
                <div class="station-box">Station <?= htmlspecialchars($s) ?></div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <span class="label">Start Time:</span> <?= htmlspecialchars($start) ?><br>
        <span class="label">End Time:</span> <?= htmlspecialchars($end) ?>
    </div>

    <div class="section">
        <span class="label">Price per Station:</span> Rs. <?= number_format($pricePerStation) ?><br>
        <span class="label">Total Stations:</span> <?= $stationCount ?><br>
        <span class="label">Total Price:</span> Rs. <?= number_format($totalPrice) ?>
    </div>

    <div class="section">
        <button class="btn" onclick="window.print()">🖨️ Print Receipt</button>
        <button class="btn" onclick="downloadReceipt()">📄 Download Receipt</button>
    </div>

    <script>
        function downloadReceipt() {
            const html = document.documentElement.outerHTML;
            const blob = new Blob([html], {
                type: 'text/html'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'booking_receipt.html';
            link.click();
        }
    </script>

    <!-- Electric Glitch Effect Gaming Animation -->
    <div class="glitch-section">
        <h2 class="glitch-text">⚡ Enjoying Your Gaming ⚡</h2>
        <dotlottie-player
            src="https://lottie.host/f508c7d4-722d-4819-8fa2-7a083f2420be/FLWmFeiWE6.lottie"
            background="transparent"
            speed="1"
            style="width: 300px; height: 300px;"
            loop autoplay>
        </dotlottie-player>
    </div>

    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>

    <!-- Footer Logo and Copyright -->
    <div class="footer-logo">
        <img src="images/logo.png" alt="Gaming Arena Logo">
        <p>&copy; <?php echo date("Y"); ?> Gaming Arena. All rights reserved.</p>
    </div>

</body>

</html>