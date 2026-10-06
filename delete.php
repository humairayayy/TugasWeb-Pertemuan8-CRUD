<?php
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
$id = (int)($_POST['id'] ?? 0);
$pdo = db();

try {
    $pdo->beginTransaction();   // Bonus: transaksi delete + log aktivitas

    $stmt = $pdo->prepare('SELECT nama FROM produk WHERE id = :id FOR UPDATE');
    $stmt->execute([':id' => $id]);
    $nama = $stmt->fetchColumn();
    if ($nama === false) throw new RuntimeException('Produk tidak ditemukan.');

    $stmt = $pdo->prepare('DELETE FROM produk WHERE id = :id');
    $stmt->execute([':id' => $id]);

    $stmt = $pdo->prepare('INSERT INTO log_aktivitas (aksi, keterangan) VALUES (:aksi, :ket)');
    $stmt->execute([':aksi' => 'DELETE', ':ket' => "Produk #$id ($nama) dihapus"]);

    $pdo->commit();
    flash('success', 'Produk berhasil dihapus.');
} catch (Throwable $ex) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('error', 'Gagal menghapus produk.');
}
redirect('index.php');
