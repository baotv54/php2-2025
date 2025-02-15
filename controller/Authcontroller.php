<?php
require_once "model/AuthModel.php";
require_once "view/helpers.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function register() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'] ?? 'user';

            if ($this->userModel->register($name, $email, $password, $role)) {
                header("Location: /login");
                exit;
            } else {
                $error = "Registration failed. Email may already be in use.";
            }
        }
        renderView("view/auth/register.php", compact('error'), "Register");
    }

    public function login() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: /");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        }
        renderView("view/auth/login.php", compact('error'), "Login");
    }

    public function logout() {
        session_destroy();
        header("Location: /login");
        exit;
    }

    public function dashboard() {
        if (!isset($_SESSION['user'])) {
            header("Location: /login");
            exit;
        }

        $user = $_SESSION['user'];
        renderView("view/auth/dashboard.php", compact('user'), "Dashboard");
    }

    public function forgotPassword() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $user = $this->userModel->findUserByEmail($email);

            if ($user) {
                $otp = rand(100000, 999999);
                $this->userModel->saveOtp($email, $otp);
                // Gửi email chứa mã OTP
                mail($email, "Your OTP Code", "Your OTP code is: $otp");
                header("Location: /verify-otp?email=$email");
                exit;
            } else {
                $error = "Email not found.";
            }
        }
        renderView("view/auth/forgot_password.php", compact('error'), "Forgot Password");
    }

    public function verifyOtp() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $otp = $_POST['otp'];
            $user = $this->userModel->verifyOtp($email, $otp);

            if ($user) {
                header("Location: /reset-password?email=$email");
                exit;
            } else {
                $error = "Invalid OTP.";
            }
        }
        renderView("view/auth/verify_otp.php", compact('error'), "Verify OTP");
    }

    public function resetPassword() {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->userModel->updatePassword($email, $password)) {
                header("Location: /login");
                exit;
            } else {
                $error = "Failed to reset password.";
            }
        }
        renderView("view/auth/reset_password.php", compact('error'), "Reset Password");
    }
}
?>