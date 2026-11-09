<?php
session_start();
require 'db.php';

// Validar que el usuario tenga sesión iniciada
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $user_id = $_SESSION['user_id'];
    $rol = $_SESSION['rol'];

    // 1. Lógica para AGREGAR una tarea
    if ($accion === 'agregar' && !empty($_POST['descripcion'])) {
        $descripcion = trim($_POST['descripcion']);
        
        $stmt = $conn->prepare("INSERT INTO tareas (descripcion, usuario_id) VALUES (?, ?)");
        $stmt->bind_param("si", $descripcion, $user_id);
        $stmt->execute();
        $stmt->close();
    } 
    
    // 2. Lógica para COMPLETAR una tarea
    elseif ($accion === 'completar' && isset($_POST['id'])) {
        $tarea_id = (int)$_POST['id'];
        
        if ($rol === 'admin') {
            // El admin puede completar cualquier tarea
            $stmt = $conn->prepare("UPDATE tareas SET estado = 'completada' WHERE id = ?");
            $stmt->bind_param("i", $tarea_id);
        } else {
            // El usuario normal solo puede completar sus propias tareas
            $stmt = $conn->prepare("UPDATE tareas SET estado = 'completada' WHERE id = ? AND usuario_id = ?");
            $stmt->bind_param("ii", $tarea_id, $user_id);
        }
        $stmt->execute();
        $stmt->close();
    } 
    
    // 3. Lógica para ELIMINAR una tarea
    elseif ($accion === 'eliminar' && isset($_POST['id'])) {
        $tarea_id = (int)$_POST['id'];
        
        if ($rol === 'admin') {
            // El admin puede eliminar cualquier tarea
            $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ?");
            $stmt->bind_param("i", $tarea_id);
        } else {
            // El usuario normal solo puede eliminar sus propias tareas
            $stmt = $conn->prepare("DELETE FROM tareas WHERE id = ? AND usuario_id = ?");
            $stmt->bind_param("ii", $tarea_id, $user_id);
        }
        $stmt->execute();
        $stmt->close();
    }
}

// Cerrar conexión y regresar al panel
$conn->close();
header("Location: dashboard.php");
exit;
?>