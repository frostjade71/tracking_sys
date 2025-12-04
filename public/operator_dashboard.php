<?php
/**
 * LEYECO III Utility Report System
 * Operator Dashboard
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/ReportController.php';

// Require operator or admin role
requireRole('OPERATOR');

$user = getCurrentUser();
$reportController = new ReportController();

// Get filters from query string
$filters = [
    'status' => $_GET['status'] ?? '',
    'municipality' => $_GET['municipality'] ?? '',
    'type' => $_GET['type'] ?? '',
    'search' => $_GET['search'] ?? ''
];

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// Get reports
$result = $reportController->getAll($filters, $page);
$reports = $result['reports'];
$totalPages = $result['total_pages'];

// Get statistics
$stats = $reportController->getStatistics();

$flashMessage = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Dashboard - <?php echo APP_NAME; ?></title>
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
                <a href="operator_dashboard.php" class="nav-item active">
                    <i class="fi fi-sr-newspaper nav-icon"></i> Dashboard
                </a>
                <?php if ($user['role'] === 'ADMIN'): ?>
                    <a href="admin_dashboard.php" class="nav-item">
                        <i class="fi fi-br-architect-plan nav-icon"></i> Admin Panel
                    </a>
                <?php endif; ?>
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
                    <i class="fi fi-sr-newspaper header-title-icon"></i>
                    Reports Dashboard
                </h1>
                <p>Manage and track all trouble reports</p>
            </header>

            <?php if ($flashMessage): ?>
                <div class="alert alert-<?php echo e($flashMessage['type']); ?>">
                    <?php echo e($flashMessage['message']); ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo number_format($stats['total'] ?? 0); ?></div>
                    <div class="stat-label">Total Reports</div>
                </div>
                <div class="stat-card stat-new">
                    <div class="stat-number"><?php echo number_format($stats['by_status']['NEW'] ?? 0); ?></div>
                    <div class="stat-label">New</div>
                </div>
                <div class="stat-card stat-investigating">
                    <div class="stat-number"><?php echo number_format($stats['by_status']['INVESTIGATING'] ?? 0); ?></div>
                    <div class="stat-label">Investigating</div>
                </div>
                <div class="stat-card stat-resolved">
                    <div class="stat-number"><?php echo number_format($stats['by_status']['RESOLVED'] ?? 0); ?></div>
                    <div class="stat-label">Resolved</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-card">
                <h3>Filter Reports</h3>
                <form method="GET" class="filters-form">
                    <div class="filter-group">
                        <label>Search</label>
                        <input type="text" name="search" value="<?php echo e($filters['search']); ?>" placeholder="Reference code, description...">
                    </div>
                    <div class="filter-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Statuses</option>
                            <?php foreach (REPORT_STATUSES as $key => $label): ?>
                                <option value="<?php echo e($key); ?>" <?php echo $filters['status'] === $key ? 'selected' : ''; ?>>
                                    <?php echo e($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Municipality</label>
                        <select name="municipality">
                            <option value="">All Municipalities</option>
                            <?php foreach (MUNICIPALITIES as $municipality): ?>
                                <option value="<?php echo e($municipality); ?>" <?php echo $filters['municipality'] === $municipality ? 'selected' : ''; ?>>
                                    <?php echo e($municipality); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Type</label>
                        <select name="type">
                            <option value="">All Types</option>
                            <?php foreach (REPORT_TYPES as $key => $label): ?>
                                <option value="<?php echo e($key); ?>" <?php echo $filters['type'] === $key ? 'selected' : ''; ?>>
                                    <?php echo e($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="operator_dashboard.php" class="btn btn-outline">Clear</a>
                    </div>
                </form>
            </div>

            <!-- Reports Table -->
            <div class="table-card">
                <div class="table-header">
                    <h3>Reports (<?php echo number_format($result['total']); ?> total)</h3>
                </div>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Reference Code</th>
                                <th>Type</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($reports)): ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-secondary);">
                                        No reports found
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reports as $report): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($report['reference_code']); ?></strong>
                                        </td>
                                        <td><?php echo e(REPORT_TYPES[$report['type']]); ?></td>
                                        <td>
                                            <?php echo e($report['municipality']); ?><br>
                                            <small style="color: var(--text-secondary);"><?php echo e(substr($report['address'], 0, 30)); ?>...</small>
                                        </td>
                                        <td>
                                            <span class="status-badge status-<?php echo e($report['status']); ?>">
                                                <?php echo e(REPORT_STATUSES[$report['status']]); ?>
                                            </span>
                                        </td>
                                        <td><?php echo e($report['assigned_to_name'] ?? 'Unassigned'); ?></td>
                                        <td><?php echo formatDate($report['created_at'], 'M d, Y'); ?></td>
                                        <td>
                                            <a href="operator_report_view.php?id=<?php echo $report['id']; ?>" class="btn btn-sm btn-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?<?php echo http_build_query(array_merge($filters, ['page' => $page - 1])); ?>" class="btn btn-outline">
                                ← Previous
                            </a>
                        <?php endif; ?>
                        
                        <span class="page-info">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="?<?php echo http_build_query(array_merge($filters, ['page' => $page + 1])); ?>" class="btn btn-outline">
                                Next →
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script src="assets/js/main.js"></script>
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
            document.body.style.overflow = 'hidden'; // Prevent scrolling when sidebar is open
        });

        // Close sidebar
        const closeSidebar = () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
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
