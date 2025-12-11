<?php
session_start();
include('verifica_login.php');
include('conexao.php');


// PARTE 1: VERIFICA SE PRECISA CRIAR OS 100 ALUNOS AUTOMÁTICOS

$sql_check = "SELECT COUNT(*) as total FROM alunos";
$result_check = mysqli_query($conexao, $sql_check);
$row_check = mysqli_fetch_assoc($result_check);
$total_alunos_no_banco = $row_check['total'];

// SÓ CRIA SE NÃO TIVER NENHUM ALUNO NO BANCO
if ($total_alunos_no_banco == 0) {
    // Mostra mensagem de criação
    echo "<div class='container mt-3'>
            <div class='alert alert-info alert-dismissible fade show'>
                <i class='fas fa-database me-2'></i>
                <strong>Sistema detectou que não há alunos.</strong> 
                Criando 100 alunos automáticos... Por favor aguarde.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
          </div>";
    
    // Arrays de dados
    $nomes = ['Ana', 'Bruno', 'Carla', 'Daniel', 'Eduarda', 'Felipe', 'Gabriela', 'Henrique', 'Isabela', 'João', 'Lucas', 'Mariana'];
    $sobrenomes = ['Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira', 'Alves', 'Pereira', 'Lima', 'Costa'];
    
    // Criar 100 alunos
    $alunos_criados = 0;
    for ($i = 0; $i < 100; $i++) {
        $nome_completo = $nomes[array_rand($nomes)] . ' ' . $sobrenomes[array_rand($sobrenomes)];
        $data_nasc = date('Y-m-d', strtotime('-' . rand(15, 25) . ' years'));
        $rua = 'Rua ' . $nomes[array_rand($nomes)];
        $numero = rand(1, 999);
        $bairro = ['Centro', 'Vila Nova', 'Jardins', 'Bela Vista'][rand(0, 3)];
        $cep = sprintf('%05d-%03d', rand(10000, 99999), rand(0, 999));
        $responsavel = $nomes[array_rand($nomes)] . ' ' . $sobrenomes[array_rand($sobrenomes)];
        $tipo = ['PAI', 'MÃE', 'TUTOR'][rand(0, 2)];
        $curso = ['Desenvolvimento de Sistemas', 'Informática', 'Administração', 'Enfermagem'][rand(0, 3)];
        
        // Data de cadastro com diferença de minutos para ordenação
        $data_cadastro = date('Y-m-d H:i:s', strtotime("-$i minutes"));
        
        $sql_insert = "INSERT INTO alunos 
                      (nome_completo, data_nascimento, rua, numero, bairro, cep, nome_responsavel, tipo_responsavel, curso, data_cadastro) 
                      VALUES 
                      ('$nome_completo', '$data_nasc', '$rua', '$numero', '$bairro', '$cep', '$responsavel', '$tipo', '$curso', '$data_cadastro')";
        
        if (mysqli_query($conexao, $sql_insert)) {
            $alunos_criados++;
        }
    }
    
    // Mensagem de sucesso
    echo "<div class='container mt-3'>
            <div class='alert alert-success alert-dismissible fade show'>
                <i class='fas fa-check-circle me-2'></i>
                <strong>Sucesso!</strong> $alunos_criados alunos foram criados automaticamente.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
            </div>
          </div>";
    
    // Atualiza o total
    $total_alunos_no_banco = $alunos_criados;
}


// PARTE 2: PAGINAÇÃO E BUSCA DE DADOS

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registros_por_pagina = 20;
$offset = ($pagina - 1) * $registros_por_pagina;

// Total de registros
$sql_total = "SELECT COUNT(*) as total FROM alunos";
$result_total = mysqli_query($conexao, $sql_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_registros = $row_total['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Busca alunos com paginação (ORDENA POR DATA MAIS RECENTE PRIMEIRO)
$sql = "SELECT * FROM alunos ORDER BY data_cadastro DESC LIMIT $offset, $registros_por_pagina";
$resultado = mysqli_query($conexao, $sql);

// Estatísticas
$sql_stats = "SELECT 
                COUNT(*) as total,
                COUNT(DISTINCT curso) as cursos,
                COUNT(DISTINCT bairro) as bairros
              FROM alunos";
$result_stats = mysqli_query($conexao, $sql_stats);
$stats = mysqli_fetch_assoc($result_stats);

// Conta quantos são manuais (ID > 100) e quantos são automáticos
$sql_tipos = "SELECT 
                COUNT(CASE WHEN id > 100 THEN 1 END) as manuais,
                COUNT(CASE WHEN id <= 100 THEN 1 END) as automaticos
              FROM alunos";
$result_tipos = mysqli_query($conexao, $sql_tipos);
$tipos = mysqli_fetch_assoc($result_tipos);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            background: #F4F7FB; 
            font-family: "Montserrat", sans-serif; 
        }
        h2 { 
            font-weight: 700; 
            color: #1E2A38; 
        }
        .stats-card { 
            background: white; 
            border-radius: 10px; 
            padding: 15px; 
            margin-bottom: 20px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            border-left: 4px solid #4FA3FF; 
        }
        .table-container { 
            background: #fff; 
            padding: 25px; 
            border-radius: 14px; 
            box-shadow: 0px 3px 10px rgba(0,0,0,0.09); 
        }
        .table thead { 
            background: #4FA3FF; 
            color: white; 
        }
        .table thead th { 
            font-weight: 600; 
        }
        .table-hover tbody tr:hover { 
            background: #E9F4FF !important; 
        }
        .badge-curso { 
            background: #1E88E5; 
            font-weight: 600; 
            padding: 6px 10px; 
        }
        .badge-manual {
            background: #28a745;
            font-size: 0.8em;
            padding: 4px 8px;
        }
        .badge-auto {
            background: #6c757d;
            font-size: 0.8em;
            padding: 4px 8px;
        }
        .aluno-manual {
            background-color: #f8fff8 !important;
        }
        .progress-bar-custom {
            background: linear-gradient(90deg, #4FA3FF, #1E88E5);
            border-radius: 5px;
        }
    </style>
    
    <title>Visualizar Alunos</title>
</head>
<body>

<?php include('navbar.php'); ?>

<!-- MENSAGENS DE SUCESSO/ERRO -->
<?php if(isset($_SESSION['mensagem_sucesso'])): ?>
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        <?= $_SESSION['mensagem_sucesso'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['mensagem_sucesso']); endif; ?>

<?php if(isset($_SESSION['mensagem_erro'])): ?>
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>
        <?= $_SESSION['mensagem_erro'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php unset($_SESSION['mensagem_erro']); endif; ?>

<div class="container mt-4">
    <!-- CABEÇALHO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-users me-2"></i> Lista de Alunos</h2>
            <p class="text-muted">Total de <?= $total_registros ?> alunos cadastrados</p>
            <?php if($total_registros >= 100): ?>
            <small class="text-success">
                <i class="fas fa-info-circle"></i> 
                Sistema: <?= $tipos['automaticos'] ?> automáticos + <?= $tipos['manuais'] ?> manuais
            </small>
            <?php endif; ?>
        </div>
        <div>
            <a href="formulario_aluno.php" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Novo Aluno
            </a>
            <?php if($total_registros == 0): ?>
            <a href="inserir_alunos.php" class="btn btn-warning ms-2">
                <i class="fas fa-bolt me-2"></i>Criar 100 Alunos
            </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- CARDS DE ESTATÍSTICAS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total de Alunos</h6>
                        <h3 class="fw-bold"><?= $stats['total'] ?></h3>
                    </div>
                    <div class="text-primary"><i class="fas fa-users fa-2x"></i></div>
                </div>
                <div class="mt-2">
                    <small class="text-muted">
                        <span class="badge badge-auto"><?= $tipos['automaticos'] ?> automáticos</span>
                        <span class="badge badge-manual ms-1"><?= $tipos['manuais'] ?> manuais</span>
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Cursos Ativos</h6>
                        <h3 class="fw-bold"><?= $stats['cursos'] ?></h3>
                    </div>
                    <div class="text-success"><i class="fas fa-graduation-cap fa-2x"></i></div>
                </div>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar progress-bar-custom" style="width: <?= min($stats['cursos'] * 25, 100) ?>%"></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Bairros</h6>
                        <h3 class="fw-bold"><?= $stats['bairros'] ?></h3>
                    </div>
                    <div class="text-warning"><i class="fas fa-map-marker-alt fa-2x"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stats-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Cadastros Hoje</h6>
                        <h3 class="fw-bold text-info">
                            <?php 
                            $sql_hoje = "SELECT COUNT(*) as hoje FROM alunos WHERE DATE(data_cadastro) = CURDATE()";
                            $result_hoje = mysqli_query($conexao, $sql_hoje);
                            $hoje = mysqli_fetch_assoc($result_hoje);
                            echo $hoje['hoje'];
                            ?>
                        </h3>
                    </div>
                    <div class="text-info"><i class="fas fa-calendar-day fa-2x"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABELA DE ALUNOS -->
    <?php if(mysqli_num_rows($resultado) > 0): ?>
    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Completo</th>
                        <th>Data de Nasc.</th>
                        <th>Endereço</th>
                        <th>Responsável</th>
                        <th>Curso</th>
                        <th>Data Cadastro</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($aluno = mysqli_fetch_assoc($resultado)): 
                        // Determina se é manual ou automático
                        $tipo_aluno = ($aluno['id'] > 100) ? 'manual' : 'auto';
                        $classe_linha = ($tipo_aluno == 'manual') ? 'aluno-manual' : '';
                    ?>
                    <tr class="<?= $classe_linha ?>">
                        <td class="text-muted">#<?= $aluno['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($aluno['nome_completo']) ?></strong>
                        </td>
                        <td><?= date('d/m/Y', strtotime($aluno['data_nascimento'])) ?></td>
                        <td>
                            <?= htmlspecialchars($aluno['rua']) ?>, <?= $aluno['numero'] ?><br>
                            <small class="text-muted"><?= htmlspecialchars($aluno['bairro']) ?> — CEP <?= $aluno['cep'] ?></small>
                        </td>
                        <td>
                            <?= htmlspecialchars($aluno['nome_responsavel']) ?><br>
                            <small class="badge bg-secondary"><?= $aluno['tipo_responsavel'] ?></small>
                        </td>
                        <td><span class="badge badge-curso text-white"><?= $aluno['curso'] ?></span></td>
                        <td><small><?= date('d/m/Y H:i', strtotime($aluno['data_cadastro'])) ?></small></td>
                        <td>
                            <?php if($tipo_aluno == 'manual'): ?>
                            <span class="badge badge-manual">Manual</span>
                            <?php else: ?>
                            <span class="badge badge-auto">Automático</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINAÇÃO -->
        <?php if($total_paginas > 1): ?>
        <nav aria-label="Navegação de páginas" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $pagina - 1 ?>">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                </li>
                
                <?php 
                // Mostra no máximo 5 páginas
                $inicio = max(1, $pagina - 2);
                $fim = min($total_paginas, $pagina + 2);
                
                for($i = $inicio; $i <= $fim; $i++): 
                ?>
                <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
                <?php endfor; ?>
                
                <li class="page-item <?= ($pagina >= $total_paginas) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $pagina + 1 ?>">
                        Próximo <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
            
            <p class="text-center text-muted mt-2">
                Página <?= $pagina ?> de <?= $total_paginas ?> 
                | Mostrando <?= $registros_por_pagina ?> alunos por página
                | Total: <?= $total_registros ?> alunos
            </p>
        </nav>
        <?php endif; ?>
    </div>

    <!-- RESUMO -->
    <div class="alert alert-info mt-4">
        <div class="row">
            <div class="col-md-8">
                <h5><i class="fas fa-info-circle me-2"></i> Resumo do Sistema</h5>
                <p class="mb-1">
                    Mostrando alunos de <?= $offset + 1 ?> a <?= min($offset + $registros_por_pagina, $total_registros) ?> 
                    de um total de <?= $total_registros ?> registros.
                </p>
                <p class="mb-0">
                    <span class="badge badge-auto me-2"><?= $tipos['automaticos'] ?> alunos automáticos</span>
                    <span class="badge badge-manual"><?= $tipos['manuais'] ?> alunos manuais</span>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <a href="relatorios.php" class="btn btn-outline-info me-2">
                    <i class="fas fa-chart-bar me-2"></i>Relatórios
                </a>
                <a href="painel.php" class="btn btn-outline-primary">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </div>
        </div>
    </div>

    <?php else: ?>
        <!-- SE NÃO HOUVER ALUNOS -->
        <div class="alert alert-info mt-4">
            <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4>Nenhum aluno cadastrado</h4>
                <p class="text-muted mb-4">O sistema ainda não possui alunos cadastrados.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="formulario_aluno.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Cadastrar Primeiro Aluno
                    </a>
                    <a href="inserir_alunos.php" class="btn btn-warning btn-lg">
                        <i class="fas fa-bolt me-2"></i>Criar 100 Alunos Automáticos
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php 
// Libera memória
mysqli_free_result($resultado);
mysqli_free_result($result_total);
mysqli_free_result($result_stats);
mysqli_free_result($result_tipos);
if(isset($result_hoje)) mysqli_free_result($result_hoje);
?>