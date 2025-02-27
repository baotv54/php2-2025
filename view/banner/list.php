<div class="container mt-5">
    <h2>Quản lý Banner</h2>

    <form action="/banners/create" method="POST">
        <div class="mb-3">
            <label class="form-label">Link Banner:</label>
            <input type="text" name="link" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">URL Hình ảnh:</label>
            <input type="text" name="image_url" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Thêm Banner</button>
    </form>

    <h3 class="mt-4">Danh sách Banner</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Link</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($banners as $banner): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($banner['image_url']) ?>" width="100"></td>
                    <td><a href="<?= htmlspecialchars($banner['link']) ?>" target="_blank">Xem</a></td>
                    <td>
                        <a href="/banners/edit/<?= $banner['id'] ?>" class="btn btn-warning">Edit</a>

                        <a href="/banners/delete/<?= $banner['id'] ?>" class="btn btn-danger">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>