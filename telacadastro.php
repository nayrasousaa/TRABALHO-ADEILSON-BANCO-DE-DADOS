<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Cadastrar Usuário</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

	<style>
		body {
			font-family: 'Montserrat', sans-serif;
			background: linear-gradient(135deg, #0F2027, #203A43, #2C5364);
			height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.card-custom {
			border-radius: 20px;
			padding: 30px;
			box-shadow: 0 8px 20px rgba(0,0,0,0.25);
			background: #ffffff;
		}

		.card-custom h1 {
			font-weight: 700;
			color: #203A43;
		}

		.form-control {
			border-radius: 12px;
			padding: 12px;
			border: 1px solid #cdd6dd;
		}

		.btn-primary {
			background: #2C5364;
			border-radius: 12px;
			border: none;
			font-weight: 600;
			padding: 10px 18px;
		}
		.btn-primary:hover {
			background: #1e3c4d;
		}

		a {
			font-weight: 600;
			color: #2C5364;
		}
		a:hover {
			color: #1e3c4d;
		}
	</style>

</head>
<body>

<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-5">

			<div class="card card-custom">

				<h1 class="text-center mb-4">Criar Conta</h1>

				<?php if(isset($_SESSION['mensagem'])): ?>
					<div class="alert alert-info">
						<?= $_SESSION['mensagem']; ?>
					</div>
				<?php unset($_SESSION['mensagem']); endif; ?>

				<form action="cadastro.php" method="POST">

					<div class="mb-3">
						<label class="form-label">Nome</label>
						<input type="text" name="nome" class="form-control" required>
					</div>

					<div class="mb-3">
						<label class="form-label">E-mail</label>
						<input type="email" name="email" class="form-control" required>
					</div>

					<div class="mb-3">
						<label class="form-label">Senha</label>
						<input type="password" name="senha" class="form-control" required>
					</div>

					<button type="submit" class="btn btn-primary w-100 mt-3">Cadastrar</button>

				</form>

				<p class="text-center mt-3">
					Já possui conta? <a href="index.php">Entrar</a>
				</p>

			</div>

		</div>
	</div>
</div>

</body>
</html>
