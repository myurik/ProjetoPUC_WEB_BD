<?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Montserrat:wght@600;700&display=swap" rel="stylesheet"/>
  <title><?= htmlspecialchars($title ?? 'Academia') ?></title>
  <link
    href="<?= $assetPath ?? '' ?>assets/css/bootstrap.min.css"
    rel="stylesheet"
  >
  <!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-…" crossorigin="anonymous"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="<?= $assetPath ?>assets/css/custom.css">
</head>
<body class="<?= htmlspecialchars($bodyClass ?? '') ?>">
