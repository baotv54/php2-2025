<?php
// Kiểm tra nếu biến $variant tồn tại và có dữ liệu
if (!isset($variant)) {
    $variant = [
        'product_id' => '',
        'sizeId' => '',
        'colorId' => '',
        'sku' => '',
        'price' => '',
        'quantity' => '',
        'image' => ''
    ];
}
?>

<h1>Edit Product Variant</h1>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="product_id" class="form-label">Product</label>
        <select class="form-select" id="product_id" name="product_id" required>
            <option value="">Select a Product</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= $product['id'] ?>" <?= ($variant['product_id'] == $product['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($product['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="size_id" class="form-label">Size</label>
        <select class="form-select" id="size_id" name="size_id" required>
            <option value="">Select a Size</option>
            <?php foreach ($sizes as $size): ?>
                <option value="<?= $size['id'] ?>" <?= ($variant['sizeId'] == $size['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($size['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="color_id" class="form-label">Color</label>
        <select class="form-select" id="color_id" name="color_id" required>
            <option value="">Select a Color</option>
            <?php foreach ($colors as $color): ?>
                <option value="<?= $color['id'] ?>" <?= ($variant['colorId'] == $color['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($color['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
<!-- <?php var_dump($variant)?> -->
    <div class="mb-3">
        <label for="sku" class="form-label">SKU</label>
        <input type="text" class="form-control" id="sku" name="sku" value="<?= htmlspecialchars($variant['sku']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($variant['price']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($variant['quantity']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" id="image" name="image">
        <?php if (!empty($variant['image'])): ?>
            <img src="<?= htmlspecialchars($variant['image']) ?>" alt="Image" width="100">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-success">Update</button>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mt-3">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</form>
