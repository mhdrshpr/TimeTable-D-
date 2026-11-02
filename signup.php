<?php
require_once 'db.php';
init_secure_session();

if (isset($_SESSION['username']) && isset($_SESSION['user_id'])) {
    header("Location: timetable.php");
    exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    $token = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($token)) {
        $errorMessage = "درخواست نامعتبر است. لطفاً مجدداً تلاش کنید.";
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validation
        if (mb_strlen($username, 'UTF-8') < 3 || mb_strlen($username, 'UTF-8') > 30) {
            $errorMessage = "طول نام کاربری باید بین ۳ تا ۳۰ کاراکتر باشد.";
        } elseif (!preg_match('/^[\p{L}\p{N}_\-\.]+$/u', $username)) {
            $errorMessage = "نام کاربری فقط می‌تواند شامل حروف، اعداد، زیرخط و خط تیره باشد.";
        } elseif (strlen($password) < 6) {
            $errorMessage = "رمز عبور باید حداقل ۶ کاراکتر باشد.";
        } elseif (strlen($password) > 128) {
            $errorMessage = "رمز عبور بیش از حد طولانی است.";
        } else {
            // Check if user already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);

            if ($stmt->fetch()) {
                $errorMessage = "این نام کاربری قبلاً ثبت شده است. لطفاً نام دیگری انتخاب کنید.";
            } else {
                try {
                    $pdo->beginTransaction();

                    // Hash password with strong algorithm
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);

                    // Insert new user
                    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
                    $stmt->execute([$username, $password_hash]);
                    $user_id = (int)$pdo->lastInsertId();

                    // Create initial default schedule
                    $emptyData = json_encode([]);
                    $stmt = $pdo->prepare("INSERT INTO schedules (user_id, name, data, is_default) VALUES (?, 'جدول اصلی', ?, 1)");
                    $stmt->execute([$user_id, $emptyData]);

                    $pdo->commit();

                    // Session Fixation Prevention
                    session_regenerate_id(true);
                    $_SESSION['username'] = $username;
                    $_SESSION['user_id'] = $user_id;

                    header("Location: timetable.php");
                    exit;
                } catch (Exception $e) {
                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    error_log("Signup error: " . $e->getMessage());
                    $errorMessage = "خطایی در ثبت‌نام رخ داد. لطفاً مجدداً تلاش کنید.";
                }
            }
        }
    }
}

$csrf_token = get_csrf_token();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ثبت‌نام | دروس پلاس وب</title>

<style>
:root {
    --primary: #2563eb;
    --primary-hover: #1d4ed8;
    --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    --card-bg: #ffffff;
    --border: #e2e8f0;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --danger: #ef4444;
    --danger-bg: #fef2f2;
    --danger-border: #fecaca;
}

@font-face {
    font-family: 'IRANSansX';
    src: url('./fonts/IRANSansX-Regular.woff2') format('woff2');
    font-weight: 400;
    font-style: normal;
    font-display: swap;
}

@font-face {
    font-family: 'IRANSansX';
    src: url('./fonts/IRANSansX-Bold.woff2') format('woff2');
    font-weight: 700;
    font-style: normal;
    font-display: swap;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'IRANSansX', sans-serif;
    background: var(--bg-gradient);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    color: var(--text-main);
}

.signup-card {
    background: var(--card-bg);
    padding: 40px 32px;
    border-radius: 24px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.12), 0 0 1px 1px rgba(0, 0, 0, 0.05);
    text-align: center;
}

.brand-icon {
    width: 60px;
    height: 60px;
    background: #eff6ff;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
    border: 1px solid #dbeafe;
}

.brand-icon img {
    width: 32px;
    height: 32px;
}

.card-title {
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 6px;
}

.card-subtitle {
    font-size: 0.88rem;
    color: var(--text-muted);
    margin-bottom: 24px;
}

.alert-error {
    background: var(--danger-bg);
    border: 1px solid var(--danger-border);
    color: var(--danger);
    padding: 12px 14px;
    border-radius: 12px;
    font-size: 0.85rem;
    margin-bottom: 20px;
    text-align: right;
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1.5;
}

.form-group {
    margin-bottom: 18px;
    text-align: right;
    position: relative;
}

.form-label {
    display: block;
    font-size: 0.83rem;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.form-input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1.5px solid var(--border);
    background: #f8fafc;
    font-family: inherit;
    font-size: 0.95rem;
    color: var(--text-main);
    outline: none;
    transition: all 0.2s ease;
}

.form-input:focus {
    background: #ffffff;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}

.form-hint {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 4px;
}

.toggle-password {
    position: absolute;
    left: 12px;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.8rem;
    color: var(--text-muted);
    padding: 4px;
    display: flex;
    align-items: center;
}

.btn-submit {
    width: 100%;
    padding: 13px;
    border-radius: 12px;
    border: none;
    background: var(--primary);
    color: white;
    font-family: inherit;
    font-size: 1rem;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 10px;
}

.btn-submit:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
}

.btn-submit:active {
    transform: translateY(0);
}

.footer-link {
    margin-top: 22px;
    font-size: 0.88rem;
    color: var(--text-muted);
}

.footer-link a {
    color: var(--primary);
    text-decoration: none;
    font-weight: bold;
    margin-right: 4px;
}

.footer-link a:hover {
    text-decoration: underline;
}
</style>
</head>

<body>
<div class="signup-card">
    <div class="brand-icon">
        <img src="./icons/signup.svg" alt="signup">
    </div>

    <h2 class="card-title">ثبت‌نام در دروس پلاس</h2>
    <p class="card-subtitle">ایجاد حساب کاربری جدید برای ذخیره برنامه‌ها</p>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert-error">
            <img src="./icons/alert.svg" style="width: 18px; height: 18px; filter: invert(34%) sepia(88%) saturate(2200%) hue-rotate(335deg);" alt="warning">
            <span><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    <?php endif; ?>

    <form method="POST" action="signup.php" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
        
        <div class="form-group">
            <label class="form-label" for="username">نام کاربری انتخابی</label>
            <div class="input-wrapper">
                <input type="text" id="username" name="username" class="form-input" placeholder="حداقل ۳ کاراکتر (لاتین یا فارسی)" required minlength="3" maxlength="30" autofocus>
            </div>
            <div class="form-hint">شامل حروف و اعداد بدون فاصله</div>
        </div>

        <div class="form-group">
            <label class="form-label" for="password">رمز عبور</label>
            <div class="input-wrapper">
                <input type="password" id="password" name="password" class="form-input" placeholder="حداقل ۶ کاراکتر" required minlength="6" maxlength="128">
                <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" title="نمایش/عدم نمایش رمز">
                    👁️
                </button>
            </div>
            <div class="form-hint">رمز عبور امن با ترکیب حروف و اعداد</div>
        </div>

        <button type="submit" class="btn-submit">
            <img src="./icons/check.svg" style="width: 18px; height: 18px; filter: brightness(0) invert(1);" alt="check">
            ایجاد حساب و ورود
        </button>
    </form>

    <div class="footer-link">
        قبلاً حساب کاربری ساخته‌اید؟
        <a href="login.php">وارد شوید</a>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}
</script>
</body>
</html>
