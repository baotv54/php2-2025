<?php
require_once "model/DiscountModel.php";
require_once "view/helpers.php";
class DiscountController
{
    private $discountModel;

    public function __construct()
    {
        $this->discountModel = new DiscountModel();
    }

    public function index()
    {
        $discounts = $this->discountModel->getAllDiscounts();
        renderView("view/discounts/list.php", compact('discounts'), "Manage Discounts");
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'];
            $type = $_POST['discount_type'];
            $value = isset($_POST['discount_value']) && $_POST['discount_value'] !== '' ? floatval($_POST['discount_value']) : 0;
            $maxUses = $_POST['max_uses'] !== '' ? intval($_POST['max_uses']) : 1;
            $expiresAt = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;

            $this->discountModel->createDiscount($code, $type, $value, $maxUses, $expiresAt);
            header("Location: /discounts");
            exit;
        }
        renderView("view/discounts/create.php", [], "Create Discount");
    }




    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'];
            $type = $_POST['discount_type'];
            $value = isset($_POST['discount_value']) && $_POST['discount_value'] !== '' ? floatval($_POST['discount_value']) : 0;
            $maxUses = $_POST['max_uses'] !== '' ? intval($_POST['max_uses']) : 1;
            $expiresAt = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;

            $this->discountModel->updateDiscount($id, $code, $type, $value, $maxUses, $expiresAt);
            header("Location: /discounts");
            exit;
        }
        $discount = $this->discountModel->getDiscountById($id);
        renderView("view/discounts/edit.php", compact('discount'), "Edit Discount");
    }


    public function applyDiscount()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['discount_code'] ?? '';
            $discount = $this->discountModel->getDiscountByCode($code);

            if ($discount) {
                $this->discountModel->applyDiscount($code);
                $_SESSION['discount'] = $discount;
                $_SESSION['success'] = "Discount applied successfully!";
            } else {
                $_SESSION['error'] = "Invalid or expired discount code.";
            }
            header("Location: /carts");
            exit;
        }
    }
}
