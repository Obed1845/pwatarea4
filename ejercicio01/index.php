<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Ejercicio 1</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background: #eaeaea; margin: 0; }
        form { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column; width: 300px; }
        input { margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        button:hover { background: #218838; }
        h2 { text-align: center; margin-top: 0; }
    </style>
</head>
<body>
    <form action="login.php" method="POST">
        <h2>Iniciar Sesión</h2>
        <input type="text" name="username" placeholder="Usuario(admin)" required>
        <input type="password" name="password" placeholder="Contraseña(12345)" required>
        <button type="submit">Ingresar</button>
    </form>
</body>
</html>