<?php
/* Template Name: Discipline Page */
get_header();
while (have_posts()): the_post();

$discipline = get_post_meta(get_the_ID(), 'nrm_page_discipline', true);
$specialty = get_post_meta(get_the_ID(), 'nrm_page_specialty', true);
$levels_json = get_post_meta(get_the_ID(), 'nrm_cert_levels', true);
$levels = $levels_json ? json_decode($levels_json, true) : [];
$books = get_post_meta(get_the_ID(), 'nrm_recommended_books', true);
$discord_channel = get_post_meta(get_the_ID(), 'nrm_discord_channel', true);
$national_url = get_post_meta(get_the_ID(), 'nrm_national_standards_url', true);

// Badge color mapping
$badge_colors = [
    'Alpine' => '#DBEAFE', 'Snowboard' => '#EDE9FE', 'Telemark' => '#FEF3C7',
    'Nordic' => '#D1FAE5', 'Adaptive' => '#FFE4E6',
    "Children's Specialist" => '#FCE7F3', 'Freestyle' => '#FFEDD5', 'Senior Teaching' => '#E0E7FF',
];
$badge_text = [
    'Alpine' => '#1E40AF', 'Snowboard' => '#5B21B6', 'Telemark' => '#92400E',
    'Nordic' => '#065F46', 'Adaptive' => '#9F1239',
    "Children's Specialist" => '#9D174D', 'Freestyle' => '#9A3412', 'Senior Teaching' => '#3730A3',
];
$label = $discipline ?: $specialty;
$bg = $badge_colors[$label] ?? 'var(--psia-teal-light)';
$fg = $badge_text[$label] ?? 'var(--psia-teal)';
?>

<!-- Hero -->
<?php $hero_img = get_post_meta(get_the_ID(), "nrm_hero_image", true); ?>
<section class="hero" style="padding:3rem 0;position:relative;overflow:hidden;<?php echo $hero_img ? "min-height:300px;" : ""; ?>">
  <?php if ($hero_img): ?>
  <div style="position:absolute;inset:0;"><img src="<?php echo esc_url($hero_img); ?>" alt="" style="width:100%;height:100%;object-fit:cover;object-position:center;"><div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(3,83,104,0.85),rgba(4,89,150,0.8));"></div></div>
  <?php endif; ?>
  <div class="container">
    <nav style="font-size:0.8125rem;margin-bottom:1rem;">
      <a href="<?php echo home_url('/disciplines/'); ?>" style="color:rgba(255,255,255,0.6);">Disciplines & Specialties</a>
      <span style="margin:0 0.5rem;opacity:0.4;">/</span>
      <span><?php the_title(); ?></span>
    </nav>
    <div class="flex items-center" style="gap:1rem;">
      <span style="background:<?php echo $bg; ?>;color:<?php echo $fg; ?>;padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.875rem;font-weight:600;"><?php echo esc_html($label); ?></span>
    </div>
    <h1 style="font-size:2.5rem;font-weight:700;margin:0.5rem 0;"><?php the_title(); ?></h1>
    <?php if (get_the_content()): ?>
      <div style="color:rgba(255,255,255,0.8);max-width:640px;font-size:1.125rem;line-height:1.6;">
        <?php the_content(); ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section" style="padding-top:2rem;">
  <div class="container">

    <?php if ($levels): ?>
    <!-- Certification Levels -->
    <h2 class="section-title">Certification Levels</h2>

    <!-- Level tabs -->
    <div style="display:flex;gap:0.5rem;margin-bottom:1.5rem;flex-wrap:wrap;" id="level-tabs">
      <?php foreach ($levels as $i => $level): ?>
        <button onclick="showLevel(<?php echo $i; ?>)"
          class="level-tab" id="tab-<?php echo $i; ?>"
          style="padding:0.5rem 1.25rem;border-radius:0.5rem;font-size:0.875rem;font-weight:600;border:2px solid var(--border-light);background:white;color:var(--text-secondary);cursor:pointer;transition:all 0.2s;">
          <?php echo esc_html($level['name']); ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- Level content panels -->
    <?php foreach ($levels as $i => $level): ?>
    <div class="level-panel" id="panel-<?php echo $i; ?>" style="display:none;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

        <!-- Left column: Requirements -->
        <div>
          <?php if (!empty($level['prerequisites'])): ?>
          <div class="card mb-4">
            <h3 class="text-teal font-bold mb-2" style="font-size:1rem;">Prerequisites</h3>
            <div style="font-size:0.875rem;line-height:1.8;color:var(--text-secondary);">
              <?php echo nl2br(esc_html($level['prerequisites'])); ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($level['modules'])): ?>
          <div class="card mb-4">
            <h3 class="text-teal font-bold mb-2" style="font-size:1rem;">Assessment Modules</h3>
            <div style="font-size:0.875rem;line-height:1.8;color:var(--text-secondary);">
              <?php echo nl2br(esc_html($level['modules'])); ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($level['exam_note'])): ?>
          <div class="card mb-4" style="background:#FFFBEB;border-color:#FCD34D;">
            <h3 class="font-bold mb-2" style="font-size:1rem;color:#92400E;">Professional Knowledge Exam</h3>
            <p style="font-size:0.875rem;color:#B45309;"><?php echo esc_html($level['exam_note']); ?></p>
            <?php if (!empty($level['exam_url'])): ?>
              <a href="<?php echo esc_url($level['exam_url']); ?>" target="_blank" rel="noopener" class="btn btn-teal" style="margin-top:0.75rem;padding:0.5rem 1rem;font-size:0.8125rem;">
                Take Exam →
              </a>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Right column: Documents -->
        <div>
          <?php if (!empty($level['documents'])): ?>
          <div class="card mb-4">
            <h3 class="text-teal font-bold mb-2" style="font-size:1rem;">Documents & Resources</h3>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
              <?php foreach ($level['documents'] as $doc): ?>
                <a href="<?php echo esc_url($doc['url']); ?>" target="_blank" rel="noopener"
                   style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:var(--ice);border-radius:0.5rem;text-decoration:none;color:inherit;transition:background 0.2s;">
                  <span style="font-size:1.25rem;flex-shrink:0;">📄</span>
                  <div>
                    <div style="font-size:0.875rem;font-weight:600;color:var(--psia-teal);"><?php echo esc_html($doc['name']); ?></div>
                    <?php if (!empty($doc['type'])): ?>
                      <div style="font-size:0.6875rem;color:var(--text-muted);"><?php echo esc_html($doc['type']); ?></div>
                    <?php endif; ?>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <?php if (!empty($level['description'])): ?>
          <div class="card mb-4" style="background:var(--psia-teal-light);">
            <p style="font-size:0.875rem;color:var(--psia-teal);line-height:1.7;"><?php echo esc_html($level['description']); ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php endif; // end if levels ?>

    <!-- Upcoming Events (dynamic) -->
    <?php
    $event_query_args = [
        'post_type' => 'nrm_event',
        'posts_per_page' => 6,
        'meta_key' => 'nrm_event_start',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    ];
    if ($discipline) {
        $event_query_args['tax_query'] = [['taxonomy' => 'nrm_discipline', 'field' => 'name', 'terms' => $discipline]];
    }
    $events = new WP_Query($event_query_args);
    if ($events->have_posts()):
    ?>
    <h2 class="section-title" style="margin-top:2rem;">Upcoming <?php echo esc_html($label); ?> Events</h2>
    <div class="card-grid card-grid-3" style="margin-bottom:2rem;">
      <?php while ($events->have_posts()): $events->the_post();
        $start = get_post_meta(get_the_ID(), 'nrm_event_start', true);
        $end = get_post_meta(get_the_ID(), 'nrm_event_end', true);
        $location = get_post_meta(get_the_ID(), 'nrm_event_location', true);
        $price = get_post_meta(get_the_ID(), 'nrm_event_price', true);
        $reg_url = get_post_meta(get_the_ID(), 'nrm_event_reg_url', true);
        $types = get_the_terms(get_the_ID(), 'nrm_event_type');
      ?>
        <div class="card" style="padding:0;overflow:hidden;">
          <div style="background:var(--psia-teal);color:white;padding:0.75rem 1rem;display:flex;align-items:baseline;gap:0.5rem;">
            <span style="font-size:1.5rem;font-weight:700;"><?php echo $start ? date('M j', strtotime($start)) : ''; ?></span>
            <?php if ($end && $end !== $start): ?>
              <span style="font-size:0.875rem;opacity:0.7;">– <?php echo date('j', strtotime($end)); ?></span>
            <?php endif; ?>
          </div>
          <div style="padding:1rem;">
            <?php if ($types && !is_wp_error($types)): foreach ($types as $t): ?>
              <span class="badge" style="background:#FEF2F2;color:#991B1B;margin-bottom:0.5rem;"><?php echo esc_html($t->name); ?></span>
            <?php endforeach; endif; ?>
            <h3 style="font-size:0.9375rem;color:var(--psia-teal);margin:0.25rem 0;"><?php the_title(); ?></h3>
            <p class="text-muted text-xs">📍 <?php echo esc_html($location); ?></p>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:0.75rem;">
              <span class="text-teal font-bold text-sm"><?php echo esc_html($price); ?></span>
              <?php if ($reg_url): ?>
                <a href="<?php echo esc_url($reg_url); ?>" target="_blank" rel="noopener" class="btn btn-teal" style="padding:0.375rem 0.75rem;font-size:0.75rem;">Register</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <p><a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" style="font-size:0.875rem;">View all events →</a></p>
    <?php endif; ?>

    <!-- Education Team (dynamic) -->
    <?php
    $team_tax_query = ['relation' => 'AND'];
    $team_tax_query[] = ['taxonomy' => 'nrm_role', 'field' => 'name', 'terms' => 'Education Staff'];
    if ($discipline) {
        $team_tax_query[] = ['taxonomy' => 'nrm_discipline', 'field' => 'name', 'terms' => $discipline];
    } elseif ($specialty) {
        $team_tax_query[] = ['taxonomy' => 'nrm_specialty', 'field' => 'name', 'terms' => $specialty];
    }
    $team = new WP_Query([
        'post_type' => 'nrm_member',
        'posts_per_page' => 50,
        'orderby' => 'title', 'order' => 'ASC',
        'tax_query' => $team_tax_query,
    ]);

    // Also get the discipline chair
    $chair = null;
    if ($discipline) {
        $chair_query = new WP_Query([
            'post_type' => 'nrm_member',
            'posts_per_page' => 1,
            'tax_query' => [
                ['taxonomy' => 'nrm_role', 'field' => 'name', 'terms' => 'Discipline Chair'],
                ['taxonomy' => 'nrm_discipline', 'field' => 'name', 'terms' => $discipline],
            ],
        ]);
        if ($chair_query->have_posts()) {
            $chair_query->the_post();
            $chair = get_the_ID();
            wp_reset_postdata();
        }
    }

    if ($team->have_posts()):
    ?>
    <h2 class="section-title" style="margin-top:2rem;"><?php echo esc_html($label); ?> Education Team</h2>

    <?php if ($chair): ?>
    <div class="card mb-4" style="background:var(--psia-teal-light);border-color:var(--psia-teal-mid);">
      <div style="display:flex;align-items:center;gap:1rem;">
        <?php
        $chair_name = get_the_title($chair);
        $chair_initials = implode('', array_map(function($w) { return strtoupper($w[0] ?? ''); }, explode(' ', $chair_name)));
        $chair_title = get_post_meta($chair, 'nrm_chair_title', true);
        $chair_email = get_post_meta($chair, 'nrm_email', true);
        ?>
        <a href="<?php echo get_permalink($chair); ?>" class="member-avatar" style="text-decoration:none;font-size:1rem;"><?php echo esc_html($chair_initials); ?></a>
        <div>
          <a href="<?php echo get_permalink($chair); ?>" class="text-teal font-bold" style="text-decoration:none;"><?php echo esc_html($chair_name); ?></a>
          <p class="text-secondary text-sm" style="margin:0;"><?php echo esc_html($chair_title); ?></p>
          <?php if ($chair_email): ?>
            <a href="mailto:<?php echo esc_attr($chair_email); ?>" class="text-muted text-xs"><?php echo esc_html($chair_email); ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <div class="card-grid card-grid-4" style="margin-bottom:1.5rem;">
      <?php while ($team->have_posts()): $team->the_post();
        $name = get_the_title();
        $initials = implode('', array_map(function($w) { return strtoupper($w[0] ?? ''); }, explode(' ', $name)));
        $member_roles = get_the_terms(get_the_ID(), 'nrm_role');
        $is_chair = false;
        if ($member_roles && !is_wp_error($member_roles)) {
            foreach ($member_roles as $mr) { if ($mr->name === 'Discipline Chair' || $mr->name === 'Specialty Chair') $is_chair = true; }
        }
      ?>
        <a href="<?php the_permalink(); ?>" class="card" style="text-decoration:none;color:inherit;padding:1rem;display:flex;align-items:center;gap:0.75rem;">
          <div class="member-avatar" style="width:36px;height:36px;font-size:0.75rem;"><?php echo esc_html($initials); ?></div>
          <div style="min-width:0;">
            <div class="text-teal font-bold text-sm" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo esc_html($name); ?></div>
            <?php if ($is_chair): ?>
              <div class="text-muted" style="font-size:0.6875rem;">Chair</div>
            <?php endif; ?>
          </div>
        </a>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <!-- Sidebar: Books & Resources -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-top:2rem;">
      <?php if ($books): ?>
      <div class="card">
        <h3 class="text-teal font-bold mb-2" style="font-size:1rem;">📚 Recommended Reading</h3>
        <div style="display:flex;flex-direction:column;gap:0.375rem;">
          <?php foreach (explode("\n", $books) as $book): $book = trim($book); if (!$book) continue; ?>
            <span style="font-size:0.875rem;color:var(--text-secondary);">• <?php echo esc_html($book); ?></span>
          <?php endforeach; ?>
        </div>
        <?php if ($national_url): ?>
          <a href="<?php echo esc_url($national_url); ?>" target="_blank" rel="noopener" style="display:inline-block;margin-top:0.75rem;font-size:0.8125rem;">
            Digital manuals at thesnowpros.org →
          </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>

      <div class="card">
        <h3 class="text-teal font-bold mb-2" style="font-size:1rem;">💬 Connect</h3>
        <?php if ($discord_channel): ?>
          <p style="font-size:0.875rem;color:var(--text-secondary);margin-bottom:0.75rem;">
            Join <?php echo esc_html($discord_channel); ?> on Discord to find study partners, ask questions, and share resources.
          </p>
        <?php endif; ?>
        <a href="https://discord.gg/khuz6TYKX3" target="_blank" rel="noopener"
           style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;background:#5865F2;color:white;border-radius:0.5rem;text-decoration:none;font-size:0.8125rem;font-weight:600;">
          Join Discord
        </a>
      </div>
    </div>

  </div>
</section>

<!-- Tab switching script -->
<script>
function showLevel(index) {
    document.querySelectorAll('.level-panel').forEach(p => p.style.display = 'none');
    document.querySelectorAll('.level-tab').forEach(t => {
        t.style.background = 'white';
        t.style.color = 'var(--text-secondary)';
        t.style.borderColor = 'var(--border-light)';
    });
    var panel = document.getElementById('panel-' + index);
    var tab = document.getElementById('tab-' + index);
    if (panel) panel.style.display = 'block';
    if (tab) {
        tab.style.background = 'var(--psia-teal)';
        tab.style.color = 'white';
        tab.style.borderColor = 'var(--psia-teal)';
    }
}
// Show first level by default
document.addEventListener('DOMContentLoaded', function() { showLevel(0); });
</script>

<?php endwhile; get_footer(); ?>
