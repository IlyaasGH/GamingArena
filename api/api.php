<?php
header('Content-Type: application/json');

$serverName = "AHAMED-ILYAAS";
$connectionOptions = [
    "Database" => "gaming_arena",
    "Uid" => "pll",
    "PWD" => "myposadminauthentication",
    "TrustServerCertificate" => true
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Example: fetch all bookings (or add filters later)
$sql = "SELECT station_id, start_time, end_time FROM bookings WHERE status != 'cancelled'";
$stmt = sqlsrv_query($conn, $sql);

$bookings = [];
if ($stmt) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $bookings[] = $row;
    }
}

echo json_encode($bookings);
