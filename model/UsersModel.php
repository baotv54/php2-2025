<?php
require_once "Database.php";

class UsersModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAddressesByUserId($user_id)
    {
        $query = "SELECT * FROM addresses WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAddressById($address_id)
    {
        $query = "SELECT address FROM addresses WHERE id = :address_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':address_id', $address_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAddress($user_id, $address, $is_default = false)
    {
        try {
            $query = "INSERT INTO addresses (user_id, address, is_default) VALUES (:user_id, :address, :is_default)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':is_default', $is_default, PDO::PARAM_BOOL);

            if (!$stmt->execute()) {
                error_log("SQL Error: " . json_encode($stmt->errorInfo()));
                return false;
            }
            return true;
        } catch (Exception $e) {
            error_log("Exception: " . $e->getMessage());
            return false;
        }
    }

    public function updateAddress($address_id, $address, $is_default = false)
    {
        $query = "UPDATE addresses SET address = :address, is_default = :is_default WHERE id = :address_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':address_id', $address_id, PDO::PARAM_INT);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':is_default', $is_default, PDO::PARAM_BOOL);
        return $stmt->execute();
    }

    public function deleteAddress($address_id)
    {
        $query = "DELETE FROM addresses WHERE id = :address_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':address_id', $address_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function setDefaultAddress($user_id, $address_id)
    {
        // Bỏ mặc định của tất cả địa chỉ thuộc user
        $query = "UPDATE addresses SET is_default = 0 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Đặt địa chỉ mới làm mặc định
        $query = "UPDATE addresses SET is_default = 1 WHERE id = :address_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':address_id', $address_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function createUser($name, $email, $password, $role = 'user')
    {
        $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_BCRYPT));
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function updateUser($id, $name, $email, $role)
    {
        $query = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
