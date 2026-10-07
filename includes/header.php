<?php
/**
 * header.php
 * Expects: $pageTitle (string), $pageCss (string[] extra stylesheets, optional)
 */
$pageTitle = $pageTitle ?? APP_NAME;
$pageCss   = $pageCss ?? ['auth.css'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?> · <?= e(APP_NAME) ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Joan&family=Judson:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/base.css">
<?php foreach ($pageCss as $css): ?>
    <link rel="stylesheet" href="assets/css/<?= e($css) ?>">
<?php endforeach; ?>
</head>
<body>
<main class="page">
