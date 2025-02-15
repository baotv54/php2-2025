<?php
session_start();
require_once "controller/ProductController.php";
require_once "controller/CategoriesController.php";
require_once "controller/UsersController.php";
require_once "controller/ColorController.php";
require_once "controller/SizeController.php";
require_once "middleware.php";
require_once "controller/AuthController.php";
require_once "controller/ProductVariantController.php";
require_once "controller/CartController.php";
require_once "router/Router.php";


$router = new Router();
$authController = new AuthController();
$productController = new ProductController();
$colorController = new ColorController();
$sizeController = new SizeController();
$productVariantController = new ProductVariantController();
$cartController = new CartController();


$router->addMiddleware('logRequest');

$router->addRoute("/", [$productController, "home"]); // Accessible to all logged-in users
$router->addRoute("/products", [$productController, "index"], ['isUser']); // Accessible to all logged-in users
$router->addRoute("/products/create", [$productController, "create"], ['isAdmin']); // Admin only
$router->addRoute("/products/{id}", [$productController, "show"],);
$router->addRoute("/products/edit/{id}", [$productController, "edit"], ['isAdmin']); // Admin only
$router->addRoute("/products/delete/{id}", [$productController, "delete"], ['isAdmin']); // Admin only
# routers variant products
$router->addRoute("/products-variants/create/{id}", [$productVariantController, "create"]);

// Accessible to all logged-in users
$router->addRoute("/login", [$authController, "login"]);
$router->addRoute("/logout", [$authController, "logout"]);
$router->addRoute("/register", [$authController, "register"]);

// $router->addRoute("/login", [$authController, "login"]);
// $router->addRoute("/logout", [$authController, "logout"]);
// $router->addRoute("/register", [$authController, "register"]);
// $router->addRoute("/forgot-password", [$authController, "forgotPassword"]);
// $router->addRoute("/verify-otp", [$authController, "verifyOtp"]);
// $router->addRoute("/reset-password", [$authController, "resetPassword"]);

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


$router->dispatch();
