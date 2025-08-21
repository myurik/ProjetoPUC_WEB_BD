<?php
    
    session_start();

    require_once __DIR__ . '/../bd/conectaBD.php';
    require_once __DIR__ . '/../inc/autentica.php';

    if (isLoggedIn()) {
      header('Location: dashboard.php');
      exit;
    }

    $loginError     = '';
    $registerError  = '';
    $registerSuccess = '';

    // se não está logado e veio POST, trata login/cadastro
    if (!isLoggedIn() && $_SERVER['REQUEST_METHOD'] === 'POST') {

      // LOGIN
      if (($_POST['action'] ?? '') === 'login') {
        $userInput = trim($_POST['nome'] ?? '');
        $passInput = $_POST['senha'] ?? '';

        $stmt = $pdo->prepare("SELECT id,senha FROM usuarios WHERE nome = ?");
        $stmt->execute([$userInput]);
        $u = $stmt->fetch();
        if ($u && password_verify($passInput, $u['senha'])) {
          $_SESSION['user_id'] = $u['id'];
          header('Location: index.php');
          exit;
        }
        $loginError = 'Usuário ou senha inválidos.';
      }

      // CADASTRO
      if (($_POST['action'] ?? '') === 'register') {
        $userInput = trim($_POST['nome'] ?? '');
        $passInput = $_POST['senha'] ?? '';
        $pass2     = $_POST['confirmar_senha'] ?? '';

        if (!$userInput || !$passInput || !$pass2) {
          $registerError = 'Todos os campos são obrigatórios.';
        } elseif ($passInput !== $pass2) {
          $registerError = 'As senhas não conferem.';
        } else {
          $hash = password_hash($passInput, PASSWORD_DEFAULT);
          $stmt = $pdo->prepare("INSERT INTO usuarios (nome,senha) VALUES (?,?)");
          try {
            $stmt->execute([$userInput, $hash]);
            $registerSuccess = 'Usuário cadastrado com sucesso!';
          } catch (PDOException $e) {
            $registerError = ($e->getCode()==='23000')
              ? 'Este usuário já existe.'
              : 'Erro no cadastro: '.$e->getMessage();
          }
        }
      }
    }

    // título e classe do <body>
    $title     = 'Academia · Sistema';
    $bodyClass = isLoggedIn() ? 'flex-fill p-4' : 'd-flex flex-column vh-100';

    // inclui header comum
    $assetPath = '../';  
    require __DIR__ . '/../inc/header.php';


    // decide qual view exibir
    if (isLoggedIn()) {
      // busca nome para dashboard
      $stmt = $pdo->prepare("SELECT nome FROM usuarios WHERE id = ?");
      $stmt->execute([ $_SESSION['user_id'] ]);
      $user = $stmt->fetchColumn();
      require __DIR__ . '/dashboard.php';

    } else {
      require __DIR__ . '/home.php';
    }

   
?>