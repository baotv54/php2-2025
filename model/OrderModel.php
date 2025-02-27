<?php
require_once "Database.php";

class OrderModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getOrders()
    {
        $query = "SELECT * FROM orders";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderByUserId($user_id)
    {
        $query = "SELECT * FROM orders WHERE userId = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($user_id)
    {
        $query = "SELECT * FROM users WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getOrderItemsByOrderId($order_id)
    {
        $query = "SELECT * FROM order_items WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getOrderByCode($order_code)
    {
        $query = "SELECT * FROM orders WHERE code = :order_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_code', $order_code, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function createOrderDetail($order_id, $productvarian_id, $quantity, $price, $sku)
    {
        $query = "INSERT INTO order_items (order_id, productvarian_id, quantity, price, sku) VALUES (:order_id, :productvarian_id, :quantity, :price, :sku)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->bindParam(':productvarian_id', $productvarian_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':sku', $sku);
        return $stmt->execute();
    }

    public function updateOrderStatus($order_id, $status)
    {
        $query = "UPDATE orders SET status = :status WHERE id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status, PDO::PARAM_INT);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getOrderById($order_id)
    {
        $query = "SELECT * FROM orders WHERE id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Trừ số lượng sản phẩm khi đặt hàng
    // Trừ số lượng sản phẩm khi đặt hàng
    public function decreaseProductQuantity($productvarian_id, $quantity)
    {
        $query = "UPDATE productvarian SET quantity = quantity - :quantity WHERE id = :productvarian_id AND quantity >= :quantity";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':productvarian_id', $productvarian_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Tăng số lượng sản phẩm khi hủy đơn hàng
    public function increaseProductQuantity($productvarian_id, $quantity)
    {
        $query = "UPDATE productvarian SET quantity = quantity + :quantity WHERE id = :productvarian_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':productvarian_id', $productvarian_id, PDO::PARAM_INT);
        return $stmt->execute();
    }



    public function createOrder($userId, $code, $total, $address, $note, $status, $paymentMethod, $carts)
    {
        $query = "INSERT INTO orders (userId, code, total, address, note, status, paymentMethod, createDate) VALUES (:user_id, :code, :total, :address, :note, :status, :payment_method, :createDate)";
        $stmt = $this->conn->prepare($query);
        $date = date('Y-m-d H:i:s');
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':code', $code);
        $stmt->bindParam(':total', $total);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':payment_method', $paymentMethod);
        $stmt->bindParam(':createDate', $date, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $order_id = $this->conn->lastInsertId();
            foreach ($carts as $cart) {
                $this->createOrderDetail($order_id, $cart['productvarian_id'], $cart['quantity'], $cart['price'], $cart['sku']);
                // Trừ số lượng sản phẩm trong kho
                $this->decreaseProductQuantity($cart['productvarian_id'], $cart['quantity']);
            }
            return true;
        }
        return false;
    }
}
