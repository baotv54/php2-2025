<h1>Verify OTP</h1>
<form method="POST">
    <input type="hidden" name="email" value="<?= htmlspecialchars($_GET['email']) ?>">
    <div class="mb-3">
        <label for="otp" class="form-label">OTP</label>
        <input type="text" class="form-control" id="otp" name="otp" required>
    </div>
    <button type="submit" class="btn btn-primary">Verify OTP</button>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3"><?= $error ?></div>
    <?php endif; ?>
</form>