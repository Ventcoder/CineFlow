<?php include 'header.php'; ?>

<div class="container" style="margin-top: 60px;">
    <div style="text-align: center; margin-bottom: 60px;">
        <h1 style="font-size: 48px; margin-bottom: 15px;">Special Offers & Memberships</h1>
        <p style="color: var(--text-muted); font-size: 18px;">Unlock exclusive premium cinematic perks.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
        <!-- Offer 1 -->
        <div style="background: var(--bg-surface); border: 1px solid var(--gold); border-radius: var(--radius-lg); padding: 40px; text-align: center; transition: var(--transition);">
            <div style="font-size: 40px; margin-bottom: 20px;">⭐</div>
            <h3 style="margin-bottom: 15px;">CineFlow Black Tier</h3>
            <p style="color: var(--text-muted); margin-bottom: 25px;">Get 1 free movie ticket every month, 20% off all F&B, and priority VIP seat selection.</p>
            <h2 style="color: var(--gold); margin-bottom: 25px;">₹999 / yr</h2>
            <button class="btn btn-primary">Join Black Tier</button>
        </div>

        <!-- Offer 2 -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 40px; text-align: center; transition: var(--transition);">
            <div style="font-size: 40px; margin-bottom: 20px;">🍿</div>
            <h3 style="margin-bottom: 15px;">Snack Combo Bonanza</h3>
            <p style="color: var(--text-muted); margin-bottom: 25px;">Book any 2 VIP tickets and receive a complimentary large popcorn and dual large drinks.</p>
            <h2 style="color: white; margin-bottom: 25px;">Auto-applied</h2>
            <button class="btn btn-outline" style="width:100%">T&C Apply</button>
        </div>

        <!-- Offer 3 -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 40px; text-align: center; transition: var(--transition);">
            <div style="font-size: 40px; margin-bottom: 20px;">🦇</div>
            <h3 style="margin-bottom: 15px;">Midnight Premiere</h3>
            <p style="color: var(--text-muted); margin-bottom: 25px;">Get flat 15% off on all tickets booked for showings starting at or after 11:00 PM.</p>
            <h2 style="color: white; margin-bottom: 25px;">Code: MIDNIGHT</h2>
            <button class="btn btn-outline" style="width:100%">Copy Code</button>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
