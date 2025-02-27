<?php
require_once "model/CategoryModel.php";
require_once "view/helpers.php";
require_once "model/CartModel.php";
require_once 'model/OrderModel.php';
require_once 'model/UsersModel.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
    private $userModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->orderModel = new OrderModel();
        $this->userModel = new UsersModel();
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
                $address_id = $_POST['address_id'] ?? null;
                $address = $this->userModel->getAddressById($address_id)['address'] ?? null;
                $note = $_POST['note'] ?? null;
                $email = $_POST['email'] ?? null;
                $status = 0; // pending
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

                    // Gửi email thông báo đơn hàng mới
                    $user = $this->orderModel->getUserById($user_id);
                    $email_body = "
                    <h3>Xin chào {$user['name']},</h3>
                    <p>Đơn hàng <strong>{$code}</strong> của bạn đã được tạo thành công!</p>
                    <p>Tổng giá trị đơn hàng: <strong>{$total}</strong></p>
                    <p>Địa chỉ giao hàng: <strong>{$address}</strong></p>
                    <p>Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi!</p>
                ";
                    $this->sendEmail($user['email'], "Đơn hàng mới", $email_body);

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
            $addresses = $this->userModel->getAddressesByUserId($user_id);
            renderView("view/cart/checkout.php", compact('carts', 'addresses'), "Checkout");
        }
    }

    // view orders
    public function allOrders()
    {
        $orders = $this->orderModel->getOrders();

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

        renderView("view/cart/all_orders.php", compact('orders'), "All Orders");
    }

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
    // view order history
    public function orderHistory()
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

            renderView("view/cart/order_history.php", compact('orders'), "Order History");
        } else {
            header("Location: /login");
            exit();
        }
    }
    // track order
    public function trackOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_code = $_POST['order_code'];
            $order = $this->orderModel->getOrderByCode($order_code);

            if ($order) {
                $orderItems = $this->orderModel->getOrderItemsByOrderId($order['id']);

                // Mảng ánh xạ giá trị số nguyên thành giá trị chuỗi tương ứng trong enum StatusOrder
                $statusMap = [
                    0 => StatusOrder::Pending->value,
                    1 => StatusOrder::Confirm->value,
                    2 => StatusOrder::Shipping->value,
                    3 => StatusOrder::Success->value,
                    4 => StatusOrder::Cancel->value,
                ];

                $order['status'] = $statusMap[$order['status']] ?? 'Unknown';

                renderView("view/cart/track_order.php", compact('order', 'orderItems'), "Track Order");
            } else {
                $_SESSION['error'] = "Order not found!";
                header("Location: /track_order");
                exit();
            }
        } else {
            renderView("view/cart/track_order_form.php", [], "Track Order");
        }
    }
    // view order details
    public function orderDetails($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);
        $orderItems = $this->orderModel->getOrderItemsByOrderId($order_id);
        renderView("view/cart/order_details.php", compact('order', 'orderItems'), "Order Details");
    }
    // Edit order status
    public function editOrderStatus($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);
        renderView("view/cart/edit_order_status.php", compact('order'), "Edit Order Status");
    }
    private function sendEmail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $mail->Port       = $_ENV['MAIL_PORT'];

            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Lỗi gửi email: " . $mail->ErrorInfo);
            return false;
        }
    }


    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $order_id = $_POST['order_id'];
            $status = $_POST['status'];

            if ($this->orderModel->updateOrderStatus($order_id, $status)) {
                $_SESSION['success'] = "Trạng thái đơn hàng đã được cập nhật!";

                // Lấy thông tin đơn hàng và user
                $order = $this->orderModel->getOrderById($order_id);
                if (!$order) {
                    $_SESSION['error'] = "Không tìm thấy đơn hàng!";
                    header("Location: /orders");
                    exit();
                }

                $user = $this->orderModel->getUserById($order['userId']); // Đảm bảo đúng cột user_id
                if (!$user) {
                    $_SESSION['error'] = "Không tìm thấy thông tin người dùng!";
                    header("Location: /orders");
                    exit();
                }

                // Cập nhật số lượng sản phẩm trong kho
                $orderItems = $this->orderModel->getOrderItemsByOrderId($order_id);

                if ($status == 4 && !empty($orderItems)) { // Đơn hàng bị hủy & có sản phẩm
                    foreach ($orderItems as $item) {
                        $this->orderModel->increaseProductQuantity($item['productvarian_id'], $item['quantity']);
                    }
                }



                // Chuyển đổi trạng thái đơn hàng sang dạng dễ hiểu
                $status_labels = [
                    0 => "Đang chờ xử lý",
                    1 => "Đã xác nhận",
                    2 => "Đang giao hàng",
                    3 => "Hoàn thành",
                    4 => "Đã hủy"
                ];
                $status_text = $status_labels[$status] ?? "Không xác định";

                // Nội dung email
                $email_body = "
                <h3>Xin chào {$user['name']},</h3>
                <p>Đơn hàng <strong>{$order['code']}</strong> của bạn đã được cập nhật trạng thái:</p>
                <p><strong>{$status_text}</strong></p>
                <p>Cảm ơn bạn đã mua sắm tại cửa hàng chúng tôi!</p>
            ";

                // Gửi email thông báo
                if (!$this->sendEmail($user['email'], "Cập nhật trạng thái đơn hàng", $email_body)) {
                    $_SESSION['error'] = "Gửi email thất bại!";
                }
            } else {
                $_SESSION['error'] = "Cập nhật trạng thái thất bại!";
            }

            header("Location: /orders");
            exit();
        } else {
            header("Location: /orders");
            exit();
        }
    }

    // Thêm sản phẩm vào giỏ hàng
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

    // Cập nhật số lượng sản phẩm trong giỏ hàng
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
    // Xóa giỏ hàng
    public function delete($id)
    {
        $this->cartModel->deleteCart($id);
        $_SESSION['success'] = "Cart deleted successfully";
        header("Location: /carts");
        exit;
    }

    // Xóa toàn bộ giỏ hàng
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
