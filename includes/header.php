<?php require_once __DIR__ . '/functions.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title ?? 'Inventaris') ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
<h1>📦 CRUD Inventaris</h1>
<?php show_flash(); ?>
