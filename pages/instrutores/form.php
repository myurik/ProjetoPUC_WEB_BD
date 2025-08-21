<?php
    // autenticação e conexão
    require_once __DIR__ . '/../../inc/autentica.php';
    requireLogin();
    require_once __DIR__ . '/../../bd/conectaBD.php';

    //  busca padrão de edição
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $instrutor = [];
    $fotoData   = null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM instrutores WHERE id = ?');
        $stmt->execute([$id]);
        $instrutor = $stmt->fetch();
        $fotoData  = $instrutor['foto'] ?? null;
    }

    $listaEspecialidades = [
      'Musculação',
      'Funcional',
      'Pilates',
      'CrossFit',
      'Yoga',
      'Zumba'
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
    // 4) header
    $title     = $id ? 'Editar Instrutor' : 'Novo Instrutor';
    $bodyClass = '';
    $assetPath = '../../';
    require __DIR__ . '/../../inc/header.php';
?>

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title mb-4"><?= $title ?></h3>

          <form action="index.php<?= $id ? "?id={$id}" : '' ?>" method="post" class="needs-validation" enctype="multipart/form-data" novalidate>
          <?php if ($id): ?>
            <input type="hidden" name="id" value="<?= $id ?>">
          <?php endif; ?>
          <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger">
              <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
          <?php endif; ?>
            <div class="row">
              <!-- Nome -->
              <div class="col-12 col-md-6 mb-3">
                <label for="nome" class="form-label">Nome <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="nome"
                  name="nome"
                  class="form-control"
                  value="<?= htmlspecialchars($instrutor['nome'] ?? '') ?>"
                  required
                >
              </div>

              <!-- Data de Nascimento -->
            <div class="col-12 col-md-6 mb-3">
              <label for="data_nascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
              <?php $maxInstrutor = date('Y-m-d', strtotime('-18 years'));?>
              <input
                type="date"
                id="data_nascimento"
                name="data_nascimento"
                class="form-control"
                value="<?= htmlspecialchars($instrutor['data_nascimento'] ?? '') ?>"
                max="<?= $maxInstrutor ?>"
                required
              >
              <div class="invalid-feedback">
                Instrutor deve ter no mínimo 18 anos. (Máx: <?= date('d/m/Y', strtotime($maxInstrutor)) ?>)
              </div>
            </div>

              <!-- Especialidade -->
              <div class="col-12 col-md-6 mb-3">
                <label for="especialidade" class="form-label">Especialidade <span class="text-danger">*</span></label>
                <select
                  id="especialidade"
                  name="especialidade"
                  class="form-select"
                  required
                >
                  <option value="" disabled <?= empty($instrutor['especialidade']) ? 'selected' : '' ?>>
                    — Selecione —
                  </option>
                  <?php foreach($listaEspecialidades as $esp): ?>
                  <option
                    value="<?= htmlspecialchars($esp) ?>"
                    <?= (isset($instrutor['especialidade']) && $instrutor['especialidade']===$esp) ? 'selected' : '' ?>
                  >
                    <?= htmlspecialchars($esp) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row">
              <!-- E-mail -->
              <div class="col-12 col-md-6 mb-3">
                <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control"
                  value="<?= htmlspecialchars($instrutor['email'] ?? '') ?>"
                  required
                >
              </div>

              <!-- Telefone -->
              <div class="col-12 col-md-6 mb-3">
                <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                <input
                  type="tel"
                  id="telefone"
                  name="telefone"
                  class="form-control"
                  pattern="\(\d{2}\)\d{5}-\d{4}"
                  placeholder="(DD)99999-0000"
                  value="<?= htmlspecialchars($instrutor['telefone'] ?? '') ?>"
                  required
                >
              </div>
            </div>

            <div class="row">
              <!-- Biografia -->
              <div class="col-12 col-md-6 mb-3">
                <label for="biografia" class="form-label">Biografia</label>
                <textarea
                  id="biografia"
                  name="biografia"
                  class="form-control"
                  rows="3"
                ><?= htmlspecialchars($instrutor['biografia'] ?? '') ?></textarea>
              </div>

              <!-- Disponibilidade -->
              <div class="col-12 col-md-6 mb-3">
                <label for="disponibilidade" class="form-label">Disponibilidade</label>
                <select
                  id="disponibilidade"
                  name="disponibilidade"
                  class="form-select"
                >
                  <option value="" disabled <?= empty($instrutor['disponibilidade']) ? 'selected' : '' ?>>
                    — Selecione —
                  </option>
                  <?php foreach($listaDisponibilidade as $hora): ?>
                  <option
                    value="<?= htmlspecialchars($hora) ?>"
                    <?= (isset($instrutor['disponibilidade']) && $instrutor['disponibilidade']===$hora) ? 'selected' : '' ?>
                  >
                    <?= htmlspecialchars($hora) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row">
              <!-- Foto -->
              <div class="col-12 col-md-6 mb-3">
                <label for="foto" class="form-label">Foto</label>
                <input
                  type="file"
                  id="foto"
                  name="foto"
                  class="form-control"
                  accept="image/*"
                  <?= $id ? '' : 'required' ?>
                >
              </div>

              <!-- Preview da foto atual -->
              <?php if ($fotoData): ?>
              <div class="col-md-6 mb-4 d-flex align-items-center">
                <img
                  src="data:image/jpeg;base64,<?= base64_encode($fotoData) ?>"
                  class="img-thumbnail"
                  style="max-width:150px"
                  alt="Foto existente"
                >
              </div>
              <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">
              <?= $id ? 'Salvar' : 'Cadastrar' ?>
            </button>
            <a href="index.php" class="btn btn-secondary ms-2">
              Cancelar
            </a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
