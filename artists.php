    <?php
    include 'db.php';

    // Ambil semua artis
    $sql_artists = "SELECT * FROM artists";
    $result_artists = $conn->query($sql_artists);
    $selected_artist_id = isset($_GET['artist_id']) ? $_GET['artist_id'] : null;
    $selected_artist = null;

    // Ambil informasi artis yang dipilih
    if ($selected_artist_id) {
        $sql_artist = "SELECT * FROM artists WHERE id = ?";
        $stmt = $conn->prepare($sql_artist);
        $stmt->bind_param("i", $selected_artist_id);
        $stmt->execute();
        $result_artist = $stmt->get_result();
        $selected_artist = $result_artist->fetch_assoc();
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Music CRUD</title>
        <link rel="stylesheet" href="style2.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            /* Tambahkan gaya CSS sesuai kebutuhan */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background-color: rgb(20, 20, 20); /* Warna latar belakang lebih gelap */
            }
            
            .container {
                max-width: 800px;
                margin: auto;
                background: rgba(30, 30, 30, 0.9); /* Latar belakang transparan untuk konten */
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            }

            .h2{

                color: #ffffff;
            }

            .tab-buttons a {
                margin-right: 15px;
                text-decoration: none;
                color: #ffffff; /* Warna teks putih */
            }

            .tab-buttons a.active {
                font-weight: bold;
                color: #00BFFF; /* Warna biru cerah */
            }

            .artists-list {
                display: flex;
                flex-direction: column;
                margin: 20px 0;
            }

            .artists-list a {
                display: inline-block;
                margin: 10px 0;
                text-decoration: none;
                color: #ffffff;
                background-color: #007BFF; /* Warna tombol biru */
                padding: 10px 15px;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            .artists-list a:hover {
                background-color: #0056b3; /* Warna tombol saat hover */
            }

            .artist-info {
                margin-top: 20px;
                display: none; /* Sembunyikan informasi artis secara default */
                text-align: center; /* Pusatkan teks */
            }
            
            .artist-info img {
                max-width: 200px;
                border-radius: 10px;
                margin-bottom: 15px;
            }

            .artist-description, 
            .artist-biography {
                margin-top: 10px;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                background-color: rgb(50, 50, 50); /* Warna latar belakang deskripsi */
                color: #ffffff; /* Teks putih */
            }

            .action-buttons {
                margin-top: 15px;
            }

            .action-buttons a {
                margin-right: 10px;
                text-decoration: none;
                color: #fff;
                padding: 5px 10px;
                border-radius: 5px;
                font-size: 0.9em;
                display: inline-block;
            }

            .edit-btn {
                background-color: #28a745; /* Warna hijau */
            }

            .delete-btn {
                background-color: #dc3545; /* Warna merah */
            }

            .edit-btn:hover {
                background-color: #218838; /* Hijau lebih gelap saat hover */
            }

            .delete-btn:hover {
                background-color: #c82333; /* Merah lebih gelap saat hover */
            }
        </style>
    </head>

    <body>

        <div class="container">
            <h1>Music CRUD</h1>

            <!-- Tombol tab -->
            <div class="tab-buttons">
                <a href="index.php"><i class="fas fa-music"></i> Search Music</a>
                <a href="genre.php"><i class="fas fa-tags"></i> Genres</a>
                <a href="artists.php" class="active"><i class="fas fa-user"></i> Artists</a>
            </div>

            <a href="create_artist.php" class="btn btn-add">Add New Artist</a>

            <!-- Menampilkan daftar artis -->
            <h2 style="color: white;">Artists</h2>
            <div class="artists-list">
                <?php if ($result_artists->num_rows > 0): ?>
                    <?php while ($artist = $result_artists->fetch_assoc()): ?>
                        <div>
                            <a href="#" class="artist-toggle" data-artist-id="<?php echo $artist['id']; ?>"><?php echo htmlspecialchars($artist['name']); ?></a>
                            <div class="artist-info" id="artist-info-<?php echo $artist['id']; ?>">
                                <img src="<?php echo htmlspecialchars($artist['image_path']); ?>" alt="Artist Image">
                                <div class="artist-description">
                                    <strong>Description:</strong>
                                    <p><?php echo htmlspecialchars($artist['description']); ?></p>
                                </div>
                                <div class="artist-biography">
                                    <strong>Biography:</strong>
                                    <p><?php echo htmlspecialchars($artist['biography']); ?></p>
                                </div>
                                <div class="action-buttons">
                                    <a href="edit_artist.php?id=<?php echo $artist['id']; ?>" class="edit-btn"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="delete_artist.php?id=<?php echo $artist['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this artist?');"><i class="fas fa-trash"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No artists found.</p>
                <?php endif; ?>
            </div>
        </div>

        <script>
            // Script untuk menangani klik pada nama artis
            document.querySelectorAll('.artist-toggle').forEach(item => {
                item.addEventListener('click', event => {
                    event.preventDefault(); // Mencegah perilaku default link
                    const artistId = item.getAttribute('data-artist-id');
                    const artistInfo = document.getElementById(`artist-info-${artistId}`);

                    // Toggle tampilkan atau sembunyikan informasi artis
                    if (artistInfo.style.display === "none" || artistInfo.style.display === "") {
                        artistInfo.style.display = "block"; // Tampilkan informasi
                    } else {
                        artistInfo.style.display = "none"; // Sembunyikan informasi
                    }
                });
            });
        </script>
    </body>

    </html>