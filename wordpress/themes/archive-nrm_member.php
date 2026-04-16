<?php get_header(); ?>
<section class="section">
  <div class="container">
    <h1 class="section-title" style="font-size:2rem;">Member Directory</h1>
    <p class="text-secondary mb-4">Connect with NRM instructors across the region.</p>

    <div class="card-grid card-grid-3">
      <?php
      $members = new WP_Query(['post_type' => 'nrm_member', 'posts_per_page' => 50, 'orderby' => 'title', 'order' => 'ASC']);
      if ($members->have_posts()): while ($members->have_posts()): $members->the_post();
        $resort = get_post_meta(get_the_ID(), 'nrm_resort', true);
        $since = get_post_meta(get_the_ID(), 'nrm_member_since', true);
        $disciplines = get_the_terms(get_the_ID(), 'nrm_discipline');
        $roles = get_the_terms(get_the_ID(), 'nrm_role');
        $initials = implode('', array_map(function($w) { return strtoupper($w[0]); }, explode(' ', get_the_title())));
      ?>
      <a href="<?php the_permalink(); ?>" class="card" style="text-decoration:none;color:inherit;">
        <div class="member-card">
          <div class="member-avatar"><?php echo esc_html($initials); ?></div>
          <div style="min-width:0;">
            <h3 class="text-teal font-bold" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php the_title(); ?></h3>
            <p class="text-secondary text-sm" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo esc_html($resort); ?></p>
          </div>
        </div>
        <div class="flex flex-wrap gap-2 mt-2">
          <?php if ($disciplines): foreach ($disciplines as $d): ?>
            <span class="badge badge-<?php echo sanitize_title($d->name); ?>"><?php echo esc_html($d->name); ?></span>
          <?php endforeach; endif; ?>
          <?php if ($roles): foreach ($roles as $r): if ($r->name !== 'Member'): ?>
            <span class="badge" style="background:#FEF3C7;color:#92400E;"><?php echo esc_html($r->name); ?></span>
          <?php endif; endforeach; endif; ?>
        </div>
        <p class="text-muted text-xs mt-1">Member since <?php echo esc_html($since); ?></p>
      </a>
      <?php endwhile; wp_reset_postdata(); else: ?>
        <p class="text-muted">No members found.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
