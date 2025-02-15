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

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];

            $this->productModel->updateProduct($id, $name, $description, $price);
            header("Location: /products");
        } else {
            $product = $this->productModel->getProductById($id);
            renderView("view/product_edit.php", compact('product'), "Edit Product");
        }
    }

    public function delete($id)
    {
        $this->productModel->deleteProduct($id);
        header("Location: /products");
        exit;
    }
}
