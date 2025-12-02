<?php
/**
 * LEYECO III Utility Report System
 * Login Page
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/AuthController.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $user = getCurrentUser();
    if ($user['role'] === 'ADMIN') {
        redirect('admin_dashboard.php');
    } else {
        redirect('operator_dashboard.php');
    }
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid security token';
    } else {
        $email = sanitizeInput($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Please enter both email and password';
        } else {
            $authController = new AuthController();
            $result = $authController->login($email, $password);

            if ($result['success']) {
                // Redirect based on role
                if ($result['user']['role'] === 'ADMIN') {
                    redirect('admin_dashboard.php');
                } else {
                    redirect('operator_dashboard.php');
                }
            } else {
                $error = $result['error'];
            }
        }
    }
}

$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        .login-container {
            max-width: 480px;
            margin: 80px auto;
            background: white;
            padding: 45px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border: 3px solid var(--accent-yellow);
            position: relative;
            overflow: hidden;
        }
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-red), var(--accent-yellow));
        }
        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }
        .login-header::before {
            content: '';
            display: block;
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: url('assets/images/logoL3iii.webp') center/contain no-repeat;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }
        .login-header h1 {
            font-size: 32px;
            color: var(--primary-red);
            margin-bottom: 10px;
            font-weight: 800;
        }
        .login-header p {
            color: var(--text-gray);
            font-weight: 500;
            font-size: 15px;
        }
        
        /* Mobile Responsive Styles */
        @media (max-width: 767px) {
            .login-container {
                margin: 30px 15px;
                padding: 25px 20px;
                border-radius: 12px;
            }
            
            .login-header {
                margin-bottom: 20px;
            }
            
            .login-header::before {
                width: 60px;
                height: 60px;
                margin: 0 auto 12px;
            }
            
            .login-header h1 {
                font-size: 24px;
                margin-bottom: 6px;
            }
            
            .login-header p {
                font-size: 13px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .form-group label {
                font-size: 13px;
                margin-bottom: 6px;
            }
            
            .form-group input {
                padding: 10px 14px;
                font-size: 15px;
                border-radius: 8px;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 15px;
                border-radius: 8px;
            }
        }
        .form-group {
            margin-bottom: 22px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--text-dark);
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--border-light);
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: var(--white);
        }
        .form-group input:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            transform: translateY(-2px);
        }
        .login-actions {
            margin-top: 30px;
        }
        .login-actions button {
            width: 100%;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .back-link:hover {
            color: var(--primary-red-dark);
            transform: translateX(-5px);
        }
        .credentials-info {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 2px solid var(--accent-yellow-light);
            font-size: 13px;
            color: var(--text-gray);
            background: var(--accent-yellow-light);
            padding: 20px;
            border-radius: 10px;
            line-height: 1.8;
        }
        .credentials-info strong {
            color: var(--primary-red);
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-header">
                <h1>Staff Login</h1>
                <p>LEYECO III Utility Report System</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo e($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">

                <div class="form-group">
                    <label>Email Address</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?php echo e($_POST['email'] ?? ''); ?>" 
                        required 
                        autofocus
                        placeholder="your.email@example.com"
                    >
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        placeholder="Enter your password"
                    >
                </div>

                <div class="login-actions">
                    <button type="submit" class="btn btn-primary btn-large">Login</button>
                </div>
            </form>

            <a href="homepage.php" class="back-link">← Back to Homepage</a>
        </div>
    </div>
</body>
</html>
