<?php
/**
 * LEYECO III Utility Report System
 * View Report Page (Public)
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/ReportController.php';

$report = null;
$error = null;

if (isset($_GET['ref'])) {
    $referenceCode = sanitizeInput($_GET['ref']);
    $reportController = new ReportController();
    $report = $reportController->getByReferenceCode($referenceCode);
    
    if (!$report) {
        $error = 'Report not found. Please check your reference code.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
    <title>View Report - <?php echo APP_NAME; ?></title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="homepage.css">
    <style>
        .report-container {
            max-width: 100%;
            margin: 0 auto;
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
        }
        
        @media (min-width: 768px) {
            .report-container {
                max-width: 900px;
                padding: 30px;
                margin: 15px auto;
            }
        }
        .report-header {
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .report-ref {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            text-align: center;
            min-width: 100px;
        }
        .status-NEW {
            background-color: #84CC16; /* lime */
        }
        .status-INVESTIGATING {
            background-color: #F59E0B; /* yellow */
        }
        .status-RESOLVED {
            background-color: #10B981; /* green */
        }
        .status-CLOSED {
            background-color: #6B7280; /* grey */
        }
        .report-details {
            display: grid;
            gap: 20px;
        }
        .detail-row {
            display: flex;
            flex-direction: column;
            gap: 5px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        @media (min-width: 768px) {
            .detail-row {
                display: grid;
                grid-template-columns: 200px 1fr;
                gap: 15px;
                padding: 15px 0;
            }
            .detail-row > .detail-label {
                text-align: right;
            }
        }
        .detail-label {
            font-weight: 600;
            color: var(--text-secondary);
        }
        .detail-value {
            color: var(--text-primary);
        }
        .timeline {
            margin-top: 40px;
        }
        .timeline h3 {
            margin-bottom: 30px;
            color: var(--text-primary);
            font-size: 20px;
        }
        .timeline-item {
            position: relative;
            padding-left: 40px;
            padding-bottom: 30px;
            border-left: 3px solid #e5e7eb;
            margin-left: 10px;
        }
        .timeline-item:last-child {
            border-left-color: transparent;
            padding-bottom: 0;
        }
        .timeline-dot {
            position: absolute;
            left: -11px;
            top: 4px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            border: 3px solid white;
            box-shadow: 0 0 0 3px #e5e7eb, 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        .timeline-item:first-child .timeline-dot {
            background: #10b981;
            box-shadow: 0 0 0 3px #d1fae5, 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .timeline-item:nth-child(2) .timeline-dot {
            background: #3b82f6;
            box-shadow: 0 0 0 3px #dbeafe, 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .timeline-item:nth-child(3) .timeline-dot {
            background: #f59e0b;
            box-shadow: 0 0 0 3px #fef3c7, 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .timeline-item:nth-child(n+4) .timeline-dot {
            background: #6b7280;
            box-shadow: 0 0 0 3px #f3f4f6, 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .timeline-content {
            background: #f9fafb;
            padding: 16px;
            border-radius: 10px;
            font-size: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        .timeline-content:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        .timeline-date {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .timeline-message {
            color: var(--text-primary);
            line-height: 1.6;
        }
        .timeline-user {
            font-size: 12px;
            color: #6b7280;
            font-style: italic;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
        }
        .photo-preview {
            margin-top: 15px;
            max-width: 100%;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        .photo-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .search-again {
            background: var(--bg-color);
            padding: 20px 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .search-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        
        .search-form input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
        }
        
        .search-form button {
            width: 100%;
            padding: 10px;
        }
        
        @media (min-width: 768px) {
            .search-form {
                flex-direction: row;
                max-width: 600px;
                margin: 0 auto;
            }
            
            .search-form input[type="text"] {
                width: 70%;
            }
            
            .search-form button {
                width: 30%;
            }
            
            .search-again {
                padding: 25px;
                margin: 30px auto;
                max-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="homepage.php" class="back-link">← Back to Home</a>

        <?php if ($error): ?>
            <div class="report-container">
                <div class="alert alert-error">
                    <?php echo e($error); ?>
                </div>
                <div class="search-again">
                    <h3>Search Again</h3>
                    <form action="view_report.php" method="GET" class="search-form">
                        <input 
                            type="text" 
                            name="ref" 
                            placeholder="Enter reference code" 
                            required
                            class="search-input"
                        >
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
        <?php elseif ($report): ?>
            <div class="report-container">
                <div class="report-header">
                    <div class="report-ref"><?php echo e($report['reference_code']); ?></div>
                    <span class="status-badge status-<?php echo e($report['status']); ?>">
                        <?php echo e(REPORT_STATUSES[$report['status']]); ?>
                    </span>
                </div>

                <div class="report-details">
                    <div class="detail-row">
                        <div class="detail-label">Report Type</div>
                        <div class="detail-value"><?php echo e(REPORT_TYPES[$report['type']]); ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Description</div>
                        <div class="detail-value"><?php echo nl2br(e($report['description'])); ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">
                            <?php echo e($report['municipality']); ?><br>
                            <?php echo e($report['address']); ?>
                            <?php if ($report['lat'] && $report['lon']): ?>
                                <div style="display: flex; flex-direction: column; align-items: flex-start; margin-top: 10px;">
                                    <div id="map" style="height: 200px; width: 100%; max-width: 300px; border-radius: 8px; border: 1px solid #ddd;"></div>
                                    <div class="coordinates" style="font-size: 11px; color: #666; margin-top: 5px; background: #f5f5f5; padding: 3px 8px; border-radius: 4px;">
                                        Coordinates: <?php echo e($report['lat']); ?>, <?php echo e($report['lon']); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if ($report['reporter_name']): ?>
                        <div class="detail-row">
                            <div class="detail-label">Reporter</div>
                            <div class="detail-value"><?php echo e($report['reporter_name']); ?></div>
                        </div>
                    <?php endif; ?>

                    <div class="detail-row">
                        <div class="detail-label">Submitted</div>
                        <div class="detail-value"><?php echo formatDate($report['created_at']); ?></div>
                    </div>

                    <div class="detail-row">
                        <div class="detail-label">Last Updated</div>
                        <div class="detail-value"><?php echo formatDate($report['updated_at']); ?></div>
                    </div>

                    <?php if ($report['assigned_to_name']): ?>
                        <div class="detail-row">
                            <div class="detail-label">Assigned To</div>
                            <div class="detail-value"><?php echo e($report['assigned_to_name']); ?></div>
                        </div>
                    <?php endif; ?>

                    <?php if ($report['photo_path']): ?>
                        <div class="detail-row">
                            <div class="detail-label">Photo</div>
                            <div class="detail-value">
                                <div class="photo-preview">
                                    <img src="<?php echo e($report['photo_path']); ?>" alt="Report photo">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($report['comments'])): ?>
                    <div class="timeline">
                        <h3>Status History</h3>
                        <?php foreach ($report['comments'] as $comment): ?>
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

                <div class="search-again">
                    <p>Track another report?</p>
                    <form action="view_report.php" method="GET" class="search-form" style="margin-top: 15px;">
                        <input 
                            type="text" 
                            name="ref" 
                            placeholder="Enter reference code" 
                            required
                            class="search-input"
                        >
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="report-container">
                <h1 style="margin-bottom: 20px;">Track Your Report</h1>
                <p style="color: var(--text-secondary); margin-bottom: 30px;">
                    Enter your reference code to view the status of your report.
                </p>
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
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($report && $report['lat'] && $report['lon']): ?>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var lat = <?php echo $report['lat']; ?>;
            var lon = <?php echo $report['lon']; ?>;
            
            // Initialize map centered on the report location with a closer zoom
            var map = L.map('map').setView([lat, lon], 16);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19,
            }).addTo(map);
            
            // Add marker at the report location
            L.marker([lat, lon]).addTo(map)
                .bindPopup('<?php echo e(addslashes($report['address'])); ?>')
                .openPopup();
            
            // Add a circle to highlight the area (100m radius)
            L.circle([lat, lon], {
                color: '#3498db',
                fillColor: '#3498db',
                fillOpacity: 0.2,
                radius: 100
            }).addTo(map);
        });
    </script>
    <?php endif; ?>
</body>
</html>
