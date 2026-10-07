<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validation.php';
require_once __DIR__ . '/includes/users.php';

require_guest();

$errors = [];
$name   = '';
$email  = '';
$alert  = null;

if (is_post()) {
    $name     = post_str('name');
    $email    = strtolower(post_str('email'));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_valid()) {
        $errors['form'] = 'Your session expired. Please try again.';
    } elseif (isset($_POST['google'])) {
        $alert = ['type' => 'info', 'message' => 'Google sign-up is not set up in this demo. Please fill in the form.'];
    } else {
        if ($err = validate_name($name))               $errors['name'] = $err;
        if ($err = validate_email($email))             $errors['email'] = $err;
        if ($err = validate_new_password($password))   $errors['password'] = $err;

        if (!$errors) {
            if (user_create($name, $email, $password) === null) {
                $errors['email'] = 'An account with this email already exists. Try logging in instead.';
            } else {
                flash_set('success', 'Account created successfully! You can now log in.');
                redirect('login.php');
            }
        }
    }
}

if (!empty($errors['form'])) {
    $alert = ['type' => 'error', 'message' => $errors['form']];
} elseif ($errors) {
    $alert = ['type' => 'error', 'message' => 'Please fix the highlighted fields and try again.'];
}

$pageTitle = 'Registration';
$pageCss   = ['auth.css'];
$pageJs    = ['password-toggle.js'];
$googleLabel = 'Sign up with Google';
require __DIR__ . '/includes/header.php';
?>
<section class="card card--register" aria-labelledby="page-title">
    <header class="card__header">
        <h1 id="page-title">Registration</h1>
        <p>Create a new account to explore.</p>
    </header>

    <div class="form-wrap">
        <?php require __DIR__ . '/includes/alert.php'; ?>

        <form method="post" action="register.php" class="form" novalidate>
            <?= csrf_field() ?>

            <div class="field<?= isset($errors['name']) ? ' field--error' : '' ?>">
                <label for="name">Name<span aria-hidden="true">*</span></label>
                <input type="text" id="name" name="name" placeholder="Enter your name"
                       value="<?= e($name) ?>" autocomplete="name" maxlength="60" required
                       aria-invalid="<?= isset($errors['name']) ? 'true' : 'false' ?>"
                       <?= isset($errors['name']) ? 'aria-describedby="name-error"' : '' ?>>
                <?php if (isset($errors['name'])): ?><p class="field__error" id="name-error"><?= e($errors['name']) ?></p><?php endif; ?>
            </div>

            <div class="field<?= isset($errors['email']) ? ' field--error' : '' ?>">
                <label for="email">Email<span aria-hidden="true">*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                       value="<?= e($email) ?>" autocomplete="email" maxlength="120" required
                       aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>"
                       <?= isset($errors['email']) ? 'aria-describedby="email-error"' : '' ?>>
                <?php if (isset($errors['email'])): ?><p class="field__error" id="email-error"><?= e($errors['email']) ?></p><?php endif; ?>
            </div>

            <div class="field<?= isset($errors['password']) ? ' field--error' : '' ?>">
                <label for="password">Password<span aria-hidden="true">*</span></label>
                <div class="input-icon">
                    <input type="password" id="password" name="password" placeholder="Create a password"
                           autocomplete="new-password" minlength="8" maxlength="72" required
                           aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>"
                           aria-describedby="<?= isset($errors['password']) ? 'password-error ' : '' ?>password-hint">
                    <button type="button" class="toggle-password" data-toggle-password aria-label="Show password" aria-pressed="false">
                        <img src="assets/img/eye.svg" alt="" width="20" height="20">
                    </button>
                </div>
                <?php if (isset($errors['password'])): ?><p class="field__error" id="password-error"><?= e($errors['password']) ?></p><?php endif; ?>
                <p class="field__hint" id="password-hint">Must be at least 8 characters, with a letter and a number.</p>
            </div>

            <button type="submit" class="btn btn--primary">Get started</button>
            <?php require __DIR__ . '/includes/google-button.php'; ?>
        </form>
    </div>

    <p class="card__footer">Already have an account? <a href="login.php" class="link link--strong">Log in</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
