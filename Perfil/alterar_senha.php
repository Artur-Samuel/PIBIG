<?php
session_start();
require "../Eventos/conexao.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['usuario_id'];
$mensagem = "";

// Caso o formulário seja enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senha_atual = $_POST['senha_atual'];
    $senha_nova = $_POST['senha_nova'];
    $senha_confirmar = $_POST['senha_confirmar'];

    // Buscar a senha atual do BD
    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verificar senha atual
    if (!password_verify($senha_atual, $user['senha'])) {
        $mensagem = "❌ Senha atual incorreta!";
    } elseif ($senha_nova !== $senha_confirmar) {
        $mensagem = "❌ As senhas novas não coincidem!";
    } else {
        // Atualizar senha
        $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE usuarios SET senha=? WHERE id=?");
        $stmt->bind_param("si", $senha_hash, $id);
        $stmt->execute();

        $mensagem = "✅ Senha alterada com sucesso!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../imagens/icon.png">
    <title>Alterar Senha</title>
    <style>
        body{
            font-family:Arial; background:#eef2f7;
            display:flex; justify-content:center;
            padding-top:60px;
            }
        .card{
            background:white; width:420px;
            border-radius:18px; padding:30px;
            box-shadow:0 8px 26px rgba(0,0,0,.12);
            animation:fade .4s ease;
            }

        @keyframes fade{from{opacity:0; transform:translateY(10px)}}
        h2{color:#1e3a8a; text-align:center}
        form{margin-top:20px}
        input{
            width:100%; padding:12px; margin-bottom:14px;
            border:1px solid #ccc; border-radius:8px;
            }
        button{
            width:100%; background:#1e3a8a; color:white;
            border:none; padding:12px; border-radius:8px;
            cursor:pointer; transition:.3s;
            }
        button:hover{background:#15306c}
        .msg{text-align:center; margin-bottom:14px; font-weight:bold}
        a{text-decoration:none; display:block; text-align:center; margin-top:12px}
    </style>
</head>
<body>
<div class="card">
  <h2>Alterar Senha</h2>

  <?php if($mensagem): ?>
    <p class="msg"><?php echo $mensagem; ?></p>
  <?php endif; ?>

  <form method="POST">
    <input type="password" name="senha_atual" placeholder="Senha atual" required>
    <input type="password" name="senha_nova" placeholder="Nova senha" required>
    <input type="password" name="senha_confirmar" placeholder="Confirmar nova senha" required>

    <button type="submit">Salvar nova senha</button>
  </form>

  <a href="perfil.php">Voltar ao Perfil</a>
</div>
</body>
</html>
