<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ativação de Eletricista</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">

</head>
</html>


<?php
    require __DIR__ ."/../banco/bdEletro.php";
    require __DIR__ ."/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";
    
    $id = $_POST["id_eletri"] = $_POST["id_eletri"] ?? null;

    $q = "UPDATE tabela_eletricistas SET data_demissao = NULL WHERE id_eletri = $id";
    if($banco->query($q)){
        msgSuccess("Eletricista reativado com sucesso!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";

    } else {
        msgError("Erro ao reativar eletricista: " . $banco->error);
        echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
    }


?>