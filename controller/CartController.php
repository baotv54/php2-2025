<?php
require_once "model/CategoryModel.php";
require_once "view/helpers.php";
require_once "model/CartModel.php";
require_once 'model/OrderModel.php';




enum StatusOrder: string
{
    case Pending = 'pending';
    case Confirm = 'confirm';
    case Shipping = 'shipping';
    case Success = 'success';
    case Cancel = 'cancel';
}
class CartController
{
    private $cartModel;
    private $orderModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $user_id = $_SESSION['user']['id'] ?? null;
        $session_id = session_id();
        $carts = $this->cartModel->getCart($user_id, $session_id);

        // Nếu sản phẩm không có ảnh, gán ảnh mặc định
        foreach ($carts as &$cart) {
            $cart['image'] = $cart['image'] ?? '/img/default-product.jpg';
        }

        renderView("view/cart/list.php", compact('carts'), "Giỏ hàng");
    }

    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $payment = $_POST['payment'];
            if ($payment == 'cod') {
                $user_id = $_SESSION['user']['id'] ?? null;
                $session_id = session_id();
                $carts = $this->cartModel->getCart($user_id, $session_id);
                $total = 0;
                foreach ($carts as $cart) {
                    $total += $cart['price'] * $cart['quantity'];
                }
                $code = uniqid(); // code của order
                $address = $_POST['address'] ?? null;
                $note = $_POST['note'] ?? null;
                $email = $_POST['email'] ?? null;
                $status = 'pending';
                $isCreate = $this->orderModel->createOrder(
                    $user_id,
                    $code,
                    $total,
                    $address,
                    $note,
                    $status,
                    $payment,
                    $carts
                );
                if ($isCreate) {
                    $this->cartModel->clearCart($user_id, $session_id);    
                    header("Location: view/cart/thankyou.php");
                    exit();
                }
            } else if ($payment == 'vnpay') {
                // Xử lý thanh toán VNPAY
            }
        } else {
            $user_id = $_SESSION['user']['id'] ?? null;
            $session_id = session_id();
            $carts = $this->cartModel->getCart($user_id, $session_id);
            renderView("view/cart/checkout.php", compact('carts'), "Checkout");
        }
    }
    // view orders
    public function orders()
    {
        $user_id = $_SESSION['user']['id'] ?? null;
        if ($user_id) {
            $orders = $this->orderModel->getOrderByUserId($user_id);

            // Mảng ánh xạ giá trị số nguyên thành giá trị chuỗi tương ứng trong enum StatusOrder
            $statusMap = [
                0 => StatusOrder::Pending->value,
                1 => StatusOrder::Confirm->value,
                2 => StatusOrder::Shipping->value,
                3 => StatusOrder::Success->value,
                4 => StatusOrder::Cancel->value,
            ];

            foreach ($orders as &$order) {
                $order['status'] = $statusMap[$order['status']] ?? 'Unknown';
            }
            renderView("view/cart/orders.php", compact('orders'), "Your Orders");
        } else {
            header("Location: /login");
            exit();
        }
    }
    // public function editOrderStatus($order_id)
    // {
    //     $order = $this->orderModel->getOrderById($order_id);
    //     renderView("view/cart/edit_order_status.php", compact('order'), "Edit Order Status");
    // }

    // public function updateOrderStatus()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $order_id = $_POST['order_id'];
    //         $status = $_POST['status'];
    //         $this->orderModel->updateOrderStatus($order_id, $status);
    //         header("Location: /orders");
    //         exit();
    //     }
    // }

    // public function show($id) {
    //     $categories = $this->categoryModel->getCategoryById($id);
    //     renderView("view/category_detail.php", compact('categories'), "categories Detail");
    // }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['id'] ?? null;
            $cart_session = session_id();
            $product_id = $_POST['product_id'];
            $name = $_POST['name'] ?? 'No Name';
            $image = $_POST['image'] ?? '/img/default-product.jpg';
            $sku = $_POST['sku'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];

            $this->cartModel->addCart($user_id, $cart_session, $product_id, $name, $image, $sku, $quantity, $price);
            $_SESSION['success'] = "Cart added successfully";
            header("Location: /carts");
            exit();
        } else {
            renderView("view/cart/create.php", [], "Create Cart");
        }
    }

    public function updateQuantity($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantity = $_POST['quantity'];
            $this->cartModel->updateQuantity($id, $quantity);
            $_SESSION['success'] = "Cart updated successfully";
            header("Location: /carts");
            exit();
        } else {
            header("Location: /carts");
            exit();
        }
    }
    public function delete($id)
    {
        $this->cartModel->deleteCart($id);
        $_SESSION['success'] = "Cart deleted successfully";
        header("Location: /carts");
        exit;
    }

    public function clear()
    {
        $user_id = $_SESSION['user']['id'] ?? null;
        $cart_session = session_id();

        $this->cartModel->clearCart($user_id, $cart_session);
        $_SESSION['success'] = "Cart cleared successfully";
        header("Location: /carts");
        exit();
    }
}
