<?php
/**
 * LEYECO III Utility Report System
 * Authentication Controller
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

class AuthController {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Login user
     */
    public function login($email, $password) {
        try {
            // Debug: Log login attempt
            error_log("Login attempt for email: " . $email);
            
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            // Debug: Check if user was found
            if ($user) {
                error_log("User found in database");
                error_log("Stored hash: " . $user['password_hash']);
                error_log("Password verify result: " . (password_verify($password, $user['password_hash']) ? 'true' : 'false'));
            } else {
                error_log("No user found with email: " . $email);
                return ['success' => false, 'error' => 'Invalid email or password'];
            }

            if ($user && password_verify($password, $user['password_hash'])) {
                startSession();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];

                logAudit('USER_LOGIN', "User {$user['email']} logged in", $user['id']);

                return ['success' => true, 'user' => $user];
            } else {
                logAudit('LOGIN_FAILED', "Failed login attempt for {$email}");
                error_log("Password verification failed for user: " . $email);
                return ['success' => false, 'error' => 'Invalid email or password'];
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Login failed. Please try again.'];
        }
    }

    /**
     * Logout user
     */
    public function logout() {
        startSession();
        
        if (isset($_SESSION['user_id'])) {
            logAudit('USER_LOGOUT', "User logged out");
        }

        session_destroy();
        return ['success' => true];
    }

    /**
     * Check if user is authenticated
     */
    public function isAuthenticated() {
        startSession();
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($role) {
        startSession();
        return $this->isAuthenticated() && $_SESSION['user_role'] === $role;
    }

    /**
     * Check if user is admin
     */
    public function isAdmin() {
        return $this->hasRole('ADMIN');
    }

    /**
     * Get current user
     */
    public function getCurrentUser() {
        return getCurrentUser();
    }
}
