<?php
/**
 * LEYECO III Utility Report System
 * Operator Report View & Management
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/ReportController.php';
require_once __DIR__ . '/../app/UserController.php';

requireRole('OPERATOR');

$user = getCurrentUser();
$reportController = new ReportController();
$userController = new UserController();

$reportId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$report = $reportController->getById($reportId);

if (!$report) {
    setFlashMessage('error', 'Report not found');
    redirect('operator_dashboard.php');
}


$errors = [];
$success = null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'update_status') {
            $newStatus = $_POST['status'] ?? '';
            if (in_array($newStatus, array_keys(REPORT_STATUSES))) {
                $result = $reportController->updateStatus($reportId, $newStatus, $user['id']);
                if ($result['success']) {
                    $success = 'Status updated successfully';
                    $report = $reportController->getById($reportId); // Refresh
                } else {
                    $errors[] = $result['error'];
                }
            } else {
                $errors[] = 'Invalid status';
            }
        } elseif ($action === 'add_comment') {
            $comment = sanitizeInput($_POST['comment'] ?? '');
            if (!empty($comment)) {
                $result = $reportController->addComment($reportId, $user['id'], $comment);
                if ($result['success']) {
                    $success = 'Comment added successfully';
                    $report = $reportController->getById($reportId); // Refresh
                } else {
                    $errors[] = $result['error'];
                }
            } else {
                $errors[] = 'Comment cannot be empty';
            }
        } elseif ($action === 'assign_technician') {
            $technicianId = intval($_POST['technician_id'] ?? 0);
            if ($technicianId > 0) {
                $result = $reportController->assignTechnician($reportId, $technicianId, $user['id']);
                if ($result['success']) {
                    $success = 'Technician assigned successfully';
                    $report = $reportController->getById($reportId); // Refresh
                } else {
                    $errors[] = $result['error'];
                }
            } else {
                $errors[] = 'Please select a technician';
            }
        }
    }
}

$comments = $reportController->getComments($reportId);
$operators = $userController->getOperators();
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report <?php echo e($report['reference_code']); ?> - <?php echo APP_NAME; ?></title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="homepage.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/mobile-sidebar.css">
    <style>
        /* Map Styles */
        #map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            margin: 15px 0;
            border: 1px solid #ddd;
        }
        .map-info {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border-left: 4px solid var(--accent-yellow);
            padding: 12px 15px;
            border-radius: 6px;
            margin: 10px 0;
            font-size: 14px;
            color: #5F3F16;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .coordinates-display {
            margin-top: 10px;
            padding: 8px;
            background: #f5f5f5;
            border-radius: 4px;
            font-size: 14px;
        }
        @media (max-width: 767px) {
            #map {
                height: 300px;
            }
        }
    </style>
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
                <a href="operator_dashboard.php" class="nav-item">
                    <img src="assets/icons/fc9.png" alt="Dashboard" class="nav-icon"> Dashboard
                </a>
                <?php if ($user['role'] === 'ADMIN'): ?>
                    <a href="admin_dashboard.php" class="nav-item">
                        <img src="assets/icons/fc1911.png" alt="Admin" class="nav-icon"> Admin Panel
                    </a>
                <?php endif; ?>
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
            <!-- Mobile Menu Button -->
            <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <header class="dashboard-header">
                <div>
                    <a href="operator_dashboard.php" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">← Back to Dashboard</a>
                    <h1 style="margin-top: 10px;"><?php echo e($report['reference_code']); ?></h1>
                    <p>Report Details & Management</p>
                </div>
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

            <div class="report-view-grid">
                <!-- Report Details -->
                <div class="report-details-card">
                    <h3>Report Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Status</label>
                            <span class="status-badge status-<?php echo e($report['status']); ?>">
                                <?php echo e(REPORT_STATUSES[$report['status']]); ?>
                            </span>
                        </div>
                        <div class="detail-item">
                            <label>Type</label>
                            <span><?php echo e(REPORT_TYPES[$report['type']]); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Municipality</label>
                            <span><?php echo e($report['municipality']); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Address</label>
                            <span><?php echo e($report['address']); ?></span>
                        </div>
                        <?php if ($report['reporter_name']): ?>
                            <div class="detail-item">
                                <label>Reporter Name</label>
                                <span><?php echo e($report['reporter_name']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($report['contact']): ?>
                            <div class="detail-item">
                                <label>Contact</label>
                                <span><?php echo e($report['contact']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="detail-item">
                            <label>Description</label>
                            <span><?php echo nl2br(e($report['description'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Submitted</label>
                            <span><?php echo formatDate($report['created_at']); ?></span>
                        </div>
                        <div class="detail-item">
                            <label>Last Updated</label>
                            <span><?php echo formatDate($report['updated_at']); ?></span>
                        </div>
                        <?php if ($report['assigned_to_name']): ?>
                            <div class="detail-item">
                                <label>Assigned To</label>
                                <span><?php echo e($report['assigned_to_name']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Map Display -->
                        <?php if (!empty($report['lat']) && !empty($report['lon'])): ?>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <label>Report Location</label>
                                <div class="map-info">
                                    This is the reported location of the issue
                                </div>
                                <div id="map"></div>
                                <div class="coordinates-display">
                                    📌 Coordinates: 
                                    <span id="coord-text">
                                        <?php echo e($report['lat']); ?>, <?php echo e($report['lon']); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($report['photo_path']): ?>
                            <div class="detail-item" style="grid-column: 1 / -1;">
                                <label>Photo</label>
                                <img src="/<?php echo e($report['photo_path']); ?>" alt="Report photo" style="max-width: 100%; border-radius: 8px; margin-top: 10px;">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Actions -->
                <div class="actions-card">
                    <h3>Actions</h3>

                    <!-- Update Status -->
                    <form method="POST" class="action-form">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="action" value="update_status">
                        <label>Update Status</label>
                        <select name="status" required>
                            <?php foreach (REPORT_STATUSES as $key => $label): ?>
                                <option value="<?php echo e($key); ?>" <?php echo $report['status'] === $key ? 'selected' : ''; ?>>
                                    <?php echo e($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </form>

                    <!-- Assign Technician -->
                    <form method="POST" class="action-form">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="action" value="assign_technician">
                        <label>Assign Technician</label>
                        <select name="technician_id" required>
                            <option value="">-- Select Technician --</option>
                            <?php foreach ($operators as $operator): ?>
                                <option value="<?php echo $operator['id']; ?>" <?php echo $report['assigned_to'] == $operator['id'] ? 'selected' : ''; ?>>
                                    <?php echo e($operator['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </form>

                    <!-- Add Comment -->
                    <form method="POST" class="action-form">
                        <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">
                        <input type="hidden" name="action" value="add_comment">
                        <label>Add Note/Comment</label>
                        <textarea name="comment" rows="4" required placeholder="Enter your note or update..."></textarea>
                        <button type="submit" class="btn btn-primary">Add Comment</button>
                    </form>
                </div>
            </div>

            <!-- Timeline -->
            <div class="timeline-card">
                <h3>Activity Timeline</h3>
                <?php if (empty($comments)): ?>
                    <p style="color: var(--text-secondary); text-align: center; padding: 20px;">No activity yet</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($comments as $comment): ?>
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <div class="timeline-date"><?php echo formatDate($comment['created_at']); ?></div>
                                    <div class="timeline-message"><?php echo e($comment['message']); ?></div>
                                    <?php if ($comment['user_name']): ?>
                                        <div class="timeline-user">by <?php echo e($comment['user_name']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Only initialize map if coordinates exist
        <?php if (!empty($report['lat']) && !empty($report['lon'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                var lat = <?php echo floatval($report['lat']); ?>;
                var lon = <?php echo floatval($report['lon']); ?>;
                
                // Initialize map centered on the report location
                var map = L.map('map').setView([lat, lon], 15);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19,
                }).addTo(map);
                
                // Add marker at the report location
                L.marker([lat, lon]).addTo(map)
                    .bindPopup('Report Location')
                    .openPopup();
                
                // Add a circle to highlight the area
                L.circle([lat, lon], {
                    color: '#3498db',
                    fillColor: '#3498db',
                    fillOpacity: 0.2,
                    radius: 100 // 100 meters radius
                }).addTo(map);
            });
        <?php endif; ?>
    </script>
    
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
