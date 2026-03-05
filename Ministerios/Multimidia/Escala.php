<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.php");
    exit();
}

require "../../Eventos/conexao.php";

$sql = "SELECT e.id, e.data_servico, e.turno, e.funcao, e.usuario_id, u.nome
        FROM escala_multimidia e
        JOIN usuarios u ON e.usuario_id = u.id
        ORDER BY e.data_servico, e.turno";

$result = $conn->query($sql);

$escala = [];
while ($row = $result->fetch_assoc()) {
    $data = $row['data_servico'];
    $turno = $row['turno'];
    $funcao = $row['funcao'];

    $escala[$data][$turno][$funcao] = [
        'nome' => $row['nome'],
        'id' => $row['id'],
        'usuario_id' => $row['usuario_id']
    ];
}

$isAdmin = (isset($_SESSION['usuario_role']) && $_SESSION['usuario_role'] === 'admin');

function renderFuncaoCell($funcoes, $nomeFuncao) {
    $userId = $_SESSION['usuario_id'];
    $isAdminLocal = (isset($_SESSION['usuario_role']) && $_SESSION['usuario_role'] === 'admin');

    if (!isset($funcoes[$nomeFuncao])) {
        echo '<span class="vazio">-</span>';
        return;
    }

    $p = $funcoes[$nomeFuncao];
    echo '<span class="nome-escala">' . htmlspecialchars($p['nome']);
    if ($isAdminLocal || $userId == $p['usuario_id']) {
        echo '<a class="btn-remover" href="remover.php?id=' . urlencode($p['id']) . '" aria-label="Remover">✖</a>';
    }
    echo '</span>';
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="icon" type="image/png" href="../../imagens/icon.png">
    <link rel="stylesheet" href="style4.css">
    <title>Escala</title>
</head>

<body>
    <div class="escala-header">
        <h2 class="titulo-escala">Escala Multimídia</h2>

        <div class="escala-actions">
            <a class="bnt-me-escalar" href="escolher.php">➕ Me escalar</a>

            <?php if ($isAdmin): ?>
                <a class="bnt-escalar" href="admin_adicionar.php">👑 Escalar membro</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-escala">
        <table class="tabela-escala">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Turno</th>
                    <th>Projeção</th>
                    <th>Som</th>
                    <th>Fotografia</th>
                    <th>Transmissão</th>
                    <?php if ($isAdmin): ?>
                        <th class="col-admin">Admin</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($escala as $data => $turnos): ?>
                    <?php foreach ($turnos as $turno => $funcoes): ?>
                        <tr class="col-text">
                            <td class="col-data"><?= date('d/m/Y', strtotime($data)) ?></td>
                            <td class="col-turno"><?= htmlspecialchars($turno) ?></td>

                            <td class="col-funcao"><?php renderFuncaoCell($funcoes, 'Projeção'); ?></td>
                            <td class="col-funcao"><?php renderFuncaoCell($funcoes, 'Som'); ?></td>
                            <td class="col-funcao"><?php renderFuncaoCell($funcoes, 'Fotografia'); ?></td>
                            <td class="col-funcao"><?php renderFuncaoCell($funcoes, 'Transmissão'); ?></td>

                            <?php if ($isAdmin): ?>
                                <td class="col-admin">
                                    <a class="btn-lixeira"
                                       href="remover_linha.php?data=<?= urlencode($data) ?>&turno=<?= urlencode($turno) ?>"
                                       onclick="return confirm('Remover escala inteira deste culto?')"
                                       aria-label="Remover escala inteira">🗑️</a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php include '../../rodape.html'; ?>
    <!-- Opcional: seu script do modal/UX (se você quiser usar) -->
    <script src="script.js"></script>
    
</body>
</html>
