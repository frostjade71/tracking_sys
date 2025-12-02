<?php
/**
 * LEYECO III Utility Report System
 * Report Controller
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

class ReportController {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    /**
     * Create new report
     */
    public function create($data) {
        try {
            $referenceCode = generateReferenceCode();
            
            $stmt = $this->db->prepare("
                INSERT INTO reports (reference_code, reporter_name, contact, description, type, municipality, address, lat, lon, photo_path)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $stmt->execute([
                $referenceCode,
                $data['reporter_name'] ?? null,
                $data['contact'] ?? null,
                $data['description'],
                $data['type'],
                $data['municipality'],
                $data['address'],
                $data['lat'] ?? null,
                $data['lon'] ?? null,
                $data['photo_path'] ?? null
            ]);

            $reportId = $this->db->lastInsertId();

            // Add initial comment
            $this->addComment($reportId, null, "Report submitted");

            logAudit('REPORT_CREATED', "Report {$referenceCode} created");

            return ['success' => true, 'reference_code' => $referenceCode, 'id' => $reportId];
        } catch (Exception $e) {
            error_log("Report creation error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to create report'];
        }
    }

    /**
     * Get report by reference code
     */
    public function getByReferenceCode($referenceCode) {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, u.name as assigned_to_name
                FROM reports r
                LEFT JOIN users u ON r.assigned_to = u.id
                WHERE r.reference_code = ?
            ");
            $stmt->execute([$referenceCode]);
            $report = $stmt->fetch();

            if ($report) {
                $report['comments'] = $this->getComments($report['id']);
            }

            return $report;
        } catch (Exception $e) {
            error_log("Get report error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all reports with filters
     */
    public function getAll($filters = [], $page = 1, $perPage = REPORTS_PER_PAGE) {
        try {
            $where = [];
            $params = [];

            if (!empty($filters['status'])) {
                $where[] = "status = ?";
                $params[] = $filters['status'];
            }

            if (!empty($filters['municipality'])) {
                $where[] = "municipality = ?";
                $params[] = $filters['municipality'];
            }

            if (!empty($filters['type'])) {
                $where[] = "type = ?";
                $params[] = $filters['type'];
            }

            if (!empty($filters['search'])) {
                $where[] = "(reference_code LIKE ? OR description LIKE ? OR address LIKE ?)";
                $searchTerm = "%{$filters['search']}%";
                $params[] = $searchTerm;
                $params[] = $searchTerm;
                $params[] = $searchTerm;
            }

            $whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

            // Get total count
            $countStmt = $this->db->prepare("SELECT COUNT(*) as total FROM reports $whereClause");
            $countStmt->execute($params);
            $total = $countStmt->fetch()['total'];

            // Get paginated results
            $offset = ($page - 1) * $perPage;
            $stmt = $this->db->prepare("
                SELECT r.*, u.name as assigned_to_name
                FROM reports r
                LEFT JOIN users u ON r.assigned_to = u.id
                $whereClause
                ORDER BY r.created_at DESC
                LIMIT ? OFFSET ?
            ");
            
            $params[] = $perPage;
            $params[] = $offset;
            $stmt->execute($params);
            $reports = $stmt->fetchAll();

            return [
                'reports' => $reports,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => ceil($total / $perPage)
            ];
        } catch (Exception $e) {
            error_log("Get reports error: " . $e->getMessage());
            return ['reports' => [], 'total' => 0, 'page' => 1, 'per_page' => $perPage, 'total_pages' => 0];
        }
    }

    /**
     * Update report status
     */
    public function updateStatus($reportId, $status, $userId = null) {
        try {
            $stmt = $this->db->prepare("UPDATE reports SET status = ? WHERE id = ?");
            $stmt->execute([$status, $reportId]);

            // Get report reference code
            $report = $this->getById($reportId);
            
            // Add comment about status change
            $this->addComment($reportId, $userId, "Status changed to: " . REPORT_STATUSES[$status]);

            logAudit('REPORT_STATUS_UPDATED', "Report {$report['reference_code']} status changed to {$status}", $userId);

            return ['success' => true];
        } catch (Exception $e) {
            error_log("Update status error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to update status'];
        }
    }

    /**
     * Add comment to report
     */
    public function addComment($reportId, $userId, $message) {
        try {
            $stmt = $this->db->prepare("INSERT INTO comments (report_id, user_id, message) VALUES (?, ?, ?)");
            $stmt->execute([$reportId, $userId, $message]);
            return ['success' => true];
        } catch (Exception $e) {
            error_log("Add comment error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to add comment'];
        }
    }

    /**
     * Get comments for a report
     */
    public function getComments($reportId) {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, u.name as user_name
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.report_id = ?
                ORDER BY c.created_at ASC
            ");
            $stmt->execute([$reportId]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Get comments error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Assign technician to report
     */
    public function assignTechnician($reportId, $userId, $assignedBy = null) {
        try {
            $stmt = $this->db->prepare("UPDATE reports SET assigned_to = ? WHERE id = ?");
            $stmt->execute([$userId, $reportId]);

            // Get user name
            $userStmt = $this->db->prepare("SELECT name FROM users WHERE id = ?");
            $userStmt->execute([$userId]);
            $user = $userStmt->fetch();

            // Add comment
            $this->addComment($reportId, $assignedBy, "Assigned to: " . $user['name']);

            $report = $this->getById($reportId);
            logAudit('REPORT_ASSIGNED', "Report {$report['reference_code']} assigned to user {$userId}", $assignedBy);

            return ['success' => true];
        } catch (Exception $e) {
            error_log("Assign technician error: " . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to assign technician'];
        }
    }

    /**
     * Get report by ID
     */
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, u.name as assigned_to_name
                FROM reports r
                LEFT JOIN users u ON r.assigned_to = u.id
                WHERE r.id = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Get report by ID error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get statistics
     */
    public function getStatistics() {
        try {
            $stats = [];

            // Total reports
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM reports");
            $stats['total'] = $stmt->fetch()['total'];

            // By status
            $stmt = $this->db->query("SELECT status, COUNT(*) as count FROM reports GROUP BY status");
            $stats['by_status'] = [];
            while ($row = $stmt->fetch()) {
                $stats['by_status'][$row['status']] = $row['count'];
            }

            // By type
            $stmt = $this->db->query("SELECT type, COUNT(*) as count FROM reports GROUP BY type");
            $stats['by_type'] = [];
            while ($row = $stmt->fetch()) {
                $stats['by_type'][$row['type']] = $row['count'];
            }

            // By municipality
            $stmt = $this->db->query("SELECT municipality, COUNT(*) as count FROM reports GROUP BY municipality ORDER BY count DESC LIMIT 10");
            $stats['by_municipality'] = $stmt->fetchAll();

            // Recent reports (last 7 days)
            $stmt = $this->db->query("SELECT COUNT(*) as count FROM reports WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $stats['recent'] = $stmt->fetch()['count'];

            return $stats;
        } catch (Exception $e) {
            error_log("Get statistics error: " . $e->getMessage());
            return [];
        }
    }
}
