<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $user_id = $_SESSION['user_id'];
    $rol = $_SESSION['rol'];

    if ($accion === 'agregar' && !empty($_POST['descripcion'])) {
        $descripcion = trim($_POST['descripcion']);
        
        $stmt = $conn->prepare("INSERT INTO tareas (descripcion, usuario_id) VALUES (?, ?)");
        $stmt->bind_param("si", $descripcion, $user_id);
        $stmt->execute();
        $stmt->close();
    } 
    
    elseif ($accion === 'completar' && isset($_POST['id'])) {
        $tarea_id = (int)$_POST['id'];
        
        if ($rol === 'admin') {
            $stmt = $conn->prepare("UPDATE tareas SET estado = 'completada' WHERE id = ?");
            $stmt->bind_param("i", $tarea_id);
        } else {
            $stmt = $conn->prepare("UPDATE tareas SET estado = 'completada' WHERE id = ? AND usuario_id = ?");
            $stmt->bind_param("ii", $tarea_id, $user_id);
        }
        $stmt->execute();
        $stmt->close();
    } 
    
    elseif ($accion === 'eliminar' && isset($_POST['id'])) {
        $tarea_id = (int)$_POST['id'];
        
        if ($rol === 'admin') {
            $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ?");
            $stmt->bind_param("i", $tarea_id);
        } else {
            $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
            $stmt->bind_param("ii", $tarea_id, $user_id);
        }
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: dashboard.php");
exit;
?>