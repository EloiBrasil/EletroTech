<?php
require __DIR__. '/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php';
require __DIR__. '/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php';
require __DIR__. '/../banco/bdEletro.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EletroTech</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>EletroTech</h1>
            <p>Sistema de Login</p>
        </div>

        <div class="login-message">
            <div class="message-content">
<?php
// Verifica se foi enviado um POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo '<div class="message-icon">⚠️</div>';
        msgWarning("Username e senha são obrigatórios");
    } else {
        // Usar prepared statement para melhor segurança
        $stmt = $banco->prepare("SELECT usuario, senha FROM tabela_usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $busca = $stmt->get_result();

        if (!$busca) {
            echo '<div class="message-icon">❌</div>';
            msgError("Erro na busca: " . $banco->error);
        } else {
            $reg = $busca->fetch_object();
            if ($reg === null) {
                echo '<div class="message-icon">👤</div>';
                msgError("Usuário não encontrado!");
            } else if (hashTest($password, $reg->senha)) {
                // Criar session do usuário
                $_SESSION["logado"] = true;
                $_SESSION["username"] = $reg->usuario;
                echo '<div class="message-icon">✅</div>';
                msgSuccess("Logado com sucesso!");
?>
                <div class="message-action">
                    <a href="/EletroTech/Views/view-menu/menuView.php">Continuar para o Menu</a>
                </div>
<?php
            } else {
                echo '<div class="message-icon">🔐</div>';
                msgError("Senha incorreta!");
            }
        }
        $stmt->close();
    }
} else {
    // Se não for POST, redirecionar para o formulário de login
    header("Location: /EletroTech/Views/view-login/loginView.php");
    exit;
}
?>
            </div>
        </div>

        <div class="back-link">
            <a href="/EletroTech/Views/view-login/loginView.php">← Voltar para Login</a>
        </div>
    </div>
</body>
</html>