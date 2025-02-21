<?php
require_once "Database.php";

class CartModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getCart($user_id, $session_id)
    {
        $condition = !empty($user_id) ? "c.user_id = :user_id" : "c.cart_session = :cart_session";

        $query = "
        SELECT c.*, pv.id as productvarian_id, p.name as productName, pv.image, pv.sku, pv.colorId, col.name as colorName, pv.sizeId, s.name as sizeName
        FROM carts c
        JOIN productvarian pv ON c.sku = pv.sku
        JOIN products p ON pv.product_id = p.id
        LEFT JOIN colors col ON pv.colorId = col.id
        LEFT JOIN sizes s ON pv.sizeId = s.id
        WHERE $condition
        ";

        $stmt = $this->conn->prepare($query);
        if (!empty($user_id)) {
            $stmt->bindParam(':user_id', $user_id);
        } else {
            $stmt->bindParam(':cart_session', $session_id);
        }
        $stmt->execute();

        $carts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Gắn đường dẫn đầy đủ cho ảnh
        foreach ($carts as &$cart) {
            $cart['image_url'] = !empty($cart['image']) ? "/img/products/" . $cart['image'] : "/img/default-product.jpg";
        }

        return $carts;
    }

    public function addCart($user_id, $cart_session, $product_id, $name, $image, $sku, $quantity, $price)
    {
        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng hay chưa
        $query = "SELECT id, quantity FROM carts WHERE sku = :sku AND (user_id = :user_id OR cart_session = :cart_session)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sku', $sku);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':cart_session', $cart_session);
        $stmt->execute();
        $existingCart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingCart) {
            // Nếu sản phẩm đã tồn tại, cập nhật số lượng
            $newQuantity = $existingCart['quantity'] + $quantity;
            $query = "UPDATE carts SET quantity = :quantity WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':quantity', $newQuantity);
            $stmt->bindParam(':id', $existingCart['id']);
            return $stmt->execute();
        } else {
            // Nếu sản phẩm chưa tồn tại, thêm mới vào giỏ hàng
            $query = "INSERT INTO carts (user_id, cart_session, product_id, name, image, sku, quantity, price) VALUES (:user_id, :cart_session, :product_id, :name, :image, :sku, :quantity, :price)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':cart_session', $cart_session);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':sku', $sku);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':price', $price);
            return $stmt->execute();
        }
    }

    public function deleteCart($id)
    {
        $query = "DELETE FROM carts WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updateQuantity($id, $quantity)
    {
        $query = "UPDATE carts SET quantity = :quantity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function clearCart($user_id, $cart_session)
    {
        $condition = !empty($user_id) ? "user_id = :user_id" : "cart_session = :cart_session";

        $query = "DELETE FROM carts WHERE $condition";
        $stmt = $this->conn->prepare($query);
        if (!empty($user_id)) {
            $stmt->bindParam(':user_id', $user_id);
        } else {
            $stmt->bindParam(':cart_session', $cart_session);
        }
        return $stmt->execute();
    }
}