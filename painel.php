<?php
session_start();
include('verifica_login.php');
include('conexao.php');

// CONSULTA 1: Total de alunos
$sql_total = "SELECT COUNT(*) as total FROM alunos";
$result_total = mysqli_query($conexao, $sql_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_alunos = $row_total['total'];

// CONSULTA 2: Alunos por curso (para gráfico)
$sql_cursos = "SELECT curso, COUNT(*) as quantidade FROM alunos GROUP BY curso";
$result_cursos = mysqli_query($conexao, $sql_cursos);
$cursos_data = [];
while($row = mysqli_fetch_assoc($result_cursos)) {
    $cursos_data[] = $row;
}

// CONSULTA 3: Alunos por mês (para gráfico de linha)
$sql_meses = "SELECT 
                MONTH(data_cadastro) as mes,
                MONTHNAME(data_cadastro) as nome_mes,
                COUNT(*) as total 
              FROM alunos 
              WHERE YEAR(data_cadastro) = YEAR(CURDATE())
              GROUP BY MONTH(data_cadastro)
              ORDER BY mes";
$result_meses = mysqli_query($conexao, $sql_meses);
$meses_data = [];
while($row = mysqli_fetch_assoc($result_meses)) {
    $meses_data[] = $row;
}

// CONSULTA 4: Últimos cadastros
$sql_ultimos = "SELECT * FROM alunos ORDER BY data_cadastro DESC LIMIT 5";
$result_ultimos = mysqli_query($conexao, $sql_ultimos);

// CONSULTA 5: Idade média
$sql_idade = "SELECT 
                AVG(YEAR(CURDATE()) - YEAR(data_nascimento)) as idade_media,
                MIN(YEAR(CURDATE()) - YEAR(data_nascimento)) as idade_min,
                MAX(YEAR(CURDATE()) - YEAR(data_nascimento)) as idade_max 
              FROM alunos";
$result_idade = mysqli_query($conexao, $sql_idade);
$idade_data = mysqli_fetch_assoc($result_idade);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - Dashboard</title>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { background: #F5F9FF; font-family: 'Montserrat', sans-serif; }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-4">
    <!-- Cabeçalho -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stat-card bg-primary text-white">
                <div class="row">
                    <div class="col-md-8">
                        <h2><i class="fas fa-chart-line"></i> Dashboard Analítico</h2>
                        <p>Estatísticas em tempo real do sistema acadêmico</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <h4>Total: <?= $total_alunos ?> alunos</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Alunos</h6>
                        <h3 class="fw-bold"><?= $total_alunos ?></h3>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Idade Média</h6>
                        <h3 class="fw-bold"><?= round($idade_data['idade_media']) ?> anos</h3>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <a href="formulario_aluno.php" class="text-decoration-none text-dark">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Novo Cadastro</h6>
                            <h4 class="fw-bold">+ Aluno</h4>
                        </div>
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-user-plus"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3 mb-3">
            <a href="relatorios.php" class="text-decoration-none text-dark">
                <div class="stat-card">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Relatórios</h6>
                            <h4 class="fw-bold">10 Consultas</h4>
                        </div>
                        <div class="stat-icon bg-info bg-opacity-10 text-info">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <!-- Gráficos -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <h5><i class="fas fa-chart-pie me-2"></i> Alunos por Curso</h5>
                <canvas id="graficoCursos"></canvas>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="chart-container">
                <h5><i class="fas fa-chart-line me-2"></i> Cadastros Mensais (<?= date('Y') ?>)</h5>
                <canvas id="graficoMensal"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Tabela de Últimos -->
    <div class="row">
        <div class="col-12">
            <div class="chart-container">
                <h5><i class="fas fa-history me-2"></i> Últimos Cadastros</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Nome</th>
                                <th>Curso</th>
                                <th>Responsável</th>
                                <th>Data Cadastro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($aluno = mysqli_fetch_assoc($result_ultimos)): ?>
                            <tr>
                                <td><?= htmlspecialchars($aluno['nome_completo']) ?></td>
                                <td><span class="badge bg-primary"><?= $aluno['curso'] ?></span></td>
                                <td><?= htmlspecialchars($aluno['nome_responsavel']) ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($aluno['data_cadastro'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Gráfico de Pizza - Cursos
const ctx1 = document.getElementById('graficoCursos').getContext('2d');
new Chart(ctx1, {
    type: 'pie',
    data: {
        labels: [<?php foreach($cursos_data as $c) echo "'" . $c['curso'] . "',"; ?>],
        datasets: [{
            data: [<?php foreach($cursos_data as $c) echo $c['quantidade'] . ","; ?>],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Gráfico de Linha - Mensal
const ctx2 = document.getElementById('graficoMensal').getContext('2d');
new Chart(ctx2, {
    type: 'line',
    data: {
        labels: [<?php foreach($meses_data as $m) echo "'" . $m['nome_mes'] . "',"; ?>],
        datasets: [{
            label: 'Cadastros',
            data: [<?php foreach($meses_data as $m) echo $m['total'] . ","; ?>],
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_free_result($result_total);
mysqli_free_result($result_cursos);
mysqli_free_result($result_meses);
mysqli_free_result($result_ultimos);
mysqli_free_result($result_idade);
?>