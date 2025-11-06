<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require "conexao.php"; // sua conexão com o BD

$id = $_SESSION['usuario_id'];

// Atualizar foto se enviada
if (isset($_POST['trocar_foto'])) {
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $novoNome = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $novoNome);

        $stmt = $conn->prepare("UPDATE usuarios SET foto=? WHERE id=?");
        $stmt->bind_param("si", $novoNome, $id);
        $stmt->execute();

        $_SESSION['foto'] = $novoNome;

        header("Location: perfil.php?ok=1");
        exit();
    }
}

// Buscar dados do usuário
$stmt = $conn->prepare("SELECT nome, email, 'role', foto FROM usuarios WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Meu Perfil</title>
<style>
    body{
    font-family: Arial, sans-serif;
    background:#eef2f7;
    margin:0;
    display:flex;
    justify-content:center;
    padding-top:60px;
    }
    .perfil-card{
    background:white;
    width:420px;
    border-radius:18px;
    box-shadow:0 8px 26px rgba(0,0,0,.12);
    padding:32px;
    text-align:center;
    animation:fade .4s ease;
    }
    @keyframes fade{from{opacity:0;transform:translateY(10px)}}
    .foto-wrap{
    position:relative;
    display:inline-block;
    }
    .foto-wrap img{
    width:130px;
    height:130px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #1e3a8a;
    }
    .foto-wrap label{
    position:absolute;
    bottom:4px; right:4px;
    background:#1e3a8a;
    color:white;
    padding:6px 10px;
    border-radius:12px;
    font-size:12px;
    cursor:pointer;
    }
    h2{color:#1e3a8a;margin-top:14px}
    .info{margin:18px 0}
    .info p{margin:6px 0}
    button{
    background:#1e3a8a;
    color:white;
    border:none;
    padding:10px 18px;
    border-radius:8px;
    cursor:pointer;
    transition:.3s;
    margin-top:10px;
    }
    button:hover{background:#15306c}
</style>
</head>
<body>

<div class="perfil-card">
  <div class="foto-wrap">
    <img src="uploads/<?php echo $user['foto'] ?? 'default.png'; ?>">
    <form method="POST" enctype="multipart/form-data">
      <label>
        Trocar
        <input type="file" name="foto" accept="image/*" style="display:none" onchange="this.form.submit()">
      </label>
      <input type="hidden" name="trocar_foto">
    </form>
  </div>

  <h2><?php echo $user['nome']; ?></h2>

  <div class="info">
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <p><strong>Tipo de usuário:</strong> <?php echo $user['role'] == "admin" ? "Administrador" : "Membro"; ?></p>
  </div>

  <a href="index.php"><button>Voltar ao Início</button></a>
</div>

</body>
</html>
