<?php

require_once __DIR__ . '/../bd/conectaBD.php';
require_once __DIR__ . '/../inc/autentica.php';



// Título da página
$title = 'Sobre a Academia Esportiva';
$bodyClass = '';
$assetPath = '../';  
include __DIR__ . '/../inc/header.php';
?>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-secondary" onclick="window.location.href='../index.php'">
        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
        </button>
        <a href="logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Sair
        </a>
    </div>
  <!-- DADOS DO TRABALHO -->
  <section class="mb-4">
    <h2>Dados do Trabalho</h2>
    <p><strong>Autor:</strong> Matheus Yuri Franco Miguel</p>
    <p><strong>Disciplina:</strong> Fundamentos de Programação para Web</p>
    <p><strong>Instituição:</strong> PUC-PR</p>
    <p><strong>Professor:</strong> JULIANO SARTORI LANGARO</p>
    <p><strong>Data de Entrega:</strong>  07/07/2025</p>
  </section>
    <hr>
  <!-- CRÉDITOS -->
  <section class="mb-4">
    <h2>Créditos</h2>
    <ul>
      <li>Framework front-end: <a href="https://getbootstrap.com" target="_blank">Bootstrap</a></li>
      <li>Biblioteca de ícones: <a href="https://icons.getbootstrap.com" target="_blank">Bootstrap Icons</a></li>
      <li>Arte e imagens: <a href="https://getavataaars.com">Avataaars</a></li>
    </ul>
  </section>
    <hr>
   <!-- RESUMO DO SITE -->
  <section>
    <h2>Resumo do Site</h2>
    <p>Este sistema “Academia Esportiva” foi desenvolvido como parte da disciplina de Fundamentos de Programação para Web da PUC-PR.
        <br> <strong>Funcionalidades:</strong>
    </p>
    <ul>
      <li>Cadastro e gerenciamento de membros (alunos) da academia;</li>
      <li>Cadastro e gerenciamento de instrutores e suas especialidades;</li>
      <li>Autenticação de usuários e controle de acesso;</li>
      <li>Interface responsiva construída com Bootstrap para facilitar o uso em dispositivos móveis.</li>
    </ul>
  </section>
</main>

<?php include __DIR__ . '/../inc/footer.php'; ?>
