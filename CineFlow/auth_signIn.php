<?php
require 'db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $query = "SELECT * FROM users WHERE email = $1";
        $result = pg_query_params($db, $query, [$email]);

        if ($result && pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            $is_valid = false;
            if (password_verify($password, $user['password'])) {
                $is_valid = true;
            } elseif ($password === $user['password']) {
                // If it was plain-text in the database seed, automatically upgrade it to a secure hash
                $new_hash = password_hash($password, PASSWORD_DEFAULT);
                pg_query_params($db, "UPDATE users SET password = $1 WHERE id = $2", [$new_hash, $user['id']]);
                $is_valid = true;
            }

            if ($is_valid) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['username'] = $user['name'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                
                if (isset($_POST['remember'])) {
                    setcookie('cf_remember', $user['id'] . ':' . hash('sha256', $user['password']), time() + (86400 * 30), "/");
                }
                
                header("Location: 01_page_home.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="auth-page">
  <div class="auth-box">
    <div class="auth-logo">CineFlow</div>
    <div class="auth-subtitle">Sign in to your account</div>

    <?php if ($error): ?>
      <div class="toast error" style="display:block;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="auth_signIn.php">
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="Email Address" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required />
      </div>
      <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
        <input type="checkbox" name="remember" id="remember" style="width: auto;" />
        <label for="remember" style="margin: 0; color: var(--text-muted); cursor: pointer; font-size: 14px;">Remember me for 30 days</label>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px">Sign In →</button>
    </form>

    <div class="auth-footer">
      Don't have an account? <a href="auth_signUp.php">Create one</a>
    </div>

    <div style="margin-top:24px;padding:16px;background:var(--surface2);border-radius:6px;border:1px solid var(--border)">
      <div style="font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--gray);margin-bottom:10px">Demo Credentials</div>
      <div style="font-size:12px;color:var(--gray);line-height:1.8">
        <strong style="color:var(--white)">Admin:</strong> admin@cineflow.com / admin123<br>
        <strong style="color:var(--white)">User:</strong> Register a new account
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
