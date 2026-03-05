<?php
session_start();

// Se não estiver logado, volta para login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
}

// Bloqueia quem não for admin
if ($_SESSION['usuario_role'] !== 'admin') {
    echo "<h2>Acesso negado! Esta área é somente para administradores.</h2>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="../imagens/icon.png">
<link rel="stylesheet" href="style.css">
<title>Painel Administrativo</title>
</head>

<body>
    <div class="sidebar">
        <h2>Administração</h2>

        <a href="index.php">🏠 Dashboard</a>
        <a href="usuarios.php">👥 Gerenciar Usuários</a>
        <a href="../Eventos/eventos.php">📅 Gerenciar Eventos</a>
        <a href="ministerios.php">🎤 Ministérios</a>
        <a href="../index.php" class="return">🚪 Inicio</a>
        <a href="../Perfil/logout.php" class="logout">🚪 Sair</a>
    </div>

    <div class="content">
        <h1>Bem-vindo, <?= $_SESSION['usuario_nome'] ?>!</h1>
        <p>Use o menu lateral para gerenciar o sistema da igreja.</p>

        <div class="cards">
            <div class="card">
                <h3>Total de Membros</h3>
                <p>📌 (futuramente: puxar do banco)</p>
            </div>

            <div class="card">
                <h3>Próximos Eventos</h3>
                <p>📌 (lista automática depois)</p>
            </div>

            <div class="card">
                <h3>Ministérios ativos</h3>
                <p>📌 (informações internas)</p>
            </div>
        </div>
    </div>

</body>
</html>
