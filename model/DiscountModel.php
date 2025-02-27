<?php
require_once "Database.php";

class DiscountModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllDiscounts()
    {
        return $this->conn->query("SELECT * FROM discounts")->fetchAll();
    }

    public function getDiscountById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM discounts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createDiscount($code, $type, $value, $maxUses, $expiresAt)
    {
        $value = ($value === null || $value === '') ? 0 : floatval($value); // Đảm bảo không NULL
        $stmt = $this->conn->prepare("INSERT INTO discounts (code, discount_type, discount_value, max_uses, expires_at) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$code, $type, $value, $maxUses, $expiresAt]);
    }


    public function updateDiscount($id, $code, $type, $value, $maxUses, $expiresAt)
    {
        $value = ($value === null || $value === '') ? 0 : floatval($value);
        $stmt = $this->conn->prepare("UPDATE discounts SET code=?, discount_type=?, discount_value=?, max_uses=?, expires_at=? WHERE id=?");
        return $stmt->execute([$code, $type, $value, $maxUses, $expiresAt, $id]);
    }


    public function deleteDiscount($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM discounts WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getDiscountByCode($code) {
        $stmt = $this->conn->prepare("SELECT * FROM discounts WHERE code = ? AND expires_at > NOW() AND used_count < max_uses");
        $stmt->execute([$code]);
        return $stmt->fetch();
    }

    public function applyDiscount($code) {
        $stmt = $this->conn->prepare("UPDATE discounts SET used_count = used_count + 1 WHERE code = ?");
        return $stmt->execute([$code]);
    }
}
