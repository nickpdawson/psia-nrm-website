<?php get_header();

// One card renderer used for both sections.
function nrm_event_card_row() {
    $start = get_post_meta(get_the_ID(), 'nrm_event_start', true);
    $end = get_post_meta(get_the_ID(), 'nrm_event_end', true);
    $location = get_post_meta(get_the_ID(), 'nrm_event_location', true);
    $price = get_post_meta(get_the_ID(), 'nrm_event_price', true);
    $reg_url = get_post_meta(get_the_ID(), 'nrm_event_reg_url', true);
    $disciplines = get_the_terms(get_the_ID(), 'nrm_discipline');
    $types = get_the_terms(get_the_ID(), 'nrm_event_type');
    $is_past = $start && strtotime($start) < strtotime('today');
    ?>
    <div class="card event-card" style="padding:0;<?php if ($is_past) echo 'opacity:0.75;'; ?>">
      <div class="event-date">
        <div class="month"><?php echo $start ? date('M', strtotime($start)) : ''; ?></div>
        <div class="day"><?php echo $start ? date('j', strtotime($start)) : ''; ?><?php if ($end && $end !== $start) echo '–' . date('j', strtotime($end)); ?></div>
        <div style="font-size:0.75rem;opacity:0.6;"><?php echo $start ? date('Y', strtotime($start)) : ''; ?></div>
      </div>
      <div class="event-content">
        <div class="flex flex-wrap gap-2 mb-2">
          <?php if ($disciplines): foreach ($disciplines as $d): ?>
            <span class="badge badge-teal"><?php echo esc_html($d->name); ?></span>
          <?php endforeach; endif; ?>
          <?php if ($types): foreach ($types as $t): ?>
            <span class="badge" style="background:#FEF2F2;color:#991B1B;"><?php echo esc_html($t->name); ?></span>
          <?php endforeach; endif; ?>
          <?php if ($is_past): ?><span class="badge" style="background:var(--border-light);color:var(--text-muted);">Past event</span><?php endif; ?>
        </div>
        <h3><?php the_title(); ?></h3>
        <p class="text-secondary text-sm" style="display:flex;align-items:center;gap:0.35rem;"><?php echo nrm_icon('map-pin', 14); ?> <?php echo esc_html($location); ?></p>
        <div class="entry-content text-sm mt-1" style="color:var(--text-secondary);"><?php the_content(); ?></div>
        <div class="flex items-center justify-between mt-2">
          <span class="text-teal font-bold"><?php echo esc_html($price); ?></span>
          <?php if ($reg_url && !$is_past): ?>
            <a href="<?php echo esc_url($reg_url); ?>" target="_blank" class="btn btn-teal" style="padding:0.5rem 1rem;">Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php
}

$today = date('Y-m-d');
$base = [
    'post_type' => 'nrm_event', 'posts_per_page' => 30,
    'meta_key' => 'nrm_event_start', 'orderby' => 'meta_value',
];
$upcoming = new WP_Query($base + ['order' => 'ASC',
    'meta_query' => [['key' => 'nrm_event_start', 'value' => $today, 'compare' => '>=', 'type' => 'DATE']]]);
$past = new WP_Query($base + ['order' => 'DESC',
    'meta_query' => [['key' => 'nrm_event_start', 'value' => $today, 'compare' => '<', 'type' => 'DATE']]]);
?>
<section class="section">
  <div class="container">
    <h1 class="section-title" style="font-size:2rem;">Events &amp; Clinics</h1>
    <p class="text-secondary mb-4">Find clinics, assessments, and community events across the NRM region.</p>

    <div style="display:flex;flex-direction:column;gap:1rem;">
      <?php if ($upcoming->have_posts()): ?>
        <?php while ($upcoming->have_posts()): $upcoming->the_post(); nrm_event_card_row(); endwhile; wp_reset_postdata(); ?>
      <?php else: ?>
        <div class="card">
          <p class="text-secondary">The new season's schedule is being finalized — check back soon, or browse the
            <a href="https://www.psia-nrm.org/events/calendar/" target="_blank" rel="noopener">current NRM event calendar</a>
            in the meantime.</p>
        </div>
      <?php endif; ?>
    </div>

    <?php if ($past->have_posts()): ?>
      <h2 class="section-title" style="font-size:1.375rem;margin-top:3rem;">Past Events</h2>
      <div style="display:flex;flex-direction:column;gap:1rem;">
        <?php while ($past->have_posts()): $past->the_post(); nrm_event_card_row(); endwhile; wp_reset_postdata(); ?>
      </div>
    <?php endif; ?>

    <div class="card mt-4" style="text-align:center;background:var(--ice);">
      <p class="text-secondary">Looking for events outside the NRM region?</p>
      <a href="https://www.thesnowpros.org/events-education/" target="_blank">View the PSIA-AASI National Event Calendar &rarr;</a>
    </div>
  </div>
</section>
<?php get_footer(); ?>
