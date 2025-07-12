<?php
include 'db.php';

// Periksa apakah ada ID artis yang diterima dari URL
if (isset($_GET['id'])) {
    $artist_id = intval($_GET['id']);

    // Ambil informasi artis dari database
    $sql = "SELECT * FROM artists WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $artist = $result->fetch_assoc();

    // Jika artis tidak ditemukan, alihkan kembali
    if (!$artist) {
        header("Location: artists.php?message=Artist+not+found");
        exit();
    }

    // Proses pembaruan data ketika formulir disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $biography = $_POST['biography'];
        $image_path = $_POST['image_path']; // Pastikan untuk menangani upload gambar jika diperlukan

        // Siapkan dan jalankan pernyataan untuk memperbarui artis
        $sql_update = "UPDATE artists SET name = ?, description = ?, biography = ?, image_path = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssi", $name, $description, $biography, $image_path, $artist_id);

        if ($stmt_update->execute()) {
            // Jika berhasil, alihkan kembali ke halaman daftar artis
            header("Location: artists.php?message=Artist+updated+successfully");
            exit();
        } else {
            // Jika gagal, alihkan kembali dengan pesan error
            header("Location: artists.php?message=Error+updating+artist");
            exit();
        }
    }
} else {
    // Jika tidak ada ID artis yang diberikan, alihkan kembali
    header("Location: artists.php?message=No+artist+ID+provided");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artist</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <h1>Edit Artist</h1>
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($artist['name']); ?>" required>
            
            <label for="description">Description:</label>
            <textarea name="description" id="description" required><?php echo htmlspecialchars($artist['description']); ?></textarea>
            
            <label for="biography">Biography:</label>
            <textarea name="biography" id="biography" required><?php echo htmlspecialchars($artist['biography']); ?></textarea>
            
            <label for="image_path">Image Path:</label>
            <input type="text" name="image_path" id="image_path" value="<?php echo htmlspecialchars($artist['image_path']); ?>" required>
            
            <button type="submit">Update Artist</button>
        </form>
        <a href="artists.php">Cancel</a>
    </div>
</body>
</html>