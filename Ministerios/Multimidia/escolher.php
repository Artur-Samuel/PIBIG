<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="style3.css">
    <link rel="icon" type="image/png" href="../../imagens/icon.png">
    <title>Registrar na Escala</title>
</head>
<body>

<div class="page-admin">
    <h2 class="titulo-escala">Escolher dia para servir</h2>

    <div class="card-form">
        <form class="form-escala" action="salvar_escala.php" method="POST">

            <div class="form-group">
                <label>Data</label>
                <input type="date" name="data_servico" required>
            </div>

            <div class="form-group">
                <label>Turno</label>
                <select name="turno" required>
                    <option value="Manhã">Manhã</option>
                    <option value="Noite">Noite</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>

            <div class="form-group">
                <label>Função</label>
                <select name="funcao" required>
                    <option value="Som">Som</option>
                    <option value="Projeção">Projeção</option>
                    <option value="Fotografia">Fotografia</option>
                    <option value="Transmissão">Transmissão</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">✅ Confirmar</button>
                <a href="Escala.php" class="btn-secondary">⬅ Voltar</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>