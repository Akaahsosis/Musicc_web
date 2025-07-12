<?php
include 'db.php';

// Ambil data musik
$sql = "SELECT * FROM music";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music CRUD</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>

    <div class="container">
        <h1>Music CRUD</h1>

        <!-- Tambahkan tombol tab di sini -->
        <div class="tab-buttons">
            <a href="index.php" class="active"><i class="fas fa-music"></i> Music</a>
            <a href="genre.php"><i class="fas fa-tags"></i> Genres</a>
            <a href="artists.php"><i class="fas fa-user"></i> Artists</a>
        </div>

        <a href="create.php" class="btn btn-add">Add New Music</a>
        <div class="playlist">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="track">
                        <img src="<?php echo $row['album_cover']; ?>" alt="Album Cover" class="album-cover">
                        <div class="track-info">
                            <h2 onclick="playAudio('<?php echo $row['file_path']; ?>')" class="track-title"><?php echo $row['title']; ?></h2>
                            <p><strong>Artist:</strong> <?php echo $row['artist']; ?></p>
                            <p><strong>Album:</strong> <?php echo $row['album']; ?></p>
                            <p><strong>Release Date:</strong> <?php echo date('F j, Y', strtotime($row['release_date'])); ?></p>
                            <p><strong>Genre:</strong> <?php echo $row['genre']; ?></p>
                        </div>
                        <div class="track-actions">
                            <form action="edit.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form action="delete.php" method="get" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-delete" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No records found.</p>
            <?php endif; ?>
        </div>
    </div>

    <audio id="audio-player" style="display: none;" controls></audio>
    <div class="audio-controls">
        <button id="pause-button" class="btn" style="display: none;">
            <i class="fas fa-pause"></i> Pause
        </button>
    </div>

    <script>
        const audioPlayer = document.getElementById('audio-player');
        const pauseButton = document.getElementById('pause-button');

        function playAudio(file) {
            audioPlayer.src = file;
            audioPlayer.play();
            pauseButton.style.display = 'inline-block'; // Tampilkan tombol Pause saat audio diputar
        }

        pauseButton.addEventListener('click', function() {
            audioPlayer.pause();
            pauseButton.style.display = 'none'; // Sembunyikan tombol Pause setelah audio dijeda
        });

        audioPlayer.addEventListener('pause', function() {
            pauseButton.style.display = 'none'; // Sembunyikan tombol Pause saat audio dijeda
        });

        audioPlayer.addEventListener('play', function() {
            pauseButton.style.display = 'inline-block'; // Tampilkan tombol Pause saat audio diputar
        });
    </script>
</body>

</html>