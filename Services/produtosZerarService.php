<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produto Zerado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>


<?php
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php"; 

    if(!isset($_POST["id_prod"])){
        msgWarning("Produto não especificado!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'>Voltar</a>";
    } else {
        $id_prod = $_POST["id_prod"];
        $q = "UPDATE tabela_produtos SET qtd_estoque = 0 WHERE id_prod = $id_prod";
        if($banco->query($q)){
            msgSuccess("Estoque zerado com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-produtos/produtosView.php'> Clique aqui para voltar.</a>");
        } else {
            msgError("Erro ao zerar estoque: " . $banco->error);
        }
    }


?>