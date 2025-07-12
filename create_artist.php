<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $biography = $_POST['biography'];
    $genre = $_POST['genre'];
    
    // Menangani upload gambar
    $image_path = 'uploads/' . basename($_FILES['image']['name']);
    move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

    $sql = "INSERT INTO artists (name, description, biography, genre, image_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $description, $biography, $genre, $image_path);
    $stmt->execute();

    header("Location: artists.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Artist</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <div class="container">
        <h1>Create Artist</h1>
        <form method="post" action="" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" required>
            
            <label for="description">Description:</label>
            <textarea name="description" required></textarea>
            
            <label for="biography">Biography:</label>
            <textarea name="biography" required></textarea>
            
            <label for="genre">Genre:</label>
            <input type="text" name="genre" required>
            
            <label for="image">Image:</label>
            <input type="file" name="image" accept="image/*" required>
            
            <button type="submit">Add Artist</button>
            <a href="artists.php" class="btn">Cancel</a>
        </form>
    </div>

</body>
</html>