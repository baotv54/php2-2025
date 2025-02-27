<div class="container mt-5">
    <h2>Track Order</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'] ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <form action="/track_order" method="POST">
        <div class="mb-3">
            <label for="order_code" class="form-label">Order Code</label>
            <input type="text" class="form-control" id="order_code" name="order_code" required>
        </div>
        <button type="submit" class="btn btn-primary">Track Order</button>
    </form>
</div>