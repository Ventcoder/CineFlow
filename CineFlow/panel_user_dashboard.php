<?php
include 'header.php';

// Allow only users (or admins looking at their own dashboard, but primarily users)
if (!isset($_SESSION['user_id'])) {
    header("Location: auth_signIn.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$toast = '';

// Check for Delete Action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    $booking_id = (int)$_POST['booking_id'];
    
    // Validate it belongs to this user
    $check = pg_query_params($db, "SELECT * FROM bookings WHERE id = $1 AND user_id = $2", [$booking_id, $user_id]);
    if (pg_num_rows($check) > 0) {
        pg_query_params($db, "DELETE FROM bookings WHERE id = $1", [$booking_id]);
        $toast = "Booking cancelled successfully.";
    } else {
        $toast = "Failed to cancel booking.";
    }
}
?>

<div class="container">
    <h2>My Dashboard</h2>
    <p style="color:#666">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage your account and bookings here.</p>

    <?php if ($toast): ?>
      <div class="toast success"><?php echo htmlspecialchars($toast); ?></div>
    <?php endif; ?>

    <h3 style="margin-top: 30px; margin-bottom: 10px;">My Bookings</h3>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Ref ID</th>
                    <th>Movie</th>
                    <th>Date</th>
                    <th>Seats</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $res = pg_query_params($db, "SELECT * FROM bookings WHERE user_id = $1 ORDER BY created_at DESC", [$user_id]);
                if ($res && pg_num_rows($res) > 0) {
                    while ($row = pg_fetch_assoc($res)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['booking_ref']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['movie_title']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['show_date']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['seats']) . '</td>';
                        echo '<td>₹' . htmlspecialchars($row['total_amount']) . '</td>';
                        echo '<td>
                                <form method="POST" onsubmit="return confirm(\'Are you sure you want to cancel this booking?\');">
                                    <input type="hidden" name="action" value="cancel">
                                    <input type="hidden" name="booking_id" value="' . $row['id'] . '">
                                    <button type="submit" class="btn btn-danger">Cancel</button>
                                </form>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="6" style="text-align:center;">You have no bookings.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
