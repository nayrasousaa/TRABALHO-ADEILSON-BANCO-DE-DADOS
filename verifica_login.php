<?php
// verifica_login.php
// Garante que a sessão esteja iniciada antes de verificar $_SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se não houver usuário logado, redireciona para a página de login
if (empty($_SESSION['email'])) {
    header('Location: index.php');
    exit();
}
?>
