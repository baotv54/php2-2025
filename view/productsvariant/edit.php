<h1>Edit Product Variant</h1>
<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="product_id" class="form-label">Product</label>
        <select class="form-select" id="product_id" name="product_id" required>
            <option value="">Select a Product</option>
            <?php foreach ($products as $product): ?>
                <option value="<?= $product['id'] ?>" <?= isset($variant['product_id']) && $variant['product_id'] == $product['id'] ? 'selected' : '' ?>><?= $product['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="size_id" class="form-label">Size</label>
        <select class="form-select" id="size_id" name="size_id" required>
            <option value="">Select a Size</option>
            <?php foreach ($sizes as $size): ?>
                <option value="<?= $size['id'] ?>" <?= isset($variant['size_id']) && $variant['size_id'] == $size['id'] ? 'selected' : '' ?>><?= $size['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="color_id" class="form-label">Color</label>
        <select class="form-select" id="color_id" name="color_id" required>
            <option value="">Select a Color</option>
            <?php foreach ($colors as $color): ?>
                <option value="<?= $color['id'] ?>" <?= isset($variant['color_id']) && $variant['color_id'] == $color['id'] ? 'selected' : '' ?>><?= $color['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="sku" class="form-label">SKU</label>
        <input type="text" class="form-control" id="sku" name="sku" value="<?= isset($variant['sku']) ? htmlspecialchars($variant['sku']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <input type="number" class="form-control" id="price" name="price" value="<?= isset($variant['price']) ? htmlspecialchars($variant['price']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" class="form-control" id="quantity" name="quantity" value="<?= isset($variant['quantity']) ? htmlspecialchars($variant['quantity']) : '' ?>" required>
    </div>
    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" class="form-control" id="image" name="image">
        <?php if (isset($variant['image']) && $variant['image']): ?>
            <img src="<?= htmlspecialchars($variant['image']) ?>" alt="Image" width="100">
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <?php if (isset($errors)): ?>
        <div class="alert alert-danger mt-3">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</form>