<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de novo Usuario</title>
    <link rel="stylesheet" href="../Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="sec-cad">
        <img src="../imgs/Captura de Tela 2026-05-18 às 14.00.40.png" alt="">
        <h1>Cadastre-se</h1>
        <form action="/EletroTech/Services/cadastroService.php" method="POST" class="cadastro">
            <div class="usuario-cad">
                
                <label for="username">Digite o seu usuario:</label>
                <input type="text" placeholder="Digite seu nome:" name="username" required>
            </div>
            <div class="senha-cad">
                <label for="password">Digite a senha:</label>
                <input type="password" placeholder="Digite a senha:" name="password" required>
                <label for="passwordConfirm">Confirme sua senha:</label>
                <input type="password" placeholder="Confirme sua senha:" name="passwordConfirm" required>
            </div>
            <input type="submit" class="enter" value="Registrar"></input>
            <a href="/EletroTech/view-login/loginView.php">Voltar</a>
        </form>
    </section>
</body>
</html>