<?php
$host = "localhost";     // normalmente localhost
$usuario = "root";       // usuário do MySQL (no XAMPP/Laragon é root)
$senha = "";             // senha do MySQL (no XAMPP é vazio)
$banco = "sistema_login";       // nome do seu banco de dados

$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se deu certo
if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
}

// (Opcional) Define charset correto para acentos
$conn->set_charset("utf8");
?>