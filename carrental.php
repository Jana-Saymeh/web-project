<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "car_rental";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$car_type = $_POST['car_type'] ?? '';
$pickup_date = $_POST['pickup_date'] ?? '';
$return_date = $_POST['return_date'] ?? '';

$stmt = $conn->prepare("INSERT INTO bookings (fullname,email,phone,car_type,pickup_date,return_date) VALUES (?,?,?,?,?,?)");
$stmt->bind_param("ssssss", $fullname, $email, $phone, $car_type, $pickup_date, $return_date);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>
