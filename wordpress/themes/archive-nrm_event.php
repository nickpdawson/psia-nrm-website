<?php get_header();

// Pull all events once; build the data model for both the list and the calendar.
$all = new WP_Query([
  'post_type' => 'nrm_event', 'posts_per_page' => 500,
  'meta_key' => 'nrm_event_start', 'orderby' => 'meta_value', 'order' => 'ASC',
]);
$events = [];
if ($all->have_posts()) { while ($all->have_posts()) { $all->the_post();
  $id = get_the_ID();
  $start = get_post_meta($id, 'nrm_event_start', true);
  if (!$start) continue;
  $end = get_post_meta($id, 'nrm_event_end', true) ?: $start;
  $discs = get_the_terms($id, 'nrm_discipline'); $discs = ($discs && !is_wp_error($discs)) ? $discs : [];
  $types = get_the_terms($id, 'nrm_event_type');  $types = ($types && !is_wp_error($types)) ? $types : [];
  $events[] = [
    'id' => $id, 'title' => get_the_title(), 'url' => get_permalink(),
    'start' => $start, 'end' => $end,
    'location' => get_post_meta($id, 'nrm_event_location', true),
    'state' => get_post_meta($id, 'nrm_event_state', true),
    'price' => get_post_meta($id, 'nrm_event_price', true),
    'reg' => get_post_meta($id, 'nrm_event_reg_url', true),
    'ceu' => get_post_meta($id, 'nrm_event_ceu', true) ? 1 : 0,
    'ceu_hours' => get_post_meta($id, 'nrm_event_ceu_hours', true),
    'disc' => array_map(function($t){ return ['s'=>$t->slug,'n'=>$t->name]; }, $discs),
    'type' => array_map(function($t){ return ['s'=>$t->slug,'n'=>$t->name]; }, $types),
    'past' => (strtotime($start) < strtotime('today')) ? 1 : 0,
  ];
} wp_reset_postdata(); }

$disc_terms = get_terms(['taxonomy'=>'nrm_discipline','hide_empty'=>false]);
$type_terms = get_terms(['taxonomy'=>'nrm_event_type','hide_empty'=>false]);
$ics_all = esc_url(add_query_arg('nrm_ics','all', get_post_type_archive_link('nrm_event')));
$webcal = 'webcal://' . preg_replace('#^https?://#','', $ics_all);
?>
<section class="section">
  <div class="container">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
      <div>
        <h1 class="section-title" style="font-size:2rem;margin-bottom:0.25rem;">Events &amp; Clinics</h1>
        <p class="text-secondary" style="margin:0;">Clinics, assessments, and community events across the NRM region.</p>
      </div>
      <div class="flex gap-2" style="align-items:center;">
        <a href="<?php echo $webcal; ?>" class="btn btn-secondary" style="padding:0.5rem 0.85rem;"><?php echo nrm_icon('calendar',16); ?> Subscribe</a>
        <a href="<?php echo $ics_all; ?>" class="btn btn-secondary" style="padding:0.5rem 0.85rem;">Export .ics</a>
      </div>
    </div>

    <!-- Filter + view controls -->
    <div class="card mb-4" id="ev-controls" style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;">
      <select id="ev-discipline" class="ev-filter" aria-label="Filter by discipline">
        <option value="">All disciplines</option>
        <?php foreach ($disc_terms as $t) echo '<option value="'.esc_attr($t->slug).'">'.esc_html($t->name).'</option>'; ?>
      </select>
      <select id="ev-type" class="ev-filter" aria-label="Filter by event type">
        <option value="">All types</option>
        <?php foreach ($type_terms as $t) echo '<option value="'.esc_attr($t->slug).'">'.esc_html($t->name).'</option>'; ?>
      </select>
      <select id="ev-when" class="ev-filter" aria-label="Filter by time">
        <option value="upcoming">Upcoming</option>
        <option value="past">Past</option>
        <option value="all">All</option>
      </select>
      <label style="display:inline-flex;align-items:center;gap:0.35rem;font-size:0.875rem;"><input type="checkbox" id="ev-ceu" class="ev-filter"> CEU only</label>
      <span style="flex:1;"></span>
      <div class="ev-viewtoggle" role="tablist" style="display:inline-flex;border:1px solid var(--border-light);border-radius:0.5rem;overflow:hidden;">
        <button id="ev-view-list" class="ev-view is-active" role="tab" aria-selected="true" style="padding:0.4rem 0.85rem;border:0;background:var(--psia-teal);color:#fff;cursor:pointer;">List</button>
        <button id="ev-view-cal" class="ev-view" role="tab" aria-selected="false" style="padding:0.4rem 0.85rem;border:0;background:#fff;color:var(--text-secondary);cursor:pointer;">Calendar</button>
      </div>
    </div>

    <p id="ev-empty" class="text-muted" style="display:none;">No events match these filters. <a href="https://www.thesnowpros.org/events-education/" target="_blank" rel="noopener">Browse the national calendar →</a></p>

    <!-- LIST VIEW (server-rendered) -->
    <div id="ev-list" style="display:flex;flex-direction:column;gap:1rem;">
      <?php foreach ($events as $e):
        $discslugs = implode(' ', array_map(function($d){return $d['s'];}, $e['disc']));
        $typeslugs = implode(' ', array_map(function($t){return $t['s'];}, $e['type']));
      ?>
      <div class="card event-card ev-item" style="padding:0;<?php echo $e['past']?'opacity:0.75;':''; ?>"
           data-disc="<?php echo esc_attr($discslugs); ?>" data-type="<?php echo esc_attr($typeslugs); ?>"
           data-ceu="<?php echo $e['ceu']; ?>" data-past="<?php echo $e['past']; ?>">
        <div class="event-date">
          <div class="month"><?php echo date('M', strtotime($e['start'])); ?></div>
          <div class="day"><?php echo date('j', strtotime($e['start'])); ?><?php if ($e['end']!==$e['start']) echo '–'.date('j', strtotime($e['end'])); ?></div>
          <div style="font-size:0.75rem;opacity:0.6;"><?php echo date('Y', strtotime($e['start'])); ?></div>
        </div>
        <div class="event-content">
          <div class="flex flex-wrap gap-2 mb-2">
            <?php foreach ($e['disc'] as $d): ?><span class="badge badge-teal"><?php echo esc_html($d['n']); ?></span><?php endforeach; ?>
            <?php foreach ($e['type'] as $t): ?><span class="badge" style="background:#FEF2F2;color:#991B1B;"><?php echo esc_html($t['n']); ?></span><?php endforeach; ?>
            <?php if ($e['ceu']): ?><span class="badge" style="background:#ECFDF5;color:#065F46;">CEU<?php echo $e['ceu_hours']?' · '.esc_html($e['ceu_hours']).'h':''; ?></span><?php endif; ?>
            <?php if ($e['past']): ?><span class="badge" style="background:var(--border-light);color:var(--text-muted);">Past event</span><?php endif; ?>
          </div>
          <h3><a href="<?php echo esc_url($e['url']); ?>" style="color:inherit;"><?php echo esc_html($e['title']); ?></a></h3>
          <p class="text-secondary text-sm" style="display:flex;align-items:center;gap:0.35rem;"><?php echo nrm_icon('map-pin',14); ?> <?php echo esc_html(trim($e['location'].($e['state']?', '.$e['state']:''),', ')); ?></p>
          <div class="flex items-center justify-between mt-2">
            <span class="text-teal font-bold"><?php echo esc_html($e['price']); ?></span>
            <span class="flex gap-2" style="align-items:center;">
              <a href="<?php echo esc_url(add_query_arg('nrm_ics',$e['id'], home_url('/'))); ?>" class="text-muted text-xs" title="Add to calendar"><?php echo nrm_icon('calendar',14); ?></a>
              <?php if ($e['reg'] && !$e['past']): ?><a href="<?php echo esc_url($e['reg']); ?>" target="_blank" rel="noopener" class="btn btn-teal" style="padding:0.5rem 1rem;">Register</a><?php endif; ?>
            </span>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- CALENDAR VIEW (JS-rendered) -->
    <div id="ev-cal" style="display:none;">
      <div class="flex items-center justify-between mb-4">
        <button id="cal-prev" class="btn btn-secondary" style="padding:0.4rem 0.85rem;">‹ Prev</button>
        <h2 id="cal-title" style="font-size:1.25rem;margin:0;color:var(--psia-teal);"></h2>
        <button id="cal-next" class="btn btn-secondary" style="padding:0.4rem 0.85rem;">Next ›</button>
      </div>
      <div id="cal-grid" class="cal-grid"></div>
    </div>
  </div>
</section>

<script>
window.NRM_EVENTS = <?php echo wp_json_encode($events); ?>;
</script>
<?php get_footer(); ?>
