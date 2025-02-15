<h1>Reset Password</h1>
<form method="POST">
    <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email']) ?>">
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Reset Password</button>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3"><?= $error ?></div>
    <?php endif; ?>
</form>