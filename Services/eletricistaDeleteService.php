<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eletricista Deletado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>

<?php 
require_once __DIR__ . "/../banco/bdEletro.php";
require_once __DIR__ . "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";
$id = $_POST["id_eletri"] = $_POST["id_eletri"] ?? null;

if(!$id){
    msgWarning("ID do eletricista não fornecido!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
    exit;
} 

$q = "UPDATE tabela_eletricistas SET data_demissao = CURDATE() WHERE id_eletri = $id";
if($banco->query($q)){

    msgSuccess("Eletricista demitido com sucesso!");
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";

} else {
    msgError("Erro ao demitir eletricista: " . $banco->error);
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
}

$query2 = "SELECT data_demissao FROM tabela_eletricistas WHERE id_eletri = $id";
$result = $banco->query($query2);


if($result && $result->num_rows > 0){
    $demissao = true;
    return $demissao;
}else if($result->num_rows === 0){
    $demissao = false;
    return $demissao;
}else {
    msgError("Erro ao verificar demissão: " . $banco->error);
    echo "<br><a class='back-link' href='/EletroTech/Views/view-eletricista/eletricistaView.php'>Voltar</a>";
    exit;
}

?>