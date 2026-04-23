<?php 
include 'header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = pg_query_params($db, "SELECT * FROM movies WHERE id = $1", [$id]);
$movie = pg_fetch_assoc($res);

if (!$movie) {
    echo "<div class='container' style='margin-top:100px;text-align:center;'><h2>Movie Not Found</h2><p>The movie you are looking for does not exist.</p><a href='02_page_catalog.php' class='btn btn-primary'>View All Movies</a></div>";
    include 'footer.php';
    exit;
}
?>

<!-- ADVANCED ENTERPRISE MOVIE HERO -->
<div class="movie-hero" style="position:relative; width:100%; min-height:80vh; display:flex; align-items:center; justify-content:center; padding:100px 20px; overflow:hidden;">
    
    <!-- Hero Blurred Backdrop -->
    <div style="position:absolute; top:0; left:0; right:0; bottom:0; z-index:-2; 
        background: url('<?php echo htmlspecialchars($movie['banner_url']); ?>') center/cover no-repeat; filter: blur(30px) brightness(0.3) saturate(1.2); transform: scale(1.1);">
    </div>
    
    <!-- Gradient Overlay for smooth transition -->
    <div style="position:absolute; top:0; left:0; right:0; bottom:0; z-index:-1; 
        background: linear-gradient(135deg, rgba(10,10,10,0.9) 0%, rgba(10,10,10,0.4) 50%, var(--bg-dark) 100%);">
    </div>

    <!-- Content Glass Card -->
    <div style="max-width: 1200px; width: 100%; margin: 0 auto; display:flex; gap:50px; align-items:center; flex-wrap:wrap;">
        
        <!-- Left: High Res Poster -->
        <div style="flex: 0 0 350px; border-radius:16px; overflow:hidden; box-shadow: 0 25px 60px rgba(0,0,0,0.8); border: 1px solid rgba(255,255,255,0.1); perspective: 1000px;">
            <img src="<?php echo htmlspecialchars($movie['banner_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" style="width:100%; height:auto; display:block; transition:transform 0.5s;" onerror="this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=600'">
        </div>

        <!-- Right: Movie Details -->
        <div style="flex: 1; min-width:300px; color:white;">
            <div style="display:inline-block; padding:6px 12px; background:rgba(255,255,255,0.1); border-radius:20px; font-size:13px; font-weight:600; letter-spacing:1px; text-transform:uppercase; margin-bottom:15px; border:1px solid rgba(255,255,255,0.2); backdrop-filter:blur(5px);">
                <?php echo htmlspecialchars($movie['genre']); ?>
            </div>
            
            <h1 style="font-size: 56px; font-family:'Playfair Display', serif; line-height:1.1; margin-bottom:15px; text-shadow: 0 4px 20px rgba(0,0,0,0.5);">
                <?php echo htmlspecialchars($movie['title']); ?>
            </h1>
            
            <div style="display:flex; gap:20px; align-items:center; font-size:16px; color:var(--text-muted); margin-bottom:25px;">
                <span style="color:var(--gold); font-weight:bold; font-size:18px;">⭐ <?php echo htmlspecialchars($movie['rating']); ?></span>
                <span>•</span>
                <span><?php echo htmlspecialchars($movie['duration']); ?></span>
                <span>•</span>
                <span><?php echo htmlspecialchars($movie['language']); ?></span>
                <span>•</span>
                <span style="background:rgba(229, 9, 20, 0.15); color:var(--accent-red); padding:3px 8px; border-radius:4px; font-weight:bold; font-size:12px;">IMAX</span>
            </div>

            <p style="font-size: 18px; line-height:1.7; margin-bottom:40px; color:rgba(255,255,255,0.85); max-width:700px;">
                <?php echo nl2br(htmlspecialchars($movie['description'])); ?>
            </p>

            <!-- Action Buttons -->
            <div style="display:flex; gap:20px; align-items:center;">
                <?php if ($movie['seats_left'] <= 0): ?>
                    <button class="btn-primary" style="font-size:18px; padding:16px 40px; background:var(--accent-red); opacity:0.7; cursor:not-allowed;" disabled>
                        🚫 House Full
                    </button>
                <?php else: ?>
                    <a href="04_page_seat_booking.php?id=<?php echo $movie['id']; ?>" class="btn-primary" style="font-size:18px; padding:16px 40px; box-shadow: 0 10px 30px rgba(229, 9, 20, 0.5);">
                        🎟️ Book Tickets Now
                    </a>
                    <span style="color:var(--gold); font-weight:bold;">Only <?php echo $movie['seats_left']; ?> seats left!</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- SIMILAR MOVIES SECTION -->
<section style="padding:80px 40px; max-width: 1400px; margin: 0 auto;">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 40px; border-bottom: 1px solid var(--border-color); padding-bottom:20px;">
      <div>
        <div style="color:var(--accent-red); font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:2px; margin-bottom:5px;">You Might Also Like</div>
        <h2 style="font-size:32px;">Similar Blockbusters</h2>
      </div>
      <a href="02_page_catalog.php" class="btn-outline">See All →</a>
    </div>
    
    <div class="movies-grid">
        <?php
        $sim_res = pg_query_params($db, "SELECT * FROM movies WHERE id != $1 ORDER BY RANDOM() LIMIT 4", [$id]);
        if ($sim_res && pg_num_rows($sim_res) > 0) {
            while ($sim = pg_fetch_assoc($sim_res)) {
                echo '<div class="movie-card">';
                echo '<img src="' . htmlspecialchars($sim['banner_url']) . '" alt="Movie" onerror="this.src=\'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?q=80&w=600\'">';
                echo '<div class="card-content">';
                echo '<h3>' . htmlspecialchars($sim['title']) . '</h3>';
                echo '<div class="movie-meta">';
                echo '<span>' . htmlspecialchars($sim['genre']) . '</span>';
                echo '<span class="movie-rating">⭐ ' . htmlspecialchars($sim['rating']) . '</span>';
                echo '</div>';
                if ($sim['seats_left'] <= 0) {
                    echo '<div style="color:var(--accent-red); font-weight:bold; margin-bottom: 10px;">House Full</div>';
                } else {
                    echo '<div style="color:var(--gold); font-size:12px; margin-bottom: 10px;">Only ' . $sim['seats_left'] . ' seats left</div>';
                }
                echo '<a href="03_page_movie_details.php?id=' . $sim['id'] . '" class="btn btn-outline">View Details</a>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>
