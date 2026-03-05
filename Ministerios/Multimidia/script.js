  // Tooltip simples para os botões
  document.querySelectorAll('.btn-remover').forEach(btn => {
    btn.setAttribute('title', 'Remover sua escala');
  });
  document.querySelectorAll('.btn-lixeira').forEach(btn => {
    btn.setAttribute('title', 'Remover escala inteira (admin)');
  });

  // Confirm mais elegante (modal simples)
  const modal = document.createElement('div');
  modal.innerHTML = `
    <div class="mini-modal__backdrop" role="dialog" aria-modal="true">
      <div class="mini-modal__box">
        <h3>Confirmar remoção</h3>
        <p>Tem certeza que deseja remover a escala inteira deste culto?</p>
        <div class="mini-modal__actions">
          <button class="mm-cancel">Cancelar</button>
          <button class="mm-ok">Remover</button>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
  modal.style.display = 'none';

  const style = document.createElement('style');
  style.textContent = `
    .mini-modal__backdrop{
      position: fixed; inset: 0;
      background: rgba(2, 12, 34, .55);
      display: grid; place-items: center;
      z-index: 9999;
      padding: 16px;
    }
    .mini-modal__box{
      width: min(440px, 100%);
      background: #fff;
      border: 1px solid rgba(191,219,254,.9);
      border-radius: 16px;
      box-shadow: 0 18px 50px rgba(2, 32, 71, .22);
      padding: 16px 16px 14px;
    }
    .mini-modal__box h3{
      margin: 0 0 6px;
      font-size: 18px;
      color: #0b2a66;
      letter-spacing: -0.01em;
    }
    .mini-modal__box p{
      margin: 0 0 14px;
      color: #334155;
      line-height: 1.35;
    }
    .mini-modal__actions{
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }
    .mini-modal__actions button{
      border-radius: 999px;
      padding: 10px 14px;
      border: 1px solid rgba(191,219,254,.9);
      background: linear-gradient(180deg, #ffffff, #f4f8ff);
      color: #0b2a66;
      font-weight: 800;
      cursor: pointer;
      transition: transform .12s ease, box-shadow .12s ease;
    }
    .mini-modal__actions button:hover{
      transform: translateY(-1px);
      box-shadow: 0 10px 18px rgba(2, 32, 71, .10);
    }
    .mini-modal__actions .mm-ok{
      background: linear-gradient(135deg, #1d4ed8, #3b82f6);
      color: white;
      border-color: rgba(29,78,216,.35);
    }
  `;
  document.head.appendChild(style);

  let pendingHref = null;

  document.querySelectorAll('.btn-lixeira').forEach(a => {
    a.addEventListener('click', (e) => {
      // você já tem onclick confirm no HTML; isso substitui com UX melhor
      e.preventDefault();
      pendingHref = a.getAttribute('href');
      modal.style.display = 'block';
    });
  });

  modal.addEventListener('click', (e) => {
    if (e.target.classList.contains('mm-cancel') || e.target.classList.contains('mini-modal__backdrop')) {
      modal.style.display = 'none';
      pendingHref = null;
    }
    if (e.target.classList.contains('mm-ok') && pendingHref) {
      window.location.href = pendingHref;
    }
  });

