<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_name'])) {
    die("User not logged in");
}

// SQL Server connection
$serverName = "AHAMED-ILYAAS";
$connectionOptions = [
    "Database" => "gaming_arena",
    "Uid" => "pll",
    "PWD" => "myposadminauthentication",
    "TrustServerCertificate" => true
];
$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

// Get POST data
$station_ids = explode(',', $_POST['station_id']);
$start_time_raw = $_POST['start_time_hidden'];
$end_time_raw = $_POST['end_time_hidden'];

$start_time = date('Y-m-d H:i:s', strtotime($start_time_raw));
$end_time = date('Y-m-d H:i:s', strtotime($end_time_raw));

// Calculate total hours
$start = new DateTime($start_time);
$end = new DateTime($end_time);
$interval = $start->diff($end);
$total_hours = $interval->h + ($interval->i / 60);

// Get user_id from session
$user_name = $_SESSION['user_name'];
$userQuery = "SELECT user_id FROM users WHERE user_name = ?";
$userStmt = sqlsrv_query($conn, $userQuery, [$user_name]);
$userRow = sqlsrv_fetch_array($userStmt, SQLSRV_FETCH_ASSOC);
$user_id = $userRow['user_id'] ?? null;

if (!$user_id) {
    die("User not found");
}

// Track booked and skipped stations
$bookedStations = [];
$skippedStations = [];

// Loop through all selected stations
foreach ($station_ids as $station_id) {
    $station_id = intval($station_id);

    // Check for overlapping bookings
    $overlapSQL = "SELECT 1 FROM bookings 
                   WHERE station_id = ? 
                   AND status != 'cancelled'
                   AND (
                        (start_time < ? AND end_time > ?)
                   )";
    $checkParams = [$station_id, $end_time, $start_time];
    $checkStmt = sqlsrv_query($conn, $overlapSQL, $checkParams);

    if ($checkStmt === false) {
        die("Overlap check failed: " . print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($checkStmt)) {
        $skippedStations[] = $station_id;
        continue;
    }

    // Calculate price per station
    $pricePerStation = round($total_hours * 500);

    // Insert booking
    $sql = "INSERT INTO bookings (user_id, station_id, start_time, end_time, total_hours, total_price, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, 'confirmed', GETDATE(), GETDATE())";
    $params = [$user_id, $station_id, $start_time, $end_time, $total_hours, $pricePerStation];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Insert failed: " . print_r(sqlsrv_errors(), true));
    }

    $bookedStations[] = $station_id;
    sqlsrv_free_stmt($stmt);
}

sqlsrv_close($conn);

// Final response message (if needed)
$message = "";
if (!empty($bookedStations)) {
    $message .= "Successfully booked stations: " . implode(', ', $bookedStations) . ".\\n";
}
if (!empty($skippedStations)) {
    $message .= "Skipped (already booked): " . implode(', ', $skippedStations) . ".";
}
if (empty($message)) {
    $message = "No stations booked.";
}

// Redirect to receipt page with all booked stations
$stations_str = implode(',', $bookedStations);
$startEncoded = urlencode($start_time);
$endEncoded = urlencode($end_time);
header("Location: receipt.php?stations=$stations_str&start=$startEncoded&end=$endEncoded");
exit;
