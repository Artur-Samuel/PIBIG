<?php
session_start();
if ($_SESSION['usuario_role'] != 'admin') {
    die("Sem permissão");
}

require "../../Eventos/conexao.php";

$usuarios = $conn->query("SELECT id, nome FROM usuarios ORDER BY nome");
?>

<head>
    <link rel="stylesheet" href="style3.css">
    <link rel="icon" type="image/png" href="../../imagens/icon.png">
    <title>Adicionar na escala</title>
</head>
<div class="page-admin">
    <h2 class="titulo-escala">Escalar Membro</h2>

    <div class="card-form">
        <form class="form-escala" action="salvar_admin.php" method="POST">

            <div class="form-group">
            <label>Membro</label>
            <select name="usuario_id">
                <?php while($u = $usuarios->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>"><?= $u['nome'] ?></option>
                <?php endwhile; ?>
            </select>
            </div>

            <div class="form-group">
            <label>Data</label>
            <input type="date" name="data_servico" required>
            </div>

            <div class="form-group">
            <label>Turno</label>
            <select name="turno">
                <option>Manhã</option>
                <option>Noite</option>
                <option>Outro</option>
            </select>
            </div>

            <div class="form-group">
            <label>Função</label>
            <select name="funcao">
                <option>Som</option>
                <option>Projeção</option>
                <option>Fotografia</option>
                <option>Transmissão</option>
            </select>
            </div>

            <div class="form-actions">
            <button type="submit" class="btn-primary">➕ Adicionar à escala</button>
            <a href="Escala.php" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
