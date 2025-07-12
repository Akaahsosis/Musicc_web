<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses penambahan musik di sini
    $title = $_POST['title'];
    $artist = $_POST['artist'];
    $album = $_POST['album'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];

    // Handle file upload for music
    $target_dir = "uploads/";
    $music_file = $target_dir . basename($_FILES["music_file"]["name"]);
    $music_uploaded = false;

    if (!empty($_FILES["music_file"]["name"])) {
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($music_file, PATHINFO_EXTENSION));

        // Check if file is a valid audio file
        $allowed_types = ['mp3', 'wav', 'ogg'];
        if (in_array($fileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["music_file"]["tmp_name"], $music_file)) {
                $music_uploaded = true;
            }
        } else {
            echo "Sorry, only MP3, WAV & OGG files are allowed.";
        }
    }

    // Handle file upload for album cover
    $album_cover = $target_dir . basename($_FILES["album_cover"]["name"]);
    $cover_uploaded = false;

    if (!empty($_FILES["album_cover"]["name"])) {
        $coverType = strtolower(pathinfo($album_cover, PATHINFO_EXTENSION));
        $allowed_cover_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($coverType, $allowed_cover_types)) {
            if (move_uploaded_file($_FILES["album_cover"]["tmp_name"], $album_cover)) {
                $cover_uploaded = true;
            }
        } else {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed for album cover.";
        }
    }

    // Insert to database
    if ($music_uploaded && $cover_uploaded) {
        $sql = "INSERT INTO music (title, artist, album, genre, release_date, file_path, album_cover) VALUES ('$title', '$artist', '$album', '$genre', '$release_date', '$music_file', '$album_cover')";
    } else {
        echo "Error: Music file or album cover not uploaded.";
        exit();
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php"); // Redirect ke daftar musik setelah berhasil
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Music</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Add New Music</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="artist">Artist:</label>
                <input type="text" id="artist" name="artist" required>
            </div>
            <div class="form-group">
                <label for="album">Album:</label>
                <input type="text" id="album" name="album" required>
            </div>
            <div class="form-group">
                <label for="genre">Genre:</label>
                <input type="text" id="genre" name="genre" required>
            </div>
            <div class="form-group">
                <label for="release_date">Release Date:</label>
                <input type="date" id="release_date" name="release_date" required>
            </div>
            <div class="form-group">
                <label for="music_file">Music File:</label>
                <input type="file" id="music_file" name="music_file" accept="audio/*" required>
            </div>
            <div class="form-group">
                <label for="album_cover">Album Cover:</label>
                <input type="file" id="album_cover" name="album_cover" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Add Music</button>
        </form>
        <a href="index.php" class="btn btn-cancel">Cancel</a>
    </div>
</body>

</html>