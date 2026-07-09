</main>
<footer class="site-footer">
  <div class="container">
    <nav class="footer-grid" aria-label="Footer">
      <div class="footer-col">
        <p><strong>Northern Rocky</strong></p>
        <p>Professional Ski Instructors of America<br>American Association of Snowboard Instructors<br>Northern Rocky Mountain Division</p>
        <p class="mt-2"><?php echo esc_html(nrm_setting('footer_blurb')); ?></p>
        <p class="mt-2" style="font-size:0.75rem;">Phone: <?php echo esc_html(nrm_setting('office_phone')); ?> · <a href="mailto:<?php echo esc_attr(nrm_setting('office_email')); ?>"><?php echo esc_html(nrm_setting('office_email')); ?></a></p>
      </div>
      <div class="footer-col">
        <h3>Quick Links</h3>
        <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>">Events &amp; Clinics</a>
        <a href="<?php echo home_url('/scholarships/'); ?>">Scholarships</a>
        <a href="<?php echo home_url('/membership/'); ?>">Membership</a>
        <a href="<?php echo home_url('/contact/'); ?>">Contact</a>
      </div>
      <div class="footer-col">
        <h3>Resources</h3>
        <a href="<?php echo home_url('/disciplines/'); ?>">Disciplines</a>
        <a href="<?php echo home_url('/whos-who/'); ?>">Who's Who &amp; Schools</a>
        <a href="<?php echo home_url('/help/'); ?>">Help &amp; How-To</a>
        <a href="<?php echo home_url('/ada-accommodation/'); ?>">ADA Accommodation</a>
        <a href="https://www.thesnowpros.org" target="_blank" rel="noopener">PSIA-AASI National</a>
      </div>
      <div class="footer-col">
        <h3>Connect</h3>
        <a href="https://discord.gg/khuz6TYKX3" target="_blank" rel="noopener">Join Our Discord</a>
        <a href="<?php echo home_url('/archive/'); ?>">The Stoke Newsletter</a>
        <a href="<?php echo home_url('/our-mountain/'); ?>">Our Mountain Campaign</a>
      </div>
    </nav>
    <div class="footer-bottom">
      <span>&copy; <?php echo date('Y'); ?> PSIA-AASI Northern Rocky Mountain Division. All rights reserved.</span>
      <em>&ldquo;Creating Lifelong Adventures Through Education&rdquo;</em>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
<script>
(function () {
  var btn = document.querySelector('.nav-toggle');
  var nav = document.getElementById('primary-nav');
  if (!btn || !nav) return;
  function close() { nav.classList.remove('is-open'); btn.setAttribute('aria-expanded', 'false'); }
  btn.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') close(); });
  nav.addEventListener('click', function (e) { if (e.target.tagName === 'A') close(); });
})();
</script>
</body>
</html>
