<?php

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare(
            "SELECT id, username, email, role, created_at FROM users WHERE id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function isUsernameTaken($username, $excludeUserId = 0) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1"
        );
        $stmt->bind_param("si", $username, $excludeUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        return (bool) $result->fetch_assoc();
    }

    public function isEmailTaken($email, $excludeUserId = 0) {
        $stmt = $this->conn->prepare(
            "SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1"
        );
        $stmt->bind_param("si", $email, $excludeUserId);
        $stmt->execute();
        $result = $stmt->get_result();
        return (bool) $result->fetch_assoc();
    }

    public function updateProfile($id, $username, $email, $password = null) {
        if ($this->isUsernameTaken($username, $id)) {
            throw new Exception("Username sudah digunakan oleh pengguna lain.");
        }

        if ($this->isEmailTaken($email, $id)) {
            throw new Exception("Email sudah digunakan oleh pengguna lain.");
        }

        if ($password !== null && $password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare(
                "UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?"
            );
            $stmt->bind_param("sssi", $username, $email, $passwordHash, $id);
        } else {
            $stmt = $this->conn->prepare(
                "UPDATE users SET username = ?, email = ? WHERE id = ?"
            );
            $stmt->bind_param("ssi", $username, $email, $id);
        }

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan profil pengguna.");
        }

        return true;
    }
}
