<?php
require_once 'includes/functions.php';
$errors = [];
$data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$data, $errors] = validate_produk($_POST);
    if (!$errors) {
        try {
            $stmt = db()->prepare('INSERT INTO produk (nama, kategori_id, supplier_id, harga, stok)
                                   VALUES (:nama, :kat, :sup, :harga, :stok)');
            $stmt->execute([
                ':nama' => $data['nama'], ':kat' => $data['kategori_id'], ':sup' => $data['supplier_id'],
                ':harga' => $data['harga'], ':stok' => $data['stok'],
            ]);
            flash('success', 'Produk berhasil ditambahkan.');
        } catch (PDOException $ex) {
            flash('error', 'Gagal menambahkan produk.');
        }
        redirect('index.php');   // Post-Redirect-Get
    }
}
$title = 'Tambah Produk';
require 'includes/header.php';
?>
<h2>Tambah Produk</h2>
<form method="post" class="card">
  <?php require 'includes/form_fields.php'; ?>
  <button class="btn btn-primary">Simpan</button>
  <a href="index.php" class="btn">Batal</a>
</form>
<?php require 'includes/footer.php'; ?>
