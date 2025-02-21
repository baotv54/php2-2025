<h1 class="text-center my-4">Giỏ hàng</h1>

<?php if (count($carts) == 0): ?>
    <div class="text-center py-5">
        <img src="/img/empty-cart.png" alt="Empty Cart" class="img-fluid" style="max-width: 200px;">
        <h5 class="mt-3">Giỏ hàng của bạn đang trống</h5>
        <a href="/" class="btn btn-primary mt-3">Mua sắm ngay</a>
    </div>
<?php else: ?>
    <div class="container bg-white p-3 rounded shadow-sm">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($carts as $cart): ?>
                    <?php $productImage = $cart['image'] ?? '/img/default-product.jpg'; ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?= htmlspecialchars($productImage) ?>" alt="Product" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                <div class="ms-2">
                                    <span><?= htmlspecialchars($cart['productName'] ?? 'No Name') ?></span><br>
                                    <small>Color: <?= htmlspecialchars($cart['colorName'] ?? 'N/A') ?></small><br>
                                    <small>Size: <?= htmlspecialchars($cart['sizeName'] ?? 'N/A') ?></small><br>
                                    <small>SKU: <?= htmlspecialchars($cart['sku'] ?? 'N/A') ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <form action="/carts/update/<?= htmlspecialchars($cart['id']) ?>" method="post" class="d-flex">
                                <input type="number" value="<?= htmlspecialchars($cart['quantity']) ?>" class="form-control text-center mx-2" min="1" style="width: 60px" name="quantity">
                                <button type="submit" class="btn btn-primary btn-sm ms-2">Cập nhật</button>
                            </form>
                        </td>
                        <td class="text-danger fw-bold">$<?= number_format($cart['price'], 2) ?></td>
                        <td class="text-danger fw-bold">$<?= number_format($cart['price'] * $cart['quantity'], 2) ?></td>
                        <?php $total += $cart['price'] * $cart['quantity']; ?>
                        <td>
                            <a href="/carts/delete/<?= htmlspecialchars($cart['id']) ?>" class="btn btn-outline-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center border-top pt-3">
            <div>
                <form id="clearCartForm" action="/carts/clear" method="POST">
                    <button type="submit" id="clearCartBtn" class="btn btn-danger">Xóa toàn bộ giỏ hàng</button>
                </form>
            </div>
            <div>
                <span class="fw-bold fs-5 me-3">Tổng cộng: <span class="text-danger">$<?= number_format($total, 2) ?></span></span>
                <a href="/checkout" class="btn btn-danger">Mua hàng</a>
            </div>
        </div>
    </div>
    <a href="/" class="btn btn-primary mt-3">Mua sắm ngay</a>
<?php endif; ?>