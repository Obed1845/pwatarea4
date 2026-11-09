<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$rol = $_SESSION['rol'];
$user_id = $_SESSION['user_id'];

if ($rol === 'admin') {
    $sql = "SELECT t.id, t.descripcion, t.estado, u.username FROM tareas t JOIN usuarios u ON t.usuario_id = u.id";
    $stmt = $conn->prepare($sql);
} else {
    $sql = "SELECT t.id, t.descripcion, t.estado, u.username FROM tareas t JOIN usuarios u ON t.usuario_id = u.id WHERE t.usuario_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$tareas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tareas</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        .btn { padding: 8px 12px; text-decoration: none; color: white; border-radius: 4px; border: none; cursor: pointer; }
        .btn-add { background: #28a745; padding: 10px 15px; }
        .btn-complete { background: #17a2b8; }
        .btn-delete { background: #dc3545; }
        .btn-logout { background: #6c757d; float: right; }
        .completada { text-decoration: line-through; color: gray; }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="btn btn-logout">Cerrar Sesión</a>
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo ucfirst($rol); ?>)</h2>
        
        <form action="acciones.php" method="POST" style="margin-bottom: 20px;">
            <input type="hidden" name="accion" value="agregar">
            <input type="text" name="descripcion" placeholder="Nueva tarea..." required style="padding: 10px; width: 70%;">
            <button type="submit" class="btn btn-add">Agregar Tarea</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $tareas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td class="<?php echo $row['estado'] === 'completada' ? 'completada' : ''; ?>">
                        <?php echo htmlspecialchars($row['descripcion']); ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo ucfirst($row['estado']); ?></td>
                    <td>
                        <?php if($row['estado'] === 'pendiente'): ?>
                        <form action="acciones.php" method="POST" style="display:inline;">
                            <input type="hidden" name="accion" value="completar">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-complete">Completar</button>
                        </form>
                        <?php endif; ?>
                        
                        <?php if($rol === 'admin' || ($rol === 'usuario' && $row['username'] === $_SESSION['username'])): ?>
                        <form action="acciones.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar tarea?');">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-delete">Eliminar</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>