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
    <title>Usuarios</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
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
                        <a href="/../EletroTech/logout.php" class="btn btn-outline-danger" type="submit">Sair</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <?php
        require_once __DIR__. "/../../banco/bdEletro.php";
        include __DIR__. "/../../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMSG.php";
    ?> 

    <main>
        <div class="buttons-users">
            <button onclick="showCreate()" >
                Criar novo usuário
            </button>
        </div>

        <div class="table-users" id="table-users">
            <table class="table-user">
                <tr class="tr-user">
                    <th class="th-user">Usuários</th>
                    <th class="th-user">Ações</th>
                </tr>
                <?php 
                    $busca = $banco->query("SELECT id_user ,usuario from tabela_usuarios");
                    if(!$busca){
                        msgError("Não foi possível exibir os usuarios");
                    } else 
                        if($busca->num_rows == 0){
                        msgWarning("Não há usuarios cadastrados!");
                    } else{
                        while($reg = $busca->fetch_object()){   
                            echo "
                                <tr class='tr-user'>
                                    <td class='td-user'>$reg->usuario</td>

                                    <td>
                                        <button onclick='showModal($reg->id_user, \"$reg->usuario\")' class='button-act-edit'>
                                            <img class='icon' src='icons/edit_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg' alt='editar'>
                                        </button>
                                        <button class='button-act-exc' onclick='showDeleteModal($reg->id_user)'>
                                            <img class='icon' src='icons/delete_24dp_E3E3E3_FILL0_wght400_GRAD0_opsz24.svg' alt='deletar'>
                                        </button>
                                    </td>
                                </tr>
                            ";
                        }
                    }
                
                ?>
            </table>
        </div>

        <!-- Modal de edição de usuário -->
            <div id="myModal" class="modal" style="display: none;">
                <div class="modal-dialog modal-dialog-centered modal-content">
                    <span class="close-btn" onclick="closeBtn()">&times;</span>
                    <h2>Editar usuário</h2>
                    <form action="/EletroTech/Services/userEditService.php" method="POST" class="cadastro">

                        <input type="hidden" name="id_user" id="id_user" value="">

                        <div class="usuario-cad">
                            <label for="username">Digite o seu novo usuario:</label>
                            <input type="text" placeholder="Digite seu nome:" id="username" name="username" value="" required>
                        </div>
                        <div class="senha-cad">
                            <label for="password">Digite a nova senha:</label>
                            <input type="password" placeholder="Digite a senha:" name="password">
                            <label for="passwordConfirm">Confirme sua nova senha:</label>
                            <input type="password" placeholder="Confirme sua senha:" name="passwordConfirm">
                        </div>
                        <input type="submit" class="enter" value="Salvar"></input>
                    </form>
                </div>
            </div>  

            <!-- Formulário de criação de usuário -->
            <div id="create-users" style="display: none;">
                <form action="/EletroTech/Services/cadastroService.php" method="POST" class="cadastro-users">
                    <h1>Criar Novo Usuário</h1>
                   <div class="usuario-cad">
                        <label for="username">Digite o usuario:</label>
                        <input type="text" placeholder="Digite seu nome:" id="username" name="username" value="" required>
                    </div>
                    <div class="senha-cad">
                        <label for="password">Digite a senha:</label>
                        <input type="password" placeholder="Digite a senha:" name="password">
                        <label for="passwordConfirm">Confirme sua senha:</label>
                        <input type="password" placeholder="Confirme sua senha:" name="passwordConfirm">
                    </div>
                    <input type="submit" class="btn-users" value="Criar Usuário"></input>
                </form>

            </div>

            <!-- Modal de delete de usuário -->
        <div id="delete-modal" class="modal" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-content">
                <h2>Confirmar deleção</h2>
                <p>Tem certeza que deseja deletar este usuário?</p>
                <div class="form-delete">
                <form action="/EletroTech/Services/userDeleteService.php" method="POST">
                    <input type="hidden" name="id_user" id="id_userDel" value="">
                    <button type="submit" id="confirm-delete" class="btn btn-danger">Sim, deletar</button><br>
                </form>
                    <button id="cancel-delete" onclick="hideDeleteModal()" class="btn btn-secondary">Cancelar</button>
                </div>
            </div>
        </div>
    </main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>



    let telaTable = document.getElementById("table-users");
    let telaCad = document.getElementById("create-users");
    
    // Função para mostrar o formulário de criação de usuário e esconder a tabela
    function showCreate(){
        if(telaCad.style.display == "none" ||telaCad.style.display == ""){
            telaCad.style.display = "block";
            telaTable.style.display = "none";
        }else{
            telaCad.style.display = "none";
            telaTable.style.display = "block";
        }
    }

    const deleteModal = document.getElementById("delete-modal");
    const modal = document.getElementById("myModal");
    const openBtn = document.getElementById("openModalBtn");


    //Abre o modal ao clicar no botão
    function showModal(id, user){
        modal.style.display = "block";
        document.getElementById("id_user").value = id;
        document.getElementById("username").value = user;
    }

    // Função para mostrar o modal de delete
    function showDeleteModal(id){
        if(deleteModal.style.display == "none" || deleteModal.style.display == ""){
            deleteModal.style.display = "block";
        }
        document.getElementById("id_userDel").value = id;
   }

   // Função para fechar o modal de delete
    function hideDeleteModal(){
        deleteModal.style.display = "none";
   }

    function closeBtn(){
        modal.style.display = "none";
        telaTable.style.display = "block";
        telaCad.style.display = "none";
    }

    // Fecha o modal ao clicar em qualquer lugar fora da caixa de conteúdo
    window.onclick = function(event) {
        if (event.target.contains(modal)) {
            modal.style.display = "none";
        }
        if(event.target.contains(deleteModal)){
            deleteModal.style.display = "none";
        }   
    }
</script>
</body>
</html>