<?php
    

    //  carrega as funções de autenticação (já faz session_start())
    require_once __DIR__ . '/../inc/autentica.php';

    //  destrói a sessão (sua função logout() chamaria session_unset() e session_destroy())
    logout();

    // redireciona para a tela de login
    header('Location: index.php');
    exit;
?>