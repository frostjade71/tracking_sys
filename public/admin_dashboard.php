<?php
/**
 * LEYECO III Utility Report System
 * Admin Dashboard
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/ReportController.php';
require_once __DIR__ . '/../app/UserController.php';

requireRole('ADMIN');

$user = getCurrentUser();
$reportController = new ReportController();
$userController = new UserController();

$stats = $reportController->getStatistics();
$users = $userController->getAll();

$flashMessage = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="assets/images/logo_leyeco3.webp" alt="LEYECO III Logo" class="header-logo">
            </div>
            <nav class="sidebar-nav">
                <a href="admin_dashboard.php" class="nav-item active">
                    <img src="assets/icons/fc1911.png" alt="Admin" class="nav-icon"> Admin Dashboard
                </a>
                <a href="operator_dashboard.php" class="nav-item">
                    <img src="assets/icons/fc9.png" alt="Reports" class="nav-icon"> Reports Dashboard
                </a>
                <a href="manage_users.php" class="nav-item">
                    <img src="assets/icons/fc14.png" alt="Users" class="nav-icon"> Manage Users
                </a>
                <a href="homepage.php" class="nav-item">
                    <img src="assets/icons/fc93.png" alt="Home" class="nav-icon"> Public Homepage
                </a>
                <a href="logout.php" class="nav-item">
                    <img src="assets/icons/fc3.png" alt="Logout" class="nav-icon"> Logout
                </a>
            </nav>
            <div class="sidebar-user">
                <strong><?php echo e($user['name']); ?></strong><br>
                <small><?php echo e($user['role']); ?></small>
            </div>
        </aside>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <h1>Admin Dashboard</h1>
                <p>System overview and statistics</p>
            </header>

            <?php if ($flashMessage): ?>
                <div class="alert alert-<?php echo e($flashMessage['type']); ?>">
                    <?php echo e($flashMessage['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Overall Statistics -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total'] ?? 0); ?></div>
                    <div class="stat-label">Total Reports</div>
                </div>
                <div class="stat-card stat-new">
                    <div class="stat-number"><?php echo number_format($stats['by_status']['NEW'] ?? 0); ?></div>
                    <div class="stat-label">New Reports</div>
                </div>
                <div class="stat-card stat-investigating">
                    <div class="stat-number"><?php echo number_format($stats['by_status']['INVESTIGATING'] ?? 0); ?></div>
                    <div class="stat-label">Under Investigation</div>
                </div>
                <div class="stat-card stat-resolved">
                    <div class="stat-number"><?php echo number_format($stats['by_status']['RESOLVED'] ?? 0); ?></div>
                    <div class="stat-label">Resolved</div>
                </div>
            </div>

            <!-- Reports by Type -->
            <div class="chart-card">
                <h3>Reports by Type</h3>
                <div class="chart-bars">
                    <?php foreach (REPORT_TYPES as $key => $label): ?>
                        <?php 
                            $count = $stats['by_type'][$key] ?? 0;
                            $percentage = $stats['total'] > 0 ? ($count / $stats['total']) * 100 : 0;
                        ?>
                        <div class="chart-bar-item">
                            <div class="chart-bar-label"><?php echo e($label); ?></div>
                            <div class="chart-bar-container">
                                <div class="chart-bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                            </div>
                            <div class="chart-bar-value"><?php echo $count; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Top Municipalities -->
            <div class="chart-card">
                <h3>Top Municipalities by Report Count</h3>
                <div class="chart-bars">
                    <?php if (!empty($stats['by_municipality'])): ?>
                        <?php 
                            $maxCount = max(array_column($stats['by_municipality'], 'count'));
                        ?>
                        <?php foreach ($stats['by_municipality'] as $item): ?>
                            <?php 
                                $percentage = $maxCount > 0 ? ($item['count'] / $maxCount) * 100 : 0;
                            ?>
                            <div class="chart-bar-item">
                                <div class="chart-bar-label"><?php echo e($item['municipality']); ?></div>
                                <div class="chart-bar-container">
                                    <div class="chart-bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                                </div>
                                <div class="chart-bar-value"><?php echo $item['count']; ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: var(--text-secondary); text-align: center; padding: 20px;">No data available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- User Management Summary -->
            <div class="table-card">
                <div class="table-header">
                    <h3>System Users (<?php echo count($users); ?>)</h3>
                    <a href="manage_users.php" class="btn btn-primary">Manage Users</a>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($users, 0, 5) as $u): ?>
                                <tr>
                                    <td><?php echo e($u['name']); ?></td>
                                    <td><?php echo e($u['email']); ?></td>
                                    <td>
                                        <span class="role-badge role-<?php echo e($u['role']); ?>">
                                            <?php echo e($u['role']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatDate($u['created_at'], 'M d, Y'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (count($users) > 5): ?>
                    <div style="text-align: center; padding: 15px;">
                        <a href="manage_users.php" class="btn btn-outline">View All Users</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Quick Stats -->
            <div class="info-card">
                <h3>System Information</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Recent Reports (7 days)</label>
                        <span><?php echo number_format($stats['recent'] ?? 0); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Total Users</label>
                        <span><?php echo count($users); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Admin Users</label>
                        <span><?php echo count(array_filter($users, fn($u) => $u['role'] === 'ADMIN')); ?></span>
                    </div>
                    <div class="info-item">
                        <label>Operator Users</label>
                        <span><?php echo count(array_filter($users, fn($u) => $u['role'] === 'OPERATOR')); ?></span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="assets/js/main.js"></script>
</body>
</html>
