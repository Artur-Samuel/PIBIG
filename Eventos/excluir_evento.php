<?php
session_start();
require "conexao.php";

// Verifica se o usuário é ADMIN
if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] != 'admin') {
    header("Location: eventos.php");
    exit();
}

// Verifica se recebeu o ID
if (!isset($_GET['id'])) {
    header("Location: eventos.php");
    exit();
}

$id = $_GET['id'];

// Buscar a imagem atual do evento
$stmt = $conn->prepare("SELECT imagem FROM eventos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$evento = $resultado->fetch_assoc();

// Se tiver imagem e não for default, remover da pasta
if ($evento && $evento['imagem'] != "default.jpg" && file_exists("uploads/" . $evento['imagem'])) {
    unlink("uploads/" . $evento['imagem']);
}

// Apagar do banco
$stmt = $conn->prepare("DELETE FROM eventos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Volta para a lista
header("Location: eventos.php?evento_excluido=1");
exit();
