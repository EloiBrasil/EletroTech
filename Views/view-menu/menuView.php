<?php 
    session_start();
    if(!isset($_SESSION["logado"]) || $_SESSION["logado"] !== true || empty($_SESSION["username"])){
        header("Location: /eletrotech/view-login/tela-login.php");
        exit;
    }

    require __DIR__ . "/../../banco/bdEletro.php";

    // Total de eletricistas ativos
    $totalEletricistas = $banco->query("SELECT COUNT(*) as total FROM tabela_eletricistas WHERE data_demissao IS NULL")->fetch_object()->total;

    // Total de produtos
    $totalProdutos = $banco->query("SELECT COUNT(*) as total FROM tabela_produtos")->fetch_object()->total;

    // OS por eletricista
    $sqlGrafico = "SELECT e.nome, COUNT(o.id_ordServ) as total_os 
                FROM tabela_eletricistas e
                LEFT JOIN tabela_ordens_servico o ON e.id_eletri = o.eletricista_os
                WHERE e.data_demissao IS NULL
                GROUP BY e.id_eletri, e.nome
                ORDER BY total_os DESC";
    $resultGrafico = $banco->query($sqlGrafico);

    $labels = [];
    $dados  = [];
    // Preenche os arrays de labels e dados para o gráfico
    while($row = $resultGrafico->fetch_object()){
        $labels[] = $row->nome;
        $dados[]  = (int) $row->total_os;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Principal</title>
    <link rel="stylesheet" href="/EletroTech/Views/view-menu/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="/EletroTech/Biblioteca-HTML-CSS/Biblioteca/Components/style.css">
    <link rel="stylesheet" href="/EletroTech/Styles/StyleComp.css">
</head>
<body>

<div class="navbar-menu">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img class="eletrotech" src="/EletroTech/Views/imgs/Captura de Tela 2026-05-18 às 14.00.40.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" style="color:#ffd500;" href="/EletroTech/Views/view-users/users.php">Usuários</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="/EletroTech/Views/view-eletricista/eletricistaView.php">Eletricistas</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="/EletroTech/Views/view-produtos/produtosView.php">Produtos</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="/EletroTech/Views/view-metas/metasView.php">Metas</a></li>
                    <li class="nav-item"><a class="nav-link" style="color:#ffd500;" href="/EletroTech/Views/view-ordens-servico/ordensServicoView.php">Ordens de serviço</a></li>
                </ul>
                <a href="/EletroTech/logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
        </div>
    </nav>
</div>

<main class="container mt-5">

    <!-- Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card card-dashboard card-amarelo p-4 text-center">
                <div class="card-numero"><?= $totalEletricistas ?></div>
                <div class="fs-5 fw-semibold">Eletricistas Ativos</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-dashboard card-escuro p-4 text-center">
                <div class="card-numero"><?= $totalProdutos ?></div>
                <div class="fs-5 fw-semibold">Produtos Cadastrados</div>
            </div>
        </div>
    </div>

    <!-- Gráfico -->
    <div class="grafico-box">
        <h5 class="mb-4 fw-bold">Ordens de Serviço por Eletricista</h5>
        <canvas id="graficoOS" height="100"></canvas>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = <?= json_encode($labels) ?>;
    const dados  = <?= json_encode($dados) ?>;


    // Configuração do gráfico de barras usando Chart.js
    new Chart(document.getElementById("graficoOS"), {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Ordens de Serviço",
                data: dados,
                backgroundColor: "#ffd500",
                borderColor: "#e6c000",
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>

</body>
</html>