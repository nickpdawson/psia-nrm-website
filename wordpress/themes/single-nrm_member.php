<?php get_header();
while (have_posts()): the_post();
  $resort = '';
  $schools = get_the_terms(get_the_ID(), 'nrm_school');
  if ($schools && !is_wp_error($schools)) $resort = $schools[0]->name;
  $since = get_post_meta(get_the_ID(), 'nrm_member_since', true);
  $bio = get_post_meta(get_the_ID(), 'nrm_bio', true);
  $board_title = get_post_meta(get_the_ID(), 'nrm_board_title', true);
  $staff_title = get_post_meta(get_the_ID(), 'nrm_staff_title', true);
  $chair_title = get_post_meta(get_the_ID(), 'nrm_chair_title', true);
  $in_progress = get_post_meta(get_the_ID(), 'nrm_in_progress', true);
  $email = get_post_meta(get_the_ID(), 'nrm_email', true);
  $how_to_book = get_post_meta(get_the_ID(), 'nrm_how_to_book', true);
  $certs_json = get_post_meta(get_the_ID(), 'nrm_certifications', true);
  $certs = $certs_json ? json_decode($certs_json, true) : [];
  $roles = get_the_terms(get_the_ID(), 'nrm_role');
  $disciplines = get_the_terms(get_the_ID(), 'nrm_discipline');
  $specialties = get_the_terms(get_the_ID(), 'nrm_specialty');
  $initials = implode('', array_map(function($w) { return strtoupper($w[0] ?? ''); }, explode(' ', get_the_title())));

  // Map roles to their Who's Who pages
  $role_page_map = [
    'Board Member' => 'board-of-directors',
    'Discipline Chair' => 'discipline-chairs',
    'Specialty Chair' => 'specialty-chairs',
    'Office Staff' => 'office-staff',
    'Iron Team' => 'iron-team',
    'National Team Member' => 'national-team',
    'Examiner' => 'examiners',
  ];

  // Map disciplines to education team pages
  $disc_page_map = [
    'Alpine' => 'alpine-education-team',
    'Snowboard' => 'snowboard-education-team',
    'Telemark' => 'telemark-education-team',
    'Nordic' => 'cross-country-education-team',
    'Adaptive' => 'adaptive-education-team',
  ];

  $spec_page_map = [
    "Children's Specialist" => 'childrens-education-team',
    'Freestyle' => 'iron-team',
    'Senior Teaching' => 'senior-teaching-education-team',
  ];

  $display_title = nrm_get_person_title(get_the_ID());
?>

<!-- Profile header -->
<section style="background:linear-gradient(135deg,var(--psia-teal),var(--shield-blue));color:white;padding:3rem 0;">
  <div class="container-narrow">
    <nav style="font-size:0.8125rem;margin-bottom:1rem;">
      <a href="<?php echo home_url('/people/'); ?>" style="color:rgba(255,255,255,0.6);">Member Directory</a>
      <span style="margin:0 0.5rem;opacity:0.4;">/</span>
      <span><?php the_title(); ?></span>
    </nav>
    <div class="flex items-center" style="gap:1.5rem;">
      <?php if (has_post_thumbnail()): ?>
        <?php the_post_thumbnail('medium', [
          'alt' => get_the_title(),
          'style' => 'width:96px;height:96px;border-radius:50%;object-fit:cover;object-position:center 20%;border:3px solid rgba(255,255,255,0.3);flex-shrink:0;',
        ]); ?>
      <?php else: ?>
        <div class="member-avatar member-avatar-lg" style="border:2px solid rgba(255,255,255,0.3);"><?php echo esc_html($initials); ?></div>
      <?php endif; ?>
      <div>
        <h1 style="font-size:2rem;font-weight:700;margin:0;"><?php the_title(); ?></h1>
        <?php if ($display_title): ?>
          <p style="opacity:0.8;margin:0.25rem 0 0;font-size:0.9375rem;"><?php echo esc_html($display_title); ?></p>
        <?php endif; ?>
        <?php if ($resort): ?>
          <p style="opacity:0.6;margin:0.125rem 0 0;font-size:0.875rem;">
            <?php if ($schools && !is_wp_error($schools)): ?>
              <a href="<?php echo get_term_link($schools[0]); ?>" style="color:rgba(255,255,255,0.8);text-decoration:underline;text-underline-offset:2px;">
                <?php echo esc_html($resort); ?>
              </a>
            <?php else: ?>
              <?php echo esc_html($resort); ?>
            <?php endif; ?>
            <?php if ($since): ?> — Member since <?php echo esc_html($since); ?><?php endif; ?>
          </p>
        <?php endif; ?>

        <!-- Clickable badges -->
        <div class="flex flex-wrap gap-2" style="margin-top:0.75rem;">
          <?php if ($disciplines && !is_wp_error($disciplines)): foreach ($disciplines as $d):
            $disc_slug = $disc_page_map[$d->name] ?? '';
            $disc_url = $disc_slug ? home_url('/whos-who/' . $disc_slug . '/') : '';
          ?>
            <?php if ($disc_url): ?>
              <a href="<?php echo esc_url($disc_url); ?>" class="badge badge-<?php echo sanitize_title($d->name); ?>" style="text-decoration:none;" title="View <?php echo esc_attr($d->name); ?> Education Team"><?php echo esc_html($d->name); ?></a>
            <?php else: ?>
              <span class="badge badge-<?php echo sanitize_title($d->name); ?>"><?php echo esc_html($d->name); ?></span>
            <?php endif; ?>
          <?php endforeach; endif; ?>

          <?php if ($roles && !is_wp_error($roles)): foreach ($roles as $r):
            if ($r->name === 'Member' || $r->name === 'Education Staff') continue;
            $role_slug = $role_page_map[$r->name] ?? '';
            $role_url = $role_slug ? home_url('/whos-who/' . $role_slug . '/') : '';
          ?>
            <?php if ($role_url): ?>
              <a href="<?php echo esc_url($role_url); ?>" class="badge" style="background:rgba(255,255,255,0.2);color:white;text-decoration:none;" title="View all <?php echo esc_attr($r->name); ?>s"><?php echo esc_html($r->name); ?></a>
            <?php else: ?>
              <span class="badge" style="background:rgba(255,255,255,0.2);color:white;"><?php echo esc_html($r->name); ?></span>
            <?php endif; ?>
          <?php endforeach; endif; ?>

          <?php if ($specialties && !is_wp_error($specialties)): foreach ($specialties as $s):
            $spec_slug = $spec_page_map[$s->name] ?? '';
            $spec_url = $spec_slug ? home_url('/whos-who/' . $spec_slug . '/') : '';
          ?>
            <?php if ($spec_url): ?>
              <a href="<?php echo esc_url($spec_url); ?>" class="badge" style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);text-decoration:none;" title="View <?php echo esc_attr($s->name); ?> Team"><?php echo esc_html($s->name); ?></a>
            <?php else: ?>
              <span class="badge" style="background:rgba(255,255,255,0.15);color:rgba(255,255,255,0.9);"><?php echo esc_html($s->name); ?></span>
            <?php endif; ?>
          <?php endforeach; endif; ?>

          <?php
          // Show "Education Staff" as a linked badge if the person is on any ed team
          $is_ed_staff = false;
          if ($roles && !is_wp_error($roles)) {
            foreach ($roles as $r) { if ($r->name === 'Education Staff') { $is_ed_staff = true; break; } }
          }
          if ($is_ed_staff):
          ?>
            <a href="<?php echo home_url('/whos-who/'); ?>" class="badge badge-teal" style="text-decoration:none;" title="View all Education Teams">Education Staff</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Profile content -->
<section class="section">
  <div class="container-narrow">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">
      <div>
        <?php if ($bio): ?>
          <div class="card mb-4">
            <h2 class="text-teal font-bold mb-2">About</h2>
            <p style="line-height:1.7;"><?php echo esc_html($bio); ?></p>
          </div>
        <?php endif; ?>

        <?php if ($certs): ?>
          <div class="card mb-4">
            <h2 class="text-teal font-bold mb-2">Certifications</h2>
            <?php foreach ($certs as $cert): ?>
              <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:var(--ice);border-radius:0.5rem;margin-bottom:0.5rem;">
                <div style="width:32px;height:32px;border-radius:50%;background:var(--psia-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;color:white;">
                  <?php echo nrm_icon('check', 16); ?>
                </div>
                <div>
                  <div class="font-bold text-sm"><?php echo esc_html($cert['name']); ?>
                    <?php if (!empty($cert['designation'])): ?>
                      <span class="badge badge-teal" style="margin-left:0.5rem;"><?php echo esc_html($cert['designation']); ?></span>
                    <?php endif; ?>
                  </div>
                  <?php if (!empty($cert['date'])): ?>
                    <div class="text-muted text-xs">Earned <?php echo date('F Y', strtotime($cert['date'] . '-01')); ?></div>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>

            <?php if ($in_progress): ?>
              <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:#FFFBEB;border:1px solid #FCD34D;border-radius:0.5rem;margin-top:0.5rem;">
                <div style="width:32px;height:32px;border-radius:50%;background:#FBBF24;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <span style="color:white;font-size:0.75rem;">⏳</span>
                </div>
                <div>
                  <div class="font-bold text-sm" style="color:#92400E;"><?php echo esc_html($in_progress); ?></div>
                  <div class="text-xs" style="color:#B45309;">In Progress</div>
                </div>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <div>
        <?php if ($board_title): ?>
          <div class="card mb-4" style="background:#FFFBEB;border-color:#FCD34D;">
            <h3 class="font-bold text-sm" style="color:#92400E;">Board Position</h3>
            <p class="text-sm" style="color:#B45309;"><?php echo esc_html($board_title); ?></p>
          </div>
        <?php endif; ?>

        <?php if ($staff_title): ?>
          <div class="card mb-4" style="background:var(--psia-teal-light);border-color:var(--psia-teal-mid);">
            <h3 class="font-bold text-sm text-teal">Staff Position</h3>
            <p class="text-sm"><?php echo esc_html($staff_title); ?></p>
          </div>
        <?php endif; ?>

        <?php if ($chair_title): ?>
          <div class="card mb-4" style="background:var(--psia-teal-light);border-color:var(--psia-teal-mid);">
            <h3 class="font-bold text-sm text-teal">Chair Position</h3>
            <p class="text-sm"><?php echo esc_html($chair_title); ?></p>
          </div>
        <?php endif; ?>

        <?php if ($email): ?>
          <div class="card mb-4">
            <h3 class="text-teal font-bold text-sm mb-2">Contact</h3>
            <a href="mailto:<?php echo esc_attr($email); ?>" class="text-sm" style="word-break:break-all;"><?php echo esc_html($email); ?></a>
          </div>
        <?php endif; ?>

        <?php if ($how_to_book): ?>
          <div class="card mb-4" style="background:var(--ice);">
            <h3 class="text-teal font-bold text-sm mb-2">How to Book a Lesson</h3>
            <?php
              $book_text = $how_to_book;
              // Auto-link URLs in the booking text
              $book_text = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank" rel="noopener" style="color:var(--shield-blue);">$1</a>', esc_html($book_text));
              echo nl2br($book_text);
            ?>
          </div>
        <?php endif; ?>

        <?php if ($resort): ?>
          <div class="card mb-4">
            <h3 class="text-teal font-bold text-sm mb-2">Home Resort</h3>
            <?php if ($schools && !is_wp_error($schools)): ?>
              <a href="<?php echo get_term_link($schools[0]); ?>" class="text-sm">
                <?php echo esc_html($resort); ?> →
              </a>
              <p class="text-muted text-xs mt-1">View all instructors at this school</p>
            <?php else: ?>
              <p class="text-sm"><?php echo esc_html($resort); ?></p>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php endwhile; get_footer(); ?>
