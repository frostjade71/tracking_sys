<?php
/**
 * LEYECO III Utility Report System
 * Homepage
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/ReportController.php';

$reportController = new ReportController();
$stats = $reportController->getStatistics();
$flashMessage = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="homepage.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo">
                <img src="assets/images/logoL3iii.webp" alt="L3 Logo" class="header-logo left-logo">
                <img src="assets/images/logo_leyeco3.webp" alt="LEYECO III Logo" class="header-logo">
            </div>
            <nav class="nav">
                <?php if (isLoggedIn()): ?>
                    <?php $user = getCurrentUser(); ?>
                    <span class="user-info">Welcome, <?php echo e($user['name']); ?></span>
                    <?php if ($user['role'] === 'ADMIN'): ?>
                        <a href="admin_dashboard.php" class="btn btn-secondary">Admin Dashboard</a>
                    <?php else: ?>
                        <a href="operator_dashboard.php" class="btn btn-secondary">Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">Staff Login</a>
                <?php endif; ?>
            </nav>
        </header>

        <?php if ($flashMessage): ?>
            <div class="alert alert-<?php echo e($flashMessage['type']); ?>">
                <?php echo e($flashMessage['message']); ?>
            </div>
        <?php endif; ?>

        <main class="main">
            <section class="hero">
                <h2>Report Electrical Issues</h2>
                <p>Help us serve you better by reporting power outages, damaged equipment, or electrical hazards in your area.</p>
                
                <div class="hero-actions">
                    <a href="submit_report.php" class="btn btn-primary btn-large">
                        📝 Submit New Report
                    </a>
                    <a href="#view-report" class="btn btn-secondary btn-large">
                        🔍 Track Your Report
                    </a>
                </div>
            </section>

            <section class="stats">
                <h3>System Statistics</h3>
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
            </section>

            <section class="view-report" id="view-report">
                <h3>Track Your Report</h3>
                <p>Enter your reference code to view the status of your report</p>
                
                <form action="view_report.php" method="GET" class="search-form">
                    <input 
                        type="text" 
                        name="ref" 
                        placeholder="Enter reference code (e.g., LEY-20251202-0001)" 
                        required
                        pattern="LEY-\d{8}-\d{4}"
                        class="search-input"
                    >
                    <button type="submit" class="btn btn-primary">View Report</button>
                </form>
            </section>

            <section class="info">
                <h3>How It Works</h3>
                <div class="steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <h4>Submit Report</h4>
                        <p>Fill out the form with details about the electrical issue</p>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <h4>Get Reference Code</h4>
                        <p>Receive a unique tracking code for your report</p>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <h4>Track Progress</h4>
                        <p>Use your code to check the status anytime</p>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <h4>Issue Resolved</h4>
                        <p>Our team works to resolve the issue quickly</p>
                    </div>
                </div>
            </section>
        </main>

        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> LEYECO III - Leyte III Electric Cooperative. All rights reserved.</p>
            <p>For emergencies, call: <strong>123-4567</strong></p>
        </footer>
    </div>
</body>
</html>
