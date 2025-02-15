<div class="container mt-5">
    <style>
        .card-img-container {
            width: 100%;
            height: 200px;
            /* Chiều cao cố định cho khung chứa hình ảnh */
            overflow: hidden;
            /* Ẩn phần hình ảnh vượt quá khung chứa */
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Đảm bảo hình ảnh bao phủ toàn bộ khung chứa mà không bị méo */
        }
    </style>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-img-container">
                        <img src="<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text">$<?= htmlspecialchars($product['price']) ?></p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="/products/<?= $product['id'] ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                            <small class="text-muted">SKU: <?= htmlspecialchars($product['sku']) ?></small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>