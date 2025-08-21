<?php

require_once __DIR__ . '/../../inc/autentica.php';
requireLogin();
require_once __DIR__ . '/../../bd/conectaBD.php';

//  INSERÇÃO / ATUALIZAÇÃO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome         = trim($_POST['nome'] ?? '');
    $data_nasc    = $_POST['data_nascimento'] ?? null;
    $email        = trim($_POST['email'] ?? '');
    $telefone     = trim($_POST['telefone'] ?? '');
    $instrutor_id = filter_input(INPUT_POST, 'instrutor_id', FILTER_VALIDATE_INT);
    $status       = $_POST['status'] ?? '';
    $plano        = $_POST['plano'] ?? '';
    $data_inicio  = $_POST['data_inicio'] ?? null;
    $endereco     = trim($_POST['endereco'] ?? '');
    $emerg_nome   = trim($_POST['emergencia_nome'] ?? '');
    $emerg_tel    = trim($_POST['emergencia_telefone'] ?? '');
    $observacoes  = trim($_POST['observacoes'] ?? '');
    
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
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
    if ($idade < 15) {
        $_SESSION['error'] = "Membro deve ter no mínimo 15 anos (idade atual: {$idade}).";
        header('Location: form.php' . ($id ? "?id=$id" : ''));
        exit;
    }

    $fotoBin      = !empty($_FILES['foto']['tmp_name'])
                    ? file_get_contents($_FILES['foto']['tmp_name'])
                    : null;

    
    if ($id) {
        // UPDATE
        $sql = "UPDATE membros
                   SET nome=?, data_nascimento=?, email=?, telefone=?,
                       instrutor_id=?, status=?, endereco=?, emergencia_nome=?,
                       emergencia_telefone=?, plano=?, observacoes=?,
                       data_inicio=?
                       " . ($fotoBin!==null ? ", foto=?" : "") . "
                 WHERE id=?";
        $stmt = $pdo->prepare($sql);
        $params = [
            $nome, $data_nasc, $email, $telefone,
            $instrutor_id, $status, $endereco,
            $emerg_nome, $emerg_tel, $plano,
            $observacoes, $data_inicio
        ];
        if ($fotoBin !== null) {
            $params[] = $fotoBin;
        }
        $params[] = $id;
        $stmt->execute($params);
    } else {
        // INSERT
        $sql = "INSERT INTO membros
                  (nome, data_nascimento, email, telefone,
                   instrutor_id, foto, status, endereco,
                   emergencia_nome, emergencia_telefone,
                   plano, observacoes, criado_em, data_inicio)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?, NOW(),?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nome, $data_nasc, $email, $telefone,
            $instrutor_id, $fotoBin, $status, $endereco,
            $emerg_nome, $emerg_tel, $plano,
            $observacoes, $data_inicio
        ]);
    }

    header('Location: index.php');
    exit;
}

//  DELETE
if (!empty($_GET['delete'])) {
    $pdo->prepare("DELETE FROM membros WHERE id = ?")
        ->execute([$_GET['delete']]);
    $_SESSION['success'] = "Membro excluído com sucesso.";
    header('Location: index.php');
    exit;
}

//  BUSCA COM JOIN
$sql = "
  SELECT m.*, i.nome AS instrutor_nome
    FROM membros m
    LEFT JOIN instrutores i ON m.instrutor_id = i.id
   ORDER BY m.id
";
$membros = $pdo->query($sql)->fetchAll();

//  LISTA PARA FILTRO
$listaInstrutores = $pdo
  ->query("SELECT nome FROM instrutores ORDER BY nome")
  ->fetchAll();

// HEADER
$title     = 'Gestão de Membros';
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
    <button type="button" class="btn btn-secondary"
            onclick="window.location.href='../index.php'">
      <i class="fa-solid fa-arrow-left me-1"></i> Voltar
    </button>
    <a href="logout.php" class="btn btn-outline-danger">
      <i class="fa-solid fa-box-arrow-right me-1"></i> Sair
    </a>
  </div>

  <a class="navbar-brand" href="<?= $assetPath ?>pages/index.php"><h1>Dashboard</h1></a>
  <br>
  <h2><i class="fa-solid fa-dumbbell me-2"></i>Membros</h2>
  <a href="form.php" class="btn btn-success mb-3">
    <i class="fa-solid fa-user-plus me-1"></i> Novo Membro
  </a>

  <!-- filtros -->
  <div class="row mb-3">
    <div class="col-12 col-md-3 mb-2">
      <input id="filtroNome" type="text" class="form-control" placeholder="Pesquisar por nome">
    </div>
    <div class="col-6 col-md-3 mb-2">
      <select id="filtroStatus" class="form-select">
        <option value="">Todos Status</option>
        <option>Ativo</option>
        <option>Inativo</option>
      </select>
    </div>
    <div class="col-6 col-md-3 mb-2">
      <select id="filtroPlano" class="form-select">
        <option value="">Todos Planos</option>
        <option>Mensal</option>
        <option>Trimestral</option>
        <option>Anual</option>
      </select>
    </div>
    <div class="col-12 col-md-3 mb-2">
      <select id="filtroInstrutor" class="form-select">
        <option value="">Todos Instrutores</option>
        <?php foreach($listaInstrutores as $ins): ?>
          <option><?= htmlspecialchars($ins['nome']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <!-- TABELA -->
  <div>
    <table id="membrosTable" class="table table-hover align-middle">
      <thead class="table-light">
        <tr>
          <th>Foto</th>
          <th>ID</th>
          <th>Nome</th>
          <th>Status</th>
          <th>Plano</th>
          <th>Início</th>
          <th>E-mail</th>
          <th>Telefone</th>
          <th>Instrutor</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($membros)): ?>
          <tr>
            <td colspan="10" class="text-center">Nenhum membro cadastrado.</td>
          </tr>
        <?php else: foreach($membros as $m):
          $mime = $m['foto']
            ? getimagesizefromstring($m['foto'])['mime']
            : null;
        ?>
        <tr>
          <td style="width:80px">
            <?php if ($mime): ?>
              <img src="data:<?= $mime ?>;base64,<?= base64_encode($m['foto']) ?>"
                   class="img-thumbnail" style="max-width:60px"
                   alt="Foto de <?= htmlspecialchars($m['nome']) ?>">
            <?php endif; ?>
          </td>
          <td><?= $m['id'] ?></td>
          <td><?= htmlspecialchars($m['nome']) ?></td>
          <td>
            <span class="badge <?= $m['status']==='Ativo'?'bg-success':'bg-danger' ?>">
              <?= htmlspecialchars($m['status']) ?>
            </span>
          </td>
          <td><?= htmlspecialchars($m['plano']) ?></td>
          <td><?= date('d/m/Y', strtotime($m['data_inicio'])) ?></td>
          <td><?= htmlspecialchars($m['email']) ?></td>
          <td><?= htmlspecialchars($m['telefone']) ?></td>
          <td><?= htmlspecialchars($m['instrutor_nome']) ?></td>
          <td>
            <button class="btn btn-sm btn-info me-1"
                    data-bs-toggle="modal"
                    data-bs-target="#verMembro<?= $m['id'] ?>">
              <i class="fa-regular fa-eye"></i>
            </button>
            <a href="form.php?id=<?= $m['id'] ?>" class="btn btn-sm btn-primary me-1">
              <i class="fa-solid fa-pencil"></i>
            </a>
            <a href="?delete=<?= $m['id'] ?>"
               onclick="return confirm('Excluir este membro?')"
               class="btn btn-sm btn-danger">
              <i class="fa-solid fa-trash"></i>
            </a>
          </td>
        </tr>

        <!-- Modal detalhes -->
        <div class="modal fade" id="verMembro<?= $m['id'] ?>" tabindex="-1">
          <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"><?= htmlspecialchars($m['nome']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <?php if ($mime): ?>
                <div class="text-center mb-3">
                  <img src="data:<?= $mime ?>;base64,<?= base64_encode($m['foto']) ?>"
                       class="img-thumbnail" style="max-width:150px"
                       alt="Foto de <?= htmlspecialchars($m['nome']) ?>">
                </div>
                <?php endif; ?>
                <dl class="row">
                  <dt class="col-sm-3">Data Início</dt>
                  <dd class="col-sm-9"><?= date('d/m/Y', strtotime($m['data_inicio'])) ?></dd>
                  <dt class="col-sm-3">Data Nasc.</dt>
                  <dd class="col-sm-9"><?= date('d/m/Y', strtotime($m['data_nascimento'])) ?></dd>
                  <dt class="col-sm-3">E-mail</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($m['email']) ?></dd>
                  <dt class="col-sm-3">Telefone</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($m['telefone']) ?></dd>
                  <dt class="col-sm-3">Instrutor</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($m['instrutor_nome']) ?></dd>
                  <dt class="col-sm-3">Status</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($m['status']) ?></dd>
                  <dt class="col-sm-3">Plano</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($m['plano']) ?></dd>
                  <dt class="col-sm-3">Endereço</dt>
                  <dd class="col-sm-9"><?= htmlspecialchars($m['endereco']) ?></dd>
                  <dt class="col-sm-3">Cont. Emergência</dt>
                  <dd class="col-sm-9">
                    <?= htmlspecialchars($m['emergencia_nome']) ?> —
                    <?= htmlspecialchars($m['emergencia_telefone']) ?>
                  </dd>
                  <dt class="col-sm-3">Observações</dt>
                  <dd class="col-sm-9"><?= nl2br(htmlspecialchars($m['observacoes'])) ?></dd>
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
