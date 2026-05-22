<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eletricista Cadastrado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>
</html>

<?php 

require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
require __DIR__. "/../banco/bdEletro.php";
require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";

function VerificaCpf($cpf){
    require __DIR__. "/../banco/bdEletro.php";
    $query = "SELECT cpf FROM tabela_eletricistas";
    $result = $banco->query($query);
    $cpfs = [];
    if($result){
        while($row = $result->fetch_assoc()){
            $cpfs[] = $row["cpf"];
        }
    }
    return in_array($cpf, $cpfs);
}

if(!isset($_POST["cpf"], $_POST["name"], $_POST["contratacao"])){
    msgWarning("Preencha todos os campos!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
}
if(!is_numeric($_POST["cpf"])){
    msgWarning("O CPF deve conter apenas números!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
} else if(VerificaCpf($_POST["cpf"])){
    msgWarning("CPF já cadastrado!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
} else if(strtotime($_POST["contratacao"]) > time()){
    msgWarning("A data de contratação não pode ser no futuro!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
} else {
    $cpf = $_POST["cpf"];
    $name = $_POST["name"];
    $contratacao = $_POST["contratacao"];

    $q = "INSERT INTO tabela_eletricistas (cpf, nome, data_contratacao) VALUES ('$cpf', '$name', '$contratacao')";
    if($banco->query($q)){
        msgSuccess("Eletricista cadastrado com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'> Clique aqui para voltar.</a>");
    } else {
        msgError("Erro ao cadastrar eletricista: " . $banco->error);
    }
}




?>