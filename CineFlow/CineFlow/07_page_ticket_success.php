<?php include 'header.php';
// -- Auth & Params Check --
if (!isset($_SESSION['user_id'])) { header('Location: auth_signIn.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['movie_id'])) { header('Location: 02_page_catalog.php'); exit; }
$movie_title = $_POST['movie_title'] ?? 'Unknown Movie'; $seats = $_POST['seats'] ?? 'N/A';
$show_date = $_POST['show_date'] ?? date('Y-m-d'); $show_time = $_POST['show_time'] ?? '10:30 AM';
$screen = $_POST['screen'] ?? 'Screen 1 (IMAX)'; $tickets_total = (int)($_POST['tickets_total'] ?? 0);
$snacks_total = (int)($_POST['snacks_total'] ?? 0); $grand_total = $tickets_total + $snacks_total;

// -- Atomic Seat Update --
$num_seats = count(explode(',', $seats));
$update_res = pg_query_params($db, "UPDATE movies SET seats_left = seats_left - $1 WHERE id = $2 AND seats_left >= $1 RETURNING id", [$num_seats, (int)$_POST['movie_id']]);
if (!$update_res || pg_num_rows($update_res) === 0) {
    echo "<div class='container' style='text-align:center;margin-top:100px;'><h2>Booking Error</h2><p>Not enough seats remain.</p><a href='02_page_catalog.php' class='btn btn-primary'>Go Back</a></div>"; include 'footer.php'; exit;
}
// -- Insert Booking --
$booking_id = 'CF-' . strtoupper(substr(md5(uniqid()), 0, 8));
pg_query_params($db, "INSERT INTO bookings (booking_ref, user_id, movie_id, movie_title, seats, snacks_amount, total_amount, show_date, show_time) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9)", [$booking_id, $_SESSION['user_id'], (int)$_POST['movie_id'], $movie_title, $seats, $snacks_total, $grand_total, $show_date, $show_time]);
?>
<div class="container" style="max-width: 600px; margin-top: 60px; margin-bottom: 60px;">
  <div style="text-align:center;margin-bottom:30px;"><div style="width:60px;height:60px;background:var(--accent-red);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:30px;margin:0 auto 20px;">✓</div><h2>Payment Successful</h2><p style="color:var(--text-muted);">Confirmed. Scan ticket at entrance.</p></div>
  <div style="background:var(--bg-surface);padding:0;border-radius:12px;border:1px solid var(--gold);overflow:hidden;position:relative;">
    <div style="background:rgba(245,197,24,0.1);padding:25px;border-bottom:1px dashed rgba(255,255,255,0.2);text-align:center;"><div style="font-family:'Playfair Display',serif;font-size:24px;color:var(--gold);margin-bottom:5px;"><?php echo htmlspecialchars($movie_title); ?></div><div style="font-size:14px;text-transform:uppercase;letter-spacing:2px;color:var(--text-muted);"><?php echo htmlspecialchars($screen); ?></div></div>
    <div style="padding:30px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
      <div><span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Date</span><div style="font-weight:bold;font-size:16px;"><?php echo htmlspecialchars($show_date); ?></div></div>
      <div><span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Time</span><div style="font-weight:bold;font-size:16px;"><?php echo htmlspecialchars($show_time); ?></div></div>
      <div style="grid-column:span 2;"><span style="font-size:11px;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;">Seats</span><div style="font-weight:bold;font-size:16px;color:white;"><?php echo htmlspecialchars($seats); ?></div></div>
    </div>
    <div style="padding:20px;background:rgba(0,0,0,0.5);text-align:center;border-top:1px dashed rgba(255,255,255,0.2);">
      <div style="background:white;padding:15px;display:inline-block;border-radius:8px;">
        <div style="width:150px;height:150px;display:grid;grid-template-columns:repeat(15, 1fr);grid-template-rows:repeat(15, 1fr);">
          <?php for($i=0;$i<225;$i++){ $c = rand(0,1) ? '#000' : '#fff'; echo "<div style='background:$c'></div>"; } ?>
        </div>
      </div>
      <div style="margin-top:15px;font-family:monospace;font-size:14px;letter-spacing:2px;color:var(--gold);"><?php echo $booking_id; ?></div>
    </div>
  </div>
  <div style="text-align:center;margin-top:40px;"><a href="panel_user_dashboard.php" class="btn btn-outline">Go to Dashboard</a><a href="02_page_catalog.php" class="btn btn-primary" style="margin-left:15px;">Book Another</a></div>
</div>
<?php include 'footer.php'; ?>
