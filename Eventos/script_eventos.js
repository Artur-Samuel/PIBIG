document.addEventListener('DOMContentLoaded', () => {

    const formEditar = document.getElementById('formEditar');
    const formAdicionar = document.getElementById('formAdicionar');
    
    // --- Lógica para o botão EDITAR ---
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', (event) => {
            const data = event.currentTarget.dataset;
            
            // Preenche o formulário de edição com os dados do evento
            formEditar.querySelector('#editId').value = data.id;
            formEditar.querySelector('#editTitulo').value = data.titulo;
            formEditar.querySelector('#editDescricao').value = data.descricao;
            formEditar.querySelector('#editData').value = data.data;

            // Mostra o formulário de edição e esconde o de adição
            formEditar.style.display = 'block';
            if (formAdicionar) {
                formAdicionar.style.display = 'none';
            }
            
            // Rola a página até o formulário
            formEditar.scrollIntoView({ behavior: 'smooth' });
        });
    });

    // --- Lógica para CANCELAR a edição ---
    const btnCancelar = document.getElementById('btnCancelarEdicao');
    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            formEditar.style.display = 'none';
            if (formAdicionar) {
                formAdicionar.style.display = 'block';
            }
        });
    }

    // --- Lógica para CONFIRMAR a exclusão ---
    document.querySelectorAll('.form-excluir').forEach(form => {
        form.addEventListener('submit', (event) => {
            // Pede confirmação ao usuário antes de enviar o formulário
            if (!confirm('Tem certeza de que deseja excluir este evento? Esta ação não pode ser desfeita.')) {
                event.preventDefault(); // Cancela o envio do formulário
            }
        });
    });
});