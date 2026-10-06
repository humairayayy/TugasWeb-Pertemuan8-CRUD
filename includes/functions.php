<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

function db(): PDO { return Database::getInstance()->getConnection(); }

/** Escape output (XSS) */
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

/** Flash message: set sebelum redirect, tampil sekali */
function flash(string $type, string $msg): void { $_SESSION['flash'] = ['type' => $type, 'msg' => $msg]; }

function show_flash(): void
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($f['type']) . '">' . e($f['msg']) . '</div>';
    }
}

function redirect(string $url): void { header("Location: $url"); exit; }

function rupiah($n): string { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }

/** Validasi input produk, return [data, errors] */
function validate_produk(array $in): array
{
    $d = [
        'nama'        => trim($in['nama'] ?? ''),
        'kategori_id' => (int)($in['kategori_id'] ?? 0),
        'supplier_id' => (int)($in['supplier_id'] ?? 0),
        'harga'       => $in['harga'] ?? '',
        'stok'        => $in['stok'] ?? '',
    ];
    $err = [];
    if ($d['nama'] === '' || mb_strlen($d['nama']) > 150) $err[] = 'Nama wajib diisi (maks 150 karakter).';
    if ($d['kategori_id'] <= 0) $err[] = 'Pilih kategori.';
    if ($d['supplier_id'] <= 0) $err[] = 'Pilih supplier.';
    if (!is_numeric($d['harga']) || $d['harga'] < 0) $err[] = 'Harga harus angka >= 0.';
    if (filter_var($d['stok'], FILTER_VALIDATE_INT) === false || $d['stok'] < 0) $err[] = 'Stok harus bilangan bulat >= 0.';
    return [$d, $err];
}

function get_kategori(): array { return db()->query('SELECT id, nama FROM kategori ORDER BY nama')->fetchAll(); }
function get_supplier(): array { return db()->query('SELECT id, nama FROM supplier ORDER BY nama')->fetchAll(); }
