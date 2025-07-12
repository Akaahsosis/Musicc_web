<?php
include 'db.php';

// Ambil semua genre yang unik dari tabel music
$sql_genres = "SELECT DISTINCT genre FROM music";
$result_genres = $conn->query($sql_genres);

// Ambil track berdasarkan genre yang dipilih
$selected_genre = isset($_GET['genre']) ? $_GET['genre'] : null;
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$tracks = [];

// Jika ada genre yang dipilih
if ($selected_genre) {
    $sql_tracks = "SELECT * FROM music WHERE genre = ?";
    $stmt = $conn->prepare($sql_tracks);
    $stmt->bind_param("s", $selected_genre);
    $stmt->execute();
    $result_tracks = $stmt->get_result();
    $tracks = $result_tracks->fetch_all(MYSQLI_ASSOC);
}

// Jika ada pencarian musik
if ($search_query) {
    $sql_tracks = "SELECT * FROM music WHERE title LIKE ? OR artist LIKE ? OR album LIKE ?";
    $stmt = $conn->prepare($sql_tracks);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt->execute();
    $result_tracks = $stmt->get_result();
    $tracks = $result_tracks->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music CRUD</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .tab-buttons {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .tab-buttons a {
            background-color:rgb(0, 86, 0);
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 10px;
            transition: background-color 0.3s;
        }

        .tab-buttons a:hover {
            background-color:rgb(0, 86, 0);
        }

        .track {
            margin: 15px 0;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .genres-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .genres-list a {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
        }

        .genres-list a:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Music CRUD</h1>

        <!-- Tombol tab -->
        <div class="tab-buttons">
            <a href="index.php" class="active"><i class="fas fa-music"></i> Music</a>
            <a href="genre.php"><i class="fas fa-tags"></i> Genres</a>
            <a href="artists.php"><i class="fas fa-user"></i> Artists</a>
        </div>


        <!-- Pencarian musik -->
        <div class="search-bar">
            <form method="get" action="">
                <input type="text" name="search" placeholder="Search for music..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Menampilkan hasil pencarian musik -->
        <h2>Search Results</h2>
        <div class="playlist">
            <?php if ($tracks): ?>
                <?php foreach ($tracks as $track): ?>
                    <div class="track">
                        <img src="<?php echo htmlspecialchars($track['album_cover']); ?>" alt="Album Cover" class="album-cover">
                        <div class="track-info">
                            <h3 onclick="playAudio('<?php echo htmlspecialchars($track['file_path']); ?>')" class="track-title"><?php echo htmlspecialchars($track['title']); ?></h3>
                            <p><strong>Artist:</strong> <?php echo htmlspecialchars($track['artist']); ?></p>
                            <p><strong>Album:</strong> <?php echo htmlspecialchars($track['album']); ?></p>
                            <p><strong>Release Date:</strong> <?php echo date('F j, Y', strtotime($track['release_date'])); ?></p>
                            <p><strong>Genre:</strong> <?php echo htmlspecialchars($track['genre']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No tracks found for your search.</p>
            <?php endif; ?>
        </div>

        <!-- Menampilkan daftar genre -->
        <h2>Available Genres</h2>
        <div class="genres-list">
            <?php if ($result_genres->num_rows > 0): ?>
                <?php while ($genre = $result_genres->fetch_assoc()): ?>
                    <a href="?genre=<?php echo urlencode($genre['genre']); ?>"><?php echo htmlspecialchars($genre['genre']); ?></a>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No genres found.</p>
            <?php endif; ?>
        </div>

        <!-- Menampilkan track record untuk genre yang dipilih -->
        <?php if ($selected_genre): ?>
            <h2>Tracks in <?php echo htmlspecialchars($selected_genre); ?></h2>
            <div class="playlist">
                <?php if ($tracks): ?>
                    <?php foreach ($tracks as $track): ?>
                        <div class="track">
                            <img src="<?php echo htmlspecialchars($track['album_cover']); ?>" alt="Album Cover" class="album-cover">
                            <div class="track-info">
                                <h3 onclick="playAudio('<?php echo htmlspecialchars($track['file_path']); ?>')" class="track-title"><?php echo htmlspecialchars($track['title']); ?></h3>
                                <p><strong>Artist:</strong> <?php echo htmlspecialchars($track['artist']); ?></p>
                                <p><strong>Album:</strong> <?php echo htmlspecialchars($track['album']); ?></p>
                                <p><strong>Release Date:</strong> <?php echo date('F j, Y', strtotime($track['release_date'])); ?></p>
                                <p><strong>Genre:</strong> <?php echo htmlspecialchars($track['genre']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No tracks found for this genre.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
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