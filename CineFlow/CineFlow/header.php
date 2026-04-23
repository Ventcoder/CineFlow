<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db.php';

// Remember Me Logic
if (!isset($_SESSION['user_id']) && isset($_COOKIE['cf_remember'])) {
    $parts = explode(':', $_COOKIE['cf_remember']);
    if (count($parts) === 2) {
        $uid = (int)$parts[0];
        $uhash = $parts[1];
        $res = pg_query_params($db, "SELECT * FROM users WHERE id = $1", [$uid]);
        if ($res && pg_num_rows($res) > 0) {
            $user = pg_fetch_assoc($res);
            if (hash('sha256', $user['password']) === $uhash) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CineFlow – Movies Online</title>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

  <nav class="navbar">
    <a href="01_page_home.php" class="logo">CineFlow</a>
    <ul class="nav-links">
      <li><a href="01_page_home.php">Home</a></li>
      <li><a href="02_page_catalog.php">Movies</a></li>
          <li><a href="feedback.php">Feedback</a></li>
      <?php if (isset($_SESSION['user_role'])): ?>
          <?php if ($_SESSION['user_role'] === 'admin'): ?>
              <li><a href="panel_admin_dashboard.php">Admin Panel</a></li>
          <?php else: ?>
              <li><a href="panel_user_dashboard.php">My Account</a></li>
          <?php endif; ?>
          <li style="display: flex; align-items: center; background: rgba(255, 255, 255, 0.05); padding: 8px 16px; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.1); margin-left:15px; margin-right: 15px;">
              <span style="color: var(--text-muted); font-size: 13px; margin-right: 6px;">Secure Session:</span>
              <strong style="color: var(--gold); font-family: 'Playfair Display', serif; font-size: 15px; letter-spacing: 0.5px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</strong>
          </li>
          <li><a href="auth_signOut.php" class="btn-outline" style="border-color: rgba(255,255,255,0.2); padding: 8px 20px;">Logout</a></li>
      <?php else: ?>
          <li><a href="auth_signIn.php" class="btn-nav">Login</a></li>
          <li><a href="auth_signUp.php" class="btn-nav">Register</a></li>
      <?php endif; ?>
    </ul>
  </nav>
