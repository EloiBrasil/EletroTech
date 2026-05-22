<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Editada</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>


<?php 
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";


    if(!isset($_POST["valor"])){
        msgWarning("Preencha o campo de valor!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'>Voltar</a>";
    } else if(!is_numeric($_POST["valor"])){
        msgWarning("O valor da meta deve conter apenas números!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'>Voltar</a>";
    } else if($_POST["valor"] < 0){
        msgWarning("O valor da meta não pode ser negativo!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'>Voltar</a>";
    } else {
        $id_meta = $_POST["id_meta"];
        $novo_valor = $_POST["valor"];

        $q = "UPDATE tabela_metas SET vlr_meta = '$novo_valor' WHERE id_meta = $id_meta";
        if($banco->query($q)){
            msgSuccess("Meta atualizada com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'> Clique aqui para voltar.</a>");
        } else {
            msgError("Erro ao atualizar meta: " . $banco->error);
        }
        
}

?>