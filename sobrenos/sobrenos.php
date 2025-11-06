<!doctype html>
<html lang="pt-BR">
<link rel="stylesheet" href="sobrenos.css">
</head>
<body>

<?php // include 'menu.php'; // descomente se tiver um header/menu compartilhado ?>

<header class="hero">
<div class="hero-inner">
<div class="logo-small">PIB Iguaçu</div>
<h1>Sobre a Primeira Igreja Batista em Iguaçu</h1>
<p class="tagline">Uma comunidade acolhedora, em movimento — comprometida em amar e servir.</p>
<a class="btn-primary" href="#visao-valores">Saiba mais</a>
</div>
</header>

<main class="container">

<section id="historia" class="card-section">
<h2>Nossa História</h2>
<p>
Fundada em <strong>19XX</strong>, a Primeira Igreja Batista em Iguaçu nasceu com o propósito de
proclamar o evangelho e cultivar uma comunidade que cuida mutuamente. Ao longo das décadas,
crescemos em fé e serviço, mantendo o compromisso de estar presente na vida da nossa cidade.
</p>
<div class="timeline">
<div class="item"><strong>19XX</strong><span>Fundação e primeiros cultos</span></div>
<div class="item"><strong>2005</strong><span>Ampliação do templo e lançamento de ministérios</span></div>
<div class="item"><strong>2018</strong><span>Iniciativas sociais e projetos comunitários</span></div>
<div class="item"><strong>2024</strong><span>Reforço no discipulado e presença digital</span></div>
</div>
</section>


<section id="visao-valores" class="card-section light">
<h2>Nossa Visão e Valores</h2>
<div class="grid-3">
<div class="feature">
<h3>Visão</h3>
<p>Ser uma igreja relevante, acolhedora e em movimento — multiplicando vidas transformadas pelo amor de Cristo.</p>
</div>
<div class="feature">
<h3>Valores</h3>
<ul>
<li>Fé: Cristo como fundamento.</li>
<li>Comunhão: Vida em comunidade.</li>
<li>Serviço: Amor em ação.</li>
<li>Integridade: Honra em tudo que fazemos.</li>
</ul>
</div>
<div class="feature">
<h3>O que nos move</h3>
<p>Discipulado intencional, cuidado pastoral e participação ativa na sociedade.</p>
</div>
</div>
</section>


<section id="lideranca" class="card-section">
<h2>Nossa Liderança</h2>
<p class="muted">Conheça quem guia nossa comunidade com responsabilidade e coração.</p>


<div class="grid-lideres">
<?php foreach ($lideranca as $pessoa): ?>
<article class="lider-card">
<div class="avatar"> <!-- troque por <img src="..."> quando tiver fotos reais -->
<span><?= strtoupper(substr($pessoa['nome'],0,1)) ?></span>
</div>
<div class="lider-info">
<h4><?= htmlspecialchars($pessoa['nome']) ?></h4>
<p class="cargo"><?= htmlspecialchars($pessoa['cargo']) ?></p>
<p class="bio"><?= htmlspecialchars($pessoa['bio']) ?></p>
</div>
</article>
<?php endforeach; ?>
</div>
</section>


<section id="comunidade" class="card-section light">
<h2>Nossa Comunidade</h2>
<p>Somos formados por famílias, jovens, crianças e idosos — cada um com uma história. Nossos ministérios de base
promovem grupos de estudo, eventos de integração e projetos sociais para apoiar a cidade.</p>


<div class="galeria" id="galeria">
<?php foreach ($galeria as $img): ?>
<div class="gal-item">
<img src="<?= $img ?>" alt="Evento" loading="lazy" onclick="openModal(this.src)">
</div>
<?php endforeach; ?>
</div>
</section>


<section id="cta" class="card-section cta">
<h3>Quer conhecer a igreja pessoalmente?</h3>
<p>Venha a um de nossos cultos ou entre em contato — será um prazer recebê-lo.</p>
<div class="cta-actions">
<a class="btn-outline" href="contato.php">Entre em contato</a>
<a class="btn-primary" href="visitar.php">Planejar visita</a>
</div>
</section>


</main>


<footer class="site-footer">
<div class="wrap">
<p>© <?= date('Y') ?> Primeira Igreja Batista em Iguaçu — Todos os direitos reservados.</p>
<nav class="footer-links">
<a href="/">Início</a>
<a href="Eventos/eventos.php">Eventos</a>
<a href="/ministerios.php">Ministérios</a>
</nav>
</div>
</footer>


<!-- Modal de imagem -->
<div id="modalImg" class="modal" onclick="closeModal()">
<img id="modalContent" src="" alt="">
</div>


<script src="sobrenos.js"></script>
</body>
</html>