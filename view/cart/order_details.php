<div class="container mt-5">
    <h2>Chi tiết đơn hàng</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>SKU</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderItems as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['productvarian_id']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td><?= htmlspecialchars($item['price']) ?></td>
                    <td><?= htmlspecialchars($item['sku']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/order_history" class="btn btn-secondary">Quy lại lịch sử đơn hàng</a>
</div>