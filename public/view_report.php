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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        .report-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
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
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
        }
        .status-NEW {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-INVESTIGATING {
            background: #fef3c7;
            color: #92400e;
        }
        .status-RESOLVED {
            background: #d1fae5;
            color: #065f46;
        }
        .status-CLOSED {
            background: #e5e7eb;
            color: #374151;
        }
        .report-details {
            display: grid;
            gap: 20px;
        }
        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
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
            margin-bottom: 20px;
            color: var(--text-primary);
        }
        .timeline-item {
            position: relative;
            padding-left: 30px;
            padding-bottom: 25px;
            border-left: 2px solid var(--border-color);
        }
        .timeline-item:last-child {
            border-left-color: transparent;
        }
        .timeline-dot {
            position: absolute;
            left: -7px;
            top: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
        }
        .timeline-content {
            background: var(--bg-color);
            padding: 15px;
            border-radius: 8px;
        }
        .timeline-date {
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 5px;
        }
        .timeline-message {
            color: var(--text-primary);
        }
        .timeline-user {
            font-size: 12px;
            color: var(--text-secondary);
            font-style: italic;
            margin-top: 5px;
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
            padding: 30px;
            border-radius: 12px;
            margin-top: 30px;
            text-align: center;
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
</body>
</html>
