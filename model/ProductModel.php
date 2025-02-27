<?php
require_once "Database.php";

class ProductModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function getAllProducts()
    {
        $sql = "
        SELECT p.id, p.name, p.price, pv.image, pv.sku
        FROM products p
        LEFT JOIN productvarian pv ON p.id = pv.product_id
        GROUP BY p.id
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id)
    {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createProduct($name, $description, $price)
    {
        $query = "INSERT INTO products (name, description, price) VALUES (:name, :description, :price)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }

    public function updateProduct($id, $name, $description, $price)
    {
        $query = "UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        return $stmt->execute();
    }


    public function deleteProduct($id)
    {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function filterProducts($search = null, $category = null, $price = null, $sort = null)
    {
        $query = "SELECT p.id, p.name, p.price, MIN(pv.image) AS image, c.name AS category_name
              FROM products p
              LEFT JOIN categories c ON p.category_id = c.id
              LEFT JOIN productvarian pv ON p.id = pv.product_id
              WHERE 1=1";

        $params = [];

        // 🔍 Tìm kiếm theo tên sản phẩm
        if (!empty($search)) {
            $query .= " AND p.name LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        if (!empty($category)) {
            $query .= " AND p.category_id = :category";
            $params[':category'] = $category;
        }

        if (!empty($price)) {
            if ($price === 'low') {
                $query .= " AND p.price < 50000";
            } elseif ($price === 'mid') {
                $query .= " AND p.price BETWEEN 50000 AND 100000";
            } elseif ($price === 'high') {
                $query .= " AND p.price > 100000";
            }
        }

        $query .= " GROUP BY p.id";

        // 🏷 Sắp xếp theo yêu cầu
        if (!empty($sort)) {
            if ($sort === 'price_asc') {
                $query .= " ORDER BY p.price ASC";
            } elseif ($sort === 'price_desc') {
                $query .= " ORDER BY p.price DESC";
            } elseif ($sort === 'name_asc') {
                $query .= " ORDER BY p.name ASC";
            } elseif ($sort === 'name_desc') {
                $query .= " ORDER BY p.name DESC";
            }
        } else {
            $query .= " ORDER BY p.id DESC"; // Mặc định hiển thị sản phẩm mới nhất
        }

        $stmt = $this->conn->prepare($query);

        // Gán giá trị cho tham số
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function searchProducts($search)
    {
        $query = "SELECT * FROM products WHERE name LIKE :search";
        $stmt = $this->conn->prepare($query);

        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories()
    {
        $query = "SELECT * FROM categories";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Lấy danh sách sản phẩm liên quan
    public function getRelatedProducts($productId, $categoryId, $limit = 4)
    {
        $query = "SELECT p.id, p.name, p.price, MIN(pv.image) AS image
              FROM products p
              LEFT JOIN productvarian pv ON p.id = pv.product_id
              WHERE p.category_id = :category_id AND p.id != :product_id
              GROUP BY p.id
              ORDER BY RAND() 
              LIMIT :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Hàm lấy danh sách sản phẩm sau khi lọc
    public function getFilteredProducts($color, $size, $minPrice, $maxPrice, $sort)
    {
        $query = "SELECT DISTINCT p.* FROM products p 
                  LEFT JOIN product_variants pv ON p.id = pv.product_id WHERE 1";

        if (!empty($color)) {
            $query .= " AND pv.colorName = :color";
        }
        if (!empty($size)) {
            $query .= " AND pv.sizeName = :size";
        }
        if (!empty($minPrice)) {
            $query .= " AND p.price >= :minPrice";
        }
        if (!empty($maxPrice)) {
            $query .= " AND p.price <= :maxPrice";
        }

        // Thêm sắp xếp
        if ($sort == 'price_asc') {
            $query .= " ORDER BY p.price ASC";
        } elseif ($sort == 'price_desc') {
            $query .= " ORDER BY p.price DESC";
        } elseif ($sort == 'name_asc') {
            $query .= " ORDER BY p.name ASC";
        } elseif ($sort == 'name_desc') {
            $query .= " ORDER BY p.name DESC";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($color)) $stmt->bindParam(':color', $color);
        if (!empty($size)) $stmt->bindParam(':size', $size);
        if (!empty($minPrice)) $stmt->bindParam(':minPrice', $minPrice);
        if (!empty($maxPrice)) $stmt->bindParam(':maxPrice', $maxPrice);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableColors()
    {
        return $this->conn->query("SELECT DISTINCT colorName FROM product_variants")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAvailableSizes()
    {
        return $this->conn->query("SELECT DISTINCT sizeName FROM product_variants")->fetchAll(PDO::FETCH_ASSOC);
    }
}
