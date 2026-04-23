<?php
include 'header.php';

// Dynamically create feedback table if it doesn't already exist
$create_table = "
CREATE TABLE IF NOT EXISTS feedback (
    id SERIAL PRIMARY KEY,
    user_id INT,
    type VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";
pg_query($db, $create_table);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $error = "You must be logged in to securely submit feedback.";
    } else {
        $type = $_POST['type'] ?? 'General';
        $message = trim($_POST['message'] ?? '');
        $user_id = $_SESSION['user_id'];
        
        if (empty($message)) {
            $error = "Please enter a message or review.";
        } else {
            $query = "INSERT INTO feedback (user_id, type, message) VALUES ($1, $2, $3)";
            $result = pg_query_params($db, $query, [$user_id, $type, $message]);
            if ($result) {
                $success = "Thank you! Your feedback was strictly recorded under your secure session.";
            } else {
                $error = "Failed to submit feedback. Please try again.";
            }
        }
    }
}
?>

<div class="auth-page" style="max-width: 650px; margin-top: 80px;">
    <h2 style="text-align: center; margin-bottom: 5px;">We Value Your Feedback</h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Help us make CineFlow the best premium cinema suite in the world.</p>

    <?php if ($success): ?>
        <div class="toast success" style="display:block;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="toast error" style="display:block;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div style="background: rgba(255,255,255,0.03); padding: 30px; border-radius: 8px; border: 1px dashed var(--border-color); text-align:center;">
            <p style="margin-bottom: 15px; color: var(--text-muted);">Feedback forms are securely restricted to authenticated users.</p>
            <a href="auth_signIn.php" class="btn btn-primary">Login to Leave Feedback</a>
        </div>
    <?php else: ?>
        <form method="POST" action="feedback.php">
            <div class="form-group">
                <label for="type">Feedback Type</label>
                <select name="type" id="type" style="cursor:pointer;">
                    <option value="General Site Aesthetics" style="background:var(--bg-surface);color:white">General Site Aesthetics</option>
                    <option value="Movie Selection" style="background:var(--bg-surface);color:white">Movie Selection</option>
                    <option value="Booking Process Bug" style="background:var(--bg-surface);color:white">Booking Process Bug</option>
                    <option value="UX/UI Request" style="background:var(--bg-surface);color:white">UX/UI Request</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="message">Your Thoughts</label>
                <textarea name="message" id="message" rows="5" placeholder="Tell us exactly what you loved or what you want changed..." required style="resize:vertical;"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; font-size:16px;">Submit Review &rarr;</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
