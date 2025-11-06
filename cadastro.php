<?php
$conn = new mysqli("localhost", "root", "", "sistema_login");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nome, $email, $senha);

if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso!";
} else {
    if ($conn->errno == 1062) {
        echo "Erro: este e-mail já está cadastrado!";
    } else {
        echo "Erro ao cadastrar: " . $conn->error;
    }
}

echo "usuário cadastrado com sucesso";
header("location: menu.php");
?>
<html>
    <body>
        <form action="formlogin.html">
            <input type="submit" value="Ir para o login">
        </form>
    </body>
</html>