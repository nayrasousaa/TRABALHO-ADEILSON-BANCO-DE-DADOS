<?php
session_start();
include('verifica_login.php');
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe dados do formulário
    $nome_completo = $_POST['nome_completo'];
    $data_nascimento = $_POST['data_nascimento'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $bairro = $_POST['bairro'];
    $cep = $_POST['cep'];
    $nome_responsavel = $_POST['nome_responsavel'];
    $tipo_responsavel = $_POST['tipo_responsavel'];
    $curso = $_POST['curso'];
    
    // INSERE no banco
    $sql = "INSERT INTO alunos 
            (nome_completo, data_nascimento, rua, numero, bairro, cep, nome_responsavel, tipo_responsavel, curso, data_cadastro) 
            VALUES 
            ('$nome_completo', '$data_nascimento', '$rua', '$numero', '$bairro', '$cep', '$nome_responsavel', '$tipo_responsavel', '$curso', NOW())";
    
    if (mysqli_query($conexao, $sql)) {
        $_SESSION['mensagem_sucesso'] = "✅ Aluno cadastrado com sucesso! ID: " . mysqli_insert_id($conexao);
        header('Location: visualizar_alunos.php');
        exit();
    } else {
        $_SESSION['mensagem_erro'] = "❌ Erro ao cadastrar: " . mysqli_error($conexao);
        header('Location: formulario_aluno.php');
        exit();
    }
} else {
    header('Location: formulario_aluno.php');
    exit();
}
?>