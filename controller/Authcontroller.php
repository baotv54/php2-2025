<?php
require_once "model/AuthModel.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private $authModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->authModel->findUserByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                header("Location: /");
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password.";
                header("Location: /login");
                exit();
            }
        } else {
            renderView("view/auth/login.php");
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /login");
        exit();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            if ($this->authModel->createUser($email, $hashedPassword)) {
                header("Location: /login");
                exit();
            } else {
                $_SESSION['error'] = "Failed to register.";
                header("Location: /register");
                exit();
            }
        } else {
            renderView("view/auth/register.php");
        }
    }

    public function showForgotPasswordForm()
    {
        renderView("view/auth/forgot_password.php");
    }

    public function sendOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $email = $_GET['email'];
            $user = $this->authModel->findUserByEmail($email);

            if ($user) {
                $otp = rand(100000, 999999);
                $this->authModel->saveOtp($email, $otp);
                // Gửi OTP qua email
                $this->sendEmail($email, "Your OTP Code", "Your OTP code is: $otp");
                header("Location: /verify_otp?email=$email");
                exit();
            } else {
                $_SESSION['error'] = "Email not found.";
                header("Location: /forgot_password");
                exit();
            }
        }
    }

    private function sendEmail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST']; // Set the SMTP server to send through
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME']; // SMTP username
            $mail->Password   = $_ENV['MAIL_PASSWORD']; // SMTP password
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $mail->Port       = $_ENV['MAIL_PORT'];

            //Recipients
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function showVerifyOtpForm()
    {
        $email = $_GET['email'];
        renderView("view/auth/verify_otp.php", compact('email'));
    }

    public function verifyOtp()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $email = $_GET['email'];
            $otp = isset($_GET['otp']) ? $_GET['otp'] : null;

            $user = $this->authModel->verifyOtp($email, $otp);
            if ($user) {
                header("Location: /reset_password?email=$email");
                exit();
            } else {
                $error = "Invalid OTP.";
            }
        }
        renderView("view/auth/verify_otp.php", compact('error', 'email'), "Verify OTP");
    }

    public function showResetPasswordForm()
    {
        $email = $_GET['email'];
        renderView("view/auth/reset_password.php", compact('email'));
    }

    public function resetPassword()
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->authModel->updatePassword($email, $password)) {
                header("Location: /login");
                exit();
            } else {
                $error = "Failed to reset password.";
            }
        } else {
            $email = $_GET['email'];
            renderView("view/auth/reset_password.php", compact('email', 'error'), "Reset Password");
        }
    }
}
