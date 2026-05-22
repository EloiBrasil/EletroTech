<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/EletroTech/Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
    <link rel="stylesheet" href="/EletroTech/Views/view-login/style.css">
</head>
<body>

<main class="login-eletro"> 
    <img src="../imgs/Captura de Tela 2026-05-18 às 14.00.40.png" alt="">
    <section class="floating">
        <h1>Faça o seu login</h1>
        <form action="/EletroTech/Services/loginService.php" method="POST" class="floating-form">
            <div class="user">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" placeholder="Digite seu usuario" required>
            </div>
            <div class="pass">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
            </div>    
            <input type="submit" class="enter"></input>
        </form>

        <a href="/EletroTech/Views/view-login/CadastroView.php">Crie uma conta</a>
    </section>
</main>
</body> 
</html>