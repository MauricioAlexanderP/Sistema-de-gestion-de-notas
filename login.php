<?php
session_start();
require_once 'model/Usuario.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $correo = $_POST['username'] ?? '';
  $contraseña = $_POST['password'] ?? '';

  $usuario = new Usuario();
  if ($usuario->obtenerPorCorreo($correo)) {
    if ($usuario->verificarContraseña($contraseña)) {
      // Login exitoso
      $_SESSION['usuario_id'] = $usuario->id;
      $_SESSION['usuario_nombre'] = $usuario->nombre;
      $_SESSION['usuario_rol'] = $usuario->rol;
      if ($usuario->rol === 'profesor') {
        header('Location: addCurso.php');
      } elseif ($usuario->rol === 'alumno') {
        header('Location: vista_alumno.php');
      } else {
        header('Location: index.php');
      }
      exit;
    } else {
      $_SESSION['login_error'] = 'Contraseña incorrecta.';
    }
  } else {
    $_SESSION['login_error'] = 'Usuario no encontrado.';
  }
  header('Location: index.php');
  exit;
}
