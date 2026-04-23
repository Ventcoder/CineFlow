<?php
include 'header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h2>Access Denied. Admins Only.</h2></div>";
    include 'footer.php';
    exit();
}

$movies_count = pg_fetch_assoc(pg_query($db, "SELECT COUNT(*) AS c FROM movies"))['c'];
$bookings_count = pg_fetch_assoc(pg_query($db, "SELECT COUNT(*) AS c FROM bookings"))['c'];
$users_count = pg_fetch_assoc(pg_query($db, "SELECT COUNT(*) AS c FROM users"))['c'];

// Calculate real total revenue
$revenue_query = pg_fetch_assoc(pg_query($db, "SELECT SUM(total_amount) AS total FROM bookings"));
$total_revenue = $revenue_query['total'] ? $revenue_query['total'] : 0;
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
        <h2>Dashboard Overview</h2>
        <div class="stats-row" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 40px;">
            <div class="stat-card" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                <h3 style="color: var(--text-muted); font-size: 14px; text-transform: uppercase; margin-bottom: 10px;">Total Revenue</h3>
                <p style="font-size: 28px; color: var(--gold); font-weight: bold;">₹<?php echo number_format($total_revenue); ?></p>
            </div>
            <div class="stat-card" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                <h3 style="color: var(--text-muted); font-size: 14px; text-transform: uppercase; margin-bottom: 10px;">Total Tickets</h3>
                <p style="font-size: 28px; color: white; font-weight: bold;"><?php echo $bookings_count; ?></p>
            </div>
            <div class="stat-card" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                <h3 style="color: var(--text-muted); font-size: 14px; text-transform: uppercase; margin-bottom: 10px;">Active Movies</h3>
                <p style="font-size: 28px; color: white; font-weight: bold;"><?php echo $movies_count; ?></p>
            </div>
            <div class="stat-card" style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                <h3 style="color: var(--text-muted); font-size: 14px; text-transform: uppercase; margin-bottom: 10px;">Reg. Users</h3>
                <p style="font-size: 28px; color: white; font-weight: bold;"><?php echo $users_count; ?></p>
            </div>
        </div>
        
        <h3>Recent Bookings</h3>
        <table>
            <thead>
                <tr><th>Ref</th><th>User ID</th><th>Movie</th><th>Amount</th></tr>
            </thead>
            <tbody>
                <?php
                $res = pg_query($db, "SELECT b.*, m.title as movie_title FROM bookings b JOIN movies m ON b.movie_id = m.id ORDER BY b.id DESC LIMIT 5");
                while ($row = pg_fetch_assoc($res)) {
                    echo "<tr><td>#CF-{$row['id']}</td><td>{$row['user_id']}</td><td>{$row['movie_title']}</td><td>₹{$row['total_amount']}</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <h3 style="margin-top: 40px;">Recent User Feedback</h3>
        <table style="margin-bottom: 50px;">
            <thead>
                <tr><th>ID</th><th>Type</th><th>Message</th></tr>
            </thead>
            <tbody>
                <?php
                $feed_res = pg_query($db, "SELECT * FROM feedback ORDER BY id DESC LIMIT 5");
                if ($feed_res) {
                    while ($feed = pg_fetch_assoc($feed_res)) {
                        echo "<tr><td>{$feed['id']}</td><td>{$feed['type']}</td><td>" . htmlspecialchars($feed['message']) . "</td></tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>
