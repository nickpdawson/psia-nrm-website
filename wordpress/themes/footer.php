<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-col">
        <p><strong>PSIA-NRM</strong></p>
        <p>Professional Ski Instructors of America<br>American Association of Snowboard Instructors<br>Northern Rocky Mountain Division</p>
        <p class="mt-2">Serving ~1,200 snow sports professionals across Montana, North Dakota, and South Dakota.</p>
        <p class="mt-2" style="font-size:0.75rem;">Phone: 406-581-6139</p>
      </div>
      <div class="footer-col">
        <h3>Quick Links</h3>
        <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>">Events & Clinics</a>
        <a href="<?php echo home_url('/community'); ?>">Member Directory</a>
        <a href="<?php echo home_url('/pathway'); ?>">My Pathway</a>
        <a href="<?php echo home_url('/resources'); ?>">Resources</a>
        <a href="<?php echo home_url('/resources'); ?>">Scholarships</a>
      </div>
      <div class="footer-col">
        <h3>Resources</h3>
        <a href="<?php echo home_url('/resources'); ?>">Board of Directors</a>
        <a href="<?php echo home_url('/resources'); ?>">Member Schools</a>
        <a href="https://www.thesnowpros.org" target="_blank">PSIA-AASI National</a>
        <a href="<?php echo home_url('/our-mountain'); ?>">Our Mountain Campaign</a>
      </div>
      <div class="footer-col">
        <h3>Connect</h3>
        <a href="https://discord.gg/khuz6TYKX3" target="_blank" rel="noopener">Join Our Discord</a>
        <a href="#">Facebook</a>
        <a href="#">Instagram</a>
        <a href="#">The Stoke Newsletter</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; <?php echo date('Y'); ?> PSIA-AASI Northern Rocky Mountain Division. All rights reserved.</span>
      <em>&ldquo;Creating Lifelong Adventures Through Education&rdquo;</em>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
