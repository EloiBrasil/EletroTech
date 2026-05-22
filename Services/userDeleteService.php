<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Cadastrada</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>

<?php 
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";

    if(!isset($_POST["id_user"])){
        msgWarning("ID do usuário não fornecido!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-users/usersView.php'>Voltar</a>";
    } else {
        $id_user = $_POST["id_user"];

        $q = "DELETE FROM tabela_usuarios WHERE id_user = $id_user";
        if($banco->query($q)){
            msgSuccess("Usuário excluído com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-users/users.php'> Clique aqui para voltar.</a>");
        } else {
            msgError("Erro ao excluir usuário: " . $banco->error);
        }
    }




?>