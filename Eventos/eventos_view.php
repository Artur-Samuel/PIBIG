<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Eventos - Igreja</title>
    <link rel="stylesheet" href="style_eventos.css">
</head>
<body>
<header>
    <h1>Página de Eventos</h1>
    <p>Bem-vindo, <?php echo isset($_SESSION['nome']) ? htmlspecialchars($_SESSION['nome']) : 'Usuário'; ?> (<?php echo htmlspecialchars($tipoUsuario); ?>)</p>
</header>

<div class="container">
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="mensagem sucesso"><?php echo $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="mensagem erro"><?php echo $_SESSION['error_message']; ?></div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <h2>Próximos Eventos</h2>

    <?php if (empty($eventos)): ?>
        <p>Nenhum evento agendado no momento.</p>
    <?php else: ?>
        <?php foreach ($eventos as $evento): ?>
        <div class="evento">
            <h3><?php echo htmlspecialchars($evento['titulo']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($evento['descricao'])); ?></p>
            <p><strong>Data:</strong> <?php echo date("d/m/Y", strtotime($evento['data_evento'])); ?></p>

            <?php if ($isAdmin): ?>
            <div class="botoes">
                <button class="btn-editar" 
                        data-id="<?php echo $evento['id']; ?>"
                        data-titulo="<?php echo htmlspecialchars($evento['titulo']); ?>"
                        data-descricao="<?php echo htmlspecialchars($evento['descricao']); ?>"
                        data-data="<?php echo $evento['data_evento']; ?>">
                    Editar
                </button>
                <form method="POST" class="form-excluir">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" name="id" value="<?php echo $evento['id']; ?>">
                    <button type="submit" class="excluir">Excluir</button>
                </form>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($isAdmin): ?>
    <div id="area-admin">
        <div class="formulario" id="formAdicionar">
            <h3>Adicionar Novo Evento</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="acao" value="adicionar">
                <input type="text" name="titulo" placeholder="Título" required>
                <textarea name="descricao" placeholder="Descrição" rows="4" required></textarea>
                <input type="date" name="data_evento" required>
                <button type="submit">Adicionar Evento</button>
            </form>
        </div>

        <div class="formulario" id="formEditar" style="display:none;">
            <h3>Editar Evento</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" id="editId">
                <input type="text" name="titulo" id="editTitulo" required>
                <textarea name="descricao" id="editDescricao" rows="4" required></textarea>
                <input type="date" name="data_evento" id="editData" required>
                <button type="submit">Salvar Alterações</button>
                <button type="button" id="btnCancelarEdicao">Cancelar</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="script_eventos.js"></script>
</body>
</html>