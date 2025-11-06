// Funcionalidade simples: abrir/fechar modal de imagem
function openModal(src){
const modal = document.getElementById('modalImg');
const img = document.getElementById('modalContent');
img.src = src;
modal.style.display = 'flex';
}
function closeModal(){
const modal = document.getElementById('modalImg');
const img = document.getElementById('modalContent');
modal.style.display = 'none';
img.src = '';
}


// Smooth scroll para links internos (se desejar)
document.addEventListener('click', function(e){
const a = e.target.closest('a[href^="#"]');
if(!a) return;
e.preventDefault();
const id = a.getAttribute('href');
const el = document.querySelector(id);
if(el) el.scrollIntoView({behavior:'smooth',block:'start'});
});