<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Excluída</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>

<?php 
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";



    if(!isset($_POST["id_meta"])){
        msgWarning("ID da meta não fornecido!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'>Voltar</a>";
    } else {
        $id_meta = $_POST["id_meta"];

        $q = "DELETE FROM tabela_metas WHERE id_meta = $id_meta";
        if($banco->query($q)){
            msgSuccess("Meta excluída com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'> Clique aqui para voltar.</a>");
        } else {
            msgError("Erro ao excluir meta: " . $banco->error);
        }
    }
?>