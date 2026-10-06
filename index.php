<?php
$title = 'Daftar Produk';
require 'includes/header.php';

// Bonus: pencarian + pagination
$q       = trim($_GET['q'] ?? '');
$perPage = 5;
$page    = max(1, (int)($_GET['page'] ?? 1));
$like    = '%' . $q . '%';

$stmt = db()->prepare('SELECT COUNT(*) FROM produk WHERE nama LIKE :q');
$stmt->execute([':q' => $like]);
$total = (int)$stmt->fetchColumn();
$pages = max(1, (int)ceil($total / $perPage));
$page  = min($page, $pages);
$offset = ($page - 1) * $perPage;

// JOIN produk + kategori + supplier
$sql = 'SELECT p.id, p.nama, p.harga, p.stok, k.nama AS kategori, s.nama AS supplier
        FROM produk p
        JOIN kategori k ON k.id = p.kategori_id
        JOIN supplier s ON s.id = p.supplier_id
        WHERE p.nama LIKE :q
        ORDER BY p.id DESC
        LIMIT :lim OFFSET :off';
$stmt = db()->prepare($sql);
$stmt->bindValue(':q', $like);
$stmt->bindValue(':lim', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':off', $offset, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll();
?>
<div class="toolbar">
  <form method="get" class="search">
    <input type="text" name="q" placeholder="Cari produk..." value="<?= e($q) ?>">
    <button class="btn">Cari</button>
  </form>
  <a href="create.php" class="btn btn-primary">+ Tambah Produk</a>
</div>

<table>
  <thead><tr><th>#</th><th>Nama</th><th>Kategori</th><th>Supplier</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr></thead>
  <tbody>
  <?php if (!$rows): ?>
    <tr><td colspan="7" class="center">Tidak ada data.</td></tr>
  <?php endif; ?>
  <?php foreach ($rows as $i => $r): ?>
    <tr>
      <td><?= $offset + $i + 1 ?></td>
      <td><?= e($r['nama']) ?></td>
      <td><?= e($r['kategori']) ?></td>
      <td><?= e($r['supplier']) ?></td>
      <td><?= e(rupiah($r['harga'])) ?></td>
      <td><?= (int)$r['stok'] ?></td>
      <td class="actions">
        <a class="btn btn-sm" href="edit.php?id=<?= (int)$r['id'] ?>">Edit</a>
        <form method="post" action="delete.php" onsubmit="return confirm('Yakin hapus &quot;<?= e(addslashes($r['nama'])) ?>&quot;?');">
          <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
          <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<div class="pagination">
  <?php for ($p = 1; $p <= $pages; $p++): ?>
    <a class="<?= $p === $page ? 'active' : '' ?>" href="?q=<?= urlencode($q) ?>&page=<?= $p ?>"><?= $p ?></a>
  <?php endfor; ?>
</div>
<?php require 'includes/footer.php'; ?>
