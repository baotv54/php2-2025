<?php
require_once "model/UsersModel.php";
require_once "view/helpers.php";

class UsersController
{
    private $UsersModel;

    public function __construct()
    {
        $this->UsersModel = new UsersModel();
    }

    public function index()
    {
        $users = $this->UsersModel->getAllUsers();
        renderView("view/users_list.php", compact('users'), "Users List");
    }

    public function show($id)
    {
        $user = $this->UsersModel->getUserById($id);
        renderView("view/users_detail.php", compact('user'), "User Details");
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $role = trim($_POST['role']);
            $errors = [];

            if (empty($name)) {
                $errors[] = "Name is required.";
            }
            if (empty($email)) {
                $errors[] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (empty($password)) {
                $errors[] = "Password is required.";
            }
            if (empty($role)) {
                $errors[] = "Role is required.";
            }

            if (empty($errors)) {
                $this->UsersModel->createUser($name, $email, $password, $role);
                $_SESSION['success'] = "User created successfully with role: " . htmlspecialchars($role) . "!";
                header("Location: /users");
                exit();
            } else {
                $_SESSION['error'] = implode("<br>", $errors);
            }
        }
        renderView("view/users_create.php", [], "Create User");
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $role = trim($_POST['role']);
            $errors = [];

            if (empty($name)) {
                $errors[] = "Name is required.";
            }
            if (empty($email)) {
                $errors[] = "Email is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format.";
            }
            if (empty($role)) {
                $errors[] = "Role is required.";
            }

            if (empty($errors)) {
                $this->UsersModel->updateUser($id, $name, $email, $role);
                $_SESSION['success'] = "User updated successfully with role: " . htmlspecialchars($role) . "!";
                header("Location: /users");
                exit();
            } else {
                $_SESSION['error'] = implode("<br>", $errors);
            }
        }
        $user = $this->UsersModel->getUserById($id);
        renderView("view/users_edit.php", compact('user'), "Edit User");
    }

    public function delete($id)
    {
        $this->UsersModel->deleteUser($id);
        $_SESSION['success'] = "User deleted successfully!";
        header("Location: /users");
        exit();
    }

    // addAddress
    public function manageAddresses()
    {
        $user_id = $_SESSION['user']['id'] ?? null;
        if ($user_id) {
            $addresses = $this->UsersModel->getAddressesByUserId($user_id);
            renderView("view/user/addresses.php", compact('addresses'), "Manage Addresses");
        } else {
            header("Location: /login");
            exit();
        }
    }

    public function addAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'] ?? null;
            $address = $_POST['address'] ?? null;
            if ($user_id && $address) {
                $this->UsersModel->addAddress($user_id, $address);
            }
        }
        header("Location: /manage_addresses");
        exit();
    }

    public function editAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $address_id = $_POST['address_id'] ?? null;
            $address = $_POST['address'] ?? null;
            if ($address_id && $address) {
                $this->UsersModel->updateAddress($address_id, $address);
            }
        }
        header("Location: /manage_addresses");
        exit();
    }

    public function deleteAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $address_id = $_POST['address_id'] ?? null;
            if ($address_id) {
                $this->UsersModel->deleteAddress($address_id);
            }
        }
        header("Location: /manage_addresses");
        exit();
    }
    public function setDefaultAddress()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'] ?? null;
            $address_id = $_POST['address_id'] ?? null;
            if ($user_id && $address_id) {
                $this->UsersModel->setDefaultAddress($user_id, $address_id);
            }
        }
        header("Location: /manage_addresses");
        exit();
    }
}
