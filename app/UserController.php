<?php
/**
 * LEYECO III Utility Report System
 * User Controller
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

class UserController {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Create new user
     */
    public function create($name, $email, $password, $role = 'OPERATOR') {
        try {
            // Check if email already exists
            $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return ['success' => false, 'error' => 'Email already exists'];
            }

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $passwordHash, $role]);

            $userId = $this->db->lastInsertId();

            logAudit('USER_CREATED', "User {$email} created with role {$role}");

            return ['success' => true, 'id' => $userId];
        } catch (Exception $e) {
            error_log("User creation error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to create user'];
        }
    }

    /**
     * Get all users
     */
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Get users error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get user by ID
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Get user error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get user by email
     */
    public function getByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Get user by email error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update user role
     */
    public function updateRole($userId, $role) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$role, $userId]);

            $user = $this->getById($userId);
            logAudit('USER_ROLE_UPDATED', "User {$user['email']} role changed to {$role}");

            return ['success' => true];
        } catch (Exception $e) {
            error_log("Update role error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to update role'];
        }
    }

    /**
     * Delete user
     */
    public function delete($userId) {
        try {
            // Check if this is the last admin
            $user = $this->getById($userId);
            if ($user['role'] === 'ADMIN') {
                $stmt = $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'ADMIN'");
                $adminCount = $stmt->fetch()['count'];
                
                if ($adminCount <= 1) {
                    return ['success' => false, 'error' => 'Cannot delete the last admin user'];
                }
            }

            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$userId]);

            logAudit('USER_DELETED', "User {$user['email']} deleted");

            return ['success' => true];
        } catch (Exception $e) {
            error_log("Delete user error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to delete user'];
        }
    }

    /**
     * Get operators (for assignment dropdown)
     */
    public function getOperators() {
        try {
            $stmt = $this->db->query("SELECT id, name FROM users WHERE role IN ('OPERATOR', 'ADMIN') ORDER BY name");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Get operators error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Update password
     */
    public function updatePassword($userId, $newPassword) {
        try {
            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $stmt->execute([$passwordHash, $userId]);

            logAudit('PASSWORD_CHANGED', "Password changed for user ID {$userId}");

            return ['success' => true];
        } catch (Exception $e) {
            error_log("Update password error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to update password'];
        }
    }
}
