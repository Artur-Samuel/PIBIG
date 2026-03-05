<?php
$host = "localhost";    
$usuario = "root";       
$senha = "";            
$banco = "sistema_login";       

$conn = new mysqli($host, $usuario, $senha, $banco);


if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
}

// Define charset correto para acentos
$conn->set_charset("utf8");
?>