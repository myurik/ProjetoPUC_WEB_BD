<?php 
    require_once __DIR__ . '/../inc/autentica.php';

    require_once __DIR__ . '/../bd/conectaBD.php';   
?>
<nav class="navbar navbar-expand-md navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Academia</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNavbar">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="fa-regular fa-user"></i> Login
          </button>
        </li>
        <li class="nav-item">
          <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#registerModal">
            <i class="fa-regular fa-user-plus"></i> Cadastro
          </button>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-3">
  <?php if ($registerSuccess): ?>
    <div class="alert alert-success d-flex align-items-center" role="alert">
      <div><?= htmlspecialchars($registerSuccess) ?></div>
      <button
        class="btn btn-sm btn-primary ms-auto"
        data-bs-toggle="modal"
        data-bs-target="#loginModal"
      >Ir para Login</button>
    </div>
  <?php endif; ?>
</div>

<main class="flex-fill d-flex justify-content-center align-items-center">
  <div class="text-center">
    <h1>Bem-vindo à Academia</h1>
    <p>Para continuar, faça login ou cadastre-se.</p>
  </div>
</main>

<!-- LOGIN MODAL -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <form method="post" class="needs-validation" novalidate>
      <input type="hidden" name="action" value="login">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if ($loginError): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($loginError) ?></div>
        <?php endif; ?>

        <!-- Usuário -->
        <div class="mb-3">
          <label for="login-usuario" class="form-label">Usuário</label>
          <input
            id="login-usuario"
            name="nome"
            type="text"
            class="form-control"
            required
          >
          <div class="invalid-feedback">Informe o usuário.</div>
        </div>

        <!-- Senha  -->
        <div class="mb-3">
          <label for="login-senha" class="form-label">Senha</label>
          <div class="input-group">
            <input id="login-senha" name="senha" type="password" class="form-control" required>
            <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha('login-senha', this)">
              <i class="bi bi-eye"></i>
            </button>
          </div>
          <div class="invalid-feedback">Informe a senha.</div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Entrar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </form>
  </div></div>
</div>

<!-- CADASTRO MODAL-->
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" class="needs-validation" novalidate>
        <input type="hidden" name="action" value="register">

        <div class="modal-header">
          <h5 class="modal-title">Cadastro de Administrador</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <?php if ($registerError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($registerError) ?></div>
          <?php endif; ?>

          <div class="mb-3">
            <label for="reg-usuario" class="form-label">Usuário</label>
            <input id="reg-usuario" name="nome" type="text" class="form-control" required>
            <div class="invalid-feedback">Informe o usuário.</div>
          </div>

          <div class="mb-3">
            <label for="reg-senha" class="form-label">Senha</label>
            <div class="input-group">
              <input id="reg-senha" name="senha" type="password" class="form-control" required>
              <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha('reg-senha', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <div class="invalid-feedback">Informe a senha.</div>
          </div>

          <div class="mb-3">
            <label for="reg-confirma" class="form-label">Confirmar Senha</label>
            <div class="input-group">
              <input id="reg-confirma" name="confirmar_senha" type="password" class="form-control" required>
              <button type="button" class="btn btn-outline-secondary" onclick="toggleSenha('reg-confirma', this)">
                <i class="bi bi-eye"></i>
              </button>
            </div>
            <div class="invalid-feedback">As senhas devem coincidir.</div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Cadastrar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
        </div>
      </form>
    </div>
  </div>
</div>


<?php
// Se houve erro, reabre o modal correspondente
if ($loginError): ?>
  <script>bootstrap.Modal.getOrCreateInstance('#loginModal').show()</script>
<?php elseif($registerError): ?>
  <script>bootstrap.Modal.getOrCreateInstance('#registerModal').show()</script>
<?php endif; ?>

<?php require __DIR__ . '/../inc/footer.php'; ?>

