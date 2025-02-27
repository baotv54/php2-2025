<h2>Edit Banner</h2>

<form method="POST">
    <div class="mb-3">
        <label for="image_url" class="form-label">Image URL</label>
        <input type="text" class="form-control" name="image_url" value="<?= htmlspecialchars($banner['image_url']) ?>" required>
    </div>
    <div class="mb-3">
        <label for="link" class="form-label">Redirect Link</label>
        <input type="text" class="form-control" name="link" value="<?= htmlspecialchars($banner['link']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Banner</button>
</form>
