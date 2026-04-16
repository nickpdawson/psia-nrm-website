<?php get_header(); ?>

<!-- Hero -->
<section class="hero">
  <div class="container">
    <div style="max-width:720px;">
      <h1>Your journey.<br>Your career.<br>Our community.</h1>
      <p>PSIA-AASI Northern Rocky Mountain supports professional snow sports instructors across Montana, North Dakota, and South Dakota — helping you grow your career, connect with peers, and share the stoke.</p>
      <div style="display:flex;gap:1rem;margin-top:2rem;flex-wrap:wrap;">
        <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" class="btn btn-primary">Find Your Next Clinic &rarr;</a>
        <a href="<?php echo home_url('/resources'); ?>" class="btn btn-secondary">New Member Guide</a>
      </div>
      <div class="stats">
        <div><div class="stat-number">1,200+</div><div class="stat-label">Members</div></div>
        <div><div class="stat-number">20</div><div class="stat-label">Member Schools</div></div>
        <div><div class="stat-number">12+</div><div class="stat-label">Events This Season</div></div>
      </div>
    </div>
  </div>
</section>

<!-- Quick Actions -->
<section class="container quick-actions" style="margin-bottom:3rem;">
  <div class="card-grid card-grid-4">
    <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" class="card" style="text-decoration:none;color:inherit;">
      <div style="font-size:1.5rem;margin-bottom:0.75rem;">📋</div>
      <h3 class="text-teal font-bold mb-2">Register for a Clinic</h3>
      <p class="text-secondary text-sm">Find and sign up for upcoming clinics, prep courses, and assessments</p>
    </a>
    <a href="<?php echo home_url('/pathway'); ?>" class="card" style="text-decoration:none;color:inherit;">
      <div style="font-size:1.5rem;margin-bottom:0.75rem;">📚</div>
      <h3 class="text-teal font-bold mb-2">Exam Prep Materials</h3>
      <p class="text-secondary text-sm">Access digital manuals, study guides, and certification standards</p>
    </a>
    <a href="https://discord.gg/khuz6TYKX3" target="_blank" rel="noopener" class="card" style="text-decoration:none;color:inherit;">
      <div style="font-size:1.5rem;margin-bottom:0.75rem;">💬</div>
      <h3 class="text-teal font-bold mb-2">Join the Community</h3>
      <p class="text-secondary text-sm">Connect with fellow instructors on Discord — study groups, gear exchange, and more</p>
    </a>
    <a href="<?php echo home_url('/resources'); ?>" class="card" style="text-decoration:none;color:inherit;">
      <div style="font-size:1.5rem;margin-bottom:0.75rem;">🎓</div>
      <h3 class="text-teal font-bold mb-2">Scholarships</h3>
      <p class="text-secondary text-sm">Individual applications due Nov 15. School grants due Dec 1.</p>
    </a>
  </div>
</section>

<!-- Upcoming Events -->
<section class="section">
  <div class="container">
    <div class="flex items-center justify-between mb-4">
      <h2 class="section-title" style="margin-bottom:0;">Upcoming Events</h2>
      <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" class="text-sm">View full calendar &rarr;</a>
    </div>
    <div class="card-grid card-grid-3">
      <?php
      $events = new WP_Query([
        'post_type'      => 'nrm_event',
        'posts_per_page' => 3,
        'meta_key'       => 'nrm_event_start',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [['key' => 'nrm_event_start', 'value' => date('Y-m-d'), 'compare' => '>=', 'type' => 'DATE']],
      ]);
      if ($events->have_posts()): while ($events->have_posts()): $events->the_post();
        $start = get_post_meta(get_the_ID(), 'nrm_event_start', true);
        $location = get_post_meta(get_the_ID(), 'nrm_event_location', true);
        $price = get_post_meta(get_the_ID(), 'nrm_event_price', true);
        $reg_url = get_post_meta(get_the_ID(), 'nrm_event_reg_url', true);
        $disciplines = get_the_terms(get_the_ID(), 'nrm_discipline');
        $types = get_the_terms(get_the_ID(), 'nrm_event_type');
      ?>
      <div class="card event-card" style="padding:0;">
        <div class="event-date">
          <div class="month"><?php echo $start ? date('M', strtotime($start)) : ''; ?></div>
          <div class="day"><?php echo $start ? date('j', strtotime($start)) : ''; ?></div>
        </div>
        <div class="event-content">
          <?php if ($disciplines): foreach ($disciplines as $d): ?>
            <span class="badge badge-teal"><?php echo esc_html($d->name); ?></span>
          <?php endforeach; endif; ?>
          <?php if ($types): foreach ($types as $t): ?>
            <span class="badge" style="background:#EBF8FF;color:#2B6CB0;"><?php echo esc_html($t->name); ?></span>
          <?php endforeach; endif; ?>
          <h3 style="margin-top:0.5rem;"><?php the_title(); ?></h3>
          <p class="text-muted text-sm">📍 <?php echo esc_html($location); ?></p>
          <p class="text-secondary text-sm mt-1"><?php echo wp_trim_words(get_the_content(), 20); ?></p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-teal font-bold text-sm"><?php echo esc_html($price); ?></span>
            <?php if ($reg_url): ?>
              <a href="<?php echo esc_url($reg_url); ?>" target="_blank" class="btn btn-teal" style="padding:0.375rem 1rem;font-size:0.8125rem;">Register</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); else: ?>
        <p class="text-muted">No upcoming events. Check back soon!</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Our Mountain Campaign -->
<section class="section">
  <div class="container">
    <div class="campaign-banner">
      <span class="text-xs" style="text-transform:uppercase;letter-spacing:0.05em;opacity:0.6;">Campaign</span>
      <h2 style="font-size:2rem;font-weight:700;margin:0.5rem 0 1rem;">Our Mountain</h2>
      <p style="color:rgba(255,255,255,0.8);max-width:640px;margin-bottom:1.5rem;">NRM is building toward a future where every instructor has the resources, community, and pathways to thrive. Our Mountain is built on three pillars.</p>
      <div class="campaign-pillars">
        <div class="campaign-pillar">
          <h3 style="font-weight:600;font-size:0.875rem;margin-bottom:0.25rem;">Instructor Excellence</h3>
          <p style="font-size:0.75rem;opacity:0.7;">World-class education and certification</p>
        </div>
        <div class="campaign-pillar">
          <h3 style="font-weight:600;font-size:0.875rem;margin-bottom:0.25rem;">Professional Sustainability</h3>
          <p style="font-size:0.75rem;opacity:0.7;">Career pathways and livelihood support</p>
        </div>
        <div class="campaign-pillar">
          <h3 style="font-weight:600;font-size:0.875rem;margin-bottom:0.25rem;">Universal Access</h3>
          <p style="font-size:0.75rem;opacity:0.7;">Making snow sports instruction available to all</p>
        </div>
      </div>
      <a href="<?php echo home_url('/our-mountain'); ?>" class="btn btn-primary">Learn More</a>
    </div>
  </div>
</section>

<!-- Sponsors -->
<section class="section" style="padding-top:0;">
  <div class="container" style="text-align:center;">
    <p class="text-muted text-xs" style="text-transform:uppercase;letter-spacing:0.05em;margin-bottom:1rem;">Proud Partners & Sponsors</p>
    <div class="sponsor-bar">
      <span class="sponsor-item">The North Face</span>
      <span class="sponsor-item">Rossignol</span>
      <span class="sponsor-item">Smartwool</span>
      <span class="sponsor-item">Smith Optics</span>
      <span class="sponsor-item">Subaru</span>
      <span class="sponsor-item">Patagonia</span>
    </div>
  </div>
</section>

<?php get_footer(); ?>
