<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Password</h2>
    <form action="/reset_password" method="POST">
        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
</body>
</html>