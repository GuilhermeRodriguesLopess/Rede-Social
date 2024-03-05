<?php
session_start(); // Inicie a sessão

// Destrua a sessão existente
session_destroy();

// Redirecione o usuário de volta para a página de login (ou qualquer outra página de sua escolha)
header("Location: login.html");
exit();
?>
