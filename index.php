<?php
require 'functions.php';
require 'connect.php';

// TODO 1 (RINGKASAN)
// Semua query pakai prepare() sesuai aturan "wajib prepared statement".
// Hasil COUNT(*) diambil lewat fetch_assoc()['n'] lalu di-cast (int).
$stmt = $conn->prepare('SELECT COUNT(*) AS n FROM mata_kuliah');
$stmt->execute();
$jumlah_mata_kuliah = (int) $stmt->get_result()->fetch_assoc()['n'];

$stmt = $conn->prepare('SELECT COUNT(*) AS n FROM materi');
$stmt->execute();
$jumlah_materi = (int) $stmt->get_result()->fetch_assoc()['n'];

$stmt = $conn->prepare('SELECT COUNT(*) AS n FROM tugas');
$stmt->execute();
$jumlah_tugas = (int) $stmt->get_result()->fetch_assoc()['n'];

// bind_param butuh reference, jadi nilai disimpan di variabel dulu.
$status_aktif = 'Aktif';
$stmt = $conn->prepare('SELECT COUNT(*) AS n FROM tugas WHERE status = ?');
$stmt->bind_param('s', $status_aktif);
$stmt->execute();
$jumlah_tugas_aktif = (int) $stmt->get_result()->fetch_assoc()['n'];

$pilihan_mk = pilihan_mata_kuliah($conn);

$judul = 'Beranda';
require 'header.php';
?>
<p class="eyebrow">Learning Management System</p>
<h1 class="hero">Kelola perkuliahan dalam satu tempat.</h1>
<p class="muted">Pengelolaan mata kuliah, materi pembelajaran, dan tugas.</p>

<div class="stats">
    <a class="card stat" href="mata_kuliah.php">
        <b><?= (int) $jumlah_mata_kuliah ?></b>
        <span>Mata kuliah</span>
    </a>
    <a class="card stat" href="materi.php">
        <b><?= (int) $jumlah_materi ?></b>
        <span>Materi</span>
    </a>
    <a class="card stat" href="tugas.php">
        <b><?= (int) $jumlah_tugas ?></b>
        <span>Tugas</span>
    </a>
    <a class="card stat" href="tugas.php?status=Aktif">
        <b><?= (int) $jumlah_tugas_aktif ?></b>
        <span>Tugas aktif</span>
    </a>
</div>

<h2>Mata kuliah tersedia</h2>
<?php if (count($pilihan_mk) === 0): ?>
    <p class="empty">Belum ada mata kuliah.</p>
<?php else: ?>
    <div class="card list">
        <?php foreach ($pilihan_mk as $id => $nama): ?>
            <a class="list-item" href="mata_kuliah.php?aksi=detail&id=<?= (int) $id ?>"><?= h($nama) ?></a>
        <?php endforeach ?>
    </div>
<?php endif ?>
<?php require 'footer.php'; ?>