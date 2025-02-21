<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
</head>
<body>
    <h2>Verify OTP</h2>
    <form action="/verify_otp" method="GET">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <label for="otp">OTP:</label>
        <input type="text" id="otp" name="otp" required>
        <button type="submit">Verify OTP</button>
    </form>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
</body>
</html>