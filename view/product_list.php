<h1>Product List</h1>
<a href="/products/create" class="btn btn-primary mb-3">Create Product</a>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Color</th>
            <th>Size</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>SKU</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= $product['name'] ?></td>
                <td><?= isset($product['colorName']) ? $product['colorName'] : 'N/A' ?></td>
                <td><?= isset($product['sizeName']) ? $product['sizeName'] : 'N/A' ?></td>
                <td><img src="<?= isset($product['image']) ? $product['image'] : 'default.jpg' ?>" alt="Product Image" width="50"></td>
                <td><?= isset($product['quantity']) ? $product['quantity'] : 'N/A' ?></td>
                <td>$<?= $product['price'] ?></td>
                <td><?= isset($product['sku']) ? $product['sku'] : 'N/A' ?></td>
                <td>
                    <a href="/products/<?= $product['id'] ?>" class="btn btn-info btn-sm">View</a>
                    <a href="/products/edit/<?= $product['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/products/delete/<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    <a href="/products-variants/create/<?= $product['id'] ?>" class="btn btn-info btn-sm">Add variant</a>
                    <a href="/products-variants/edit/<?= $product['id'] ?>" class="btn btn-warning btn-sm">Edit variant</a>

                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>