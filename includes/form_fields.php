<?php /* Dipakai create.php & edit.php. Butuh $data, $errors */ ?>
<?php if (!empty($errors)): ?>
  <div class="alert alert-error"><ul>
  <?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
  </ul></div>
<?php endif; ?>
<label>Nama Produk
  <input type="text" name="nama" value="<?= e($data['nama'] ?? '') ?>" required maxlength="150">
</label>
<label>Kategori
  <select name="kategori_id" required>
    <option value="">-- Pilih Kategori --</option>
    <?php foreach (get_kategori() as $k): ?>
      <option value="<?= (int)$k['id'] ?>" <?= (int)($data['kategori_id'] ?? 0) === (int)$k['id'] ? 'selected' : '' ?>><?= e($k['nama']) ?></option>
    <?php endforeach; ?>
  </select>
</label>
<label>Supplier
  <select name="supplier_id" required>
    <option value="">-- Pilih Supplier --</option>
    <?php foreach (get_supplier() as $s): ?>
      <option value="<?= (int)$s['id'] ?>" <?= (int)($data['supplier_id'] ?? 0) === (int)$s['id'] ? 'selected' : '' ?>><?= e($s['nama']) ?></option>
    <?php endforeach; ?>
  </select>
</label>
<label>Harga (Rp)
  <input type="number" name="harga" min="0" step="0.01" value="<?= e($data['harga'] ?? '') ?>" required>
</label>
<label>Stok
  <input type="number" name="stok" min="0" step="1" value="<?= e($data['stok'] ?? '') ?>" required>
</label>
