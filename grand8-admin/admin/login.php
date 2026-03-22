<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../config/database.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        $stmt = $pdo->prepare("
            SELECT id, full_name, email, password_hash, role, is_active
            FROM admins
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if (!$admin) {
            $error = 'Invalid email or password.';
        } elseif ((int)$admin['is_active'] !== 1) {
            $error = 'This account is inactive.';
        } elseif (!password_verify($password, $admin['password_hash'])) {
            $error = 'Invalid email or password.';
        } else {
            session_regenerate_id(true);

            $_SESSION['admin_id'] = (int)$admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_role'] = $admin['role'];

            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand 8 Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(180, 20, 20, 0.35), transparent 35%),
                linear-gradient(135deg, #180000 0%, #2a0000 35%, #120016 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            background: rgba(10, 10, 14, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 22px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }

        .brand {
            font-size: 32px;
            font-weight: 700;
            margin: 0 0 8px;
        }

        .brand span {
            color: #e21b1b;
        }

        .subtitle {
            margin: 0 0 28px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.5;
        }

        .error {
            background: rgba(226, 27, 27, 0.14);
            border: 1px solid rgba(226, 27, 27, 0.35);
            color: #ffb3b3;
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .field {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            height: 50px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: #0a0a12;
            color: #fff;
            padding: 0 14px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: rgba(226, 27, 27, 0.65);
            box-shadow: 0 0 0 3px rgba(226, 27, 27, 0.12);
        }

        .btn {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 999px;
            background: #e21b1b;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .btn:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .hint {
            margin-top: 18px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.55);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1 class="brand">Grand <span>8</span></h1>
        <p class="subtitle">Admin dashboard login</p>

        <?php if ($error !== ''): ?>
            <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="field">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars((string)($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    required
                >
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="hint">Grand 8 Club Administration</p>
    </div>
</body>
</html>