<!doctype html>
<html lang="pt-BR">
<link rel="stylesheet" href="sobrenos.css">
<link rel="icon" type="image/png" href="../imagens/icon.png">
</head>
<body>



<header class="hero">
    <?php session_start(); include "../Header.php"; ?>
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
Fundada em 22 de dezembro de <strong>1967</strong>, a Primeira Igreja Batista em Iguaçu nasceu com o propósito de
proclamar o evangelho e cultivar uma comunidade que cuida mutuamente. Ao longo das décadas,
crescemos em fé e serviço, mantendo o compromisso de estar presente na vida da nossa cidade.
</p>
<div class="timeline">
<div class="item"><strong>1967</strong><span> Fundação e primeiros cultos</span></div>
<div class="item"><strong>1989</strong><span> Ampliação do templo e lançamento de ministérios</span></div>
<div class="item"><strong>1999</strong><span> Iniciativas sociais e projetos comunitários</span></div>
<div class="item"><strong>2024</strong><span> Reforço no discipulado e presença digital</span></div>
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

<section id="cta" class="card-section cta">
<h3>Quer conhecer a igreja pessoalmente?</h3>
<p>Venha a um de nossos cultos ou entre em contato — será um prazer recebê-lo.</p>
<div class="cta-actions">
<a class="btn-primary" href="https://www.instagram.com/pib.iguacu">Entre em contato</a>
<a class="btn-primary" href="../Eventos/eventos.php">Ver Eventos</a>
</div>
</section>

</main>

<!-- Modal de imagem -->
<div id="modalImg" class="modal" onclick="closeModal()">
<img id="modalContent" src="" alt="">
</div>

<?php include '../rodape.html' ?>

<script src="sobrenos.js"></script>
</body>
</html>