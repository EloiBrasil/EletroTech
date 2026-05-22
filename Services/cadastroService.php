<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro!</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>
<body>
    <?php
        require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
        require __DIR__. "/../banco/bdEletro.php";
        require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";
        if(!isset($_POST["username"], $_POST["password"], $_POST["passwordConfirm"])){
            msgWarning("Preencha todos os campos!");
            echo "<br><a class='back-link' href='/EletroTech/Views/view-login/CadastroView.php'>Voltar</a>";
        } else if($_POST["password"] !== $_POST["passwordConfirm"]){
            msgWarning("As senhas não coincidem!");
            echo "<br><a class='back-link' href='/EletroTech/Views/view-login/CadastroView.php'>Voltar</a>";

        } else {
            $usuario = $_POST["username"];
            $password = $_POST["password"];
            $confirmPassword = $_POST["passwordConfirm"];

            $hashPassword = hashGenerate($password);

            $q = "INSERT INTO tabela_usuarios (usuario, senha) VALUES ('$usuario', '$hashPassword')";
            if($banco->query($q)){
                msgSuccess("Usuário cadastrado com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-login/loginView.php'> Clique aqui para fazer login.</a>");
            } else {
                msgError("Erro ao cadastrar usuário: " . $banco->error);
            }
        }
    
    ?>
</body>
</html>