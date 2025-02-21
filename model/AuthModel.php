<?php
require_once "Database.php";

class AuthModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function findUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($email, $password)
    {
        $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

    public function saveOtp($email, $otp)
    {
        $query = "UPDATE users SET otp = :otp WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':otp', $otp);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function verifyOtp($email, $otp)
    {
        $query = "SELECT * FROM users WHERE email = :email AND otp = :otp";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':otp', $otp);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE users SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }
}