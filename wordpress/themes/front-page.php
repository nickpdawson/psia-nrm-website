<?php get_header(); ?>

<!-- Hero banner -->
<section class="hero-banner">
  <img src="<?php echo home_url('/wp-content/uploads/brand/nrm-hero-banner.jpg'); ?>"
       alt="PSIA-AASI Northern Rocky Mountain" width="2400" height="728" style="width:100%;display:block;">
</section>

<!-- Tagline + calls to action -->
<section class="hero" style="padding:2.5rem 0;">
  <div class="container">
    <div style="max-width:760px;">
      <h1 style="font-size:clamp(1.75rem,4.5vw,3rem);font-weight:700;line-height:1.15;margin-bottom:0.75rem;letter-spacing:-0.02em;color:white;">
        <?php echo nl2br(esc_html(nrm_setting('hero_heading'))); ?>
      </h1>
      <p style="font-size:1.125rem;color:rgba(255,255,255,0.85);max-width:600px;line-height:1.6;">
        <?php echo esc_html(nrm_setting('hero_text')); ?>
      </p>
      <div style="display:flex;gap:1rem;margin-top:1.5rem;flex-wrap:wrap;">
        <a href="<?php echo esc_url(nrm_setting('hero_cta1_url')); ?>" class="btn btn-primary"><?php echo esc_html(nrm_setting('hero_cta1_label')); ?></a>
        <a href="<?php echo esc_url(nrm_setting('hero_cta2_url')); ?>" class="btn btn-secondary"><?php echo esc_html(nrm_setting('hero_cta2_label')); ?></a>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,auto);gap:1.5rem;margin-top:2rem;max-width:400px;">
        <?php foreach ([1, 2, 3] as $n): ?>
        <div>
          <div style="font-size:clamp(1.5rem,3vw,2.5rem);font-weight:700;color:white;"><?php echo esc_html(nrm_setting("stat{$n}_number")); ?></div>
          <div style="font-size:0.875rem;color:rgba(255,255,255,0.6);"><?php echo esc_html(nrm_setting("stat{$n}_label")); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- Quick Actions -->
<section class="container quick-actions" style="margin-bottom:3rem;">
  <div class="card-grid card-grid-4">
    <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" class="card" style="text-decoration:none;color:inherit;">
      <div class="quick-action-icon"><?php echo nrm_icon('clipboard-list', 26); ?></div>
      <h3 class="text-teal font-bold mb-2">Register for a Clinic</h3>
      <p class="text-secondary text-sm">Find and sign up for upcoming clinics, prep courses, and assessments</p>
    </a>
    <a href="<?php echo home_url('/disciplines/'); ?>" class="card" style="text-decoration:none;color:inherit;">
      <div class="quick-action-icon"><?php echo nrm_icon('book-open', 26); ?></div>
      <h3 class="text-teal font-bold mb-2">Exam Prep Materials</h3>
      <p class="text-secondary text-sm">Access certification standards, assessment forms, and prep outlines by discipline</p>
    </a>
    <a href="https://discord.gg/khuz6TYKX3" target="_blank" rel="noopener" class="card" style="text-decoration:none;color:inherit;">
      <div class="quick-action-icon"><?php echo nrm_icon('messages-square', 26); ?></div>
      <h3 class="text-teal font-bold mb-2">Join the Community</h3>
      <p class="text-secondary text-sm">Connect with fellow instructors on Discord — study groups, gear exchange, and more</p>
    </a>
    <a href="<?php echo home_url('/whos-who/'); ?>" class="card" style="text-decoration:none;color:inherit;">
      <div class="quick-action-icon"><?php echo nrm_icon('users', 26); ?></div>
      <h3 class="text-teal font-bold mb-2">Who's Who</h3>
      <p class="text-secondary text-sm">Meet the board, education teams, discipline chairs, and office staff</p>
    </a>
  </div>
</section>

<!-- Community Photo Gallery -->
<section style="margin-bottom:3rem;">
  <div class="container">
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <?php foreach (nrm_gallery_images() as $img): ?>
        <div style="width:180px;height:180px;border-radius:1rem;overflow:hidden;flex-shrink:0;">
          <img src="<?php echo esc_url($img['src']); ?>"
               alt="<?php echo esc_attr($img['alt']); ?>"
               style="width:100%;height:100%;object-fit:cover;object-position:<?php echo esc_attr($img['pos']); ?>;">
        </div>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:0.75rem;font-size:0.75rem;color:var(--text-muted);">Real NRM instructors across the region</p>
  </div>
</section>

<!-- Upcoming Events (falls back to the most recent events between seasons) -->
<?php
$events = new WP_Query([
  'post_type'      => 'nrm_event',
  'posts_per_page' => 3,
  'meta_key'       => 'nrm_event_start',
  'orderby'        => 'meta_value',
  'order'          => 'ASC',
  'meta_query'     => [['key' => 'nrm_event_start', 'value' => date('Y-m-d'), 'compare' => '>=', 'type' => 'DATE']],
]);
$events_heading = 'Upcoming Events';
if (!$events->have_posts()) {
  $events = new WP_Query([
    'post_type'      => 'nrm_event',
    'posts_per_page' => 3,
    'meta_key'       => 'nrm_event_start',
    'orderby'        => 'meta_value',
    'order'          => 'DESC',
  ]);
  $events_heading = 'Recent Events';
}
?>
<section class="section" style="padding-top:0;">
  <div class="container">
    <div class="flex items-center justify-between mb-4">
      <h2 class="section-title" style="margin-bottom:0;"><?php echo esc_html($events_heading); ?></h2>
      <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" class="text-sm">View full calendar &rarr;</a>
    </div>
    <div class="card-grid card-grid-3">
      <?php
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
          <?php if ($disciplines && !is_wp_error($disciplines)): foreach ($disciplines as $d): ?>
            <span class="badge badge-teal"><?php echo esc_html($d->name); ?></span>
          <?php endforeach; endif; ?>
          <?php if ($types && !is_wp_error($types)): foreach ($types as $t): ?>
            <span class="badge" style="background:#EBF8FF;color:#2B6CB0;"><?php echo esc_html($t->name); ?></span>
          <?php endforeach; endif; ?>
          <h3 style="margin-top:0.5rem;"><?php the_title(); ?></h3>
          <p class="text-muted text-sm" style="display:flex;align-items:center;gap:0.35rem;"><?php echo nrm_icon('map-pin', 14); ?> <?php echo esc_html($location); ?></p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-teal font-bold text-sm"><?php echo esc_html($price); ?></span>
            <?php if ($reg_url && $start && strtotime($start) >= strtotime('today')): ?>
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

<!-- Our Mountain Campaign with image -->
<section class="section">
  <div class="container">
    <div class="campaign-banner" style="position:relative;overflow:hidden;">
      <div style="position:absolute;right:-2rem;bottom:-2rem;width:300px;height:300px;opacity:0.1;">
        <img src="<?php echo home_url('/wp-content/uploads/images/hero image tall rect.jpeg'); ?>"
             alt="" style="width:100%;height:100%;object-fit:cover;border-radius:1rem;">
      </div>
      <div style="position:relative;z-index:1;">
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
        <a href="<?php echo home_url('/our-mountain'); ?>" class="btn btn-primary" style="margin-top:1rem;">Learn More</a>
      </div>
    </div>
  </div>
</section>



<?php get_footer(); ?>
