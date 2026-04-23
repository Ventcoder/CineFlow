<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<div class='container' style='text-align:center;'><h2>Access Denied</h2><p>You must be logged in to view snacks.</p><a href='auth_signIn.php' class='btn btn-primary'>Login Now</a></div>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['seats'])) {
    header('Location: 02_page_catalog.php');
    exit;
}

$movie_id = (int)$_POST['movie_id'];
$movie_title = $_POST['movie_title'];
$seats = $_POST['seats'];
$show_date = $_POST['show_date'];
$show_time = $_POST['show_time'];
$tickets_total = (int)$_POST['tickets_total'];
?>

<div class="seats-page" style="margin-top: 40px; max-width: 1000px;">

    <div class="breadcrumb">
      <a href="02_page_catalog.php">Movies</a>
      <span>›</span>
      <a href="03_page_movie_details.php?id=<?php echo $movie_id; ?>"><?php echo htmlspecialchars($movie_title); ?></a>
      <span>›</span>
      <span>Add Snacks</span>
    </div>

    <div style="display:grid;grid-template-columns:1fr 350px;gap:50px;align-items:start">

        <div>
            <h1 style="margin-bottom: 5px;">Grab Some Snacks!</h1>
            <p style="color:var(--text-muted); margin-bottom: 30px;">Pre-book your food and beverages to skip the queue.</p>

            <div id="snacks-container">

            </div>
        </div>

        <div style="position:sticky;top:100px">
            <div class="booking-summary">
                <div style="font-size:12px;letter-spacing:3px;text-transform:uppercase;color:var(--text-muted);margin-bottom:20px; font-weight:bold;">Order Summary</div>

                <div class="summary-row">
                    <span class="summary-label">Movie</span>
                    <span class="summary-value"><?php echo htmlspecialchars($movie_title); ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">Show</span>
                    <span class="summary-value"><?php echo htmlspecialchars($show_date . ' | ' . $show_time); ?></span>
                </div>
                <div class="summary-row" style="margin-top: 15px; border-top: 1px dashed rgba(255,255,255,0.1); padding-top: 15px;">
                    <span class="summary-label">Tickets Total</span>
                    <span class="summary-value" style="font-size:16px;">₹<?php echo $tickets_total; ?></span>
                </div>
                <div class="summary-row">
                    <span class="summary-label">F&B Total</span>
                    <span class="summary-value" id="fb-total" style="font-size:16px; color:var(--gold);">₹0</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Grand Total</span>
                    <span class="summary-value total" id="grand-total">₹<?php echo $tickets_total; ?></span>
                </div>
            </div>

            <form id="checkout-form" method="POST" action="06_page_payment_gateway.php">
                <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
                <input type="hidden" name="movie_title" value="<?php echo htmlspecialchars($movie_title); ?>">
                <input type="hidden" name="seats" value="<?php echo htmlspecialchars($seats); ?>">
                <input type="hidden" name="show_date" value="<?php echo htmlspecialchars($show_date); ?>">
                <input type="hidden" name="show_time" value="<?php echo htmlspecialchars($show_time); ?>">
                <input type="hidden" name="screen" value="<?php echo htmlspecialchars($_POST['screen'] ?? 'Screen 1 (IMAX)'); ?>">
                <input type="hidden" name="tickets_total" value="<?php echo $tickets_total; ?>">

                <input type="hidden" name="snacks_total" id="input-snacks-total" value="0">

                <button type="button" id="pay-btn" class="btn btn-primary" style="width:100%; font-size:16px;">Proceed to Pay ₹<span id="btn-total"><?php echo $tickets_total; ?></span></button>
            </form>
            <a href="04_page_seat_booking.php?id=<?php echo $movie_id; ?>" class="btn btn-outline" style="width:100%;text-align:center;margin-top:10px;display:block">← Back to Seats</a>
        </div>
    </div>
</div>

<script>
    const TICKETS_TOTAL = <?php echo $tickets_total; ?>;
</script>

<?php include 'footer.php'; ?>
