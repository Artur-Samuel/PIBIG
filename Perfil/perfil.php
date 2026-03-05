<?php
session_start();
require_once '../Eventos/conexao.php'; // ajuste se necessário

// Se não estiver logado, redireciona
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_SESSION['usuario_id'];

// Busca os dados do usuário
$stmt = $conn->prepare("SELECT nome, email, role, foto FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" href="imagens/icon.png">
  <title>Perfil do Usuário</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #eef2f7;
      margin: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      padding: 0;
    }
    .header {
      background: #1e3a8a;
      color: white;
      padding: 20px;
      text-align: center;
    }
    .container {
      max-width: 800px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 26px rgba(0,0,0,.12);
    }
    .profile {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .profile img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #1e3a8a;
    }
    .info h2 {
      margin: 0 0 8px;
      color: #1e3a8a;
    }
    .info p {
      margin: 4px 0;
    }
    .buttons {
      margin-top: 30px;
      display: flex;
      gap: 12px;
    }
    button, a.btn {
      padding: 12px 16px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
    }
    .btn-primary {
      background: #1e3a8a;
      color: white;
    }
    .btn-secondary {
      background: #ccc;
      color: #333;
    }
    .btn-logout {
      background: #ff0b0bff;
      color: #ffffffff;
    }
    .admin-btn {
      background: #d97706;
      color: white;
    }
  </style>
</head>
<body>
<div class="header"><h1>Meu Perfil</h1></div>

<div class="container">
  <div class="profile">
      <img src="../uploads/<?= $user['foto'] ?: 'default.png' ?>" alt="Foto de Perfil">    <div class="info">
      <h2><?= htmlspecialchars($user['nome']) ?></h2>
      <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
      <p><strong>Função:</strong> <?= $user['role'] === 'admin' ? 'Administrador👑' : 'Membro' ?></p>
    </div>
  </div>

  <div class="buttons">
    <a href="alterar_senha.php" class="btn btn-primary">Alterar senha</a>
    <a href="editar_perfil.php" class="btn btn-secondary">Editar perfil</a>
    <a href="../index.php" class="btn btn-secondary">Voltar</a>
    <a href="logout.php" class="btn btn-logout">Sair</a>

    <?php if ($user['role'] === 'admin'): ?>
      <a href="../admin/index.php" class="btn admin-btn">Acessar área do administrador</a>
    <?php endif; ?>
  </div>
</div>

<?php
  include '../rodape.html';
?>

</body>
</html>
