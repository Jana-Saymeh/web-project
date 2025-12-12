<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db = "car_rental";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['Register'])) {

    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // شفر الباسورد
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // تأكد إذا اليوزر أو الإيميل موجودين
    $check = $conn->prepare("SELECT * FROM users WHERE username=? OR email=?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>
                alert('Username or Email already exists!');
                window.location.href='login.html';
              </script>";
        exit;
    }

    // سجل يوزر جديد
    $stmt = $conn->prepare("
        INSERT INTO users (username, email, password) 
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {

        // حفظ اليوزر بالسيشن عشان يدخله عالهوم مباشرة
        $_SESSION['user'] = $username;

        echo "<script>
                alert('Registration successful! Welcome!');
                window.location.href='web_project.html'; // ← الهوم
              </script>";
    } else {
        echo "<script>
                alert('Error during registration!');
                window.location.href='login.html';
              </script>";
    }

    $stmt->close();
}

$conn->close();
?>
