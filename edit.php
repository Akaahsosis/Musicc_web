<?php
include 'db.php';

$id = $_GET['id'];
$sql = "SELECT * FROM music WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Update SQL
    if ($music_uploaded && $cover_uploaded) {
        $sql = "UPDATE music SET title='$title', artist='$artist', album='$album', genre='$genre', release_date='$release_date', file_path='$music_file', album_cover='$album_cover' WHERE id=$id";
    } elseif ($music_uploaded) {
        $sql = "UPDATE music SET title='$title', artist='$artist', album='$album', genre='$genre', release_date='$release_date', file_path='$music_file' WHERE id=$id";
    } elseif ($cover_uploaded) {
        $sql = "UPDATE music SET title='$title', artist='$artist', album='$album', genre='$genre', release_date='$release_date', album_cover='$album_cover' WHERE id=$id";
    } else {
        $sql = "UPDATE music SET title='$title', artist='$artist', album='$album', genre='$genre', release_date='$release_date' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Music</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Edit Music</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $row['title']; ?>" required>
            </div>
            <div class="form-group">
                <label for="artist">Artist:</label>
                <input type="text" id="artist" name="artist" value="<?php echo $row['artist']; ?>" required>
            </div>
            <div class="form-group">
                <label for="album">Album:</label>
                <input type="text" id="album" name="album" value="<?php echo $row['album']; ?>">
            </div>
            <div class="form-group">
                <label for="genre">Genre:</label>
                <input type="text" id="genre" name="genre" value="<?php echo $row['genre']; ?>">
            </div>
            <div class="form-group">
                <label for="release_date">Release Date:</label>
                <input type="date" id="release_date" name="release_date" value="<?php echo $row['release_date']; ?>" required>
            </div>
            <div class="form-group">
                <label for="music_file">Upload Music File:</label>
                <input type="file" id="music_file" name="music_file" accept=".mp3, .wav, .ogg">
            </div>
            <div class="form-group">
                <label for="album_cover">Upload Album Cover:</label>
                <input type="file" id="album_cover" name="album_cover" accept=".jpg, .jpeg, .png, .gif">
            </div>
            <button type="submit" class="btn">Update Music</button>
        </form>
        <a href="index.php" class="btn btn-cancel">Cancel</a>
    </div>
</body>

</html>