<footer>
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="logo" style="margin-bottom: 15px;">CineFlow</div>
        <p>Your ultimate destination for booking movie tickets online. Experience the magic of cinema with just a few clicks.</p>
        <div style="margin-top: 20px; display:flex; gap:15px; font-size: 20px;">
            <a href="#" style="color:var(--text-muted);">&#x1F4F7;</a>
            <a href="#" style="color:var(--text-muted);">&#x1F426;</a>
            <a href="#" style="color:var(--text-muted);">&#x1F4FA;</a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Explore</h4>
        <ul>
          <li><a href="01_page_home.php">Home</a></li>
          <li><a href="02_page_catalog.php">Now Showing</a></li>
          <li><a href="info_imax.php">IMAX Experience</a></li>
          <li><a href="info_offers.php">Special Offers</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Support</h4>
        <ul>
          <li><a href="support_help.php">Help Center</a></li>
          <li><a href="feedback.php">Feedback</a></li>
          <li><a href="support_contact.php">Contact Us</a></li>
          <li><a href="support_refunds.php">Refunds</a></li>
        </ul>
      </div>
      <div class="footer-col" style="padding-left: 20px;">
        <h4>Newsletter</h4>
        <p style="font-size: 14px; margin-bottom: 15px;">Subscribe for exclusive offers and premieres.</p>
        <form style="display:flex;">
            <input type="email" placeholder="Your email address" style="flex:1; padding:10px 15px; background:var(--bg-dark); border:1px solid rgba(255,255,255,0.1); border-radius:4px 0 0 4px; color:white; font-family:inherit; font-size:14px; outline:none;" required>
            <button type="submit" style="background:var(--accent-red); border:none; padding:10px 15px; border-radius:0 4px 4px 0; color:white; font-weight:bold; cursor:pointer;">Join</button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo date("Y"); ?> CineFlow Inc. All rights reserved.</p>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
