<?php
require_once "Database.php";

class BannerModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllBanners()
    {
        $stmt = $this->conn->prepare("SELECT * FROM banners ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addBanner($image_url, $link)
    {
        $stmt = $this->conn->prepare("INSERT INTO banners (image_url, link) VALUES (?, ?)");
        return $stmt->execute([$image_url, $link]);
    }

    public function deleteBanner($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM banners WHERE id = ?");
        return $stmt->execute([$id]);
    }
    // update banner
    public function updateBanner($id, $image_url, $link)
    {
        $stmt = $this->conn->prepare("UPDATE banners SET image_url = ?, link = ? WHERE id = ?");
        return $stmt->execute([$image_url, $link, $id]);
    }
    public function getBannerById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM banners WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
