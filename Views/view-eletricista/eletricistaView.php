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
    <title>Eletricistas</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/../../Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
    <link rel="stylesheet" href="/EletroTech/Styles/StyleComp.css">
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

<?php
    require __DIR__ ."/../../banco/bdEletro.php";
    include __DIR__ ."/../../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";
?>

    <main>
        <div class="buttons-users">
            <button onclick="showCreate()" >
                Novo Eletricista
            </button>
        </div>

        <div class="table-users" id="table-eletri" >
            <table>
                <tr>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Data de Contratação</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>

                <tr>
                <?php 
                    $busca = $banco->query("SELECT id_eletri ,cpf, nome, data_contratacao, data_demissao from tabela_eletricistas");
                    if(!$busca){
                        msgError("Não foi possível exibir os usuarios");
                    } else 
                        if($busca->num_rows == 0){
                        msgWarning("Não há usuarios cadastrados!");
                    } else{
                        while($reg = $busca->fetch_object()){   
                            // <!-- Verifica o status do eletricista com base na data de demissão -->
                            $status = $reg->data_demissao ? "Demitido" : "Ativo";

                            echo "<tr class='tr-user'>
                                    <td class='td-user'>$reg->cpf</td>
                                    <td>$reg->nome</td>
                                    <td>$reg->data_contratacao</td>
                                    <td id='status'>" . $status . "</td>
                                    <td>
                                        <button onclick='showModal($reg->id_eletri, \"$reg->nome\")' class='button-act-edit'>
                                            <img class='icon' src='icons/edit_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg' alt='editar'>
                                        </button>
                                        <button onclick='showDeleteModal($reg->id_eletri, \"$status\")' class='button-act-exc'>
                                            Demitir/Ativar
                                        </button>
                                    </td>
                                </tr>
                            ";  
                        }
                    }
                
                ?>
                </tr>
            </table>
        </div>

        <!-- Form de ativação -->
         <div id="ativacao-modal" class="modal" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-content">
                <span class="close-btn" onclick="closeBtn()" >&times;</span>
                <h2>Confirmar Ação</h2>
                <p>Tem certeza que deseja alterar o status de demissão deste eletricista?</p>
                <div class="form-delete">
                <form action="/EletroTech/Services/eletricistaAtivService.php" method="POST">
                    <input type="hidden" name="id_eletri" id="id_eletri_atv" value="">
                    <button type="submit" id="confirm-delete" class="btn btn-success">Sim, Confirmar</button>
                </form>
                    <button id="cancel-delete" class="btn btn-secondary " onclick="hideActivacaoModal()">Cancelar</button>
                </div>
            </div>
         </div>


        <!--Form de demissao -->
        <div id="delete-modal" class="modal" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-content">
                <h2>Confirmar Demissão</h2>
                <p>Tem certeza que deseja demitir este eletricista?</p>
                <div class="form-delete">
                <form action="/EletroTech/Services/eletricistaDeleteService.php" method="POST">
                    <input type="hidden" name="id_eletri" id="id_eletri_dem" value="">
                    <button type="submit" id="confirm-delete" class="btn btn-danger">Sim, Demitir</button>
                </form>
                    <button id="cancel-delete" onclick="hideDeleteModal()" class="btn btn-secondary">Cancelar</button>
                </div>
            </div>
        </div>


    <!-- Form de edição -->
        <div id="myModal" class="modal" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-content">
                <span class="close-btn"  >&times;</span>
                <h2>Editar usuário</h2>
                <form action="/EletroTech/Services/eletricistaEditService.php" method="POST" class="cadastro">

                    <input type="hidden" name="id_eletri" id="id_eletri" value="">

                    <div class="usuario-cad">
                        <label for="username">Digite o seu novo usuario:</label>
                        <input type="text" placeholder="Digite seu nome:" id="username" name="username" value="" required>
                    </div>
                    <div class="senha-cad">
                        <label for="cpf">Digite o novo CPF:</label>
                        <input type="number" placeholder="Digite o CPF:" name="cpf" >
                        <label for="contratacao">Confirme sua nova data de contratação:</label>
                        <input type="date" name="contratacao">
                    </div>
                    <input type="submit" class="enter" value="Salvar"></input>
                </form>
            </div>
        </div>      



        <!-- Form de criação -->
        <div class="create-users" id="create-eletri" style="display: none;">
            <form action="/EletroTech/Services/eletricistaCadService.php" class="cadastro-users" method="POST">
                <div class="usuario-cad-eletri">
                    <label for="CPF">CPF do eletricista:</label>
                    <input type="number" placeholder="Digite o CPF:" name="cpf" required>
                </div>
                <div class="nome-cad-eletri">
                    <label for="nome">Digite o nome do eletricista:</label>
                    <input type="text" placeholder="Digite o nome:" name="name" required>
                </div>
                <div class="contratacao-cad-eletri">
                    <label for="contratacao">Data de Contratação:</label>
                    <input type="date" name="contratacao">
                </div>
                <input class="btn-users" type="submit" value="Cadastrar">
            </form>
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    let telaTable = document.getElementById("table-eletri");
    let telaCad = document.getElementById("create-eletri");

    function showCreate(){
        if(telaCad.style.display == "none" || telaCad.style.display == ""){
            telaCad.style.display = "block";
            telaTable.style.display = "none";
        } else {
            telaCad.style.display = "none";
            telaTable.style.display = "block";
        }
    }

    const modal = document.getElementById("myModal");
    const ativacaoModal = document.getElementById("ativacao-modal");
    const deleteModal = document.getElementById("delete-modal");

    function closeAll(){
        modal.style.display  = "none";
        ativacaoModal.style.display = "none";
        deleteModal.style.display = "none";
        telaTable.style.display = "block";
        telaCad.style.display = "none";
    }

    function showModal(id, user){
        modal.style.display = "flex";
        document.getElementById("id_eletri").value  = id;
        document.getElementById("username").value   = user;
    }

    function showDeleteModal(id, status){
        if(status == "Ativo"){
            deleteModal.style.display = "flex";
            document.getElementById("id_eletri_dem").value = id;
        } else {
            ativacaoModal.style.display = "flex";
            document.getElementById("id_eletri_atv").value = id;
        }
    }

    function hideDeleteModal(){ closeAll(); }
    function hideActivacaoModal(){ closeAll(); }
    function closeBtn(){ closeAll(); }

    // Fecha ao clicar fora
    window.onclick = function(event){
        if(event.target === modal)         closeAll();
        if(event.target === deleteModal)   closeAll();
        if(event.target === ativacaoModal) closeAll();
    }

    // Fecha ao clicar no X (span .close-btn)
    document.querySelectorAll(".close-btn").forEach(function(btn){
        btn.onclick = function(){ closeAll(); }
    });
</script>

</body>
</html>