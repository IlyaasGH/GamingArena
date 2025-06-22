<?php
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $user_name = $_POST["user_name"];
    $password = $_POST["password"];
    $address_line = $_POST["address_line"];
    $city = $_POST["city"];

    // Connect to SQL Server
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

    // Insert data
    $sql = "INSERT INTO users (full_name, email, phone, user_name, password, address_line, city, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, GETDATE(), GETDATE())";
    $params = [$full_name, $email, $phone, $user_name, $password, $address_line, $city];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt) {
        $success = "Registration successful!";
    } else {
        $error = "Something went wrong: " . print_r(sqlsrv_errors(), true);
    }

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register - Gaming Arena</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="register-box">
        <h2>Register</h2>

        <?php if ($success): ?>
            <p style="color: green"><?= $success ?></p>
        <?php elseif ($error): ?>
            <p style="color: red"><?= $error ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="text" name="full_name" placeholder="Full Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="text" name="phone" placeholder="Phone" required><br>
            <input type="text" name="user_name" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="text" name="address_line" placeholder="Address" required><br>
            <input type="text" name="city" placeholder="City" required><br>
            <button type="submit" class="btn">Register</button>
        </form>
    </div>

</body>

</html>