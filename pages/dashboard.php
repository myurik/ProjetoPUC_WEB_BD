<?php
    require_once __DIR__ . '/../inc/autentica.php';
    requireLogin();
    require_once __DIR__ . '/../bd/conectaBD.php';

    $title     = 'Dashboard';
    $bodyClass = '';
    $assetPath = '../';                           
    require __DIR__ . '/../inc/header.php';
?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <!-- Sair -->
    <a href="logout.php" class="btn btn-outline-danger">
      <i class="bi bi-box-arrow-right"></i> Sair
    </a>
  </div>

  <h1><?= htmlspecialchars($title) ?></h1>
  <p>Aqui você pode acessar as páginas protegidas:</p>
  <ul>
    <li>
      <a href="instrutores/index.php">
        Gestão de Instrutores
      </a>
    </li>
    <li>
      <a href="membros/index.php">
        Gestão de Membros
      </a>
    </li>
    <li>
      <a href="sobre.php">
        Sobre
      </a>
    </li>
  </ul>
</div>

<?php require __DIR__ . '/../inc/footer.php'; ?>
