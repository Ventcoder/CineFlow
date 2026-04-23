<?php
include 'header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h2>Access Denied. Admins Only.</h2></div>";
    include 'footer.php';
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$toast = '';

// Handle Update Movie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_movie'])) {
    $title = $_POST['title'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $price = (int)($_POST['price'] ?? 0);
    $rating = $_POST['rating'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $language = $_POST['language'] ?? '';
    $seats_left = (int)($_POST['seats_left'] ?? 0);

    $query = "UPDATE movies SET title=$1, genre=$2, price=$3, rating=$4, duration=$5, language=$6, seats_left=$7 WHERE id=$8";
    if(pg_query_params($db, $query, [$title, $genre, $price, $rating, $duration, $language, $seats_left, $id])) {
        $toast = "Movie successfully updated.";
    } else {
        $toast = "Error updating movie.";
    }
}

$res = pg_query_params($db, "SELECT * FROM movies WHERE id = $1", [$id]);
$movie = pg_fetch_assoc($res);

if (!$movie) {
    echo "<div class='container'><p>Movie not found.</p></div>";
    include 'footer.php';
    exit();
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
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Edit Movie: <?php echo htmlspecialchars($movie['title']); ?></h2>
            <a href="panel_admin_manage_02_page_catalog.php" class="btn btn-outline" style="padding: 6px 12px;">Back to Movies</a>
        </div>
        
        <?php if ($toast) echo "<div class='toast success' style='display:block; margin-bottom: 20px;'>$toast</div>"; ?>

        <div class="container" style="margin: 0 0 40px 0; padding: 25px;">
            <form method="POST">
                <input type="hidden" name="update_movie" value="1">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label>Movie Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Genre</label>
                        <input type="text" name="genre" value="<?php echo htmlspecialchars($movie['genre']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Base Ticket Price (₹)</label>
                        <input type="number" name="price" value="<?php echo htmlspecialchars($movie['price']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Seats Left</label>
                        <input type="number" name="seats_left" value="<?php echo htmlspecialchars($movie['seats_left']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Rating</label>
                        <input type="text" name="rating" value="<?php echo htmlspecialchars($movie['rating']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <input type="text" name="duration" value="<?php echo htmlspecialchars($movie['duration']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Language</label>
                        <input type="text" name="language" value="<?php echo htmlspecialchars($movie['language']); ?>" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="font-size:16px;">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
