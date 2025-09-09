<?php

class UserCardRepository
{
    public function __construct(private PDO $db) {}

    public function listByUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT id, card_id, name, image_url, set_name, multiverseid, quantity FROM user_cards WHERE user_id = :uid ORDER BY id DESC');
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function addOrIncrement(int $userId, string $cardId, string $name, ?string $imageUrl, ?string $setName, $multiverseId): void
    {
        $stmt = $this->db->prepare('INSERT INTO user_cards (user_id, card_id, name, image_url, set_name, multiverseid, quantity)
            VALUES (:uid, :cid, :name, :img, :setn, :mvid, 1)
            ON CONFLICT (user_id, card_id)
            DO UPDATE SET quantity = user_cards.quantity + 1');
        $stmt->execute([
            ':uid' => $userId,
            ':cid' => $cardId,
            ':name' => $name,
            ':img' => $imageUrl,
            ':setn' => $setName,
            ':mvid' => $multiverseId
        ]);
    }

    public function decrementOrDelete(int $userId, string $cardId): array
    {
        $dec = $this->db->prepare('UPDATE user_cards SET quantity = quantity - 1 WHERE user_id = :uid AND card_id = :cid AND quantity > 1');
        $dec->execute([':uid'=>$userId, ':cid'=>$cardId]);
        if ($dec->rowCount() > 0) {
            $q = $this->db->prepare('SELECT quantity FROM user_cards WHERE user_id = :uid AND card_id = :cid');
            $q->execute([':uid'=>$userId, ':cid'=>$cardId]);
            $row = $q->fetch(PDO::FETCH_ASSOC);
            return ['ok'=>true, 'quantity'=>(int)($row['quantity'] ?? 1)];
        }
        $del = $this->db->prepare('DELETE FROM user_cards WHERE user_id = :uid AND card_id = :cid');
        $del->execute([':uid'=>$userId, ':cid'=>$cardId]);
        if ($del->rowCount() > 0) { return ['ok'=>true, 'deleted'=>true]; }
        return ['error'=>'not_found'];
    }
}
