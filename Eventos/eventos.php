<?php
require_once 'config.php';

session_start();


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// captura de dados
$usuarioId = $_SESSION['usuario_id'];
$tipoUsuario = $_SESSION['tipo'] ?? 'usuario'; 
$isAdmin = ($tipoUsuario === 'admin');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //validação
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erro de validação CSRF: Ação bloqueada.");
    }


    if ($isAdmin && isset($_POST['acao'])) {
        $acao = $_POST['acao'];
        $stmt = null; 

        try {
            switch ($acao) {
                case "adicionar":
                    $sql = "INSERT INTO eventos (titulo, descricao, data_evento) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $_POST['titulo'], $_POST['descricao'], $_POST['data_evento']);
                    $_SESSION['success_message'] = "Evento adicionado com sucesso!";
                    break;

                case "editar":
                    $sql = "UPDATE eventos SET titulo=?, descricao=?, data_evento=? WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssi", $_POST['titulo'], $_POST['descricao'], $_POST['data_evento'], $_POST['id']);
                    $_SESSION['success_message'] = "Evento atualizado com sucesso!";
                    break;

                case "excluir":
                    $sql = "DELETE FROM eventos WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $_POST['id']);
                    $_SESSION['success_message'] = "Evento excluído com sucesso!";
                    break;
            }

            // Executa o statement se ele foi preparado
            if ($stmt) {
                if (!$stmt->execute()) {
                    // Se houver um erro, armazena a mensagem de erro
                    $_SESSION['error_message'] = "Erro ao executar a operação: " . $stmt->error;
                    unset($_SESSION['success_message']); // Remove a mensagem de sucesso
                }
                $stmt->close();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Ocorreu um erro: " . $e->getMessage();
            unset($_SESSION['success_message']);
        }

        // Redireciona para a mesma página para evitar reenvio de formulário (Padrão PRG)
        header("Location: eventos.php");
        exit();
    }
}

// --- Busca de Dados para Exibição ---
$result = $conn->query("SELECT * FROM eventos ORDER BY data_evento DESC");
$eventos = $result->fetch_all(MYSQLI_ASSOC);

// Fecha a conexão com o banco de dados
$conn->close();

// --- Exibição ---
// Ao final de toda a lógica, inclui o arquivo de visualização
require_once 'eventos_view.php';