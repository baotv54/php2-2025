<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa trạng thái đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Chỉnh sửa trạng thái đơn hàng</h2>
        <form action="/update_order_status" method="POST">
            <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái đơn hàng</label>
                <select class="form-select" name="status" id="status">
                    <option value="0" <?= $order['status'] == 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                    <option value="1" <?= $order['status'] == 1 ? 'selected' : '' ?>>Đã xác nhận</option>
                    <option value="2" <?= $order['status'] == 2 ? 'selected' : '' ?>>Đang giao</option>
                    <option value="3" <?= $order['status'] == 3 ? 'selected' : '' ?>>Giao thành công</option>
                    <option value="4" <?= $order['status'] == 4 ? 'selected' : '' ?>>Đã hủy</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="/orders" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
