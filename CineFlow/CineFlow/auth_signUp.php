<?php
require 'db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($pass)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($pass !== $pass2) {
        $error = "Passwords do not match.";
    } elseif (strlen($pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = pg_query_params($db, "SELECT id FROM users WHERE LOWER(email) = LOWER($1)", [$email]);
        if (pg_num_rows($check) > 0) {
            $error = "Email already registered.";
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $insert = pg_query_params($db, "INSERT INTO users (name, email, password) VALUES ($1, $2, $3)", [$name, $email, $hash]);
            if ($insert) {
                $success = "Account created! You can now log in.";
            } else {
                $error = "Registration failed. Try again.";
            }
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="auth-page">
  <div class="auth-box">
    <div class="auth-logo">CineFlow</div>
    <div class="auth-subtitle">Create a new account</div>

    <?php if ($error): ?>
      <div class="toast error" style="display:block;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
      <div class="toast success" style="display:block;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="auth_signUp.php">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" placeholder="Full Name" required />
      </div>
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="Email Address" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="At least 6 characters" required />
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Repeat your password" required />
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px">Sign Up →</button>
    </form>

    <div class="auth-footer">
      Already have an account? <a href="auth_signIn.php">Sign In</a>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
