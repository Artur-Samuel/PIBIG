<?php
session_start();
require "../../Eventos/conexao.php";

$data = $_POST['data_servico'];
$turno = $_POST['turno'];
$funcao = $_POST['funcao'];
$usuario_id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("INSERT INTO escala_multimidia (data_servico, turno, funcao, usuario_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $data, $turno, $funcao, $usuario_id);
$stmt->execute();

header("Location: Escala.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../imagens/icon.png">
    <title>Registro na escala</title>
</head>
<body>
    
</body>
</html>
