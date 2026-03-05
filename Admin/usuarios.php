<?php
session_start();
require_once "../Eventos/conexao.php"; 

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

//Função de Pesquisa
$q       = trim($_GET['q'] ?? '');          // busca nome/email
$role    = $_GET['role'] ?? 'all';          // admin | membro
$order   = $_GET['order'] ?? 'asc';         // asc | desc
$page    = max(1, intval($_GET['page'] ?? 1));
$perPage = 8;                               // itens por página

// validações simples
$order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';
$role  = in_array($role, ['admin','usuario','all']) ? $role : 'all';
$offset = ($page - 1) * $perPage;

/* -------------------------
   montar WHERE dinamicamente
   ------------------------- */
$whereClauses = [];
$params = [];
$types = '';

// busca por nome ou email
if ($q !== '') {
    $whereClauses[] = "(nome LIKE ? OR email LIKE ?)";
    $like = "%{$q}%";
    $params[] = $like; $params[] = $like;
    $types .= 'ss';
}

// filtro role
if ($role !== 'all') {
    $whereClauses[] = "role = ?";
    $params[] = $role;
    $types .= 's';
}

$whereSQL = '';
if (count($whereClauses)) {
    $whereSQL = 'WHERE ' . implode(' AND ', $whereClauses);
}

/* -------------------------
   contar total (para paginação)
   ------------------------- */
$countSql = "SELECT COUNT(*) AS total FROM usuarios $whereSQL";
$stmt = $conn->prepare($countSql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
$stmt->close();

$totalPages = max(1, ceil($total / $perPage));

/* -------------------------
   buscar usuários (paginado)
   ------------------------- */
$sql = "SELECT id, nome, email, role, foto FROM usuarios
        $whereSQL
        ORDER BY nome $order
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

// bind params dinamicamente (primeiro os filtros, depois limit/offset)
if ($types === '') {
    // só limit/offset
    $stmt->bind_param("ii", $perPage, $offset);
} else {
    // mistura tipos: $types + "ii"
    $bindTypes = $types . 'ii';
    $bindValues = array_merge($params, [$perPage, $offset]);
    // usar call_user_func_array para bind_param dinâmico
    $refs = [];
    $refs[] = & $bindTypes;
    for ($i=0;$i<count($bindValues);$i++){
        $refs[] = & $bindValues[$i];
    }
    call_user_func_array([$stmt, 'bind_param'], $refs);
}

$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Gerenciar Usuários — Admin</title>
<link rel="icon" type="image/png" href="../imagens/icon.png">
<link rel="stylesheet" href="style.css"> 
<style>
    /* ------------------------
    Estilos adicionais locais
    ------------------------ */
    .content { margin-left:260px; padding:30px; }
    .header-row { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
    .controls { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }

    .search-input {
    padding:10px; border-radius:8px; border:1px solid #cbd5e1; width:260px;
    }
    .select, .btn {
    padding:10px 12px; border-radius:8px; border:none; cursor:pointer;
    }
    .btn { background:#1e3a8a; color:#fff; }
    .btn.secondary { background:#6b7280; }
    .add-user { background:#10b981; color:white; padding:10px 14px; border-radius:10px; text-decoration:none; }

    .users-container {
        display:grid;
        grid-template-columns: repeat(auto-fill,minmax(260px,1fr));
        gap:20px;
        margin-top:20px;
    }

    .user-card {
        background:white; padding:20px; border-radius:14px; text-align:center;
        box-shadow:0 8px 24px rgba(2,6,23,0.08);
        position:relative;
    }

    .user-card img {
        width:92px;height:92px;border-radius:50%;object-fit:cover;border:3px solid #1e3a8a;margin:0 auto 12px;
    }

    .user-card h3 { color:#1e3a8a; margin:6px 0 4px; font-size:1.05rem; }
    .user-card p { margin:0;color:#111827;font-size:0.95rem; }
    .role-badge { display:inline-block; padding:6px 10px; border-radius:8px; color:#fff; margin-top:10px; font-size:0.85rem; }
    .role-admin { background:#d97706; }
    .role-membro { background:#1e3a8a; }

    .card-actions { display:flex; gap:10px; justify-content:center; margin-top:14px; }
    .action-edit { background:#1e3a8a;color:#fff;padding:8px 12px;border-radius:8px;text-decoration:none; }
    .action-delete { background:#ef4444;color:#fff;padding:8px 12px;border-radius:8px;text-decoration:none; }

    .pagination { margin-top:18px; display:flex; gap:8px; justify-content:center; align-items:center; flex-wrap:wrap; }

    /* modal detalhes */
    .modal {
    position:fixed; inset:0; display:none; align-items:center; justify-content:center;
    background: rgba(0,0,0,0.45); z-index:2000;
    }
    .modal .panel {
    background:#fff; padding:20px; border-radius:12px; width:90%; max-width:520px; text-align:center;
    }
    .modal img { width:160px;height:160px;border-radius:50%;object-fit:cover;border:4px solid #1e3a8a;margin-bottom:14px; }

    /* responsivo */
    @media (max-width:700px){
    .content { margin-left:0; padding:16px; }
    .users-container { grid-template-columns: 1fr; }
    }
</style>
</head>
<body>

<div class="sidebar"> 
  <!-- Barra Lateral -->
  <h2 style="color:white;padding:18px 16px">Administração</h2>
  <a href="index.php">🏠 Dashboard</a>
  <a href="usuarios.php" style="background:rgba(255,255,255,0.05);">👥 Gerenciar Usuários</a>
  <a href="../Eventos/eventos.php">📅 Gerenciar Eventos</a>
  <a href="ministerios.php">🎤 Ministérios</a>
  <a href="../index.php" style="background:#4DEF9A;color:white;padding:12px;border-radius:8px;margin:16px;display:inline-block;">Início</a>
  <a href="../Perfil/logout.php" style="background:#FF413D;color:white;padding:12px;border-radius:8px;margin:16px;display:inline-block;">Sair</a>
</div>

<div class="content">
  <div class="header-row">
    <div>
      <h1>Gerenciar Usuários</h1>
      <p style="color:#374151;margin-top:6px">Total: <?= $total ?> usuário(s)</p>
    </div>

    <div class="controls">
      <form method="get" style="display:flex;gap:8px;align-items:center;">
        <input class="search-input" type="search" name="q" placeholder="Buscar por nome ou email" value="<?= htmlspecialchars($q) ?>">
        <select name="role" class="select">
          <option value="all" <?= $role==='all' ? 'selected' : '' ?>>Todos</option>
          <option value="admin" <?= $role==='admin' ? 'selected' : '' ?>>Administradores</option>
          <option value="usuario" <?= $role==='usuario' ? 'selected' : '' ?>>Usuários</option>
        </select>

        <select name="order" class="select">
          <option value="asc" <?= strtoupper($order)==='ASC' ? 'selected' : '' ?>>A → Z</option>
          <option value="desc" <?= strtoupper($order)==='DESC' ? 'selected' : '' ?>>Z → A</option>
        </select>

        <button class="btn secondary" type="submit">Aplicar</button>
      </form>

      <a class="add-user" href="../cadastro.php">➕ Adicionar Usuário</a>
    </div>
  </div>

  <div class="users-container">
    <?php if (empty($users)): ?>
      <p>Nenhum usuário encontrado.</p>
    <?php endif; ?>

    <?php foreach($users as $u): 
        // caminho da imagem (relativo à pasta admin)
        $foto = $u['foto'] ? "../uploads/" . $u['foto'] : "../uploads/default.png";
    ?>
      <div class="user-card">
        <img src="<?= htmlspecialchars($foto) ?>" alt="Foto de <?= htmlspecialchars($u['nome']) ?>" onerror="this.src='../uploads/default.png'">
        <h3><?= htmlspecialchars($u['nome']) ?></h3>
        <p><?= htmlspecialchars($u['email']) ?></p>

        <span class="role-badge <?= $u['role']=='admin' ? 'role-admin' : 'role-membro' ?>">
          <?= $u['role']=='admin' ? 'Administrador' : 'Usuário' ?>
        </span>

        <div class="card-actions">
          <a href="editar_usuario.php?id=<?= $u['id'] ?>" class="action-edit">✏ Editar</a>
          <a href="deletar_usuario.php?id=<?= $u['id'] ?>" class="action-delete" onclick="return confirm('Excluir usuário <?= addslashes($u['nome']) ?>?');">🗑 Excluir</a>
          <button class="action-edit" onclick="openDetails(<?= $u['id'] ?>)">🔍 Detalhes</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- paginação -->
  <div class="pagination">
    <?php if($page > 1): ?>
      <a class="btn secondary" href="?<?= http_build_query(array_merge($_GET,['page'=> $page-1])) ?>">&larr; Anterior</a>
    <?php endif; ?>

    <span>Página <?= $page ?> de <?= $totalPages ?></span>

    <?php if($page < $totalPages): ?>
      <a class="btn" href="?<?= http_build_query(array_merge($_GET,['page'=> $page+1])) ?>">Próxima &rarr;</a>
    <?php endif; ?>
  </div>

</div>

<!-- modal de detalhes (conteúdo carregado via JS) -->
<div id="modal" class="modal" onclick="closeModal(event)">
  <div class="panel" onclick="event.stopPropagation()">
    <button style="float:right;border:none;background:transparent;font-size:18px;cursor:pointer;" onclick="closeModal()">✖</button>
    <div id="modalContent" style="padding-top:6px"></div>
  </div>
</div>

<script>
// abre modal com detalhes (carrega via fetch)
function openDetails(id){
  fetch('detalhes_usuario.php?id=' + encodeURIComponent(id))
    .then(r => r.text())
    .then(html => {
      document.getElementById('modalContent').innerHTML = html;
      document.getElementById('modal').style.display = 'flex';
    })
    .catch(err => alert('Erro ao carregar detalhes: ' + err));
}

function closeModal(e){
  document.getElementById('modal').style.display = 'none';
}
</script>
<?php include '../rodape.html'; ?>
</body>
</html>
