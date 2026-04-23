<?php include 'header.php'; ?>
<section class="container" style="max-width: 1200px; padding: 20px;">
  <div class="section-header"><h2>All Movies</h2></div>
  <div style="margin-bottom: 20px; text-align: center;">
    <form action="02_page_catalog.php" method="GET" style="display: inline-block;">
      <input type="text" name="q" placeholder="Title, genre..." style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" />
      <button type="submit" class="btn">Filter</button>
    </form>
  </div>
  <div class="movies-grid">
    <?php
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $res = !empty($q) ? pg_query_params($db, "SELECT * FROM movies WHERE title ILIKE $1 OR genre ILIKE $1 ORDER BY id ASC", ['%' . $q . '%']) : pg_query($db, "SELECT * FROM movies ORDER BY id ASC");
    $all_movies = ($res && pg_num_rows($res) > 0) ? pg_fetch_all($res) : [];
    if($res) pg_result_seek($res, 0);
    ?>
    <script>const MOVIE_DATA = <?php echo json_encode($all_movies ?: []); ?>; console.log("Dynamically loaded JSON:", MOVIE_DATA);</script>
    <?php
    if ($res && pg_num_rows($res) > 0) {
        while ($row = pg_fetch_assoc($res)) {
            $banner = !empty($row['banner_url']) ? $row['banner_url'] : 'https://images.unsplash.com/photo-1542204165-65bf26472b9b?w=500&q=80';
            echo "<div class='movie-card'><img src='".htmlspecialchars($banner)."' alt='Movie'><h3>" . htmlspecialchars($row['emoji'] . ' ' . $row['title']) . "</h3><p>" . htmlspecialchars($row['genre']) . " | ⭐ " . htmlspecialchars($row['rating']) . "</p><p>₹" . htmlspecialchars($row['price']) . "</p>";
            if ($row['seats_left'] <= 0) {
                echo "<div style='color:var(--accent-red);font-weight:bold;margin-bottom:10px;'>House Full</div><button class='btn' disabled style='opacity: 0.5; cursor: not-allowed; width: 100%;'>Sold Out</button>";
            } else {
                echo "<div style='color:var(--gold);font-size:12px;margin-bottom:10px;'>Only " . $row['seats_left'] . " seats left</div><a href='03_page_movie_details.php?id=" . $row['id'] . "' class='btn'>View Details</a>";
            }
            echo "</div>";
        }
    } else { echo "<p>No movies matched your search.</p>"; }
    ?>
  </div>
</section>
<?php include 'footer.php'; ?>
