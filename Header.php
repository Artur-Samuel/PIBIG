<?php

if (!isset($_SESSION['usuario_id'])) {
   header("Location: login.php");
    exit();
}

require "Eventos/conexao.php";

$id = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT nome, role, foto FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?> 
<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/png" href="../imagens/icon.png">
    <title>PIB Iguaçu — Início</title>
    <style>
      .perfil {
        margin-left: 20px;
      }
      .perfil-foto {
        width:70px;
        height:70px;
        border-radius:50%;
        object-fit:cover;
        cursor:pointer;
        transition:.3s;
        border:3.5px solid white; /* padrão: membro */
      }

      /* Quando for admin */
      .perfil-foto.admin {
        border-color: #FFD700;
        box-shadow: 0 0 10px rgba(255,215,0,.7);
      }
      .perfil-foto:hover {
        transform:scale(1.08);
      }
      .perfil-foto.admin:hover {
        transform:scale(1.08);
      }


      /* ===== RESET / BASE ===== */
      *{margin:0;padding:0;box-sizing:border-box}
      :root{
        --azul:#1e3a8a;
        --azul-escuro:#0f2a5a;
        --amarelo:#ffcc00;
        --cinza-bg:#f7f9fc;
        --cinza-card:#ffffff;
        --texto:#333;
        --borda:rgba(255,255,255,.15);
      }
      html,body{height:100%}
      body{
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--cinza-bg);
        color: var(--texto);
        line-height: 1.6;
        overflow-x: hidden;
      }
      .container{max-width:1200px;margin:0 auto;padding:0 16px}

      /* ===== HEADER FIXO ===== */
      .site-header{
        position:sticky; top:0; z-index:1000;
        background: var(--azul);
        box-shadow: 0 6px 18px rgba(0,0,0,.12);
      }
      .header-inner{
        display:flex; 
        align-items:center; 
        justify-content:space-between;
      }
      .brand{display:flex; align-items:center; gap:12px}
      .brand img{height:120px; width:auto; display:block}
      .nav{
        display:flex; align-items:center; gap:8px;
      }
      .nav a{
        color:#fff; text-decoration:none; font-weight:600;
        padding:10px 16px; border:1px solid var(--borda);
        border-radius:9999px; transition:.25s;
      }
      .nav a:hover{background:#fff;color:var(--azul); transform:translateY(-2px)}
      .menu-toggle{
        display:none; background:transparent; border:1px solid var(--borda);
        color:#fff; padding:10px 14px; border-radius:10px; cursor:pointer;
        font-weight:600;
      }

      /* ===== HERO / BANNER ===== */
      .banner{
        position:relative; overflow:hidden;
        background: url('https://picsum.photos/1600/550?blur=1') center/cover no-repeat;
        color:#fff;
      }
      .banner::after{
        content:""; position:absolute; inset:0;
        background: linear-gradient(180deg, rgba(30,58,138,.65), rgba(15,42,90,.8));
      }
      .banner .content{
        position:relative; z-index:1; text-align:center;
        padding: 80px 16px 90px;
        animation: fadeIn .9s ease both;
      }
      .banner h1{font-size: clamp(26px, 4vw, 42px); margin-bottom:10px}
      .banner p{font-size: clamp(15px, 2.4vw, 20px); opacity:.95}
      .banner .cta{
        margin-top:24px; display:inline-block; background:var(--amarelo); color:#000;
        border:none; padding:12px 26px; border-radius:30px; font-weight:700; cursor:pointer; transition:.25s;
        text-decoration:none;
      }
      .banner .cta:hover{filter:brightness(.95); transform: translateY(-2px)}

      /* ===== CARDS ===== */
      .cards{display:flex; justify-content:center; gap:20px; flex-wrap:wrap; padding: 40px 16px}
      .card{
        background: var(--cinza-card); width: 330px; padding:22px; border-radius:14px;
        box-shadow: 0 10px 24px rgba(0,0,0,.08); text-align:center;
        transform: translateY(40px); opacity:0;
      }
      .card h3{color:var(--azul); margin-bottom:8px}
      .card:hover{transform: translateY(-6px) !important; transition:.3s}

      /* ===== GALERIA ===== */
      .galeria{padding: 14px 16px 40px}
      .galeria h2{color:var(--azul); text-align:center; margin:12px 0 22px}
      .grid{
        display:grid; gap:16px; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      }
      .grid img{
        width:100%; height:220px; object-fit:cover; border-radius:12px;
        box-shadow: 0 6px 16px rgba(0,0,0,.12); transition:.3s;
      }
      .grid img:hover{transform: scale(1.03)}

      /* ===== MISSAO / VISAO ===== */
      .missao{
        background:#eef3f9; padding: 40px 0;
      }
      .missao .wrap{
        display:flex; gap:20px; flex-wrap:wrap; justify-content:center;
      }
      .bloco{
        background:#fff; width:360px; padding:22px; border-radius:14px;
        box-shadow: 0 12px 26px rgba(0,0,0,.08);
        transform: translateY(40px); opacity:0;
      }
      .bloco h3{color:var(--azul); margin-bottom:8px}

      /* ===== RODAPÉ ===== */
      footer{
        background: var(--azul); color:#fff; text-align:center; padding: 20px 12px; margin-top: 30px;
      }
      footer a{color:var(--amarelo); text-decoration:none}
      footer a:hover{text-decoration:underline}

      /* ===== SCROLL REVEAL ===== */
      .show{opacity:1 !important; transform: translateY(0) !important; transition: all .8s ease}

      /* ===== RESPONSIVO ===== */
      @media (max-width: 900px){
        .nav{display:none; position:absolute; left:0; right:0; top:72px; background:var(--azul); padding:10px 0; flex-direction:column}
        .site-header.open .nav{display:flex}
        .menu-toggle{display:block}
        .brand img{height:48px}
      }

      @keyframes fadeIn {from{opacity:0; transform:translateY(10px)} to{opacity:1; transform:none}}
    </style>
  </head>
  <body>
    <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="../menu.php" aria-label="Página inicial">
        <img src="../imagens/logo.png" alt="Logo da Igreja">
      </a>

      <button class="menu-toggle" aria-label="Abrir menu" aria-expanded="false">Menu</button>

      <nav class="nav" id="mainNav" aria-label="Navegação principal">
        <a href="../Eventos/eventos.php">Eventos</a>
        <a href="../sobrenos/sobrenos.php">Sobre Nós</a>
        <a href="../Ministerios/Multimidia/Escala.php">Ministérios</a>
      </nav>

      <div class="perfil">
        <a href="../perfil.php">
          <img class="perfil-foto <?php echo ($user['role'] === 'admin') ? 'admin' : ''; ?>"
          src="../uploads/<?php echo $user['foto'] ?? 'default.png'; ?>" 
           alt="Foto de Perfil">
          </a>
      </div>
    </div>
  </header>
  </body>
</html>