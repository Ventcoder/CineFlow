<?php include 'header.php';
// -- Auth Check --
if (!isset($_SESSION['user_id'])) { echo "<div class='container' style='text-align:center;'><h2>Access Denied</h2><p>Login to securely checkout.</p><a href='auth_signIn.php' class='btn btn-primary'>Login</a></div>"; include 'footer.php'; exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['movie_id'])) { header('Location: 02_page_catalog.php'); exit; }
// -- Vars --
$movie_id = (int)$_POST['movie_id']; $movie_title = $_POST['movie_title']; $seats = $_POST['seats'];
$show_date = $_POST['show_date']; $show_time = $_POST['show_time']; $screen = $_POST['screen'] ?? 'Screen 1 (IMAX)';
$tickets_total = (int)$_POST['tickets_total']; $snacks_total = (int)($_POST['snacks_total'] ?? 0);
$grand_total = $tickets_total + $snacks_total;
?>
<div class="auth-page" style="max-width: 600px; margin-top: 60px;">
  <h2 style="text-align: center; margin-bottom: 5px;">Secure Checkout</h2>
  <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Enter your payment details below</p>
  <div style="background:rgba(255,255,255,0.03);padding:20px;border-radius:8px;border:1px solid rgba(255,255,255,0.1);margin-bottom:30px;">
    <div style="display:flex;justify-content:space-between;margin-bottom:10px;"><span style="color:var(--text-muted);">Amount Due</span><strong style="font-size:20px;color:var(--gold);">₹<?php echo $grand_total; ?></strong></div>
    <div style="display:flex;justify-content:space-between;font-size:14px;"><span style="color:var(--text-muted);"><?php echo htmlspecialchars($movie_title); ?> (<?php echo htmlspecialchars($seats); ?>)</span></div>
  </div>
  <form action="07_page_ticket_success.php" method="POST" id="payment-form">
    <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>"><input type="hidden" name="movie_title" value="<?php echo htmlspecialchars($movie_title); ?>"><input type="hidden" name="seats" value="<?php echo htmlspecialchars($seats); ?>"><input type="hidden" name="show_date" value="<?php echo htmlspecialchars($show_date); ?>"><input type="hidden" name="show_time" value="<?php echo htmlspecialchars($show_time); ?>"><input type="hidden" name="screen" value="<?php echo htmlspecialchars($screen); ?>"><input type="hidden" name="tickets_total" value="<?php echo $tickets_total; ?>"><input type="hidden" name="snacks_total" value="<?php echo $snacks_total; ?>">
    <div class="form-group"><label>Name on Card</label><input type="text" placeholder="Name on Card" required></div>
    <div class="form-group"><label>Card Number</label><input type="text" placeholder="XXXX-XXXX-XXXX-XXXX" maxlength="19" required></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
      <div class="form-group"><label>Expiry Date</label><input type="text" placeholder="MM/YY" maxlength="5" required></div>
      <div class="form-group"><label>CVV</label><input type="password" placeholder="•••" maxlength="3" required></div>
    </div>
    <button type="submit" class="btn btn-primary" id="btn-pay" style="width:100%;margin-top:15px;font-size:16px;">Pay ₹<?php echo $grand_total; ?></button>
  </form>
</div>
<script>
document.getElementById('payment-form').addEventListener('submit', function(e) {
  const btn = document.getElementById('btn-pay'); btn.innerHTML = '<span style="opacity:0.7">Processing...</span>'; btn.disabled = true;
  e.preventDefault(); setTimeout(() => { document.body.style.opacity = '0'; document.body.style.transition = 'opacity 0.4s ease'; setTimeout(() => this.submit(), 400); }, 1500);
});
</script>
<?php include 'footer.php'; ?>
