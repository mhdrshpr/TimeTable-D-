<?php
/**
 * Database & Core Security Configuration
 * TimeTable (D+)
 */

// Load .env environment variables if present
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return;
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            // Remove wrapping quotes if present
            if ((strpos($value, '"') === 0 && substr($value, -1) === '"') ||
                (strpos($value, "'") === 0 && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Load .env from current directory
loadEnv(__DIR__ . '/.env');

// DB Credentials from env or fallback
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$db_name = getenv('DB_NAME') ?: 'mhdrshpr_dplus';
$username = getenv('DB_USER') ?: 'mhdrshpr_dplususer';
$password = getenv('DB_PASS') ?: '';
$charset = getenv('DB_CHARSET') ?: 'utf8mb4';

try {
    $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset={$charset}";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("خطا در اتصال امن به پایگاه داده. لطفاً تنظیمات فایل .env را بررسی کنید.");
}

/**
 * Initialize a secure PHP session
 */
function init_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        $lifetime = (int)(getenv('SESSION_LIFETIME') ?: 86400);
        $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                    (getenv('SESSION_SECURE_COOKIE') === 'true');
        
        session_set_cookie_params([
            'lifetime' => $lifetime,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_strict_mode', '1');
        
        session_start();
    }

    // Initialize CSRF Token if not set
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

/**
 * Returns current CSRF token
 */
function get_csrf_token() {
    init_secure_session();
    return $_SESSION['csrf_token'] ?? '';
}

/**
 * Validates a submitted CSRF token
 */
function verify_csrf_token($token) {
    init_secure_session();
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Output essential security headers
 */
function send_security_headers() {
    if (!headers_sent()) {
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: SAMEORIGIN");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
    }
}

send_security_headers();
?>