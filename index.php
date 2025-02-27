<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

require_once "controller/ProductController.php";
require_once "controller/CategoriesController.php";
require_once "controller/UsersController.php";
require_once "controller/ColorController.php";
require_once "controller/SizeController.php";
require_once "middleware.php";
require_once "controller/AuthController.php";
require_once "controller/ProductVariantController.php";
require_once "controller/CartController.php";
require_once "controller/BannerController.php";
require_once "controller/DiscountController.php";


require_once "router/Router.php";

$router = new Router();
$authController = new AuthController();
$productController = new ProductController();
$colorController = new ColorController();
$sizeController = new SizeController();
$productVariantController = new ProductVariantController();
$cartController = new CartController();
$bannerController = new BannerController();
$discountController = new DiscountController();

$router->addMiddleware('logRequest');

$router->addRoute("/", [$productController, "home"]); // Accessible to all logged-in users
$router->addRoute("/products", [$productController, "index"], ['isUser']); // Accessible to all logged-in users
$router->addRoute("/products/create", [$productController, "create"], ['isAdmin']); // Admin only
$router->addRoute("/products/{id}", [$productController, "show"],);
$router->addRoute("/products/edit/{id}", [$productController, "edit"], ['isAdmin']); // Admin only
$router->addRoute("/products/delete/{id}", [$productController, "delete"], ['isAdmin']); // Admin only
# routers variant products
$router->addRoute("/products-variants/create/{id}", [$productVariantController, "create"]);
$router->addRoute('/products-variants/edit/{id}', [$productVariantController, "editVariant"]);


// Accessible to all logged-in users
$router->addRoute('/forgot_password', [$authController, "showForgotPasswordForm"]);
$router->addRoute('/send_otp', [$authController, "sendOtp"]);
$router->addRoute('/verify_otp', [$authController, "showVerifyOtpForm"]);
$router->addRoute('/verify_otp', [$authController, "verifyOtp"]);
$router->addRoute('/reset_password', [$authController, "showResetPasswordForm"]);
$router->addRoute('/reset_password', [$authController, "resetPassword"]);
$router->addRoute('/login', [$authController, "login"]);
$router->addRoute('/logout', [$authController, "logout"]);
$router->addRoute('/register', [$authController, "register"]);
// routes categories
$categoryController = new CategoriesController();
$router->addRoute("/categories", [$categoryController, "index"]);
$router->addRoute("/categories/create", [$categoryController, "create"], ['isAdmin']);
$router->addRoute("/categories/{id}", [$categoryController, "show"], ['isAdmin']);
$router->addRoute("/categories/edit/{id}", [$categoryController, "edit"], ['isAdmin']);
$router->addRoute("/categories/delete/{id}", [$categoryController, "delete"], ['isAdmin']);

// routes users
$userController = new UsersController();
$router->addRoute("/users", [$userController, "index"], ['isAdmin']);
$router->addRoute("/users/create", [$userController, "create"], ['isAdmin']);
$router->addRoute("/users/{id}", [$userController, "show"], ['isAdmin']);
$router->addRoute("/users/edit/{id}", [$userController, "edit"], ['isAdmin']);
$router->addRoute("/users/delete/{id}", [$userController, "delete"], ['isAdmin']);
$router->addRoute('/manage_addresses', [$userController, "manageAddresses"]);
$router->addRoute('/add_address', [$userController, "addAddress"]);
$router->addRoute('/edit_address', [$userController, "editAddress"]);
$router->addRoute('/delete_address', [$userController, "deleteAddress"]);
$router->addRoute('/set_default_address', [$userController, "setDefaultAddress"]);


// colors
$router->addRoute("/colors", [$colorController, "index"], ['isAdmin']);
$router->addRoute("/colors/create", [$colorController, "create"], ['isAdmin']);
$router->addRoute("/colors/{id}", [$colorController, "show"], ['isAdmin']);
$router->addRoute("/colors/edit/{id}", [$colorController, "edit"], ['isAdmin']);
$router->addRoute("/colors/delete/{id}", [$colorController, "delete"], ['isAdmin']);

// sizes
$router->addRoute("/sizes", [$sizeController, "index"], ['isAdmin']);
$router->addRoute("/sizes/create", [$sizeController, "create"], ['isAdmin']);
$router->addRoute("/sizes/{id}", [$sizeController, "show"], ['isAdmin']);
$router->addRoute("/sizes/edit/{id}", [$sizeController, "edit"], ['isAdmin']);
$router->addRoute("/sizes/delete/{id}", [$sizeController, "delete"], ['isAdmin']);

// carts
$router->addRoute("/carts", [$cartController, "index"]);
$router->addRoute("/carts/delete/{id}", [$cartController, "delete"]);
$router->addRoute('/carts/create', [$cartController, "create"]);
$router->addRoute('/carts/update/{id}', [$cartController, "updateQuantity"]);
$router->addRoute("/carts/clear", [$cartController, "clear"]);


//checkout
$router->addRoute('/checkout', [$cartController, "checkout"]);
$router->addRoute('/orders', [$cartController, "orders"], ['isAdmin']);
$router->addRoute('/edit_order_status/{order_id}', [$cartController, "editOrderStatus"], ['isAdmin']);
$router->addRoute('/update_order_status', [$cartController, "updateOrderStatus"], ['isAdmin']);
$router->addRoute('/order_history', [$cartController, "orderHistory"]);
$router->addRoute('/order_details/{order_id}', [$cartController, "orderDetails"]);
$router->addRoute('/track_order', [$cartController, "trackOrder"]);

// banner
$router->addRoute('/banners', [$bannerController, "index"]);
$router->addRoute('/banners/create', [$bannerController, "create"], ['isAdmin']);
// $router->addRoute('/banners/{id}', [$bannerController, "show"], ['isAdmin']);
$router->addRoute('/banners/edit/{id}', [$bannerController, "edit"], ['isAdmin']);
$router->addRoute('/banners/delete/{id}', [$bannerController, "delete"], ['isAdmin']);

// discounts
$router->addRoute('/discounts', [$discountController, "index"], ['isAdmin']);
$router->addRoute('/discounts/create', [$discountController, "create"], ['isAdmin']);
// $router->addRoute('/discounts/{id}', [$discountController, "show"], ['isAdmin']);
$router->addRoute('/discounts/edit/{id}', [$discountController, "edit"], ['isAdmin']);
$router->addRoute('/discounts/delete/{id}', [$discountController, "delete"], ['isAdmin']);
$router->addRoute('/discounts/apply', [$discountController, "applyDiscount"]);
$router->addRoute('/discounts/apply_all', [$discountController, "applyDiscountAll"]);

$router->dispatch();
