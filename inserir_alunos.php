<?php
// inseriralunos.php
// Executa uma vez para popular o banco com 100+ alunos

// Conexão
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'login';

$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro de conexão: " . mysqli_connect_error());
}

echo "<h2>Inserindo Alunos no Banco</h2>";
echo "<div style='font-family: Arial; padding: 20px;'>";

// Arrays de dados realistas
$nomesMasculinos = [
    'João', 'Pedro', 'Carlos', 'Lucas', 'Gabriel', 'Rafael', 'Daniel', 'Marcos', 
    'André', 'Felipe', 'Bruno', 'Leonardo', 'Thiago', 'Eduardo', 'Vinicius'
];

$nomesFemininos = [
    'Maria', 'Ana', 'Julia', 'Laura', 'Beatriz', 'Camila', 'Isabela', 'Fernanda',
    'Patrícia', 'Amanda', 'Letícia', 'Mariana', 'Carolina', 'Clara', 'Sophia'
];

$sobrenomes = [
    'Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira', 'Alves',
    'Pereira', 'Lima', 'Costa', 'Ribeiro', 'Martins', 'Carvalho', 'Gomes'
];

$ruas = [
    'Rua das Flores', 'Avenida Brasil', 'Rua XV de Novembro', 'Avenida Paulista',
    'Rua da Paz', 'Avenida Central', 'Rua das Palmeiras', 'Alameda Santos',
    'Travessa Esperança', 'Praça da Liberdade', 'Rua do Comércio', 'Avenida Rio Branco'
];

$bairros = ['Centro', 'Vila Nova', 'Jardins', 'Bela Vista', 'Perdizes', 'Mooca', 'Tatuapé', 'Santana'];
$cursos = ['Desenvolvimento de Sistemas', 'Informática', 'Administração', 'Enfermagem'];
$tiposResponsavel = ['PAI', 'MÃE', 'TUTOR'];

$alunosInseridos = 0;
$erros = 0;

// Verificar se tabela já tem dados
$check = mysqli_query($conexao, "SELECT COUNT(*) as total FROM alunos");
$row = mysqli_fetch_assoc($check);
$totalAtual = $row['total'];

if ($totalAtual > 50) {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px;'>
            <strong>Aviso:</strong> O banco já tem $totalAtual alunos.<br>
            Deseja mesmo continuar?<br>
            <a href='?confirm=yes' style='color: #856404;'>SIM, adicionar mais 100</a> | 
            <a href='painel.php' style='color: #856404;'>NÃO, voltar ao painel</a>
          </div>";
    
    if (!isset($_GET['confirm']) || $_GET['confirm'] != 'yes') {
        exit();
    }
}

// Gerar 100 alunos
for ($i = 1; $i <= 100; $i++) {
    // Escolher gênero aleatório
    if (rand(0, 1) == 0) {
        $primeiroNome = $nomesMasculinos[array_rand($nomesMasculinos)];
    } else {
        $primeiroNome = $nomesFemininos[array_rand($nomesFemininos)];
    }
    
    $segundoNome = $nomesFemininos[array_rand($nomesFemininos)]; // Para nome composto
    $sobrenome1 = $sobrenomes[array_rand($sobrenomes)];
    $sobrenome2 = $sobrenomes[array_rand($sobrenomes)];
    
    $nomeCompleto = "$primeiroNome $segundoNome $sobrenome1 $sobrenome2";
    
    // Data de nascimento entre 15 e 25 anos atrás
    $anosAtras = rand(15, 25);
    $mes = rand(1, 12);
    $dia = rand(1, 28);
    $anoNasc = date('Y') - $anosAtras;
    $dataNascimento = sprintf('%04d-%02d-%02d', $anoNasc, $mes, $dia);
    
    // Endereço
    $rua = $ruas[array_rand($ruas)];
    $numero = rand(1, 9999);
    $bairro = $bairros[array_rand($bairros)];
    $cep = sprintf('%05d', rand(10000, 99999)) . '-' . sprintf('%03d', rand(0, 999));
    
    // Responsável
    $responsavelPrimeiroNome = $nomesMasculinos[array_rand($nomesMasculinos)];
    $responsavelSobrenome = $sobrenomes[array_rand($sobrenomes)];
    $nomeResponsavel = "$responsavelPrimeiroNome $responsavelSobrenome";
    $tipoResponsavel = $tiposResponsavel[array_rand($tiposResponsavel)];
    
    // Curso
    $curso = $cursos[array_rand($cursos)];
    
    // Inserir no banco
    $sql = "INSERT INTO alunos (
                nome_completo, 
                data_nascimento, 
                rua, 
                numero, 
                bairro, 
                cep, 
                nome_responsavel, 
                tipo_responsavel, 
                curso
            ) VALUES (
                '$nomeCompleto',
                '$dataNascimento',
                '$rua',
                '$numero',
                '$bairro',
                '$cep',
                '$nomeResponsavel',
                '$tipoResponsavel',
                '$curso'
            )";
    
    if (mysqli_query($conexao, $sql)) {
        $alunosInseridos++;
        
        // Mostrar progresso a cada 10 inserções
        if ($alunosInseridos % 10 == 0) {
            echo "<div style='color: #888; margin: 5px 0;'>✓ Inseridos $alunosInseridos alunos...</div>";
            flush();
        }
    } else {
        $erros++;
    }
}

// Resultado
echo "<div style='background: #d4edda; color: #155724; padding: 20px; border-radius: 5px; margin-top: 20px;'>
        <h3 style='color: #155724;'>✅ CONCLUÍDO!</h3>
        <p><strong>Alunos inseridos:</strong> $alunosInseridos</p>
        <p><strong>Erros:</strong> $erros</p>
        <p><strong>Total no banco:</strong> " . ($totalAtual + $alunosInseridos) . " alunos</p>
      </div>";

echo "<div style='margin-top: 20px;'>
        <a href='painel.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>
            📊 Ver Dashboard
        </a>
        <a href='visualizar_alunos.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-right: 10px;'>
            👥 Ver Lista de Alunos
        </a>
        <a href='relatorios.php' style='background: #17a2b8; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
            📈 Ver Relatórios
        </a>
      </div>";

// Exemplo dos últimos 5 inseridos
echo "<div style='margin-top: 30px; background: #f8f9fa; padding: 15px; border-radius: 5px;'>
        <h4>Últimos 5 alunos cadastrados:</h4>";
        
$ultimos = mysqli_query($conexao, "SELECT nome_completo, curso FROM alunos ORDER BY id DESC LIMIT 5");
echo "<ul>";
while($aluno = mysqli_fetch_assoc($ultimos)) {
    echo "<li><strong>" . htmlspecialchars($aluno['nome_completo']) . "</strong> - " . $aluno['curso'] . "</li>";
}
echo "</ul></div>";

echo "</div>";

mysqli_close($conexao);
?>