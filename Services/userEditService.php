<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario Editado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>
<?php 
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";
    
    if(!isset($_POST["username"])){
        msgWarning("Preencha pelo menos o campo de usuário!");
        echo "<br><a class='back-link' href='EletroTech/Views/view-users/users.php'>Voltar</a>";
    } else if($_POST["password"] !== $_POST["passwordConfirm"]){
        msgWarning("As senhas não coincidem!");
        echo "<br><a class='back-link' href='EletroTech/Views/view-users/users.php'>Voltar</a>";

    } else {
        $usuario = $_POST["username"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["passwordConfirm"];

        if(empty($password) && empty($confirmPassword)){
            // Se as senhas estiverem vazias, não atualiza a senha
            $q = "UPDATE tabela_usuarios SET usuario = '$usuario' WHERE id_user = " . $_POST["id_user"];
            if($banco->query($q)){
                msgSuccess("Usuário atualizado com sucesso!");
            } else {
                msgError("Erro ao atualizar usuário: " . $banco->error);
            }
            exit;
        }else{

            $hashPassword = hashGenerate($password);
            $q = "UPDATE tabela_usuarios SET usuario = '$usuario', senha = '$hashPassword' WHERE id_user = " . $_POST["id_user"];

            if($banco->query($q)){
                msgSuccess("Usuário atualizado com sucesso!");
                echo "<br><a class='back-link' href='EletroTech/Views/view-users/users.php'>Voltar</a>";
            } else {
                msgError("Erro ao atualizar usuário: " . $banco->error);
            }
        }
    }


?>