
    <div class="container mt-5">
        <h2>Edit Order Status</h2>
        <form action="/update_order_status" method="POST">
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="0" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="1" <?= $order['status'] == 'confirm' ? 'selected' : '' ?>>Confirm</option>
                    <option value="2" <?= $order['status'] == 'shipping' ? 'selected' : '' ?>>Shipping</option>
                    <option value="3" <?= $order['status'] == 'success' ? 'selected' : '' ?>>Success</option>
                    <option value="4" <?= $order['status'] == 'cancel' ? 'selected' : '' ?>>Cancel</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
            <a href="/orders" class="btn btn-secondary">Back to Orders</a>
        </form>
    </div>
