<?php
require_once 'includes/functions.php';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);

$stmt = db()->prepare('SELECT * FROM produk WHERE id = :id');
$stmt->execute([':id' => $id]);
$data = $stmt->fetch();
if (!$data) { flash('error', 'Produk tidak ditemukan.'); redirect('index.php'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validate_produk($_POST);
    if (!$errors) {
        try {
            $stmt = db()->prepare('UPDATE produk SET nama=:nama, kategori_id=:kat, supplier_id=:sup,
                                   harga=:harga, stok=:stok WHERE id=:id');
            $stmt->execute([
                ':nama' => $data['nama'], ':kat' => $data['kategori_id'], ':sup' => $data['supplier_id'],
                ':harga' => $data['harga'], ':stok' => $data['stok'], ':id' => $id,
            ]);
            flash('success', 'Produk berhasil diperbarui.');
        } catch (PDOException $ex) {
            flash('error', 'Gagal memperbarui produk.');
        }
        redirect('index.php');
    }
}
$title = 'Edit Produk';
require 'includes/header.php';
?>
<h2>Edit Produk</h2>
<form method="post" class="card">
  <input type="hidden" name="id" value="<?= (int)$id ?>">
  <?php require 'includes/form_fields.php'; ?>
  <button class="btn btn-primary">Update</button>
  <a href="index.php" class="btn">Batal</a>
</form>
<?php require 'includes/footer.php'; ?>
