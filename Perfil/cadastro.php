<?php
session_start();

$conn = new mysqli("localhost", "root", "", "sistema_login");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$senha = password_hash(isset($_POST['senha']) ? $_POST['senha'] : '', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha);

if ($stmt->execute()) {
    $usuario_id = $conn->insert_id;
    $_SESSION['usuario_id'] = $usuario_id;
    $_SESSION['usuario_nome'] = $nome;
    header("Location: menu.php");
    exit;
} else {
    if ($conn->errno == 1062) {
        echo "Erro: este e-mail já está cadastrado!";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
}
?>
<html>
    <body>
        <p>Se você não foi redirecionado automaticamente, clique abaixo:</p>
        <form action="../index.php">
            <input type="submit" value="Ir para o menu">
        </form>
    </body>
</html>