<?php
/**
 * users.php
 * Tiny JSON-file "database" so the project runs with zero setup.
 * Passwords are stored only as password_hash() hashes, never as plain text.
 */
declare(strict_types=1);

function users_all(): array
{
    if (!is_file(USERS_FILE)) {
        return [];
    }
    $data = json_decode((string) file_get_contents(USERS_FILE), true);
    return is_array($data) ? $data : [];
}

function user_find_by_email(string $email): ?array
{
    $email = strtolower($email);
    foreach (users_all() as $user) {
        if (($user['email'] ?? '') === $email) {
            return $user;
        }
    }
    return null;
}

/**
 * Create a user. Returns the new user, or null if the email is already taken.
 * The file is locked so two simultaneous sign-ups can't overwrite each other.
 */
function user_create(string $name, string $email, string $password): ?array
{
    if (!is_dir(DATA_PATH)) {
        mkdir(DATA_PATH, 0775, true);
    }

    $handle = fopen(USERS_FILE, 'c+');
    if ($handle === false) {
        throw new RuntimeException('Cannot open the users file. Check folder permissions on /data.');
    }
    flock($handle, LOCK_EX);

    $raw   = stream_get_contents($handle);
    $users = $raw ? (json_decode($raw, true) ?: []) : [];

    $email = strtolower($email);
    foreach ($users as $existing) {
        if (($existing['email'] ?? '') === $email) {
            flock($handle, LOCK_UN);
            fclose($handle);
            return null;
        }
    }

    $user = [
        'id'            => bin2hex(random_bytes(8)),
        'name'          => $name,
        'email'         => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'created_at'    => date('c'),
    ];
    $users[] = $user;

    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    return $user;
}

/** Log a user in: fresh session id, store only safe fields, optional 30-day cookie. */
function auth_login(array $user, bool $remember): void
{
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
    ];

    if ($remember) {
        setcookie(session_name(), session_id(), [
            'expires'  => time() + REMEMBER_DAYS * 86400,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}

function auth_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        setcookie(session_name(), '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    session_destroy();
}
