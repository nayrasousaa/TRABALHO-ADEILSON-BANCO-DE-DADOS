<?php
session_start();
include('verifica_login.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cadastrar Aluno</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- FONTE MONTSERRAT -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body {
    background: #F1F5FA;
    font-family: 'Montserrat', sans-serif !important;
}

.form-card {
    background: #FFFFFF;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    border: none;
}

/* título */
.form-title {
    font-weight: 700;
    color: #1E2A38;
    border-left: 5px solid #4FA3FF;
    padding-left: 12px;
}

/* inputs */
.form-control {
    border-radius: 10px;
    padding: 10px 14px;
    border: 1px solid #D0D6E0;
    font-size: 0.95rem;
}

.form-control:focus {
    border-color: #4FA3FF;
    box-shadow: 0 0 0 0.2rem rgba(79,163,255,0.25);
}

/* botao */
.btn-cadastrar {
    background: #4FA3FF;
    border: none;
    padding: 12px 20px;
    border-radius: 12px;
    color: white;
    font-weight: 600;
    transition: 0.3s;
    font-size: 1rem;
}
.btn-cadastrar:hover {
    background: #1E8AF5;
    transform: translateY(-2px);
}

/* card icone */
.top-icon {
    font-size: 55px;
    color: #4FA3FF;
    opacity: .9;
}
</style>

</head>
<body>

<?php include('navbar.php'); ?>

<div class="container mt-4 mb-4">
    <div class="text-center mb-4">
        <i class="fas fa-book-open top-icon"></i>
        <h2 class="mt-3 form-title">Cadastro de Aluno</h2>
    </div>

    <div class="form-card mx-auto" style="max-width: 750px;">

        <form action="processa_cadastro.php" method="POST">

            <h5 class="fw-bold mb-3">Dados do Aluno</h5>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome_completo" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Curso</label>
                    <select class="form-control" name="curso" required>
                        <option value="">Selecione...</option>
                        <option>Informática</option>
                        <option>Administração</option>
                        <option>Enfermagem</option>
                        <option>Edificações</option>
                    </select>
                </div>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold mb-3">Endereço</h5>
            <div class="row g-3">

                <div class="col-md-4">
                    <label class="form-label">CEP</label>
                    <input type="text" name="cep" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rua</label>
                    <input type="text" name="rua" class="form-control" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Número</label>
                    <input type="number" name="numero" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bairro</label>
                    <input type="text" name="bairro" class="form-control" required>
                </div>

            </div>

            <hr class="my-4">

            <h5 class="fw-bold mb-3">Responsável</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome do Responsável</label>
                    <input type="text" name="nome_responsavel" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Tipo</label>
                    <select name="tipo_responsavel" class="form-control" required>
                        <option>PAI</option>
                        <option>MÃE</option>
                        <option>TUTOR</option>
                    </select>
                </div>
            </div>

            <div class="text-end mt-4">
                <button class="btn-cadastrar" type="submit">
                    <i class="fas fa-save me-2"></i>Salvar Cadastro
                </button>
            </div>

        </form>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
