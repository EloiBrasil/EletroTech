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
    <title>Metas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="/EletroTech/Styles/styleComp.css"> 
    <style>
        
    </style>
</head>
<body>

<div class="navbar-menu">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../view-menu/menuView.php"><img class="eletrotech" src="../imgs/Captura de Tela 2026-05-18 às 14.00.40.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" style="color:#ffd500;" href="../view-users/users.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-eletricista/eletricistaView.php">Eletricistas</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-produtos/produtosView.php">Produtos</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-metas/metasView.php">Metas</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="../view-ordens-servico/ordensServicoView.php">Ordens de serviço</a></li>
                </ul>
                <div class="logout">
                    <a href="/../EletroTech/logout.php" class="btn btn-outline-danger">Sair</a>
                </div>
            </div>
        </div>
    </nav>
</div>

<?php
    require __DIR__ . "/../../banco/bdEletro.php";
    include __DIR__ . "/../../Biblioteca-PHP/FunctionsGPM/Models/FunctionsMsg.php";


    function formatarMoeda($valor){
        return number_format($valor, 2, ',', '.');
    }
    function formatarMes($date){
        $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                  'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
        $partes = explode('-', $date);
        return $meses[(int)$partes[1] - 1] . '/' . $partes[0];
    }

    $where = [];
    if(!empty($_GET['eletricista'])){
        $eletricista_filtro = $banco->real_escape_string($_GET['eletricista']);
        $where[] = "id_eletri = '$eletricista_filtro'";
    }
    if(!empty($_GET['mes'])){
        $mes_filtro = $banco->real_escape_string($_GET['mes']) . '-01';
        $where[] = "mes_meta = '$mes_filtro'";
    }

    $sql = "SELECT id_eletri, nome, cpf, id_meta, mes_meta, vlr_meta FROM viewmetaseletri";
    if(!empty($where)){
        $sql .= " WHERE " . implode(" AND ", $where);
    }
    $sql .= " ORDER BY mes_meta DESC";

    $filtroAtivo = !empty($_GET['eletricista']) || !empty($_GET['mes']);
?>

<main class="mt-4">
    <div class="buttons-users">
        <button onclick="showCreate()">Nova meta</button>
    </div>

    <!-- Filtro -->
    <div id="div-filtro" class="table-metas mb-3" style="display:none;">
        <div class="d-flex gap-2 mt-3">
            <select id="filtro-eletricista" class="form-select">
                <option value="">Todos os eletricistas</option>
                <?php
                    $sqlE = "SELECT id_eletri, nome FROM tabela_eletricistas WHERE data_demissao IS NULL ORDER BY nome ASC";
                    $buscaE = $banco->query($sqlE);
                    while($e = $buscaE->fetch_object()){
                        $selected = (isset($_GET['eletricista']) && $_GET['eletricista'] == $e->id_eletri) ? "selected" : "";
                        echo "<option value='{$e->id_eletri}' $selected>{$e->nome}</option>";
                    }
                ?>
            </select>
            <input type="month" id="filtro-mes" class="form-control" value="<?php echo isset($_GET['mes']) ? $_GET['mes'] : ''; ?>">
            <button class="btn btn-warning" onclick="filtrarMetas()">Filtrar</button>
            <button class="btn btn-secondary" onclick="limparFiltro()">Limpar</button>
        </div>
    </div>

    <!-- Tabela -->
    <div class="table-metas" id="table-eletri">
        <table>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>Mês da meta</th>
                <th>Valor da meta</th>
                <th>Ações</th>
            </tr>
            <?php
                $busca = $banco->query($sql);
                if(!$busca){
                    msgError("Erro ao buscar metas: " . $banco->error);
                } else if($busca->num_rows == 0){
                    echo "<tr><td colspan='5'>Nenhuma meta cadastrada.</td></tr>";
                } else {
                    while($reg = $busca->fetch_object()){
                        echo "<tr>
                            <td>{$reg->nome}</td>
                            <td>{$reg->cpf}</td>
                            <td>" . formatarMes($reg->mes_meta) . "</td>
                            <td>R$ " . formatarMoeda($reg->vlr_meta) . "</td>
                            <td>
                                <button class='btn-acao' onclick='showEdit({$reg->id_meta}, \"{$reg->vlr_meta}\")'>
                                    <img class='icon' src='icons/edit.svg' alt='Editar'>
                                </button>
                                <button class='btn-acao' onclick='confirmarExcluir({$reg->id_meta})'>
                                    <img class='icon' src='icons/delete.svg' alt='Excluir'>
                                </button>
                            </td>
                        </tr>";
                    }
                }
            ?>
        </table>
    </div>

    <!-- Modal exclusão -->
    <div id="delete-modal" class="modal" style="display:none;">
        <div class="modal-dialog modal-dialog-centered modal-content">
            <h2>Confirmar exclusão?</h2>
            <div class="form-delete">
                <form action="/EletroTech/Services/metasDeleteService.php" method="POST">
                    <input type="hidden" name="id_meta" id="id_meta_exc" value="">
                    <button type="submit" class="btn btn-danger">Sim, excluir</button>
                </form>
                <button onclick="hideDeleteModal()" class="btn btn-secondary mt-2">Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal edição -->
    <div id="myModal" class="modal" style="display:none;">
        <div class="modal-dialog modal-dialog-centered modal-content">
            <button style="border:none;background:none;" onclick="fecharEdit()"><span class="close-btn">&times;</span></button>
            <h2>Editar Valor</h2>
            <form action="/EletroTech/Services/metasEditService.php" method="POST" class="cadastro">
                <input type="hidden" name="id_meta" id="metaEdit" value="">
                <div>
                    <label>Digite o novo valor da meta:</label>
                    <input type="number" placeholder="Digite o novo valor da meta:" id="valor" name="valor" required>
                </div>
                <input type="submit" class="enter" value="Salvar">
            </form>
        </div>
    </div>

    <!-- Form de criação -->
    <div class="create-users" id="create-eletri" style="display:none;">
        <form action="/EletroTech/Services/metasCadService.php" class="cadastro-users" method="POST">
            <div class="usuario-cad-eletri">
                <select name="eletricista" required>
                    <option value="">Selecione o eletricista</option>
                    <?php
                        $sqlE2 = "SELECT id_eletri, nome FROM tabela_eletricistas WHERE data_demissao IS NULL ORDER BY nome ASC";
                        $buscaE2 = $banco->query($sqlE2);
                        while($e2 = $buscaE2->fetch_object()){
                            echo "<option value='{$e2->id_eletri}'>{$e2->nome}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="valor-cad-eletri">
                <label>Valor da meta:</label>
                <input type="number" name="valor" placeholder="Digite o valor da meta" required>
            </div>
            <div class="mes-cad-eletri">
                <label>Mês da meta:</label>
                <input type="month" name="mes" required>
            </div>
            <input class="btn-users" type="submit" value="Cadastrar">
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script>
    const telaTable   = document.getElementById("table-eletri");
    const telaCad     = document.getElementById("create-eletri");
    const modal       = document.getElementById("myModal");
    const deleteModal = document.getElementById("delete-modal");
    const filtro      = document.getElementById("div-filtro");
    const inputValor  = document.getElementById("valor");

    // Mostrar form de criação e esconder tabela
    function showCreate(){
        if(telaCad.style.display === "none" || telaCad.style.display === ""){
            telaCad.style.display = "block";
            filtro.style.display    = "none";
            telaTable.style.display = "none";
        } else {
             telaCad.style.display = "none";
             filtro.style.display    = "block";
             telaTable.style.display = "block";
        }
 
    }

    // Mostrar modal de edição e preencher campos
    function showEdit(id, valor){
        modal.style.display = "flex";
        document.getElementById("metaEdit").value = id;
        inputValor.value = valor;
    }

    // Fechar modal de edição
    function fecharEdit(){
        modal.style.display = "none";
    }

    // Mostrar modal de confirmação de exclusão e preencher campo oculto
    function confirmarExcluir(id){
        document.getElementById("id_meta_exc").value = id;
        deleteModal.style.display = "flex";
    }

    // Fechar modal de exclusão
    function hideDeleteModal(){
        deleteModal.style.display = "none";
    }
    
    // Função para filtrar metas com base nos campos de filtro
    function filtrarMetas(){
        const eletricista = document.getElementById("filtro-eletricista").value;
        const mes = document.getElementById("filtro-mes").value;
        const params = new URLSearchParams();
        if(eletricista) params.append("eletricista", eletricista);
        if(mes) params.append("mes", mes);
        window.location.href = "metasView.php?" + params.toString();
    }

    // Função para limpar os filtros e mostrar todas as metas
    function limparFiltro(){
        window.location.href = "metasView.php";
    }

    // Ao clicar fora do modal, ele fecha
    window.onclick = function(event){
        if(event.target === modal) modal.style.display = "none";
        if(event.target === deleteModal) deleteModal.style.display = "none";
    }

    // Se filtro estava ativo, mostra tabela automaticamente
    <?php if($filtroAtivo): ?>
        telaTable.style.display = "block";
        filtro.style.display    = "block";
    <?php endif; ?>
</script>

</body>
</html>