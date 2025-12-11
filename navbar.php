
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body {
        font-family: 'Montserrat', sans-serif !important;
    }

    
    .navbar-custom {
        background: #1E2A38;
        padding: 12px 20px;
        border-bottom: 3px solid #4FA3FF;
        font-family: 'Montserrat', sans-serif;
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.2rem;
        color: #E8EEF3 !important;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .navbar-custom .nav-link {
        color: #DCE7F1 !important;
        padding: 10px 16px;
        border-radius: 8px;
        transition: 0.25s ease;
        font-weight: 500;
    }

    .navbar-custom .nav-link:hover {
        background: #2E3C4C;
        color: #fff !important;
    }

    .dropdown-menu {
        background: #1E2A38;
        border-radius: 10px;
        border: none;
    }

    .dropdown-item {
        color: #E8EEF3;
        padding: 10px 18px;
        transition: 0.25s;
    }

    .dropdown-item:hover {
        background: #2E3C4C;
        color: #fff;
    }

    .navbar-toggler {
        border-color: #E8EEF3;
    }

    .navbar-toggler-icon {
        filter: brightness(0) invert(1);
    }
</style>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">

        <a class="navbar-brand" href="painel.php">
            <i class="fas fa-book-open" style="font-size: 22px;"></i>
            Sistema Acadêmico
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="painel.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="formulario_aluno.php">
                        <i class="fas fa-user-plus"></i> Cadastrar Aluno
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="visualizar_alunos.php">
                        <i class="fas fa-list"></i> Ver Alunos
                    </a>
                </li>

                
                <li class="nav-item">
                    <a class="nav-link" href="relatorios.php">
                        <i class="fas fa-chart-bar"></i> Relatórios
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                       data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?= $_SESSION['email'] ?? 'Usuário' ?>
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="logout.php">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>
</nav>


<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">