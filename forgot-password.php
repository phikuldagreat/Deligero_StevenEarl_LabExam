<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validation.php';

require_guest();

$errors = [];
$email  = '';
$alert  = null;

if (is_post()) {
    $email = strtolower(post_str('email'));

    if (!csrf_valid()) {
        $alert = ['type' => 'error', 'message' => 'Your session expired. Please try again.'];
    } elseif ($err = validate_email($email)) {
        $errors['email'] = $err;
        $alert = ['type' => 'error', 'message' => 'Please fix the highlighted field and try again.'];
    } else {
        // Same message whether or not the account exists, so emails can't be probed.
        // (Sending a real reset email would need a mail server, which is out of scope here.)
        $alert = ['type' => 'success', 'message' => 'If an account exists for that email, a reset link is on its way.'];
        $email = '';
    }
}

$pageTitle = 'Forgot password';
$pageCss   = ['auth.css'];
$pageJs    = [];
require __DIR__ . '/includes/header.php';
?>
<section class="card" aria-labelledby="page-title">
    <header class="card__header">
        <h1 id="page-title">Forgot password?</h1>
        <p>Enter your email and we'll send a reset link.</p>
    </header>

    <div class="form-wrap">
        <?php require __DIR__ . '/includes/alert.php'; ?>

        <form method="post" action="forgot-password.php" class="form" novalidate>
            <?= csrf_field() ?>

            <div class="field<?= isset($errors['email']) ? ' field--error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                       value="<?= e($email) ?>" autocomplete="email" maxlength="120"
                       aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>"
                       <?= isset($errors['email']) ? 'aria-describedby="email-error"' : '' ?>>
                <?php if (isset($errors['email'])): ?><p class="field__error" id="email-error"><?= e($errors['email']) ?></p><?php endif; ?>
            </div>

            <button type="submit" class="btn btn--primary">Send reset link</button>
        </form>
    </div>

    <p class="card__footer">Remembered it? <a href="login.php" class="link link--strong">Back to log in</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
