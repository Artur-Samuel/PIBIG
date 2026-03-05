<?php
session_start();
require "../Eventos/conexao.php";

$conn;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$email = $_POST['email'] ?? null;
$senha = $_POST['senha'] ?? null;

if (!$email || !$senha) {
    echo "Por favor, preencha email e senha.";
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, senha, role, foto FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($usuario = $result->fetch_assoc()) {

    if (password_verify($senha, $usuario['senha'])) {

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_role'] = $usuario['role']; 
        $_SESSION['usuario_foto'] = $usuario['foto'] ?? 'default.png';

        header("Location: ../index.php");
        exit;

    } else {
        echo "Senha incorreta!";
    }

} else {
    echo "Usuário não encontrado!";
}
