<h2 class="mb-4">Checkout</h2>

<!-- Display Cart Items -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Stt</th>
            <th>Sku</th>
            <th>Image</th>
            <th>Product Name</th>
            <th>Color</th>
            <th>Size</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        $stt = 1;
        ?>

        <?php foreach ($carts as $cart): ?>
            <tr>
                <td><?= $stt++ ?></td>
                <td><?= htmlspecialchars($cart['sku']) ?></td>
                <td>
                    <img src="<?= htmlspecialchars($cart['image']) ?>" alt="image 404" style="width: 100px">
                </td>
                <td><?= htmlspecialchars($cart['productName']) ?></td>
                <td><?= htmlspecialchars($cart['colorName']) ?></td>
                <td><?= htmlspecialchars($cart['sizeName']) ?></td>
                <td>
                    <form action="/carts/update/<?= htmlspecialchars($cart['id']) ?>" method="post" class="d-flex">
                        <input type="number" readonly value="<?= htmlspecialchars($cart['quantity']) ?>" class="form-control" min="0" style="width: 100px" name="quantity" />
                    </form>
                </td>
                <td><?= number_format($cart['price'], 2) ?></td>
                <td><?= number_format($cart['price'] * $cart['quantity'], 2) ?></td>
                <?php
                $total += $cart['price'] * $cart['quantity'];
                ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h4 class="text-end">Total: $<?= number_format($total, 2) ?></h4>

<!-- Checkout Form -->
<form action="/checkout" method="POST" class="mt-4">
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" id="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <!-- Hiển thị địa chỉ -->

    <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ giao hàng</label>
        <select name="address_id" id="address" class="form-control">
            <?php foreach ($addresses as $address): ?>
                <option value="<?= $address['id'] ?>" <?= $address['is_default'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($address['address']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <a href="manage_addresses" class="btn btn-link">Quản lý địa chỉ</a>
    </div>
    
    <div class="mb-3">
        <label for="note" class="form-label">Note</label>
        <textarea name="note" id="note" class="form-control" required></textarea>
    </div>
    <div class="mb-3">
        <label for="payment" class="form-label">Payment Method</label>
        <select name="payment" id="payment" class="form-control" required>
            <option value="cod">COD</option>
            <option value="vnpay">VNPAY</option>
            <option value="momo">MOMO</option>
            <option value="zalopay">ZALO PAY</option>
            <option value="paypal">PayPal</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Place Order</button>
    <a href="/carts" class="btn btn-secondary">Back to Cart</a>
</form>
