<div class="container mt-5">
    <h2>Track Order</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Code</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($order['id']) ?></td>
                <td><?= htmlspecialchars($order['code']) ?></td>
                <td><?= htmlspecialchars($order['status']) ?></td>
                <td><?= htmlspecialchars($order['total']) ?></td>
            </tr>
        </tbody>
    </table>
    <h3>Order Items</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
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
    <a href="/track_order" class="btn btn-secondary">Back to Track Order</a>
</div>