<?php
include 'db.php';

// Periksa apakah ada ID artis yang diterima dari URL
if (isset($_GET['id'])) {
    $artist_id = intval($_GET['id']); // Ambil ID artis dari URL

    // Siapkan dan jalankan pernyataan untuk menghapus artis
    $sql = "DELETE FROM artists WHERE id = $artist_id"; // Gunakan $artist_id
    if ($conn->query($sql) === TRUE) {
        // Jika berhasil, alihkan kembali ke halaman daftar artis dengan pesan sukses
        header("Location: artists.php?message=Artist+deleted+successfully");
        exit();
    } else {
        // Jika gagal, alihkan kembali dengan pesan error
        header("Location: artists.php?message=Error+deleting+artist");
        exit();
    }
} else {
    // Jika tidak ada ID artis yang diberikan, alihkan kembali
    header("Location: artists.php?message=No+artist+ID+provided");
    exit();
}
?>