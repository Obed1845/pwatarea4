<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, rol FROM usuarios WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['rol'] = $row['rol'];
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Credenciales incorrectas'); window.location.href='index.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>