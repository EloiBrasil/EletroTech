<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto Cadastrado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>

<?php
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";

    if(!isset($_POST["name"], $_POST["valor"], $_POST["estoque"])){
    msgWarning("Preencha todos os campos!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'>Voltar</a>";
    }
    if(!is_numeric($_POST["valor"])){
    msgWarning("O valor inválido!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'>Voltar</a>";
    } else if($_POST["estoque"] < 0){
    msgWarning("A quantidade em estoque não pode ser negativa!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'>Voltar</a>";
    } else {
    $name = $_POST["name"];
    $valor = $_POST["valor"];
    $estoque = $_POST["estoque"];

    $q = "INSERT INTO tabela_produtos (nome, vlr_unitario, qtd_estoque) VALUES ('$name', '$valor', '$estoque')";
    if($banco->query($q)){
        msgSuccess("Produto cadastrado com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'> Clique aqui para voltar.</a>");
    } else {
        msgError("Erro ao cadastrar produto: " . $banco->error);
    }
    }
?>
