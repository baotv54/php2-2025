<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="/send_otp" method="GET">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Send OTP</button>
    </form>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'] ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
</body>
</html>