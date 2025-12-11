<?php
// relatorios.php
session_start();
include('verifica_login.php');
include('conexao.php');

// CONSULTA 1: Total por curso
$sql1 = "SELECT curso, COUNT(*) as total FROM alunos GROUP BY curso ORDER BY total DESC";
$result1 = mysqli_query($conexao, $sql1);

// CONSULTA 2: Alunos por mês (ano atual)
$sql2 = "SELECT 
            MONTHNAME(data_cadastro) as mes,
            COUNT(*) as total 
         FROM alunos 
         WHERE YEAR(data_cadastro) = YEAR(CURDATE())
         GROUP BY MONTH(data_cadastro)
         ORDER BY MONTH(data_cadastro)";
$result2 = mysqli_query($conexao, $sql2);

// CONSULTA 3: Idade média por curso
$sql3 = "SELECT 
            curso,
            AVG(YEAR(CURDATE()) - YEAR(data_nascimento)) as idade_media,
            MIN(YEAR(CURDATE()) - YEAR(data_nascimento)) as idade_min,
            MAX(YEAR(CURDATE()) - YEAR(data_nascimento)) as idade_max
         FROM alunos 
         GROUP BY curso";
$result3 = mysqli_query($conexao, $sql3);

// CONSULTA 4: Alunos por bairro
$sql4 = "SELECT bairro, COUNT(*) as total FROM alunos GROUP BY bairro ORDER BY total DESC LIMIT 10";
$result4 = mysqli_query($conexao, $sql4);

// CONSULTA 5: Responsáveis por tipo
$sql5 = "SELECT tipo_responsavel, COUNT(*) as total FROM alunos GROUP BY tipo_responsavel";
$result5 = mysqli_query($conexao, $sql5);

// CONSULTA 6: Aniversariantes do mês
$sql6 = "SELECT nome_completo, data_nascimento 
         FROM alunos 
         WHERE MONTH(data_nascimento) = MONTH(CURDATE())
         ORDER BY DAY(data_nascimento)";
$result6 = mysqli_query($conexao, $sql6);

// CONSULTA 7: Alunos por faixa etária
$sql7 = "SELECT 
            CASE 
                WHEN YEAR(CURDATE()) - YEAR(data_nascimento) < 18 THEN 'Menor de 18'
                WHEN YEAR(CURDATE()) - YEAR(data_nascimento) BETWEEN 18 AND 25 THEN '18-25 anos'
                WHEN YEAR(CURDATE()) - YEAR(data_nascimento) BETWEEN 26 AND 35 THEN '26-35 anos'
                ELSE 'Maior de 35'
            END as faixa_etaria,
            COUNT(*) as total
         FROM alunos
         GROUP BY faixa_etaria";
$result7 = mysqli_query($conexao, $sql7);

// CONSULTA 8: Últimos 30 dias
$sql8 = "SELECT COUNT(*) as total FROM alunos 
         WHERE data_cadastro >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$result8 = mysqli_query($conexao, $sql8);
$row8 = mysqli_fetch_assoc($result8);

// CONSULTA 9: Top 5 responsáveis com mais alunos
$sql9 = "SELECT nome_responsavel, COUNT(*) as total_alunos 
         FROM alunos 
         GROUP BY nome_responsavel 
         HAVING COUNT(*) > 1
         ORDER BY total_alunos DESC 
         LIMIT 5";
$result9 = mysqli_query($conexao, $sql9);

// CONSULTA 10: Crescimento anual
$sql10 = "SELECT 
            YEAR(data_cadastro) as ano,
            COUNT(*) as total
          FROM alunos
          GROUP BY YEAR(data_cadastro)
          ORDER BY ano";
$result10 = mysqli_query($conexao, $sql10);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios - 10 Consultas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { background: #F8FAFC; font-family: 'Montserrat', sans-serif; }
        .report-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            border-left: 4px solid #4FA3FF;
        }
        .report-title {
            color: #1E2A38;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .badge-stat { background: #4FA3FF; font-size: 0.9em; }
        .badge-number { background: #28a745; }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-4">
    <h2 class="mb-4"><i class="fas fa-chart-bar text-primary me-2"></i> Relatórios - 10 Consultas SQL</h2>
    
    <!-- Consulta 1 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">1</span> Alunos por Curso</h5>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result1)): ?>
            <div class="col-md-3 mb-2">
                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                    <span><?= $row['curso'] ?></span>
                    <span class="badge badge-stat"><?= $row['total'] ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- Consulta 2 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">2</span> Cadastros Mensais (<?= date('Y') ?>)</h5>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr><th>Mês</th><th>Total</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result2)): ?>
                    <tr>
                        <td><?= $row['mes'] ?></td>
                        <td><strong><?= $row['total'] ?></strong></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Consulta 3 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">3</span> Idade Média por Curso</h5>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr><th>Curso</th><th>Média</th><th>Mínima</th><th>Máxima</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result3)): ?>
                    <tr>
                        <td><?= $row['curso'] ?></td>
                        <td><?= round($row['idade_media'], 1) ?> anos</td>
                        <td><?= $row['idade_min'] ?> anos</td>
                        <td><?= $row['idade_max'] ?> anos</td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Consulta 4 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">4</span> Top 10 Bairros</h5>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result4)): ?>
            <div class="col-md-4 mb-2">
                <div class="d-flex justify-content-between p-2 bg-light rounded">
                    <span><?= $row['bairro'] ?></span>
                    <span class="badge bg-info"><?= $row['total'] ?></span>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- Consulta 5 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">5</span> Tipo de Responsável</h5>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result5)): ?>
            <div class="col-md-3 mb-2">
                <div class="text-center p-3 border rounded">
                    <h5><?= $row['tipo_responsavel'] ?></h5>
                    <h2 class="text-primary"><?= $row['total'] ?></h2>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- Consulta 6 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">6</span> Aniversariantes do Mês</h5>
        <?php if(mysqli_num_rows($result6) > 0): ?>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result6)): ?>
            <div class="col-md-4 mb-2">
                <div class="p-2 border-start border-3 border-warning">
                    <strong><?= $row['nome_completo'] ?></strong><br>
                    <small><?= date('d/m', strtotime($row['data_nascimento'])) ?></small>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php else: ?>
        <p class="text-muted">Nenhum aniversariante este mês.</p>
        <?php endif; ?>
    </div>
    
    <!-- Consulta 7 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">7</span> Faixa Etária</h5>
        <div class="row">
            <?php while($row = mysqli_fetch_assoc($result7)): ?>
            <div class="col-md-3 mb-2">
                <div class="text-center p-3 border rounded">
                    <h6><?= $row['faixa_etaria'] ?></h6>
                    <h4><?= $row['total'] ?></h4>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <!-- Consulta 8 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">8</span> Últimos 30 Dias</h5>
        <div class="text-center p-4">
            <h1 class="display-4 text-primary"><?= $row8['total'] ?></h1>
            <p class="text-muted">novos cadastros no último mês</p>
        </div>
    </div>
    
    <!-- Consulta 9 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">9</span> Responsáveis com Mais Alunos</h5>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr><th>Responsável</th><th>Total de Alunos</th></tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result9)): ?>
                    <tr>
                        <td><?= $row['nome_responsavel'] ?></td>
                        <td><span class="badge bg-success"><?= $row['total_alunos'] ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Consulta 10 -->
    <div class="report-card">
        <h5 class="report-title"><span class="badge badge-number me-2">10</span> Crescimento Anual</h5>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr><th>Ano</th><th>Total</th><th>Crescimento</th></tr>
                </thead>
                <tbody>
                    <?php 
                    $previous = 0;
                    while($row = mysqli_fetch_assoc($result10)):
                        $growth = $previous > 0 ? (($row['total'] - $previous) / $previous * 100) : 0;
                    ?>
                    <tr>
                        <td><?= $row['ano'] ?></td>
                        <td><strong><?= $row['total'] ?></strong></td>
                        <td>
                            <?php if($growth > 0): ?>
                                <span class="badge bg-success">+<?= round($growth) ?>%</span>
                            <?php elseif($growth < 0): ?>
                                <span class="badge bg-danger"><?= round($growth) ?>%</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">0%</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                    $previous = $row['total'];
                    endwhile; 
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_free_result($result1);
mysqli_free_result($result2);
mysqli_free_result($result3);
mysqli_free_result($result4);
mysqli_free_result($result5);
mysqli_free_result($result6);
mysqli_free_result($result7);
mysqli_free_result($result8);
mysqli_free_result($result9);
mysqli_free_result($result10);
?>