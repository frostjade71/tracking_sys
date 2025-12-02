<?php
/**
 * LEYECO III Tracking System
 * Root Index File - Redirects to Public Folder
 * 
 * Upload this to: public_html/Leyeco3_fault_report/index.php
 */

// Check if public/index.php exists, otherwise try public/homepage.php
if (file_exists(__DIR__ . '/public/index.php')) {
    header('Location: public/index.php');
} elseif (file_exists(__DIR__ . '/public/homepage.php')) {
    header('Location: public/homepage.php');
} else {
    // If neither exists, show error
    die('Error: Application files not found. Please check your file structure.');
}
exit;
