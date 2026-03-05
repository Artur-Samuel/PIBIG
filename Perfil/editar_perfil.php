<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

require "../Eventos/conexao.php";

$id = $_SESSION['usuario_id'];

// Atualizar dados
if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    // Atualiza nome e email
    $stmt = $conn->prepare("UPDATE usuarios SET nome=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $nome, $email, $id);
    $stmt->execute();

    // Se trocou foto
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $novoNome = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $novoNome);

        $stmt = $conn->prepare("UPDATE usuarios SET foto=? WHERE id=?");
        $stmt->bind_param("si", $novoNome, $id);
        $stmt->execute();

        $_SESSION['usuario_foto'] = $novoNome;
    }

    header("Location: perfil.php?editado=1");
    exit();
}

// Buscar dados do usuário
$stmt = $conn->prepare("SELECT nome, email, role, foto FROM usuarios WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Editar Perfil</title>
<style>
body{
  font-family: Arial, sans-serif;
  background:#eef2f7;
  margin:0;
  display:flex;
  justify-content:center;
  padding-top:60px;
}
.card{
  background:white;
  width:450px;
  border-radius:16px;
  box-shadow:0 8px 26px rgba(0,0,0,.12);
  padding:34px;
  animation:fade .4s ease;
}
@keyframes fade{from{opacity:0;transform:translateY(10px)}}
.foto-wrap{
  text-align:center;
  margin-bottom:20px;
}
.foto-wrap img{
  width:140px;
  height:140px;
  border-radius:50%;
  object-fit:cover;
  border:4px solid #1e3a8a;
}
.foto-wrap label{
  display:inline-block;
  margin-top:12px;
  background:#1e3a8a;
  color:white;
  padding:8px 14px;
  border-radius:8px;
  cursor:pointer;
  font-size:14px;
}
input[type="text"], input[type="email"]{
  width:100%;
  padding:10px;
  border-radius:8px;
  border:1px solid #ccc;
  margin-top:6px;
  margin-bottom:12px;
}
button{
  width:100%;
  background:#1e3a8a;
  color:white;
  border:none;
  padding:12px;
  border-radius:8px;
  cursor:pointer;
  transition:.3s;
  font-size:15px;
}
button:hover{background:#15306c}
.voltar{
  display:block;
  text-align:center;
  margin-top:14px;
  color:#1e3a8a;
  text-decoration:none;
}
</style>
</head>
<body>

<div class="card">
  <h2 style="text-align:center;color:#1e3a8a;">Editar Perfil</h2>

  <form method="POST" enctype="multipart/form-data">
    <div class="foto-wrap">
      <img src="uploads/<?php echo $user['foto'] ?? 'default.png'; ?>">
      <label>
        Trocar Foto
        <input type="file" name="foto" accept="image/*" style="display:none;">
      </label>
    </div>

    <label>Nome:</label>
    <input type="text" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>">

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">

    <button type="submit" name="salvar">Salvar alterações</button>
  </form>

  <a class="voltar" href="perfil.php">← Voltar ao Perfil</a>
</div>

</body>
</html>
