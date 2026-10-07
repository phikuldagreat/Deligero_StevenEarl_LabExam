<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

redirect(is_logged_in() ? 'dashboard.php' : 'login.php');
