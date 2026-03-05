<?php
session_start();
require "conexao.php";
include "../Header.php";

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Buscar eventos
$sql = "SELECT * FROM eventos ORDER BY data_evento ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/png" href="../imagens/icon.png">
        <title>Eventos</title>
        <style>
            html, body { height: 100%; }

            body { background: #eef2f7; 
            font-family: Arial; 
            margin:0; 
            padding:0; 
            }

            .container { width: 90%; max-width: 900px; margin: 40px auto; }

            h1 { text-align:center; color:#003399; }

            .evento {
                background:#fff;
                padding:15px;
                border-radius:10px;
                margin-bottom:20px;
                box-shadow:0px 2px 5px rgba(0,0,0,0.2);
                display:flex;
                gap:15px;
                align-items: center;
            }

            .evento img {
                width:120px;
                height:120px;
                object-fit:cover;
                border-radius:10px;
            }

            .info { flex:1; }
            .titulo { font-size:20px; font-weight:bold; color:#003399; }

            .acao a, .novo-evento {
                text-decoration:none;
                padding:8px 14px;
                border-radius:6px;
                color:white;
                font-size:14px;
            }

            .editar { background:#007bff; }
            .excluir { background:#d9534f; }
            .novo-evento { background:#28a745; display:inline-block; margin-bottom:20px; }

            .acao a:hover { opacity:0.85; }
            .novo-evento:hover { opacity:0.85; }
            
        </style>
    </head>
<body>

<div class="container">
    <h1>Eventos</h1>

    <!-- Botão Criar Evento -->
    <?php if ($_SESSION['usuario_role'] == 'admin'): ?>
        <a href="criar_evento.php" class="novo-evento">+ Criar Novo Evento</a>
    <?php endif; ?>

    <?php while ($evento = $result->fetch_assoc()): ?>
        <div class="evento">
            <img src="uploads/<?= $evento['imagem'] ?>" alt="Imagem do evento">

            <div class="info">
                <div class="titulo"><?= $evento['titulo'] ?></div>
                <p><?= nl2br($evento['descricao']) ?></p>
                <small><strong>Data do evento:</strong> <?= $evento['data_evento'] ?></small>
            </div>

            <div class="acao">
                <!-- Apenas ADMIN pode Editar ou Excluir -->
                <?php if ($_SESSION['usuario_role'] == 'admin'): ?>
                    <a href="editar_evento.php?id=<?= $evento['id'] ?>" class="editar">Editar</a><br><br>
                    <a href="excluir_evento.php?id=<?= $evento['id'] ?>" class="excluir" onclick="return confirm('Deseja realmente excluir este evento?');">Excluir</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>

    <br><a href="../index.php">← Voltar ao início</a>
</div>

<?php
    include '../rodape.html';
?>

</body>
</html>
