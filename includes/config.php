<?php
/**
 * config.php
 * App-wide constants and session setup. Included first by every page.
 */
declare(strict_types=1);

define('APP_NAME', 'Rosewood');
define('ROOT_PATH', dirname(__DIR__));
define('DATA_PATH', ROOT_PATH . '/data');
define('USERS_FILE', DATA_PATH . '/users.json');
define('REMEMBER_DAYS', 30);

// Keep session files inside the project so "Remember for 30 days" really lasts 30 days.
$sessionDir = DATA_PATH . '/sessions';
if (!is_dir($sessionDir)) {
    @mkdir($sessionDir, 0775, true);
}
if (is_dir($sessionDir) && is_writable($sessionDir)) {
    session_save_path($sessionDir);
}
ini_set('session.gc_maxlifetime', (string) (REMEMBER_DAYS * 86400));
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
