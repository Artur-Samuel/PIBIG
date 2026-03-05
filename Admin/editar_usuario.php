<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sistema_login");

// Verifica permissão (somente administradores podem editar usuários)
if (!isset($_SESSION['usuario_role']) || $_SESSION['usuario_role'] !== 'admin') {
    die("Acesso negado!");
}

// Verifica se o ID do usuário foi enviado
if (!isset($_GET['id'])) {
    die("ID do usuário não informado!");
}

$id = intval($_GET['id']);

// Busca dados do usuário
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

if (!$usuario) {
    die("Usuário não encontrado!");
}

// Atualização do usuário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Se o usuário enviou nova foto
    if (!empty($_FILES['foto']['name'])) {

        $pasta = "../uploads/";
        $nomeArquivo = uniqid() . "-" . basename($_FILES["foto"]["name"]);
        $caminhoCompleto = $pasta . $nomeArquivo;

        // Salva nova foto
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $caminhoCompleto)) {

            // Exclui foto antiga (se não for a padrão)
            if ($usuario['foto'] !== "default.png" && file_exists("../uploads/" . $usuario['foto'])) {
                unlink("../uploads/" . $usuario['foto']);
            }

            $novaFoto = $nomeArquivo;
        } else {
            die("Erro ao fazer upload da foto!");
        }
    } else {
        $novaFoto = $usuario['foto']; // mantém foto antiga
    }

    // Atualiza no banco
    $stmtUpdate = $conn->prepare("UPDATE usuarios SET nome=?, email=?, role=?, foto=? WHERE id=?");
    $stmtUpdate->bind_param("ssssi", $nome, $email, $role, $novaFoto, $id);
    $stmtUpdate->execute();

    header("Location: usuarios.php?msg=editado");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="../imagens/icon.png">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            background: #f4f6fb;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 450px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #0f2b6d;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            color: #0f2b6d;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-top: 5px;
        }

        .foto-preview {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid #0f2b6d;
            object-fit: cover;
            display: block;
            margin: 10px auto 20px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #0f2b6d;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: .3s;
        }

        button:hover {
            background: #1c46a3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Usuário</h2>

    <form method="post" enctype="multipart/form-data">

        <img src="../uploads/<?php echo $usuario['foto']; ?>" class="foto-preview">

        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $usuario['nome']; ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $usuario['email']; ?>" required>

        <label>Função (role):</label>
        <select name="role" required>
            <option value="admin" <?php if ($usuario['role'] === 'admin') echo 'selected'; ?>>Administrador</option>
            <option value="usuario" <?php if ($usuario['role'] === 'usuario') echo 'selected'; ?>>Usuário</option>
        </select>

        <label>Foto de Perfil:</label>
        <input type="file" name="foto" accept="image/*">

        <button type="submit">Salvar alterações</button>
    </form>
</div>

</body>
</html>
