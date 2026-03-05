<?php
session_start();
require "conexao.php"; 

if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] != 'admin') {
    header("Location: eventos.php");
    exit();
}

if (isset($_POST['salvar'])) {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $data_evento = $_POST['data_evento'];

    // Se a imagem foi enviada
    if (!empty($_FILES['imagem']['name'])) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $novoNome = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], "uploads/" . $novoNome);
    } else {
        $novoNome = "default.jpg"; // IMAGEM PADRÃO
    }

    $stmt = $conn->prepare("INSERT INTO eventos (titulo, descricao, data_evento, imagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $titulo, $descricao, $data_evento, $novoNome);
    $stmt->execute();

    header("Location: eventos.php?evento_criado=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Evento</title>
    <link rel="icon" type="image/png" href="../imagens/icon.png">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f8fb;
            margin: 0;
            padding: 40px 15px;
        }

        form {
            background: #ffffff;
            max-width: 600px;
            margin: auto;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
            color: #1e3a8a;
        }

        input[type="text"],
        input[type="date"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            font-size: 14px;
            box-sizing: border-box;
            transition: border 0.2s, box-shadow 0.2s;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s, transform 0.05s;
        }

        button:hover {
            background: #1d4ed8;
        }

        button:active {
            transform: scale(0.98);
        }

    </style>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <h2>Criar Novo Evento</h2>

    <label>Título:</label>
    <input type="text" name="titulo" required>

    <label>Descrição:</label>
    <textarea name="descricao" rows="4" required></textarea>

    <label>Data do Evento:</label>
    <input type="date" name="data_evento" required>

    <label>Imagem (opcional):</label>
    <input type="file" name="imagem" accept="image/*">

    <button type="submit" name="salvar">Salvar Evento</button>
</form>

</body>
</html>
