<?php
/**
 * LEYECO III Utility Report System
 * Submit Report Page
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/ReportController.php';

$errors = [];
$success = false;
$referenceCode = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        // Validate required fields
        $description = sanitizeInput($_POST['description'] ?? '');
        $type = sanitizeInput($_POST['type'] ?? '');
        $municipality = sanitizeInput($_POST['municipality'] ?? '');
        $address = sanitizeInput($_POST['address'] ?? '');

        if (empty($description)) {
            $errors[] = 'Description is required';
        }
        if (empty($type) || !array_key_exists($type, REPORT_TYPES)) {
            $errors[] = 'Please select a valid report type';
        }
        if (empty($municipality)) {
            $errors[] = 'Municipality is required';
        }
        if (empty($address)) {
            $errors[] = 'Address is required';
        }

        // Process photo upload if provided
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = uploadPhoto($_FILES['photo']);
            if (!$uploadResult['success']) {
                $errors[] = $uploadResult['error'];
            } else {
                $photoPath = $uploadResult['path'];
            }
        }

        // If no errors, create report
        if (empty($errors)) {
            $reportController = new ReportController();
            $result = $reportController->create([
                'reporter_name' => sanitizeInput($_POST['reporter_name'] ?? null),
                'contact' => sanitizeInput($_POST['contact'] ?? null),
                'description' => $description,
                'type' => $type,
                'municipality' => $municipality,
                'address' => $address,
                'lat' => !empty($_POST['lat']) ? floatval($_POST['lat']) : null,
                'lon' => !empty($_POST['lon']) ? floatval($_POST['lon']) : null,
                'photo_path' => $photoPath
            ]);

            if ($result['success']) {
                $success = true;
                $referenceCode = $result['reference_code'];
            } else {
                $errors[] = $result['error'];
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
    <title>Submit Report - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="homepage.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 45px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border: 3px solid var(--accent-yellow);
            position: relative;
        }
        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(to right, var(--primary-red), var(--accent-yellow));
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--text-dark);
            font-size: 15px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--border-light);
            border-radius: 10px;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s ease;
            background: var(--white);
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
            transform: translateY(-2px);
        }
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        .form-group small {
            display: block;
            margin-top: 5px;
            color: var(--text-gray);
            font-size: 14px;
        }
        .required {
            color: var(--primary-red);
            font-weight: 800;
        }
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 35px;
        }
        .success-message {
            background: linear-gradient(135deg, var(--success-green), #059669);
            color: white;
            padding: 50px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.3);
            border: 3px solid var(--accent-yellow);
        }
        .success-message h2 {
            font-size: 36px;
            margin-bottom: 15px;
            font-weight: 800;
        }
        .reference-code {
            font-size: 32px;
            font-weight: 900;
            background: rgba(255, 255, 255, 0.25);
            padding: 20px 30px;
            border-radius: 12px;
            display: inline-block;
            margin: 25px 0;
            letter-spacing: 2px;
            border: 2px solid rgba(255, 255, 255, 0.5);
        }
        .error-list {
            background: linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%);
            border-left: 5px solid var(--primary-red);
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: var(--shadow);
        }
        .error-list ul {
            margin: 0;
            padding-left: 20px;
        }
        .error-list li {
            color: var(--primary-red-dark);
            margin-bottom: 5px;
            font-weight: 600;
        }
        .error-list strong {
            color: var(--primary-red-dark);
            font-weight: 800;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: var(--primary-red);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 16px;
        }
        .back-link:hover {
            color: var(--primary-red-dark);
            transform: translateX(-5px);
        }
        .form-container h1 {
            color: var(--primary-red);
            font-weight: 800;
            margin-bottom: 10px;
            font-size: 32px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="homepage.php" class="back-link">← Back to Home</a>

        <?php if ($success): ?>
            <div class="success-message">
                <h2>✅ Report Submitted Successfully!</h2>
                <p>Your report has been received. Please save this reference code:</p>
                <div class="reference-code"><?php echo e($referenceCode); ?></div>
                <p>You can use this code to track your report status.</p>
                <div style="margin-top: 30px;">
                    <a href="view_report.php?ref=<?php echo urlencode($referenceCode); ?>" class="btn btn-outline" style="color: white; border-color: white;">View Report Status</a>
                    <a href="submit_report.php" class="btn btn-secondary">Submit Another Report</a>
                </div>
            </div>
        <?php else: ?>
            <div class="form-container">
                <h1 style="margin-bottom: 10px;">Submit Trouble Report</h1>
                <p style="color: var(--text-secondary); margin-bottom: 30px;">
                    Report power outages, damaged equipment, or electrical hazards in your area.
                </p>

                <?php if (!empty($errors)): ?>
                    <div class="error-list">
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo e($csrfToken); ?>">

                    <div class="form-group">
                        <label>Your Name (Optional)</label>
                        <input type="text" name="reporter_name" value="<?php echo e($_POST['reporter_name'] ?? ''); ?>" placeholder="Enter your name">
                        <small>Optional - helps us contact you for updates</small>
                    </div>

                    <div class="form-group">
                        <label>Contact (Phone or Email) (Optional)</label>
                        <input type="text" name="contact" value="<?php echo e($_POST['contact'] ?? ''); ?>" placeholder="Phone number or email">
                        <small>Optional - for status updates</small>
                    </div>

                    <div class="form-group">
                        <label>Report Type <span class="required">*</span></label>
                        <select name="type" required>
                            <option value="">-- Select Type --</option>
                            <?php foreach (REPORT_TYPES as $key => $label): ?>
                                <option value="<?php echo e($key); ?>" <?php echo (($_POST['type'] ?? '') === $key) ? 'selected' : ''; ?>>
                                    <?php echo e($label); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Description <span class="required">*</span></label>
                        <textarea name="description" required placeholder="Describe the issue in detail..."><?php echo e($_POST['description'] ?? ''); ?></textarea>
                        <small>Please provide as much detail as possible</small>
                    </div>

                    <div class="form-group">
                        <label>Municipality <span class="required">*</span></label>
                        <select name="municipality" required>
                            <option value="">-- Select Municipality --</option>
                            <?php foreach (MUNICIPALITIES as $municipality): ?>
                                <option value="<?php echo e($municipality); ?>" <?php echo (($_POST['municipality'] ?? '') === $municipality) ? 'selected' : ''; ?>>
                                    <?php echo e($municipality); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Detailed Address <span class="required">*</span></label>
                        <input type="text" name="address" value="<?php echo e($_POST['address'] ?? ''); ?>" required placeholder="Street, landmarks, etc.">
                    </div>

                    <div class="form-group">
                        <label>GPS Coordinates (Optional)</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <input type="number" step="any" name="lat" value="<?php echo e($_POST['lat'] ?? ''); ?>" placeholder="Latitude">
                            <input type="number" step="any" name="lon" value="<?php echo e($_POST['lon'] ?? ''); ?>" placeholder="Longitude">
                        </div>
                        <small>Optional - helps us locate the issue faster</small>
                    </div>

                    <div class="form-group">
                        <label>Photo (Optional)</label>
                        <input type="file" name="photo" accept="image/jpeg,image/jpg,image/png">
                        <small>Max 5MB, JPG or PNG only</small>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large" style="flex: 1;">Submit Report</button>
                        <a href="homepage.php" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
