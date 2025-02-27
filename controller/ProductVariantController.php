<?php
require_once "model/ProductModel.php";
require_once "model/ProductVariantsModel.php";
require_once "model/ColorModel.php";
require_once "model/SizeModel.php";
require_once "view/helpers.php";

class ProductVariantController
{
    private $productModel;
    private $sizeModel;
    private $colorModel;
    private $productVariantModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->sizeModel = new SizeModel();
        $this->colorModel = new ColorModel();
        $this->productVariantModel = new ProductVariantModel();
    }

    public function index()
    {
        $products = $this->productModel->getAllProducts();
        //compact: gom bien dien thanh array
        renderView("view/product_list.php", compact('products'), "Product List");
    }



    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        $product_variants = $this->productVariantModel->getVariantByProductId($id);


        renderView("view/product_detail.php", compact('product', 'product_variants'), "Product Detail");
    }

    public function create($id)
    {
        $message = "";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $product_id = $_POST['product_id'] ?? null;
            $colorId = $_POST['color_id'] ?? null;
            $sizeId = $_POST['size_id'] ?? null;
            $image = $_FILES['image']['name'] ?? null;
            $quantity = $_POST['quantity'] ?? null;
            $price = $_POST['price'] ?? null;
            $sku = $_POST['sku'] ?? null;
            if ($this->productVariantModel->checkExistSku($sku)) {
                $errors[] = "Sku is already exist";
                $products = $this->productModel->getAllProducts();
                $sizes = $this->sizeModel->getAll();
                $colors = $this->colorModel->getAll();
                renderView("view/producctsvariant/create.php", compact("products", "colors", "sizes", "errors"), "Create ProductVariants");
            }

            // Xử lý upload hình ảnh
            if ($image) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
                $originalName = $_FILES['image']['name'];
                $safeFileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $originalName); // Xóa ký tự đặc biệt
                $uploadPath = "uploads/" . $safeFileName;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
            }

            $this->productVariantModel->createVariants($product_id, $colorId, $sizeId, $target_file, $quantity, $price, $sku);
            $message = "<p class='alert alert-primary '>Create product variant successfully</p>";
            $_SESSION['message'] = $message;
            header("Location: /products");
            exit();
        } else {
            $products = $this->productModel->getAllProducts();
            $sizes = $this->sizeModel->getAll();
            $colors = $this->colorModel->getAll();

            renderView("view/productsvariant/create.php", compact("products", "colors", "sizes"), "Create ProductVariants");
        }
    }


    public function editVariant($id)
    {
        $variant = $this->productVariantModel->getVariantByProductId($id); // Lấy thông tin biến thể
        $products = $this->productModel->getAllProducts();
        $sizes = $this->sizeModel->getAll();
        $colors = $this->colorModel->getAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $colorId = $_POST['color_id'];
            $sizeId = $_POST['size_id'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            $sku = $_POST['sku'];

            // Xử lý upload ảnh nếu có
            if (!empty($_FILES['image']['name'])) {
                $target_dir = "uploads/";
                $safeFileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $_FILES['image']['name']);
                $uploadPath = $target_dir . $safeFileName;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
                $image = $uploadPath;
            } else {
                $image = $variant['image']; // Giữ nguyên ảnh cũ
            }

            $this->productVariantModel->updateVariant($id, $product_id, $colorId, $sizeId, $image, $quantity, $price, $sku);

            $_SESSION['message'] = "Product variant updated successfully!";
            header("Location: /products");
            exit();
        }

        renderView("view/productsvariant/edit.php", compact("variant", "products", "sizes", "colors"), "Edit Product Variant");
    }


    public function delete($id)
    {
        $this->productModel->deleteProduct($id);
        header("Location: /products");
        exit;
    }
}
