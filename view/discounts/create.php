-- View: discount_create.php --
<h2>Create Discount</h2>
<form method="POST">
    <label>Code:</label>
    <input type="text" name="code" required>
    <label>Type:</label>
    <select name="discount_type">
        <option value="fixed">Fixed</option>
        <option value="percentage">Percentage</option>
    </select>
    <label>Value:</label>
    <input type="number" name="discount_value" required min="0" step="0.01" value="<?= isset($discount['discount_value']) ? $discount['discount_value'] : 0 ?>">
    <label>Max Uses:</label>
    <input type="number" name="max_uses" required>
    <label>Expires At:</label>
    <input type="datetime-local" name="expires_at">
    <button type="submit">Create</button>
</form>