<?php
session_start();
require "../../Eventos/conexao.php";

if ($_SESSION['usuario_role'] != 'admin') {
    die("Sem permissão.");
}

$data = $_GET['data'];
$turno = $_GET['turno'];

$stmt = $conn->prepare("DELETE FROM escala_multimidia WHERE data_servico = ? AND turno = ?");
$stmt->bind_param("ss", $data, $turno);
$stmt->execute();

header("Location: Escala.php");
exit();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../../imagens/icon.png">

    <title>Remover da Escala</title>
</head>
<body>
    
</body>
</html>
