<?php
/**
 * LEYECO III Utility Report System
 * Configuration File
 */

// Error Reporting (set to 0 in production)
error_reporting(0);
ini_set('display_errors', 0);

// Timezone
date_default_timezone_set('Asia/Manila');

// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'frostjad_leyeco_db');
define('DB_USER', getenv('DB_USER') ?: 'frostjad_leyeco_db');
define('DB_PASS', getenv('DB_PASS') ?: '2SPdmDLDYxT9vbFF5kct');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'LEYECO III Utility Report System');
define('APP_URL', getenv('APP_URL') ?: 'https://wh1494404.ispot.cc/Leyeco3_fault_report');

// File Upload Configuration
define('UPLOAD_DIR', __DIR__ . '/../public/assets/uploads/');
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB in bytes
define('UPLOAD_ALLOWED_TYPES', ['image/jpeg', 'image/jpg', 'image/png']);
define('UPLOAD_ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png']);

// Session Configuration
define('SESSION_LIFETIME', 3600 * 8); // 8 hours
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);
ini_set('session.cookie_lifetime', SESSION_LIFETIME);

// Security
define('CSRF_TOKEN_NAME', 'csrf_token');

// Pagination
define('REPORTS_PER_PAGE', 20);

// Municipality List
define('MUNICIPALITIES', [
    'Sta. Fe',
    'Alang-alang',
    'San Miguel',
    'Barugo',
    'Carigara',
    'Jaro',
    'Tunga',
    'Capoocan',
    'Pastrana',
]);

// Report Types
define('REPORT_TYPES', [
    'OUTAGE' => 'Power Outage',
    'TRANSFORMER_DAMAGE' => 'Transformer Damage',
    'WIRES_DOWN' => 'Wires Down',
    'HAZARD' => 'Electrical Hazard',
    'OTHER' => 'Other Issue',
]);

// Report Statuses
define('REPORT_STATUSES', [
    'NEW' => 'New',
    'INVESTIGATING' => 'Under Investigation',
    'RESOLVED' => 'Resolved',
    'CLOSED' => 'Closed',
]);

// Create upload directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
