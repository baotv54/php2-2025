    <?php
    require_once "model/ProductModel.php";
    require_once "model/ProductVariantsModel.php";
    require_once "model/BannerModel.php";

    require_once "view/helpers.php";

    class ProductController
    {
        private $productModel;
        private $productVariantsModel;
        private $bannerModel;

        public function __construct()
        {
            $this->productModel = new ProductModel();
            $this->productVariantsModel = new ProductVariantModel();
            $this->bannerModel = new BannerModel();
        }
        public function home()
        {
            $search = isset($_GET['search']) ? $_GET['search'] : null;
            $category = isset($_GET['category']) ? $_GET['category'] : null;
            $price = isset($_GET['price']) ? $_GET['price'] : null;
            $sort = isset($_GET['sort']) ? $_GET['sort'] : null;

            // Gọi hàm tìm kiếm, lọc và sắp xếp trong Model
            $products = $this->productModel->filterProducts($search, $category, $price, $sort);
            $categories = $this->productModel->getAllCategories(); // Lấy danh sách danh mục
            $banners = $this->bannerModel->getAllBanners();

            renderView("view/home.php", compact('products', 'categories','banners'), "Home");
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

            if (!$product) {
                die("Sản phẩm không tồn tại.");
            }

            // Lấy danh sách biến thể của sản phẩm
            $product_variants = $this->productVariantsModel->getVariantByProductId($id);

            // Lấy danh sách sản phẩm liên quan
            $relatedProducts = $this->productModel->getRelatedProducts($id, $product['category_id']);

            // Hiển thị dữ liệu ra giao diện
            renderView("view/product_detail.php", compact('product', 'product_variants', 'relatedProducts'), "Chi tiết sản phẩm");
        }
        

        public function create()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $description = $_POST['description'];
                $price = $_POST['price'];

                $this->productModel->createProduct($name, $description, $price);
                header("Location: /products");
            } else {
                renderView("view/product_create.php", [], "Create Product");
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
