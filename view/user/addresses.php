<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Địa chỉ</th>
            <th>Địa chỉ mặc định</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <h4 class="mt-4">Thêm địa chỉ mới</h4>
    <form method="POST" action="/add_address">
        <div class="mb-3">
            <label for="new_address" class="form-label">Thêm Đia chỉ mới</label>
            <input type="text" name="address" id="new_address" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm địa chỉ mới</button>
    </form> 
    <br>
    <tbody>
        <?php foreach ($addresses as $address): ?>
            <tr>
                <td><?= htmlspecialchars($address['id']) ?></td>
                <td><?= htmlspecialchars($address['address']) ?></td>
                <td>
                    <?= $address['is_default'] ? '<span class="badge bg-success">Default</span>' : '' ?>
                </td>
                <td>
                    <form method="POST" action="/set_default_address" style="display:inline;">
                        <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-primary"
                            <?= $address['is_default'] ? 'disabled' : '' ?>>
                            Đặt địa chỉ mặc định
                        </button>
                    </form>
                    <form method="POST" action="/edit_address" style="display:inline;">
                        <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-warning">Sửa</button>
                    </form>
                    <form method="POST" action="/delete_address" style="display:inline;">
                        <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/checkout" class="btn btn-secondary">Quay lại trang thanh toán</a>
