<?php
require_once "model/ColorModel.php";
require_once "view/helpers.php";

class ColorController {
    private $colorModel;

    public function __construct() {
        $this->colorModel = new ColorModel();
    }

    public function index() {
        $colors = $this->colorModel->getAll();
        renderView("view/color/list.php", compact('colors'), "Colors List");
    }

    public function show($id) {
        $color = $this->colorModel->getById($id);
        renderView("view/color/detail.php", compact('color'), "Color Detail");
    }

    public function create() {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            
            // Xác thực
            if (empty($name)) {
                $errors[] = "Color name is required.";
            }

            if (empty($errors)) {
                $this->colorModel->create($name);
                $_SESSION['success'] = "Color created successfully!";
                header("Location: /colors");
                exit();
            } else {
                $_SESSION['error'] = implode("<br>", $errors);
            }
        }
        renderView("view/color/create.php", [], "Create Color");
    }

    public function edit($id) {
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            
            // Xác thực
            if (empty($name)) {
                $errors[] = "Color name is required.";
            }

            if (empty($errors)) {
                $this->colorModel->update($id, $name);
                $_SESSION['success'] = "Color updated successfully!";
                header("Location: /colors");
                exit();
            } else {
                $_SESSION['error'] = implode("<br>", $errors);
            }
        } else {
            $color = $this->colorModel->getById($id);
            renderView("view/color/edit.php", compact('color'), "Edit Color");
        }
    }

    public function delete($id) {
        $this->colorModel->delete($id);
        $_SESSION['success'] = "Color deleted successfully!";
        header("Location: /colors");
        exit();
    }
}
