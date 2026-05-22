<?php
    session_start();
    if(!isset($_SESSION["logado"]) || $_SESSION["logado"] !== true || empty($_SESSION["username"])){
        header("Location: /EletroTech/Views/view-login/loginView.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../../Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
    <link rel="stylesheet" href="/EletroTech/Styles/styleComp.css">
</head>
<body>
       <div class="navbar-menu">

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../view-menu/menuView.php"><img class="eletrotech" src="../imgs/Captura de Tela 2026-05-18 às 14.00.40.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" style="color:#ffd500;" aria-current="page" href="../view-users/users.php">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:#ffd500;" href="../view-eletricista/eletricistaView.php">Eletricistas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:#ffd500;" href="../view-produtos/produtosView.php">Produtos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:#ffd500;" href="../view-metas/metasView.php">Metas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color:#ffd500;" href="../view-ordens-servico/ordensServicoView.php">Ordens de serviço</a>
                    </li>
                    <div class="logout">
                        <button class="btn btn-outline-danger" type="submit">Sair</button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
      



    <main>
        <div class="buttons-users">
            <button onclick="showCreate()">
                Novo produto
            </button>
            <button onclick="showTableZerados()">
                Listar Produtos com estoque zerado
            </button>

        </div>



        <?php 
            require __DIR__ ."/../../banco/bdEletro.php";
            require __DIR__ ."/../../Biblioteca-PHP/FunctionsGPM/models/FunctionsMsg.php";
        ?>

        <!-- Tabela de produtos com estoque zerado -->
        <div class="table-users" id="table-prodzerados" style="display:none;">
            <table>
                <tr>
                    <th>Nome produto</th>
                    <th>Valor unitário(R$)</th>
                    <th>Quantidade em estoque</th>
                    <th>Ações</th>
                </tr>
                    <?php
                        $busca = $banco->query("SELECT id_prod, nome, vlr_unitario, qtd_estoque from tabela_produtos");
                        if(!$busca){
                        msgError("Não foi possível exibir os produtos: " . $banco->error);
                        } else 
                            if($busca->num_rows == 0){
                            msgWarning("Não há produtos cadastrados!");
                            } else{
                                function formatarValor($valor) {
                                    return number_format($valor, 2, ',', '.');
                                }
                            }
                            while($reg = $busca->fetch_object()){   
                                // Se estoque for igual a 0 adiciona o produto à tabela
                                if($reg->qtd_estoque == 0){
                                    echo "<tr>
                                        <td>$reg->nome</td>
                                        <td>R$ " . formatarValor($reg->vlr_unitario) . "</td>
                                        <td>$reg->qtd_estoque</td>
                                        <td>
                                            <button class='button-act-edit' onclick='showEdit($reg->id_prod, \"$reg->nome\", \"$reg->vlr_unitario\", \"$reg->qtd_estoque\")'>
                                                <img class='icon' src='icons/edit_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg' alt='editar'>
                                            </button>
                                        </td>
                                    </tr>";
                                }
                        }
                        
                    
                    ?>
            </table>
        </div>            

        <!-- Tabela de produtos -->
        <div class="table-users" id="table-prod">
            <table>
                <tr>
                    <th>Nome produto</th>
                    <th>Valor unitário(R$)</th>
                    <th>Quantidade em estoque</th>
                    <th>Ações</th>
                    
                </tr>
                    <?php 

                        $busca = $banco->query("SELECT id_prod, nome, vlr_unitario, qtd_estoque from tabela_produtos");
                        if(!$busca){
                            msgError("Não foi possível exibir os produtos: " . $banco->error);
                        } else 
                            if($busca->num_rows == 0){
                                msgWarning("Não há produtos cadastrados!");
                            } else{
                                while($reg = $busca->fetch_object()){   
                        

                                // Se estoque for 0 ou menor, pula para o próximo produto
                                if($reg->qtd_estoque <= 0){
                                    continue;
                                }
                                echo "<tr>
                                        <td>$reg->nome</td>
                                        <td>R$ " . formatarValor($reg->vlr_unitario) . "</td>
                                        <td>$reg->qtd_estoque</td>
                                        <td>
                                            <button class='button-act-edit' onclick='showEdit($reg->id_prod, \"$reg->nome\", \"$reg->vlr_unitario\", \"$reg->qtd_estoque\")'>
                                                <img class='icon' src='icons/edit_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg' alt='editar'>
                                            </button>

                                            <button onclick='zerarEstoque($reg->id_prod)' class='button-act-exc'>
                                                Zerar
                                            </button>
                                        </td>
                                    </tr>";
                                }
                        }
                    ?>
                    
                <tr>
                </tr>
            </table>
        </div>

        <!-- Modal de confirmação para zerar estoque -->
        <div id="confirmModal" class="modal" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-content">
                <span class="close-btn" onclick="closeConfirmModal()">&times;</span>
                <h2>Confirmar ação</h2>
                <p>Tem certeza que deseja zerar o estoque deste produto?</p>
                <div class="div-buttons-zerar">
                    <form action="/EletroTech/Services/produtosZerarService.php" method="POST">
                        <input type="hidden" name="id_prod" id="id_prod_zerar" value="">
                        <button type="submit" class="btn btn-danger" id="confirmZerarBtn">Zerar estoque</button>
                    </form>
                    <button class="btn btn-secondary" onclick="closeBtn()">Cancelar</button>
                </div>
            </div>
        </div>

        <!-- Form de edição -->
        <div id="myModal" class="modal" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-content">
                <span class="close-btn" onclick="closeBtn()">&times;</span>
                <h2>Editar produto</h2>
                <form action="/EletroTech/Services/produtosEditService.php" method="POST" class="cadastro">

                    <input type="hidden" name="id_prod" id="id_prod" value="">

                    <div class="nome-cad-produto">
                        <label for="nome">Nome do produto:</label>
                        <input type="text" placeholder="Digite o nome do produto:" id="nomeEdit" name="nome" value="" required>
                    </div>
                    <div class="valor-cad-produto">
                        <label for="valor">Valor unitário do produto(R$):</label>
                        <input type="text" placeholder="Digite o valor unitário do produto:" id="valorEdit" name="valor" value="" required>
                    </div>
                    <div class="estoque-cad-produto">
                        <label for="estoque">Quantidade em estoque:</label>
                        <input type="text" placeholder="Digite a quantidade em estoque:" id="estoqueEdit" name="estoque" value="" required>
                    </div>
                    <input type="submit" class="enter" value="Salvar"></input>
                </form>
            </div>
        </div>   

        <!-- Form de cadastro -->
        <div class="create-users" id="create-prod" style="display: none;">
            <form action="/EletroTech/Services/produtosCadService.php" class="cadastro-users" method="POST">

            <div class="nome-cad-produto">
                <label for="nome">Nome do produto:</label>
                <input type="text" placeholder="Digite o nome do produto:" name="name">
            </div>
            <div class="valor-cad-produto">
                <label for="valor">Valor unitário do produto:</label>
                <input type="text" placeholder="Digite o valor unitário do produto:" name="valor" value="">
            </div>
            <div class="estoque-cad-produto">
                <label for="estoque">Quantidade em estoque:</label>
                <input type="text" placeholder="Digite a quantidade em estoque" name="estoque" value="">
            </div>
            <input class="btn-users" type="submit" value="Cadastrar">
        </form>
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>

    const telaEdit = document.getElementById("myModal");
    const telaCad = document.getElementById("create-prod");
    const telaConfirm = document.getElementById("confirmModal");
    const telaTableZerados = document.getElementById("table-prodzerados");
    const telaTable = document.getElementById("table-prod");

    function closeConfirmModal(){
        telaConfirm.style.display = "none";
        telaTable.style.display = "block";
    }

    // Função para mostrar o modal de confirmação para zerar estoque
    function zerarEstoque(id_prod){
        if(telaConfirm.style.display == "none" || telaConfirm.style.display == ""){
            telaConfirm.style.display = "block";
            telaTable.style.display = "none";
            telaCad.style.display = "none";
        }else{
            telaConfirm.style.display = "none";
            telaTable.style.display = "none";
            telaCad.style.display = "none";
    }
        document.getElementById("id_prod_zerar").value = id_prod;

    }
    
    // Função para mostrar o modal de edição e preencher os campos com os dados do produto
    function showEdit(id, nome, valor, estoque){
        if(telaEdit.style.display == "none" ||telaEdit.style.display == ""){
            telaEdit.style.display = "block";
            telaTable.style.display = "none";
            telaCad.style.display = "none";
        }else{
            telaEdit.style.display = "none";
            telaTable.style.display = "none";
            telaCad.style.display = "none";
        }

        document.getElementById("id_prod").value = id;
        document.getElementById("nomeEdit").value = nome;
        document.getElementById("valorEdit").value = valor; 
        document.getElementById("estoqueEdit").value = estoque;

    }

    // Função para fechar os modais e mostrar a tabela
    function closeBtn(){
        telaEdit.style.display = "none";
        telaTable.style.display = "block";
        telaCad.style.display = "none";
        telaConfirm.style.display = "none";

    }

    // Função para mostrar o formulário de criação de produto e esconder a tabela
    function showCreate(){
        if(telaCad.style.display == "none" ||telaCad.style.display == ""){
            telaCad.style.display = "block";
            telaTable.style.display = "none";
        }else{
            telaCad.style.display = "none";
            telaTable.style.display = "block";
        }
    }

    // Função para mostrar a tabela de produtos com estoque zerado 
    function showTableZerados(){
            if(telaTableZerados.style.display == "none" ||telaTableZerados.style.display == ""){
            telaTableZerados.style.display = "block";
            telaTable.style.display = "none";
            telaCad.style.display = "none";
        }else{
            telaTableZerados.style.display = "none";
            telaCad.style.display = "none";
            telaTable.style.display = "block";
        }
    }
    
    // Clicar fora da tela fechar o modal
    window.onclick = function(event) {
        if (event.target === telaEdit) {
            telaEdit.style.display = "none";
            telaTable.style.display = "block";
        }
        if(event.target === telaConfirm){
            telaConfirm.style.display = "none";
            telaTable.style.display = "block";
        }   
    }
    </script>

</body>
</html>