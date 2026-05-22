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


   if(!isset($_POST["eletricista"], $_POST["valor"], $_POST["mes"])){
        msgWarning("Preencha todos os campos!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'>Voltar</a>";
    } else {
        $eletricista = $_POST["eletricista"];
        $meta = $_POST["valor"];
        $mes = $_POST['mes']; // 2026-05
        $data_meta = $mes . "-01"; // 2026-05-01

        $q = "INSERT INTO tabela_metas (mes_meta, vlr_meta, eletricista_meta) VALUES ('$data_meta', '$meta', '$eletricista')";
        if($banco->query($q)){
            msgSuccess("Meta cadastrada com sucesso! \n <a class='back-link' href='/EletroTech/Views/view-metas/metasView.php'> Clique aqui para voltar.</a>");
        } else {
            msgError("Erro ao cadastrar meta: " . $banco->error);
        }
    }

?>