<?php
/**
 * LEYECO III Utility Report System
 * Manage Users (Admin Only)
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/UserController.php';

requireRole('ADMIN');

$user = getCurrentUser();
$userController = new UserController();

$errors = [];
$success = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'create_user') {
            $name = sanitizeInput($_POST['name'] ?? '');
            $email = sanitizeInput($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = sanitizeInput($_POST['role'] ?? '');

            if (empty($name) || empty($email) || empty($password) || empty($role)) {
                $errors[] = 'All fields are required';
            } elseif (!in_array($role, ['ADMIN', 'OPERATOR'])) {
                $errors[] = 'Invalid role';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters';
            } else {
                $result = $userController->create($name, $email, $password, $role);
                if ($result['success']) {
                    $success = 'User created successfully';
                } else {
                    $errors[] = $result['error'];
                }
            }
        } elseif ($action === 'update_role') {
            $userId = intval($_POST['user_id'] ?? 0);
            $newRole = sanitizeInput($_POST['role'] ?? '');

            if ($userId === $user['id']) {
                $errors[] = 'You cannot change your own role';
            } elseif (!in_array($newRole, ['ADMIN', 'OPERATOR'])) {
                $errors[] = 'Invalid role';
            } else {
                $result = $userController->updateRole($userId, $newRole);
                if ($result['success']) {
                    $success = 'User role updated successfully';
                } else {
                    $errors[] = $result['error'];
                }
            }
        } elseif ($action === 'delete_user') {
            $userId = intval($_POST['user_id'] ?? 0);

            if ($userId === $user['id']) {
                $errors[] = 'You cannot delete your own account';
            } else {
                $result = $userController->delete($userId);
                if ($result['success']) {
                    $success = 'User deleted successfully';
                } else {
                    $errors[] = $result['error'];
                }
            }
        }
    }
}

$users = $userController->getAll();
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/mobile-sidebar.css">
    <link rel="stylesheet" href="assets/css/header-icon.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-straight/css/uicons-bold-straight.css'>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="assets/images/logo_leyeco3.webp" alt="LEYECO III Logo" class="header-logo">
                <button class="sidebar-close" id="sidebarClose" aria-label="Close Menu">
                    <span>&times;</span>
                </button>
            </div>
            <nav class="sidebar-nav">
                <a href="admin_dashboard.php" class="nav-item">
                    <i class="fi fi-br-architect-plan nav-icon"></i> Admin Dashboard
                </a>
                <a href="operator_dashboard.php" class="nav-item">
                    <i class="fi fi-sr-newspaper nav-icon"></i> Reports Dashboard
                </a>
                <a href="manage_users.php" class="nav-item active">
                    <i class="fi fi-sr-users-alt nav-icon"></i> Manage Users
                </a>
                <a href="homepage.php" class="nav-item">
                    <i class="fi fi-sr-home nav-icon"></i> Public Homepage
                </a>
                <a href="logout.php" class="nav-item">
                    <i class="fi fi-bs-sign-out-alt nav-icon"></i> Logout
                </a>
            </nav>
            <div class="sidebar-user">
                <strong><?php echo e($user['name']); ?></strong><br>
                <small><?php echo e($user['role']); ?></small>
            </div>
        </aside>

        <main class="dashboard-main">
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <header class="dashboard-header">
                <h1>
                    <i class="fi fi-sr-users-alt header-title-icon"></i>
                    Manage Users
                </h1>
                <p>Add, edit, or remove system users</p>
            </header>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo e($success); ?>
                </div>
            <?php endif; ?>

            <!-- Create User Form -->
            <div class="form-card">
                <h3>Add New User</h3>
                <form method="POST" class="user-form">
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                    <input type="hidden" name="action" value="create_user">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" required placeholder="Full name">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" required placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" required placeholder="Minimum 6 characters">
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" required>
                                <option value="">-- Select Role --</option>
                                <option value="OPERATOR">Operator</option>
                                <option value="ADMIN">Admin</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Create User</button>
                </form>
            </div>

            <!-- Users List -->
            <div class="table-card">
                <div class="table-header">
                    <h3>All Users (<?php echo count($users); ?>)</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><strong><?php echo e($u['name']); ?></strong></td>
                                    <td><?php echo e($u['email']); ?></td>
                                    <td>
                                        <?php if ($u['id'] === $user['id']): ?>
                                            <span class="role-badge role-<?php echo e($u['role']); ?>">
                                                <?php echo e($u['role']); ?> (You)
                                            </span>
                                        <?php else: ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                                <input type="hidden" name="action" value="update_role">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <select name="role" onchange="if(confirm('Change role for <?php echo e($u['name']); ?>?')) this.form.submit();" class="role-select">
                                                    <option value="OPERATOR" <?php echo $u['role'] === 'OPERATOR' ? 'selected' : ''; ?>>OPERATOR</option>
                                                    <option value="ADMIN" <?php echo $u['role'] === 'ADMIN' ? 'selected' : ''; ?>>ADMIN</option>
                                                </select>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo formatDate($u['created_at'], 'M d, Y'); ?></td>
                                    <td>
                                        <?php if ($u['id'] !== $user['id']): ?>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete <?php echo e($u['name']); ?>?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color: var(--text-secondary); font-size: 14px;">Current User</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Mobile Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebarClose = document.getElementById('sidebarClose');

        // Open sidebar
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // Close sidebar
        const closeSidebar = () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        };

        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Close sidebar when clicking nav items on mobile
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>
