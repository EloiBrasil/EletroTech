<?php
    session_start();
    if(!isset($_SESSION["logado"]) || $_SESSION["logado"] !== true || empty($_SESSION["username"])){
        header("Location: /EletroTech/Views/view-login/loginView.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordens de Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/EletroTech/Styles/styleComp.css">
</head>
<body>
<?php
    require __DIR__ . "/../../banco/bdEletro.php";
    require __DIR__ . "/../../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMsg.php";
?>

<div class="navbar-menu">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../view-menu/menuView.php"><img class="eletrotech" src="../imgs/Captura de Tela 2026-05-18 às 14.00.40.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-users/users.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-eletricista/eletricistaView.php">Eletricistas</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-produtos/produtosView.php">Produtos</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-metas/metasView.php">Metas</a></li>
                    <li class="nav-item"><a class="nav-link active" style="color:#ffd500;" href="#">Ordens de serviço</a></li>
                </ul>
                <a href="/../EletroTech/logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
        </div>
    </nav>
</div>

<main class="mt-4">
    <div class="buttons-users">
        <button onclick="showCreate()">Nova OS</button>
    </div>

    <!-- Tabela de OS -->
    <div class="table-os mt-3" id="table-os">
        <table>
            <tr>
                <th>OS</th>
                <th>Eletricista</th>
                <th>Data da Operação</th>
                <th>Materiais Utilizados</th>
            </tr>
            <?php
                // Busca todas as OS agrupando materiais por OS
                $sqlOS = "SELECT id_ordServ, data_os, nome_eletricista FROM view_materiais_ord GROUP BY id_ordServ, data_os, nome_eletricista ORDER BY data_os DESC";
                $buscaOS = $banco->query($sqlOS);

                if(!$buscaOS){
                    msgError("Erro ao buscar ordens: " . $banco->error);
                } else if($buscaOS->num_rows == 0){
                    echo "<tr><td colspan='4'>Nenhuma ordem de serviço registrada.</td></tr>";
                } else {
                    while($os = $buscaOS->fetch_object()){
                        // Busca materiais dessa OS
                        $idOS = $os->id_ordServ;
                        $sqlMat = "SELECT nome_produto, qtd_utilizada FROM view_materiais_ord WHERE id_ordServ = '$idOS'";
                        $buscaMat = $banco->query($sqlMat);

                        $materiais = "";
                        while($mat = $buscaMat->fetch_object()){
                            $materiais .= "• {$mat->nome_produto} (qtd: {$mat->qtd_utilizada})<br>";
                        }

                        $dataFormatada = date('d/m/Y', strtotime($os->data_os));

                        echo "<tr>
                            <td>#$idOS</td>
                            <td>{$os->nome_eletricista}</td>
                            <td>$dataFormatada</td>
                            <td style='text-align:left; padding: 8px;'>$materiais</td>
                        </tr>";
                    }
                }
            ?>
        </table>
    </div>

    <!-- Modal: Nova OS -->
    <div id="modalCreate" class="modal">
        <div class="modal-content-lg">
            <span class="close-btn" onclick="fecharModal()">&times;</span>
            <h2>Nova Ordem de Serviço</h2>
            <form action="/EletroTech/Services/ordensServicoCadService.php" method="POST" class="cadastro" id="formOS">
                <div>
                    <label>Eletricista responsável (ativo):</label>
                    <select name="eletricista_os" required>
                        <option value="">Selecione...</option>
                        <?php
                            $sqlE = "SELECT id_eletri, nome FROM tabela_eletricistas WHERE data_demissao IS NULL ORDER BY nome ASC";
                            $buscaE = $banco->query($sqlE);
                            while($e = $buscaE->fetch_object()){
                                echo "<option name='eletricista_os' value='{$e->id_eletri}'>{$e->nome}</option>";
                            }
                        ?>
                    </select>
                </div>
                
                <!-- Data da OS -->
                <div>
                    <label>Data da operação:</label>
                    <input type="date" name="data_os" required>
                </div>

                <div>
                    <label>Materiais utilizados:</label>
                    <div id="lista-materiais">
                    </div>
                    <button type="button" class="btn-add-material" onclick="addMaterial()">+ Adicionar material</button>
                </div>

                <!-- Produtos disponíveis em JSON para o JS -->
                <?php
                    $sqlP = "SELECT id_prod, nome, qtd_estoque FROM tabela_produtos WHERE qtd_estoque > 0 ORDER BY nome ASC";
                    $buscaP = $banco->query($sqlP);
                    $produtos = [];
                    while($p = $buscaP->fetch_object()){
                        $produtos[] = ['id' => $p->id_prod, 'nome' => $p->nome, 'estoque' => $p->qtd_estoque];
                    }
                    echo "<script>const produtos = " . json_encode($produtos) . ";</script>";
                ?>

                <button type="submit" class="enter">Registrar OS</button>
            </form>
        </div>
    </div>


</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    const tableOS     = document.getElementById("table-os");
    const modalCreate = document.getElementById("modalCreate");


    // Função para mostrar o modal de criação de OS
    function showCreate(){
        if(modalCreate.style.display == "none" || modalCreate.style.display == ""){
            modalCreate.style.display = "block";
            tableOS.style.display    = "none";
        } else {
            modalCreate.style.display = "none";
            tableOS.style.display    = "block";
        }
        if(document.querySelectorAll(".linha-material").length === 0){
            addMaterial(); // já começa com uma linha
        }
    }

    // Função para mostrar a tabela e esconder o modal
    function showTable(){

        modalCreate.style.display = "none";
        tableOS.style.display = "block";
    }

    // Função para fechar o modal de criação
    function fecharModal(){
        modalCreate.style.display = "none";
        tableOS.style.display = "block";
    }

    // Clicar fora do modal fechar
    window.onclick = function(e){
        if(e.target === modalCreate) fecharModal();
    }

    // Função para adicionar uma nova linha de material no formulário
    function addMaterial(){
        const lista = document.getElementById("lista-materiais");
        const div = document.createElement("div");
        div.className = "linha-material";

        // Monta o select de produtos
        let options = '<option value="">Selecione o produto</option>';
        produtos.forEach(p => {
            options += `<option name="produto" value="${p.id}" data-estoque="${p.estoque}">${p.nome} (estoque: ${p.estoque})</option>`;
        });

        div.innerHTML = `
            <select name="produtos[]" required onchange="atualizarMax(this)">
                ${options}
            </select>
            <input type="number" name="qtd[]" min="1" value="1" placeholder="Qtd" required>
            <button type="button" class="btn-remover" onclick="removerLinha(this)">✕</button>
        `;

        lista.appendChild(div);
    }

    // Função para atualizar o atributo max do input de quantidade com base no estoque do produto selecionado
    function atualizarMax(select){
        const estoque = select.options[select.selectedIndex].dataset.estoque;
        const input = select.nextElementSibling;
        if(estoque){
            input.max = estoque;
            input.title = "Máximo disponível: " + estoque;
        }
    }

    // Função para remover uma linha de material do formulário
    function removerLinha(btn){
        const linhas = document.querySelectorAll(".linha-material");
        if(linhas.length === 1){
            alert("A OS precisa ter pelo menos um material.");
            return;
        }
        btn.parentElement.remove();
    }
</script>

</body>
</html>