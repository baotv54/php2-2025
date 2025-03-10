<?php
require_once "Database.php";

class ProductVariantModel
{
    private $conn;


    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM productvarian WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function checkExistSku($sku)
    {
        $query = "SELECT * FROM productvarian WHERE sku = :sku";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sku', $sku);
        $stmt->execute();
        return ($stmt->fetchColumn() > 0);
    }

    public function getVariantByProductId($productId)
    {
        $query = "SELECT *, c.name as colorName, s.name as sizeName
         FROM productvarian p 
         INNER JOIN colors c on p.colorId = c.id
         INNER JOIN sizes s on p.sizeId = s.id
         WHERE p.product_id = :productId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':productId', $productId);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // print_r($result);
        // echo "</pre>"; // Debug dữ liệu
        return $result;
    }


    public function createVariants($product_id, $colorId, $sizeId, $image, $quantity, $price, $sku)
    {
        $query = "INSERT INTO productvarian (product_id, colorId, sizeId,image, quantity,price,sku) VALUES (:product_id, :colorId, :sizeId, :image, :quantity, :price, :sku)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':colorId', $colorId);
        $stmt->bindParam(':sizeId', $sizeId);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':sku', $sku);
        return $stmt->execute();
    }

    public function updateVariant($id, $product_id, $colorId, $sizeId, $image, $quantity, $price, $sku)
    {
        $query = "UPDATE productvarian 
                  SET product_id = :product_id, colorId = :colorId, sizeId = :sizeId, image = :image, 
                      quantity = :quantity, price = :price, sku = :sku 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':colorId', $colorId);
        $stmt->bindParam(':sizeId', $sizeId);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':sku', $sku);

        return $stmt->execute();
    }
}
