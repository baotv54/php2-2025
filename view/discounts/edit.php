<h2>Edit Discount</h2>
<form method="POST">
    <label>Code:</label>
    <input type="text" name="code" value="<?= $discount['code'] ?>" required>
    <label>Type:</label>
    <select name="discount_type">
        <option value="fixed" <?= $discount['discount_type'] == 'fixed' ? 'selected' : '' ?>>Fixed</option>
        <option value="percentage" <?= $discount['discount_type'] == 'percentage' ? 'selected' : '' ?>>Percentage</option>
    </select>
    <label>Value:</label>
    <input type="number" name="discount_value" value="<?= $discount['discount_value'] ?>" required>
    <label>Max Uses:</label>
    <input type="number" name="max_uses" value="<?= $discount['max_uses'] ?>" required>
    <label>Expires At:</label>
    <input type="datetime-local" name="expires_at" value="<?= $discount['expires_at'] ?>">
    <button type="submit">Update</button>
</form>
