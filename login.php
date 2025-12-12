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

if (isset($_POST['login'])) {

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // جلب اليوزر من الداتا بيس
    $stmt = $conn->prepare("SELECT password FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // تحقق من وجود اليوزر
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // تحقق من الباسورد
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user'] = $username;

            echo "<script>
                    window.location.href='web_project.html';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Incorrect password!');
                    window.location.href='login.html';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('User not found!');
                window.location.href='login.html';
              </script>";
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
