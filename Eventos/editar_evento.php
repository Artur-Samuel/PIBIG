<?php
session_start();
require "conexao.php";

if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] != 'admin') {
    header("Location: eventos.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: eventos.php");
    exit();
}

$id = $_GET['id'];

// Buscar os dados do evento
$stmt = $conn->prepare("SELECT * FROM eventos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$evento = $stmt->get_result()->fetch_assoc();

if (!$evento) {
    echo "Evento não encontrado.";
    exit();
}

// Se o formulário foi enviado
if (isset($_POST['salvar'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_evento = $_POST['data_evento'];

    // Se trocou a imagem
    if (!empty($_FILES['imagem']['name'])) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $novoNome = uniqid() . "." . $ext;

        move_uploaded_file($_FILES['imagem']['tmp_name'], "uploads/" . $novoNome);

        // Apagar imagem anterior (se não for a padrão)
        if ($evento['imagem'] != "default.jpg" && file_exists("uploads/" . $evento['imagem'])) {
            unlink("uploads/" . $evento['imagem']);
        }
    } else {
        $novoNome = $evento['imagem']; // mantém a mesma imagem
    }

    // Atualizar no banco
    $stmt = $conn->prepare("UPDATE eventos SET titulo=?, descricao=?, data_evento=?, imagem=? WHERE id=?");
    $stmt->bind_param("ssssi", $titulo, $descricao, $data_evento, $novoNome, $id);
    $stmt->execute();

    header("Location: eventos.php?editado=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../imagens/icon.png">
    <title>Editar Evento</title>
    <style>
        body { background: #eef2f7; font-family: Arial; }
        .container { max-width: 500px; margin: 50px auto; background: #fff; padding: 25px; border-radius: 10px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px; background: #003399; color: #fff; border: none; cursor: pointer; width: 100%; border-radius: 5px; }
        button:hover { background: #002070; }
        img { width: 100%; border-radius: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="container">
<h2>Editar Evento</h2>

<img src="uploads/<?= $evento['imagem'] ?>" alt="Imagem do Evento">

<form action="" method="POST" enctype="multipart/form-data">
    <label>Título:</label>
    <input type="text" name="titulo" value="<?= $evento['titulo'] ?>" required>

    <label>Descrição:</label>
    <textarea name="descricao" rows="5" required><?= $evento['descricao'] ?></textarea>

    <label>Data do Evento:</label>
    <input type="date" name="data_evento" value="<?= $evento['data_evento'] ?>" required>

    <label>Trocar Imagem (opcional):</label>
    <input type="file" name="imagem">

    <button type="submit" name="salvar">Salvar Alterações</button>
</form>

<br>
<a href="eventos.php">← Voltar</a>

</div>

</body>
</html>
