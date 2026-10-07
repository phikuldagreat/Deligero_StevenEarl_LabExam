<?php
/**
 * validation.php
 * One function per field. Each returns an error message, or null when the value is valid.
 */
declare(strict_types=1);

function validate_name(string $name): ?string
{
    if ($name === '') {
        return 'Please enter your name.';
    }
    if (mb_strlen($name) < 2) {
        return 'Name must be at least 2 characters.';
    }
    if (mb_strlen($name) > 60) {
        return 'Name must be 60 characters or fewer.';
    }
    if (!preg_match("/^[\p{L}][\p{L}\s.'-]*$/u", $name)) {
        return 'Name can only contain letters, spaces, periods, apostrophes and hyphens.';
    }
    return null;
}

function validate_email(string $email): ?string
{
    if ($email === '') {
        return 'Please enter your email.';
    }
    if (mb_strlen($email) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return 'Please enter a valid email address (e.g. name@example.com).';
    }
    return null;
}

/** Strict rules, used when creating an account. */
function validate_new_password(string $password): ?string
{
    if ($password === '') {
        return 'Please create a password.';
    }
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters.';
    }
    if (strlen($password) > 72) {
        return 'Password must be 72 characters or fewer.';
    }
    if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
        return 'Password must include at least one letter and one number.';
    }
    return null;
}

/** Login only needs to know a password was typed; the hash check decides the rest. */
function validate_login_password(string $password): ?string
{
    return $password === '' ? 'Please enter your password.' : null;
}
