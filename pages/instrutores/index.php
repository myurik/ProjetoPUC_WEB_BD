<?php

require_once __DIR__ . '/../../inc/autentica.php';
requireLogin();
require_once __DIR__ . '/../../bd/conectaBD.php';


$listaEspecialidades = [
    'Musculação','Funcional','Pilates',
    'CrossFit','Yoga','Zumba'
];
$listaDisponibilidade = [
    '06:00-08:00',
    '08:00-10:00',
    '10:00-12:00',
    '14:00-16:00',
    '16:00-18:00',
    '18:00-20:00',
    '20:00-22:00'
];

//  INSERÇÃO / ATUALIZAÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome            = trim($_POST['nome'] ?? '');
    $data_nasc       = $_POST['data_nascimento'] ?? null;
    $especialidade   = trim($_POST['especialidade'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $telefone        = trim($_POST['telefone'] ?? '');
    $biografia       = trim($_POST['biografia'] ?? '');
    $disponibilidade = trim($_POST['disponibilidade'] ?? '');
    
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    //  Validação de idade mínima (18 anos)
    $dob = DateTime::createFromFormat('Y-m-d', $data_nasc);
    $today = new DateTime();
    if (!$dob) {
        $_SESSION['error'] = "Data de nascimento inválida.";
        header('Location: form.php' . ($id ? "?id=$id" : ''));
        exit;
    }
    if ($dob > $today) {
      $_SESSION['error'] = "Data de nascimento não pode ser no futuro.";
      header('Location: form.php' . ($id ? "?id={$id}" : ''));
      exit;
    }
    $idade = $dob->diff($today)->y;
    if ($idade < 18) {
        $_SESSION['error'] = "Instrutor deve ter no mínimo 18 anos (idade atual: {$idade}).";
        header('Location: form.php' . ($id ? "?id=$id" : ''));
        exit;
    }

    $fotoBin = !empty($_FILES['foto']['tmp_name'])
        ? file_get_contents($_FILES['foto']['tmp_name'])
        : null;

    
    if ($id) {
        $sql = "UPDATE instrutores
                   SET nome=?, data_nascimento=?, especialidade=?, email=?, telefone=?,
                       biografia=?, disponibilidade=?
                       " . ($fotoBin!==null ? ", foto=?" : "") . "
                 WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $params = [$nome,$data_nasc,$especialidade,$email,$telefone,$biografia,$disponibilidade];
        if ($fotoBin !== null) {
            $params[] = $fotoBin;
        }
        $params[] = $id;
        $stmt->execute($params);
    } else {
        $sql = "INSERT INTO instrutores
                  (nome,data_nascimento,especialidade,email,telefone,
                   biografia,disponibilidade,foto,criado_em)
                VALUES (?,?,?,?,?,?,?,?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome,$data_nasc,$especialidade,$email,$telefone,$biografia,$disponibilidade,$fotoBin]);
    }

    header('Location: index.php');
    exit;
}

// 2) DELETE 
if (!empty($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    // impede exclusão se houver membros
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM membros WHERE instrutor_id = ?");
    $stmt->execute([$id]);
    $count = (int)$stmt->fetchColumn();
    if ($count > 0) {
        $_SESSION['error'] = "Não é possível excluir este instrutor: {$count} membro(s) associado(s).";
    } else {
        $pdo->prepare("DELETE FROM instrutores WHERE id = ?")
            ->execute([$id]);
        $_SESSION['success'] = "Instrutor excluído com sucesso.";
    }
    header('Location: index.php');
    exit;
}

//  LISTAGEM
$stmt = $pdo->query("
    SELECT id,nome, data_nascimento,especialidade,email,telefone,
           disponibilidade,biografia,foto,criado_em
      FROM instrutores
     ORDER BY id
");
$instrutores = $stmt->fetchAll();

//  HEADER
$title     = 'Gestão de Instrutores';
$bodyClass = '';
$assetPath = '../../';
require __DIR__ . '/../../inc/header.php';
?>

<div class="container mt-4">
  <!-- alertas -->
  <?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
      <button class="btn btn-secondary" onclick="window.location.href='../index.php'">
        <i class="fa-solid fa-arrow-left me-1"></i> Voltar
      </button>
      <a href="logout.php" class="btn btn-outline-danger">
        <i class="bi bi-box-arrow-right"></i> Sair
      </a>
  </div>

  <a class="navbar-brand" href="<?= $assetPath ?>pages/index.php"><h1>Dashboard</h1></a>
  <br>
  <h2><i class="fa-solid fa-dumbbell me-2"></i>Instrutores</h2>
  <a href="form.php" class="btn btn-success mb-3">
    <i class="fa-solid fa-user-plus me-1"></i> Novo Instrutor
  </a>

  <!-- FILTROS -->
  <div class="row mb-3">
    <div class="col-12 col-md-4 mb-2">
      <input id="filtroNome"
             type="text"
             class="form-control"
             placeholder="Pesquisar por nome">
    </div>
    <div class="col-6 col-md-4 mb-2">
      <select id="filtroEspecialidade" class="form-select">
        <option value="">Todas Especialidades</option>
        <?php foreach($listaEspecialidades as $esp): ?>
          <option><?= htmlspecialchars($esp) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-6 col-md-4 mb-2">
      <select id="filtroDisponibilidade" class="form-select">
        <option value="">Todas Disponibilidades</option>
        <?php foreach($listaDisponibilidade as $disp): ?>
          <option><?= htmlspecialchars($disp) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- TABELA -->
  <div>
    <table id="instrutoresTable" class="table table-striped align-middle">
      <thead class="table-light">
        <tr>
          <th>Foto</th>
          <th>ID</th>
          <th>Nome</th>
          <th>Especialidade</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Disponibilidade</th>
          <th>Início</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($instrutores)): ?>
          <tr>
            <td colspan="9" class="text-center">Nenhum instrutor cadastrado.</td>
          </tr>
        <?php else: foreach($instrutores as $i):
          $mime = $i['foto']
            ? getimagesizefromstring($i['foto'])['mime']
            : null;
        ?>
        <tr>
          <td style="width:80px">
            <?php if ($mime): ?>
              <img src="data:<?= $mime ?>;base64,<?= base64_encode($i['foto']) ?>"
                   class="img-thumbnail" style="max-width:60px"
                   alt="Foto de <?= htmlspecialchars($i['nome']) ?>">
            <?php endif; ?>
          </td>
          <td><?= $i['id'] ?></td>
          <td><?= htmlspecialchars($i['nome']) ?></td>
          <td><?= htmlspecialchars($i['especialidade']) ?></td>
          <td><?= htmlspecialchars($i['email']) ?></td>
          <td><?= htmlspecialchars($i['telefone']) ?></td>
          <td><?= htmlspecialchars($i['disponibilidade']) ?></td>
          <td>
            <?= date('d/m/Y H:i', strtotime($i['criado_em'])) ?>
          </td>
          <td>
            <button class="btn btn-sm btn-info me-1"
                    data-bs-toggle="modal"
                    data-bs-target="#verInstrutor<?= $i['id'] ?>">
              <i class="fa-regular fa-eye"></i>
            </button>
            <a href="form.php?id=<?= $i['id'] ?>"
               class="btn btn-sm btn-primary me-1">
              <i class="fa-solid fa-pencil"></i>
            </a>
            <a href="?delete=<?= $i['id'] ?>"
               onclick="return confirm('Excluir este instrutor?')"
               class="btn btn-sm btn-danger">
              <i class="fa-solid fa-trash"></i>
            </a>
          </td>
        </tr>
        <!-- Modal visualizar -->
        <div class="modal fade" id="verInstrutor<?= $i['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($i['nome']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <?php if ($mime): ?>
                  <div class="text-center mb-3">
                    <img src="data:<?= $mime ?>;base64,<?= base64_encode($i['foto']) ?>"
                         class="img-thumbnail" style="max-width:150px"
                         alt="">
                  </div>
                <?php endif; ?>
                <dl class="row">
                  <dt class="col-sm-3">Início</dt>
                  <dd class="col-sm-9"><?= date('d/m/Y H:i', strtotime($i['criado_em'])) ?></dd>
                  <dt class="col-sm-3">Data Nasc.</dt>
                  <dd class="col-sm-9"><?= date('d/m/Y', strtotime($i['data_nascimento'])) ?></dd>
                  <dt class="col-sm-3">Especialidade</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($i['especialidade']) ?></dd>
                  <dt class="col-sm-3">E-mail</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($i['email']) ?></dd>
                  <dt class="col-sm-3">Telefone</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($i['telefone']) ?></dd>
                  <dt class="col-sm-3">Disponibilidade</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($i['disponibilidade']) ?></dd>
                  <dt class="col-sm-3">Biografia</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($i['biografia'] ?? '— Sem biografia —') ?></dd>
                </dl>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Fechar</button>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
require __DIR__ . '/../../inc/footer.php';
?>
