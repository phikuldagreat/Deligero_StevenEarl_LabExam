<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validation.php';
require_once __DIR__ . '/includes/users.php';

require_guest();

$errors = [];
$email  = '';
$alert  = flash_get();   // e.g. "Account created!" after registering

if (is_post()) {
    $email    = strtolower(post_str('email'));
    $password = (string) ($_POST['password'] ?? '');
    $remember = isset($_POST['remember']);

    if (!csrf_valid()) {
        $errors['form'] = 'Your session expired. Please try again.';
    } elseif (isset($_POST['google'])) {
        // Placeholder: real Google OAuth needs API credentials.
        $alert = ['type' => 'info', 'message' => 'Google sign-in is not set up in this demo. Please use your email and password.'];
    } else {
        if ($err = validate_email($email))                 $errors['email'] = $err;
        if ($err = validate_login_password($password))     $errors['password'] = $err;

        if (!$errors) {
            $user = user_find_by_email($email);
            // Always run password_verify so response time doesn't reveal whether the email exists.
            $hash = $user['password_hash'] ?? '$2y$10$usesomesillystringforsaltfakehashxxxxxxxxxxxxxxxxxxxx';
            if ($user && password_verify($password, $hash)) {
                auth_login($user, $remember);
                flash_set('success', 'Welcome back, ' . $user['name'] . '!');
                redirect('dashboard.php');
            }
            $errors['form'] = 'Invalid email or password.';
        }
    }
}

if (!empty($errors['form'])) {
    $alert = ['type' => 'error', 'message' => $errors['form']];
} elseif ($errors && is_post()) {
    $alert = ['type' => 'error', 'message' => 'Please fix the highlighted fields and try again.'];
}

$pageTitle = 'Log in';
$pageCss   = ['auth.css'];
$pageJs    = ['password-toggle.js'];
$googleLabel = 'Sign in with Google';
require __DIR__ . '/includes/header.php';
?>
<section class="card" aria-labelledby="page-title">
    <header class="card__header">
        <h1 id="page-title">Log in to your account</h1>
        <p>Welcome back! Please enter your details.</p>
    </header>

    <div class="form-wrap">
        <?php require __DIR__ . '/includes/alert.php'; ?>

        <form method="post" action="login.php" class="form" novalidate>
            <?= csrf_field() ?>

            <div class="field<?= isset($errors['email']) ? ' field--error' : '' ?>">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                       value="<?= e($email) ?>" autocomplete="email" maxlength="120"
                       aria-invalid="<?= isset($errors['email']) ? 'true' : 'false' ?>"
                       <?= isset($errors['email']) ? 'aria-describedby="email-error"' : '' ?>>
                <?php if (isset($errors['email'])): ?><p class="field__error" id="email-error"><?= e($errors['email']) ?></p><?php endif; ?>
            </div>

            <div class="field<?= isset($errors['password']) ? ' field--error' : '' ?>">
                <label for="password">Password</label>
                <div class="input-icon">
                    <input type="password" id="password" name="password" placeholder="••••••••••"
                           autocomplete="current-password"
                           aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>"
                           <?= isset($errors['password']) ? 'aria-describedby="password-error"' : '' ?>>
                    <button type="button" class="toggle-password" data-toggle-password aria-label="Show password" aria-pressed="false">
                        <img src="assets/img/eye.svg" alt="" width="20" height="20">
                    </button>
                </div>
                <?php if (isset($errors['password'])): ?><p class="field__error" id="password-error"><?= e($errors['password']) ?></p><?php endif; ?>
            </div>

            <div class="form__row">
                <label class="check">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember for 30 days</span>
                </label>
                <a href="forgot-password.php" class="link">Forgot password</a>
            </div>

            <button type="submit" class="btn btn--primary">Sign in</button>
            <?php require __DIR__ . '/includes/google-button.php'; ?>
        </form>
    </div>

    <p class="card__footer">Don't have an account? <a href="register.php" class="link link--strong">Sign up</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
