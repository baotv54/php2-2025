<h2>Manage Discounts</h2>
<a href="/discounts/create" class="btn btn-primary">Create New Discount</a>
<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Code</th>
            <th>Type</th>
            <th>Value</th>
            <th>Max Uses</th>
            <th>Expires At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($discounts as $discount): ?>
            <tr>
                <td><?= $discount['id'] ?></td>
                <td><?= $discount['code'] ?></td>
                <td><?= ucfirst($discount['discount_type']) ?></td>
                <td><?= $discount['discount_value'] ?></td>
                <td><?= $discount['max_uses'] ?></td>
                <td><?= $discount['expires_at'] ?></td>
                <td>
                    <a href="/discounts/edit/<?= $discount['id'] ?>" class="btn btn-warning">Edit</a>
                    <a href="/discounts/delete/<?= $discount['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>