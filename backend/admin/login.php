<?php
session_start();

// Simple admin credentials (in production, use database)
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials. Try admin/admin123';
    }
}

if (isset($_SESSION['admin_logged_in'])) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Steel & Iron Traders</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: flex;
            max-width: 900px;
            width: 90%;
        }

        .login-image {
            flex: 1;
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .login-image h1 {
            font-size: 32px;
            margin-bottom: 20px;
            font-weight: 900;
        }

        .login-image p {
            font-size: 16px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .login-image-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .login-form {
            flex: 1;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 900;
        }

        .login-form p {
            color: #999;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus {
            border-color: #c0392b;
            box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.1);
        }

        .form-group input:hover:not(:focus) {
            border-color: #d0d0d0;
        }

        .error-message {
            background: #fee;
            color: #c0392b;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            border-left: 4px solid #c0392b;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .login-btn {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
            color: white;
            padding: 14px 32px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
        }

        .login-btn:hover {
            box-shadow: 0 8px 20px rgba(192, 57, 43, 0.3);
            transform: translateY(-2px);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .demo-creds {
            background: #f5f7fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            border-left: 3px solid #3498db;
        }

        .demo-creds strong {
            color: #333;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-image {
                padding: 40px 20px;
            }

            .login-form {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-image">
            <div class="login-image-icon">🔐</div>
            <h1>Admin Panel</h1>
            <p>Secure access to manage your business. Control products, orders, and invoices all in one place.</p>
        </div>

        <div class="login-form">
            <h2>Login</h2>
            <p>Enter your admin credentials</p>

            <div class="error-message <?php echo $error ? 'show' : ''; ?>">
                <?php echo htmlspecialchars($error); ?>
            </div>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter username" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter password" required>
                </div>

                <button type="submit" class="login-btn">Login to Dashboard</button>
            </form>

            <div class="demo-creds">
                <strong>Demo Credentials:</strong><br>
                Username: <strong>admin</strong><br>
                Password: <strong>admin123</strong>
            </div>
        </div>
    </div>
</body>
</html>