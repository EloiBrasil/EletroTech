<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto Editado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>

<?php
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";

    if(!is_numeric($_POST["valor"])){
    msgWarning("O valor inválido!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'>Voltar</a>";
    } else if($_POST["estoque"] < 0){
    msgWarning("A quantidade em estoque não pode ser negativa!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'>Voltar</a>";
    } else {
    $id_prod = $_POST["id_prod"];
    $name = $_POST["nome"];
    $valor = $_POST["valor"];
    $estoque = $_POST["estoque"];

    $q = "UPDATE tabela_produtos SET nome = '$name', vlr_unitario = '$valor', qtd_estoque = '$estoque' WHERE id_prod = $id_prod";
    if($banco->query($q)){
        msgSuccess("Produto atualizado com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'> Clique aqui para voltar.</a>");
    } else {
        msgError("Erro ao atualizar produto: " . $banco->error);
    }
    }

?>