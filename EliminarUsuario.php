<?php
require_once 'model/Usuario.php';

$id = $_GET['id'] ?? null;
$usuario = new Usuario();
if ($id && $usuario->obtenerPorId($id)) {
  if ($usuario->eliminar()) {
    $mensaje = 'Usuario eliminado correctamente.';
  } else {
    $mensaje = 'Error al eliminar usuario.';
  }
} else {
  $mensaje = 'Usuario no encontrado.';
}
header('Location: AddRoles.php?mensaje=' . urlencode($mensaje));
exit;
