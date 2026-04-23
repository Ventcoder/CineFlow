<?php include 'header.php'; ?>

<div class="auth-page" style="max-width: 650px; margin-top: 80px;">
    <h2 style="text-align: center; margin-bottom: 5px;">Contact Customer Support</h2>
    <p style="text-align: center; color: var(--text-muted); margin-bottom: 30px;">Reach out our 24/7 dedicated support desk.</p>

    <div style="display: flex; justify-content: space-between; margin-bottom: 30px; text-align: center; gap: 20px;">
        <div style="flex: 1; padding: 20px; background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-radius: 8px;">
            <div style="font-size: 24px; margin-bottom: 10px;">📞</div>
            <strong style="display:block;">Phone Support</strong>
            <span style="font-size: 13px; color: var(--text-muted);">1-800-CINEFLOW</span>
        </div>
        <div style="flex: 1; padding: 20px; background: rgba(255,255,255,0.03); border: 1px solid var(--border-color); border-radius: 8px;">
            <div style="font-size: 24px; margin-bottom: 10px;">✉️</div>
            <strong style="display:block;">Email Desk</strong>
            <span style="font-size: 13px; color: var(--text-muted);">support@cineflow.com</span>
        </div>
    </div>

    <form method="POST" action="#">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label>Your Name</label>
                <input type="text" placeholder="Your Name" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" placeholder="Your Email" required>
            </div>
        </div>
        <div class="form-group">
            <label>Subject</label>
            <input type="text" placeholder="e.g. Missing Ticket Issue" required>
        </div>
        <div class="form-group">
            <label>Message</label>
            <textarea rows="5" placeholder="How can we help you?" required style="resize:vertical;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; font-size:16px;">Send Message</button>
    </form>
</div>

<?php include 'footer.php'; ?>
