<?php include 'header.php'; ?>
<section class="hero">
  <h1>CineFlow</h1><p>Find the best movies and enjoy your time.</p>
  <form class="search-bar" action="02_page_catalog.php" method="GET"><input type="text" name="q" placeholder="Search for movies..." /><button type="submit" class="btn">Search</button></form>
</section>
<section>
  <div class="section-header"><h2>Featured Movies</h2></div>
  <div class="movies-grid">
    <?php
    $res = pg_query($db, "SELECT * FROM movies ORDER BY id ASC LIMIT 8");
    if ($res && pg_num_rows($res) > 0) {
        while ($row = pg_fetch_assoc($res)) {
            $banner = !empty($row['banner_url']) ? $row['banner_url'] : 'https://images.unsplash.com/photo-1542204165-65bf26472b9b?w=500&q=80';
            echo "<div class='movie-card'><img src='".htmlspecialchars($banner)."' alt='Movie' onerror=\"this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=600'\">";
            echo "<div class='card-content'><h3>".htmlspecialchars($row['title'])."</h3><div class='movie-meta'><span>".htmlspecialchars($row['genre'])."</span><span class='movie-rating'>⭐ {$row['rating']}</span></div>";
            if ($row['seats_left'] <= 0) {
                echo "<div style='color:var(--accent-red);font-weight:bold;margin-bottom:10px;'>House Full</div>";
            } else {
                echo "<div style='color:var(--gold);font-size:12px;margin-bottom:10px;'>Only {$row['seats_left']} seats left</div>";
            }
            echo "<a href='03_page_movie_details.php?id={$row['id']}' class='btn'>View Details</a></div></div>";
        }
    } else { echo "<p>No movies available right now.</p>"; }
    ?>
  </div>
  <div style="text-align: center; margin-top: 20px;"><a href="02_page_catalog.php" class="btn">View All Movies</a></div>
</section>
<?php include 'footer.php'; ?>
