<?php
session_start();
require "../../Eventos/conexao.php";

if ($_SESSION['usuario_role'] != 'admin') {
    die("Sem permissão");
}

$stmt = $conn->prepare("INSERT INTO escala_multimidia (data_servico, turno, funcao, usuario_id) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $_POST['data_servico'], $_POST['turno'], $_POST['funcao'], $_POST['usuario_id']);
$stmt->execute();

header("Location: Escala.php");
exit();
