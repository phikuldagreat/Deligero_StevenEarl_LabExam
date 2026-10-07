<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

require_login();

$user  = $_SESSION['user'];
$alert = flash_get();

$pageTitle = 'Dashboard';
$pageCss   = ['auth.css', 'dashboard.css'];
$pageJs    = [];
require __DIR__ . '/includes/header.php';
?>
<section class="card card--dashboard" aria-labelledby="page-title">
    <header class="card__header">
        <h1 id="page-title">Hello, <?= e($user['name']) ?></h1>
        <p>You're signed in to <?= e(APP_NAME) ?>.</p>
    </header>

    <div class="form-wrap">
        <?php require __DIR__ . '/includes/alert.php'; ?>

        <dl class="profile">
            <div><dt>Name</dt><dd><?= e($user['name']) ?></dd></div>
            <div><dt>Email</dt><dd><?= e($user['email']) ?></dd></div>
        </dl>

        <form method="post" action="logout.php" class="form">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn--primary">Log out</button>
        </form>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
