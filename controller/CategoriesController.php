<?php
require_once "model/CategoryModel.php";
require_once "view/helpers.php";

class CategoriesController {
    private $CategoriesModel;

    public function __construct() {
        $this->CategoriesModel = new CategoriesModel();
    }

    public function index() {
        $categories = $this->CategoriesModel->getAllCategories();
        renderView("view/categories_list.php", compact('categories'), "Categories List");
    }

    public function show($id) {
        $category = $this->CategoriesModel->getCategoryById($id);
        renderView("view/categories_detail.php", compact('category'), "Category Details");
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            if (!empty($name)) {
                $this->CategoriesModel->createCategory($name);
                $_SESSION['success'] = "Category created successfully!";
                header("Location: /categories");
                exit();
            } else {
                $_SESSION['error'] = "Category name is required.";
            }
        }
        renderView("view/Categories_create.php", [], "Create Category");
    }
    

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            if (!empty($name)) {
                $this->CategoriesModel->updateCategory($id, $name);
                $_SESSION['success'] = "Category updated successfully!";
                header("Location: /categories");
                exit();
            } else {
                $_SESSION['error'] = "Category name is required.";
            }
        }
        $category = $this->CategoriesModel->getCategoryById($id);
        renderView("view/Categories_edit.php", compact('category'), "Edit Category");
    }
    

    public function delete($id) {
        $this->CategoriesModel->deleteCategory($id);
        $_SESSION['success'] = "Category deleted successfully!";
        header("Location: /categories");
        exit();
    }
    
}
