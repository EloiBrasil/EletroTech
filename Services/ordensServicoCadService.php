<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordem/Serviço Cadastrado</title>
    <link rel="stylesheet" href="/EletroTech/Styles/styleUser.css">
</head>

</html>

<?php 
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Auth/FunctionsSession.php";
    require __DIR__. "/../banco/bdEletro.php";
    require __DIR__. "/../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";

    if(
    empty($_POST["eletricista_os"]) ||
    empty($_POST["data_os"]) ||
    empty($_POST["produtos"]) ||
    empty($_POST["qtd"])
) {
        msgWarning("Preencha todos os campos obrigatórios!");
        echo "<br><a class='back-link' href='/EletroTech/Views/view-ordens-servico/ordensServicoView.php'>Voltar</a>";
    } else {
        $eletricista = $_POST["eletricista_os"];
        $data_os = $_POST["data_os"];
        $produtos = (array) $_POST["produtos"];
        $quantidades = (array) $_POST["qtd"];

        // 1. Insere a OS principal
        $q = "INSERT INTO tabela_ordens_servico (eletricista_os, data_os) VALUES ('$eletricista', '$data_os')";
        if($banco->query($q)){
            $id_os = $banco->insert_id;

            // 2. Insere cada material na tabela intermediária
            $erro = false;
            for($i = 0; $i < count($produtos); $i++){
                $id_prod = $produtos[$i];
                $qtd     = $quantidades[$i];

                $qMat = "INSERT INTO tabela_os_materiais (id_os, id_produto, qtd_utilizada) VALUES ('$id_os', '$id_prod', '$qtd')";
                if(!$banco->query($qMat)){
                    $erro = true;
                    msgError("Erro ao inserir material: " . $banco->error);
                    break;
                }
                // Subtrai a quantidade utilizada do estoque
                $qEstoque = "UPDATE tabela_produtos SET qtd_estoque = qtd_estoque - '$qtd' WHERE id_prod = '$id_prod'";
                if(!$banco->query($qEstoque)){
                    $erro = true;
                    msgError("Erro ao atualizar estoque: " . $banco->error);
                    break;
                }
            }

            

            if(!$erro){
                msgSuccess("Ordem de serviço cadastrada com sucesso! <br><a class='back-link' href='/EletroTech/Views/view-ordens-servico/ordensServicoView.php'>Clique aqui para voltar.</a>");
            }

        } else {
            msgError("Erro ao cadastrar ordem de serviço: " . $banco->error);
        }
    }
?>