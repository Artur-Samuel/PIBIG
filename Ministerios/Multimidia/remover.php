<?php
session_start();
require "../../Eventos/conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    die("Acesso negado");
}

$id = $_GET['id'];

// Descobrir quem é o dono do registro
$stmt = $conn->prepare("SELECT usuario_id FROM escala_multimidia WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$registro = $result->fetch_assoc();

if (!$registro) {
    die("Registro não encontrado");
}

$usuarioLogado = $_SESSION['usuario_id'];
$ehAdmin = $_SESSION['usuario_role'] == 'admin';

// Permitir se for admin OU se for o próprio usuário
if ($ehAdmin || $registro['usuario_id'] == $usuarioLogado) {
    $del = $conn->prepare("DELETE FROM escala_multimidia WHERE id = ?");
    $del->bind_param("i", $id);
    $del->execute();
} else {
    die("Sem permissão para remover este registro.");
}

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
