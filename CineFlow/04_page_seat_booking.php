<?php include 'header.php';
// -- Auth Check --
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container' style='text-align:center;'><h2>Access Denied</h2><p>Login to book seats.</p><a href='auth_signIn.php' class='btn btn-primary'>Login</a></div>";
    include 'footer.php'; exit;
}
$movie = pg_fetch_assoc(pg_query_params($db, "SELECT * FROM movies WHERE id = $1", [isset($_GET['id']) ? (int)$_GET['id'] : 0]));
if (!$movie) { echo "<div class='container'><p>Movie not found.</p></div>"; include 'footer.php'; exit; }
if ($movie['seats_left'] <= 0) {
    echo "<div class='container' style='text-align:center;margin-top:100px;'><h2>House Full</h2><p>No seats left.</p><a href='02_page_catalog.php' class='btn btn-primary'>View Other Movies</a></div>";
    include 'footer.php'; exit;
}
?>
<div class="seats-page" style="margin-top: 40px;">
  <div class="breadcrumb"><a href="02_page_catalog.php">Movies</a><span>›</span><a href="03_page_movie_details.php?id=<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></a><span>›</span><span>Seat Selection</span></div>
  <div style="display:grid;grid-template-columns:1fr 350px;gap:50px;align-items:start">
    <div>
      <h1 style="margin-bottom:5px;">Configure & Pick Seats</h1>
      <p style="color:var(--text-muted);margin-bottom:30px;">Select date, time, and seats for <?php echo htmlspecialchars($movie['title']); ?>.</p>
      <h3 style="margin-bottom:10px;font-size:16px;">1. Select Date</h3><div class="chip-group" id="date-selector"></div>
      <h3 style="margin:10px 0;font-size:16px;">2. Select Time</h3>
      <div class="chip-group" id="time-selector"><div class="chip active">09:00 AM</div><div class="chip">10:30 AM</div><div class="chip">01:15 PM</div><div class="chip">04:45 PM</div><div class="chip">09:15 PM</div></div>
      <h3 style="margin:10px 0;font-size:16px;">3. Select Screen</h3>
      <div class="chip-group" id="screen-selector"><div class="chip active">Screen 1 (IMAX)</div><div class="chip">Screen 2 (Dolby)</div><div class="chip">Screen 3 (4DX)</div><div class="chip">Screen 4 (Standard)</div></div>
      <h3 style="margin:10px 0;font-size:16px;">4. Select Seats</h3>
      <div class="screen-bar" style="margin-left:30px;"></div>
      <div style="display:flex;justify-content:space-between;margin-bottom:20px;font-size:12px;color:var(--text-muted);">
          <div style="color:var(--gold);">■ VIP - ₹<?php echo $movie['price'] + 150; ?></div><div style="color:#4CAF50;">■ Premium - ₹<?php echo $movie['price'] + 50; ?></div><div style="color:#2196F3;">■ Standard - ₹<?php echo $movie['price']; ?></div>
      </div>
      <div style="display:flex;justify-content:center;margin-bottom:40px">
        <div class="seats-grid" id="seats-grid" style="flex-grow:1;display:grid;grid-template-columns:30px repeat(10, 1fr);gap:12px;perspective:1000px;" data-movie-id="<?php echo $movie['id']; ?>" data-movie-title="<?php echo htmlspecialchars($movie['title']); ?>" data-movie-price="<?php echo $movie['price']; ?>"></div>
      </div>
      <div class="seat-legend">
        <div class="legend-item"><div class="legend-dot free"></div>Available</div><div class="legend-item"><div class="legend-dot selected"></div>Selected</div><div class="legend-item"><div class="legend-dot taken"></div>Booked</div><div class="legend-item"><div class="legend-dot vip" style="background:rgba(245, 197, 24, 0.2);border:1px solid var(--gold);"></div>VIP</div>
      </div>
    </div>
    <div style="position:sticky;top:100px">
      <div class="booking-summary">
        <div style="font-size:12px;letter-spacing:3px;text-transform:uppercase;color:var(--text-muted);margin-bottom:20px;font-weight:bold;">Booking Summary</div>
        <div class="summary-row"><span class="summary-label">Movie</span><span class="summary-value" id="seat-movie-title"><?php echo htmlspecialchars($movie['title']); ?></span></div>
        <div class="summary-row"><span class="summary-label">Date</span><span class="summary-value" id="summary-date">Today</span></div>
        <div class="summary-row"><span class="summary-label">Show Time</span><span class="summary-value" id="summary-time">10:30 AM</span></div>
        <div class="summary-row"><span class="summary-label">Screen</span><span class="summary-value" id="summary-screen">Screen 1 (IMAX)</span></div>
        <div class="summary-row" style="margin-top:15px;border-top:1px dashed rgba(255,255,255,0.1);padding-top:15px;"><span class="summary-label">Seats (<span id="summary-count">0</span>)</span><span class="summary-value" id="summary-seats" style="font-size:13px;max-width:150px;text-align:right;">—</span></div>
        <div class="summary-row" style="border:none;padding-bottom:0;"><span class="summary-label" style="font-size:12px;color:var(--text-muted);">Tickets Amount</span><span class="summary-value" id="summary-subtotal" style="font-size:14px;">₹0</span></div>
        <div class="summary-row"><span class="summary-label">Total Amount</span><span class="summary-value total" id="summary-total">₹0</span></div>
      </div>
      <button class="btn btn-primary" id="continue-snacks" style="width:100%;font-size:16px;">Continue to Snacks →</button>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
