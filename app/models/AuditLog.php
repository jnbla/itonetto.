<?php
class AuditLog {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
        $this->ensureTable();
    }

    public function log($userId, $action, $entityType, $entityId, $description = null, $oldValues = null, $newValues = null) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $oldValuesJson = $oldValues ? json_encode($oldValues) : null;
        $newValuesJson = $newValues ? json_encode($newValues) : null;

        $stmt = $this->conn->prepare(
            "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, description, ip_address)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        
        $stmt->bind_param(
            "issiisss",
            $userId,
            $action,
            $entityType,
            $entityId,
            $oldValuesJson,
            $newValuesJson,
            $description,
            $ipAddress
        );

        return $stmt->execute();
    }

    public function getByEntity($entityType, $entityId, $limit = 50) {
        $stmt = $this->conn->prepare(
            "SELECT a.*, u.username FROM audit_logs a
             LEFT JOIN users u ON a.user_id = u.id
             WHERE a.entity_type = ? AND a.entity_id = ?
             ORDER BY a.created_at DESC
             LIMIT ?"
        );
        
        $stmt->bind_param("sii", $entityType, $entityId, $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getRecent($limit = 50) {
        $stmt = $this->conn->prepare(
            "SELECT a.*, u.username FROM audit_logs a
             LEFT JOIN users u ON a.user_id = u.id
             ORDER BY a.created_at DESC
             LIMIT ?"
        );
        
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getByUser($userId, $limit = 50) {
        $stmt = $this->conn->prepare(
            "SELECT a.*, u.username FROM audit_logs a
             LEFT JOIN users u ON a.user_id = u.id
             WHERE a.user_id = ?
             ORDER BY a.created_at DESC
             LIMIT ?"
        );
        
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getStats($days = 7) {
        $stmt = $this->conn->prepare(
            "SELECT 
                DATE(created_at) as date,
                COUNT(*) as total,
                action,
                entity_type
             FROM audit_logs
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
             GROUP BY DATE(created_at), action, entity_type
             ORDER BY date DESC"
        );
        
        $stmt->bind_param("i", $days);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    private function ensureTable() {
        $this->conn->query(
            "CREATE TABLE IF NOT EXISTS audit_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                action VARCHAR(50) NOT NULL,
                entity_type VARCHAR(50) NOT NULL,
                entity_id INT NOT NULL,
                old_values JSON NULL,
                new_values JSON NULL,
                description VARCHAR(255) NULL,
                ip_address VARCHAR(45) NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_id (user_id),
                INDEX idx_entity (entity_type, entity_id),
                INDEX idx_created_at (created_at),
                CONSTRAINT fk_audit_user
                    FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE
            )"
        );
    }
}
