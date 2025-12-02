<?php
/**
 * Password Hash Generator
 * Run this script to generate password hashes for seed.sql
 */

$passwords = [
    'admin123' => password_hash('admin123', PASSWORD_DEFAULT),
    'operator123' => password_hash('operator123', PASSWORD_DEFAULT),
];

echo "Password Hashes Generated:\n\n";
foreach ($passwords as $plain => $hash) {
    echo "Password: $plain\n";
    echo "Hash: $hash\n\n";
}

echo "Copy these hashes to sql/seed.sql\n";
