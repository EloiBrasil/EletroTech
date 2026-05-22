<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eletricista Editado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>

<?php 
require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
require __DIR__. "/../banco/bdEletro.php";
require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";
if(!isset($_POST["username"])){
    msgWarning("Preencha pelo menos o campo de usuário!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
} else{
    $usuario = $_POST["username"];
    $cpf = $_POST["cpf"];
    $contratacao = $_POST["contratacao"];

    if(empty($cpf) && empty($contratacao)){
        $q = "UPDATE tabela_eletricistas SET nome = '$usuario' WHERE id_eletri = " . $_POST["id_eletri"];
        if($banco->query($q)){
            msgSuccess("Eletricista atualizado com sucesso!");
            echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
        } else {
            msgError("Erro ao atualizar eletricista: " . $banco->error);
        }
        exit;
    } else if(empty($cpf)){
        $q = "UPDATE tabela_eletricistas SET nome = '$usuario', data_contratacao = '$contratacao' WHERE id_eletri = " . $_POST["id_eletri"];
        if($banco->query($q)){
            msgSuccess("Eletricista atualizado com sucesso!");
            echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
        } else {
            msgError("Erro ao atualizar eletricista: " . $banco->error);
        }
        exit;
    } else if(empty($contratacao)){
        $q = "UPDATE tabela_eletricistas SET nome = '$usuario', cpf = '$cpf' WHERE id_eletri = " . $_POST["id_eletri"];
        if($banco->query($q)){
            msgSuccess("Eletricista atualizado com sucesso!");
            echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
        } else {
            msgError("Erro ao atualizar eletricista: " . $banco->error);
        }
        exit;
    } else {
        $q = "UPDATE tabela_eletricistas SET nome = '$usuario', cpf = '$cpf', data_contratacao = '$contratacao' WHERE id_eletri = " . $_POST["id_eletri"];
        if($banco->query($q)){
            msgSuccess("Eletricista atualizado com sucesso!");
            echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
        } else {
            msgError("Erro ao atualizar eletricista: " . $banco->error);
        }
    }
}



?>