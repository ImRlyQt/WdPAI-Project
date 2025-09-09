<?php

class UserRepository
{
    public function __construct(private PDO $db) {}

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nick, email, password, dob, is_admin FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, nick, email, password, dob, is_admin FROM users WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(string $nick, string $email, string $passwordHash, string $dob, bool $isAdmin = false): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (nick, email, password, dob, is_admin) VALUES (:n, :e, :p, :d, :adm)');
        $stmt->execute([':n'=>$nick, ':e'=>$email, ':p'=>$passwordHash, ':d'=>$dob, ':adm'=>$isAdmin]);
        return (int)$this->db->lastInsertId('users_id_seq');
    }

    public function setAdminByEmail(string $email, string $passwordHash): void
    {
        $up = $this->db->prepare('UPDATE users SET password = :p, is_admin = TRUE WHERE email = :e');
        $up->execute([':p'=>$passwordHash, ':e'=>$email]);
    }

    public function promoteToAdmin(int $id): void
    {
        $this->db->prepare('UPDATE users SET is_admin = TRUE WHERE id = :id')->execute([':id'=>$id]);
    }
}
