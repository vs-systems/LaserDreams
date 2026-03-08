<?php
session_start();
require __DIR__.'/config/db.php';

if(empty($_POST['nombre']) || empty($_POST['email'])){
  header('Location: carrito.php'); exit;
}

$carrito = $_SESSION['carrito'] ?? [];
if(!$carrito){ header('Location: carrito.php'); exit; }

$total = 0;
$lineas = [];
foreach($carrito as $i){
  $sub = $i['precio']*$i['cantidad'];
  $total += $sub;
  $lineas[] = "- {$i['titulo']} x{$i['cantidad']} ($".number_format($sub,0,',','.').")";
}

$mensaje = "Hola! 👋\nConsulta desde MG Muebles\n\n";
$mensaje .= "Cliente: {$_POST['nombre']}\nEmail: {$_POST['email']}\n\n";
$mensaje .= "Productos:\n".implode("\n",$lineas)."\n\n";
$mensaje .= "Total: $".number_format($total,0,',','.');

$stmt = $pdo->prepare(
  "INSERT INTO pedidos (nombre,email,carrito,total) VALUES (?,?,?,?)"
);
$stmt->execute([
  $_POST['nombre'],
  $_POST['email'],
  json_encode($carrito, JSON_UNESCAPED_UNICODE),
  $total
]);

$telefono = "5491138891414";
$url = "https://wa.me/$telefono?text=".urlencode($mensaje);
header("Location: $url");
