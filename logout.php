<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/users.php';

// Logout must be a POST with a valid CSRF token, so other sites can't log users out.
if (is_post() && csrf_valid()) {
    auth_logout();
    session_start();                       // fresh session just to carry the message
    flash_set('success', 'You have been logged out.');
}
redirect('login.php');
