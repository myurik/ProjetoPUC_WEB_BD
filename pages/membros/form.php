<?php
    //  autenticação e conexão
    require_once __DIR__ . '/../../inc/autentica.php';
    requireLogin();
    require_once __DIR__ . '/../../bd/conectaBD.php';

    //  busca padrão de edição
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $membro    = [];
    $fotoDataM = null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM membros WHERE id = ?');
        $stmt->execute([$id]);
        $membro    = $stmt->fetch();
        $fotoDataM = $membro['foto'] ?? null;
    }

    //  listas para selects
    // instrutores (id + nome)
    $listaInstrutores = $pdo
      ->query('SELECT id, nome FROM instrutores ORDER BY nome')
      ->fetchAll();
    // status e planos (static)
    $listaStatus = ['Ativo','Inativo'];
    $listaPlanos = ['Mensal','Trimestral','Anual'];
    
    // header
    $title     = $id ? 'Editar Membro' : 'Novo Membro';
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

          <form action="index.php<?= $id ? "?id={$id}" : '' ?>"method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
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
                  value="<?= htmlspecialchars($membro['nome'] ?? '') ?>"
                  required
                >
              </div>

              <!-- Data de Nascimento -->
              <div class="col-12 col-md-6 mb-3">
                <label for="data_nascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                <?php $maxMembro = date('Y-m-d', strtotime('-15 years'));?>
                <input
                  type="date"
                  id="data_nascimento"
                  name="data_nascimento"
                  class="form-control"
                  value="<?= htmlspecialchars($membro['data_nascimento'] ?? '') ?>"
                  max="<?= $maxMembro ?>"
                  required
                >
              <div class="invalid-feedback">
                Instrutor deve ter no mínimo 15 anos. (Máx: <?= date('d/m/Y', strtotime($maxInstrutor)) ?>)
              </div>
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
                  value="<?= htmlspecialchars($membro['email'] ?? '') ?>"
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
                  value="<?= htmlspecialchars($membro['telefone'] ?? '') ?>"
                  required
                >
              </div>
            </div>

            <div class="row">
              <!-- Instrutor -->
              <div class="col-12 col-md-6 mb-3">
                <label for="instrutor_id" class="form-label">Instrutor <span class="text-danger">*</span></label>
                <select
                  id="instrutor_id"
                  name="instrutor_id"
                  class="form-select"
                  required
                >
                  <option value="" disabled <?= empty($membro['instrutor_id']) ? 'selected' : '' ?>>
                    — Selecione —
                  </option>
                  <?php foreach($listaInstrutores as $i): ?>
                  <option
                    value="<?= $i['id'] ?>"
                    <?= (isset($membro['instrutor_id']) && $membro['instrutor_id']==$i['id']) ? 'selected' : '' ?>
                  >
                    <?= htmlspecialchars($i['nome']) ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Status -->
              <div class="col-12 col-md-6 mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select
                  id="status"
                  name="status"
                  class="form-select"
                  required
                >
                  <option value="" disabled <?= empty($membro['status']) ? 'selected' : '' ?>>
                    — Selecione —
                  </option>
                  <?php foreach($listaStatus as $s): ?>
                  <option
                    value="<?= $s ?>"
                    <?= (isset($membro['status']) && $membro['status']===$s) ? 'selected' : '' ?>
                  >
                    <?= $s ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="row">
              <!-- Plano -->
              <div class="col-12 col-md-6 mb-3">
                <label for="plano" class="form-label">Plano <span class="text-danger">*</span></label>
                <select
                  id="plano"
                  name="plano"
                  class="form-select"
                  required
                >
                  <option value="" disabled <?= empty($membro['plano']) ? 'selected' : '' ?>>
                    — Selecione —
                  </option>
                  <?php foreach($listaPlanos as $p): ?>
                  <option
                    value="<?= $p ?>"
                    <?= (isset($membro['plano']) && $membro['plano']===$p) ? 'selected' : '' ?>
                  >
                    <?= $p ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Data de Início -->
              <div class="col-12 col-md-6 mb-3">
                <label for="data_inicio" class="form-label">Data de Início <span class="text-danger">*</span></label>
                <input
                  type="date"
                  id="data_inicio"
                  name="data_inicio"
                  class="form-control"
                  value="<?= htmlspecialchars($membro['data_inicio'] ?? '') ?>"
                  required
                >
              </div>
            </div>

            <div class="row">
              <!-- Endereço -->
              <div class="col-12 col-md-6 mb-3">
                <label for="endereco" class="form-label">Endereço <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="endereco"
                  name="endereco"
                  class="form-control"
                  value="<?= htmlspecialchars($membro['endereco'] ?? '') ?>"
                  required
                >
              </div>

              <!-- Contato de Emergência -->
              <div class="col-12 col-md-6 mb-3">
                <label for="emergencia_nome" class="form-label">Contato de Emergência <span class="text-danger">*</span></label>
                <input
                  type="text"
                  id="emergencia_nome"
                  name="emergencia_nome"
                  class="form-control"
                  value="<?= htmlspecialchars($membro['emergencia_nome'] ?? '') ?>"
                  required
                >
              </div>
            </div>

            <div class="row">
              <!-- Telefone Emergência -->
              <div class="col-12 col-md-6 mb-3">
                <label for="emergencia_telefone" class="form-label">Telefone Emergência <span class="text-danger">*</span></label>
                <input
                  type="tel"
                  id="emergencia_telefone"
                  name="emergencia_telefone"
                  class="form-control"
                  pattern="\(\d{2}\)\d{5}-\d{4}"
                  placeholder="(DD)99999-0000"
                  value="<?= htmlspecialchars($membro['emergencia_telefone'] ?? '') ?>"
                  required
                >
              </div>

              <!-- Foto do Membro -->
              <div class="col-12 col-md-6 mb-3">
                <label for="foto" class="form-label">Foto do Membro <?= $id ? '' : '<span class="text-danger">*</span>' ?></label>
                <input
                  type="file"
                  id="foto"
                  name="foto"
                  class="form-control"
                  accept="image/*"
                  <?= $id ? '' : 'required' ?>
                >
              </div>
            </div>

            <?php if ($fotoDataM): ?>
            <div class="row mb-4">
              <div class="col-12">
                <p>Foto atual:</p>
                <img
                  src="data:image/jpeg;base64,<?= base64_encode($fotoDataM) ?>"
                  class="img-thumbnail"
                  style="max-width:150px"
                  alt="Foto existente"
                >
              </div>
            </div>
            <?php endif; ?>

            <div class="row">
              <!-- Observações  -->
              <div class="col-12 col-md-6 mb-3">
                <label for="observacoes" class="form-label">Observações</label>
                <textarea
                  id="observacoes"
                  name="observacoes"
                  class="form-control"
                  rows="3"
                ><?= htmlspecialchars($membro['observacoes'] ?? '') ?></textarea>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">
              <?= $id ? 'Salvar' : 'Cadastrar' ?>
            </button>
            <a href="index.php" class="btn btn-secondary ms-2">Cancelar</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
