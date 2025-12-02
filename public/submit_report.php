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
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
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

        /* Mobile Styles */
        @media (max-width: 767px) {
            .form-container {
                padding: 25px 20px;
                margin: 20px 15px;
            }

            h1 {
                font-size: 24px;
                margin-bottom: 8px !important;
            }

            .form-container > p {
                font-size: 14px;
                margin-bottom: 20px !important;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-group label {
                font-size: 13px;
                margin-bottom: 5px;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 10px 14px;
                font-size: 14px;
                border-radius: 8px;
            }

            .form-group textarea {
                min-height: 100px;
            }

            .form-actions {
                flex-direction: column;
                gap: 10px;
                margin-top: 25px;
            }

            .btn {
                width: 100%;
                padding: 10px 16px;
                font-size: 14px;
            }

            .success-message {
                padding: 30px 20px;
                margin: 15px;
            }

            .success-message h2 {
                font-size: 24px;
            }

            .reference-code {
                font-size: 20px;
                padding: 15px 20px;
                margin: 15px 0;
            }

            .error-list {
                padding: 15px 20px;
                margin: 0 15px 20px;
            }
            
            .success-message {
                padding: 25px 20px;
                margin: 15px;
            }
            
            .success-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 15px;
            }
            
            .success-icon::after {
                font-size: 36px;
            }
            
            .success-message h2 {
                font-size: 20px;
                margin-bottom: 8px;
            }
            
            .success-message > p {
                font-size: 13px;
            }
            
            .reference-code-container {
                padding: 15px 12px;
                margin: 15px 0;
            }
            
            .reference-code-label {
                font-size: 11px;
                margin-bottom: 6px;
            }
            
            .reference-code {
                font-size: 18px;
                letter-spacing: 1.5px;
            }
            
            .success-actions {
                flex-direction: column;
                gap: 8px;
                margin-top: 20px;
            }
            
            .success-actions .btn {
                width: 100%;
                justify-content: center;
                padding: 10px 18px;
                font-size: 13px;
            }
        }
        .success-message {
            background: white;
            padding: 35px 30px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border: 2px solid var(--accent-yellow);
            position: relative;
            overflow: hidden;
        }
        
        .success-message::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to right, var(--primary-red), var(--accent-yellow), var(--success-green));
        }
        
        .success-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--success-green), #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
            animation: scaleIn 0.5s ease-out;
        }
        
        .success-icon::after {
            content: '✓';
            font-size: 42px;
            color: white;
            font-weight: bold;
        }
        
        @keyframes scaleIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        
        .success-message h2 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 800;
            color: var(--text-dark);
            background: linear-gradient(135deg, var(--success-green), #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .success-message > p {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 15px;
            font-weight: 500;
        }
        
        .reference-code-container {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            padding: 20px;
            border-radius: 12px;
            margin: 20px 0;
            border: 2px dashed var(--success-green);
        }
        
        .reference-code-label {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        
        .reference-code {
            font-size: 28px;
            font-weight: 900;
            color: var(--success-green);
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .success-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-top: 25px;
            flex-wrap: wrap;
        }
        
        .success-actions .btn {
            padding: 11px 22px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .success-actions .btn-primary {
            background: linear-gradient(135deg, var(--success-green), #059669);
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }
        
        .success-actions .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
        }
        
        .success-actions .btn-outline {
            background: white;
            color: var(--primary-red);
            border: 2px solid var(--primary-red);
        }
        
        .success-actions .btn-outline:hover {
            background: var(--primary-red);
            color: white;
            transform: translateY(-2px);
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
        
        /* Map Styles */
        #map {
            height: 400px;
            width: 100%;
            border-radius: 10px;
            border: 2px solid var(--border-light);
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        
        .map-info {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border-left: 4px solid var(--accent-yellow);
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 14px;
            color: #78350f;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .map-info::before {
            content: "📍";
            font-size: 18px;
        }
        
        .location-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, var(--primary-red), #b91c1c);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .location-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        
        .location-btn:active {
            transform: translateY(0);
        }
        
        .coordinates-display {
            background: #f9fafb;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #374151;
            border: 1px solid #e5e7eb;
        }
        
        @media (max-width: 767px) {
            #map {
                height: 300px;
            }
            
            .location-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="homepage.php" class="back-link">← Back to Home</a>

        <?php if ($success): ?>
            <div class="success-message">
                <div class="success-icon"></div>
                <h2>Report Submitted Successfully!</h2>
                <p>Your report has been received and is being processed. Please save your reference code below to track your report status.</p>
                
                <div class="reference-code-container">
                    <div class="reference-code-label">Your Reference Code</div>
                    <div class="reference-code"><?php echo e($referenceCode); ?></div>
                </div>
                
                <p style="color: var(--text-secondary); font-size: 14px; margin-top: 20px;">
                    💡 <strong>Tip:</strong> Screenshot or write down this code. You'll need it to check your report status.
                </p>
                
                <div class="success-actions">
                    <a href="view_report.php?ref=<?php echo urlencode($referenceCode); ?>" class="btn btn-primary">
                        <span>👁️</span> View Report Status
                    </a>
                    <a href="submit_report.php" class="btn btn-outline">
                        <span>📝</span> Submit Another Report
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="form-container">
                <h1 style="margin-bottom: 10px; font-size: 28px;">Submit Trouble Report</h1>
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
                    
                    <div style="border-top: 1px solid var(--primary-red-light); margin: 20px 0 30px;"></div>
                    
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
                        <label>Pin Point Fault Location on Map (Optional)</label>
                        <div class="map-info">
                            Click on the map or drag the marker to pinpoint the exact fault location
                        </div>
                        <button type="button" class="location-btn" onclick="useMyLocation()">
                            <span>📍</span> Use My Current Location
                        </button>
                        <div id="map"></div>
                        <div class="coordinates-display" id="coordinates-display">
                            📌 Coordinates: <span id="coord-text">Click on map to set location</span>
                        </div>
                        <!-- Hidden fields to store coordinates -->
                        <input type="hidden" name="lat" id="lat" value="<?php echo e($_POST['lat'] ?? ''); ?>">
                        <input type="hidden" name="lon" id="lon" value="<?php echo e($_POST['lon'] ?? ''); ?>">
                        <small>Optional - helps us locate the issue faster. The map shows Leyte, Philippines.</small>
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
    
    <script>
        // Initialize map centered on Leyte, Philippines
        var map = L.map('map').setView([11.2500, 124.8500], 10);
        
        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19,
        }).addTo(map);
        
        // Initialize marker variable
        var marker = null;
        
        // Check if there are existing coordinates from form submission
        var existingLat = document.getElementById('lat').value;
        var existingLon = document.getElementById('lon').value;
        
        if (existingLat && existingLon) {
            var lat = parseFloat(existingLat);
            var lon = parseFloat(existingLon);
            marker = L.marker([lat, lon], {draggable: true}).addTo(map);
            map.setView([lat, lon], 15);
            updateCoordinates(lat, lon);
            
            // Add drag event listener
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        }
        
        // Add click event to map
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lon = e.latlng.lng;
            
            // Remove existing marker if any
            if (marker) {
                map.removeLayer(marker);
            }
            
            // Add new draggable marker
            marker = L.marker([lat, lon], {draggable: true}).addTo(map);
            
            // Update coordinates
            updateCoordinates(lat, lon);
            
            // Add drag event listener
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        });
        
        // Function to update coordinate display and hidden fields
        function updateCoordinates(lat, lon) {
            document.getElementById('lat').value = lat.toFixed(6);
            document.getElementById('lon').value = lon.toFixed(6);
            document.getElementById('coord-text').textContent = 
                'Lat: ' + lat.toFixed(6) + ', Lon: ' + lon.toFixed(6);
        }
        
        // Function to use current location
        function useMyLocation() {
            if (navigator.geolocation) {
                // Show loading state
                var btn = event.target.closest('.location-btn');
                var originalText = btn.innerHTML;
                btn.innerHTML = '<span>⏳</span> Getting location...';
                btn.disabled = true;
                
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var lat = position.coords.latitude;
                        var lon = position.coords.longitude;
                        
                        // Remove existing marker if any
                        if (marker) {
                            map.removeLayer(marker);
                        }
                        
                        // Add new marker at user's location
                        marker = L.marker([lat, lon], {draggable: true}).addTo(map);
                        map.setView([lat, lon], 15);
                        
                        // Update coordinates
                        updateCoordinates(lat, lon);
                        
                        // Add drag event listener
                        marker.on('dragend', function(e) {
                            var position = marker.getLatLng();
                            updateCoordinates(position.lat, position.lng);
                        });
                        
                        // Restore button
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    },
                    function(error) {
                        alert('Unable to get your location. Please click on the map to set the location manually.');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 5000,
                        maximumAge: 0
                    }
                );
            } else {
                alert('Geolocation is not supported by your browser. Please click on the map to set the location manually.');
            }
        }
    </script>
</body>
</html>
