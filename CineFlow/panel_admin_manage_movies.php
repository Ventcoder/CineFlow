<?php
include 'header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h2>Access Denied. Admins Only.</h2></div>";
    include 'footer.php';
    exit();
}

$toast = '';

// Handle Delete Movie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_movie'])) {
    $id = (int)$_POST['movie_id'];
    pg_query_params($db, "DELETE FROM movies WHERE id = $1", [$id]);
    $toast = "Movie deleted.";
}

// Handle Add Movie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_movie'])) {
    $title = $_POST['title'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $banner_url = "https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&q=80"; // Default Cinema aesthetic banner
    $backdrop_url = "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1600&q=80"; // Default Theater backdrop
    $description = "An intense, emotionally gripping cinematic journey. Experience the unmissable masterpiece of the year exclusively at CineFlow multiplexes.";

    $query = "INSERT INTO movies (title, genre, price, rating, duration, language, emoji, banner_url, backdrop_url, description) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)";
    pg_query_params($db, $query, [$title, $genre, $price, $rating, $duration, $language, $emoji, $banner_url, $backdrop_url, $description]);
    $toast = "Movie successfully added with auto-generated rich data.";
}
?>

<div class="admin-layout">
    <div class="admin-sidebar">
        <h3>Admin Panel</h3>
        <ul class="sidebar-nav">
            <li><a href="panel_admin_dashboard.php">Dashboard</a></li>
            <li><a href="panel_admin_manage_02_page_catalog.php">Manage Movies</a></li>
            <li><a href="panel_admin_manage_users.php">Manage Users</a></li>
        </ul>
    </div>
    
    <div class="admin-main">
        <h2>Manage Movies</h2>
        <?php if ($toast) echo "<div class='toast success'>$toast</div>"; ?>

        <div class="container" style="margin: 0 0 40px 0; padding: 25px;">
            <h3 style="margin-bottom:20px;">Deploy Movie (Simplified)</h3>
            <form method="POST">
                <input type="hidden" name="add_movie" value="1">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom: 20px;">
                    <div class="form-group"><input type="text" name="title" placeholder="Movie Title" required></div>
                    <div class="form-group"><input type="text" name="genre" placeholder="Genre" required></div>
                    <div class="form-group"><input type="number" name="price" placeholder="Base Ticket Price (₹)" required></div>
                    <div class="form-group"><input type="text" name="rating" placeholder="Rating" required></div>
                    <div class="form-group"><input type="text" name="duration" placeholder="Duration" required></div>
                    <div class="form-group"><input type="text" name="language" placeholder="Language" required></div>
                </div>
                <button type="submit" class="btn btn-primary" style="font-size:16px;">+ Quick Deploy</button>
            </form>
        </div>

        <table>
            <thead>
                <tr><th>ID</th><th>Title</th><th>Genre</th><th>Price</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php
                $res = pg_query($db, "SELECT * FROM movies ORDER BY id ASC");
                while ($row = pg_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['genre']}</td>
                        <td>₹{$row['price']}</td>
                        <td style='display: flex; gap: 10px;'>
                            <a href='panel_admin_edit_03_page_movie_details.php?id={$row['id']}' class='btn btn-primary' style='padding: 6px 12px; background: #2196F3; border-color: #2196F3;'>Edit</a>
                            <form method='POST' onsubmit='return confirm(\"Delete movie?\");'>
                                <input type='hidden' name='movie_id' value='{$row['id']}'>
                                <button type='submit' name='delete_movie' class='btn btn-danger' style='padding: 6px 12px;'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
