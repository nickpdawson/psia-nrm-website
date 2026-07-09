<?php
/* Template Name: Who's Who Hub */
get_header(); ?>

<section class="hero" style="padding:3rem 0;">
  <div class="container">
    <h1 style="font-size:2.5rem;font-weight:700;"><?php the_title(); ?></h1>
    <p style="color:rgba(255,255,255,0.8);max-width:600px;">Meet the people who make PSIA-NRM run — from the board of directors to education teams across every discipline.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <?php
    // Get child pages (the dynamic org pages)
    $children = get_pages(['parent' => get_the_ID(), 'sort_column' => 'menu_order,post_title']);
    if ($children):
    ?>

    <h2 class="section-title">Leadership</h2>
    <div class="card-grid card-grid-4 mb-4">
      <?php foreach ($children as $child):
        $role = get_post_meta($child->ID, 'nrm_query_role', true);
        if (!in_array($role, ['Board Member', 'Discipline Chair', 'Specialty Chair', 'Office Staff', 'National Team Member'])) continue;
        // Count members
        $tax_query = [['taxonomy' => 'nrm_role', 'field' => 'name', 'terms' => $role]];
        $count = new WP_Query(['post_type' => 'nrm_member', 'tax_query' => $tax_query, 'fields' => 'ids', 'posts_per_page' => -1]);
        if ($count->found_posts === 0) continue; // don't render empty groups (§1.4)
      ?>
        <a href="<?php echo get_permalink($child->ID); ?>" class="card" style="text-decoration:none;color:inherit;">
          <h3 class="text-teal font-bold mb-2"><?php echo esc_html($child->post_title); ?></h3>
          <p class="text-secondary text-sm"><?php echo wp_trim_words($child->post_content, 15); ?></p>
          <p class="text-muted text-xs mt-2"><?php echo $count->found_posts . ' ' . _n('person', 'people', $count->found_posts); ?></p>
        </a>
      <?php endforeach; ?>
    </div>

    <h2 class="section-title">Education Teams</h2>
    <div class="card-grid card-grid-3 mb-4">
      <?php foreach ($children as $child):
        $role = get_post_meta($child->ID, 'nrm_query_role', true);
        if (!in_array($role, ['Education Staff', 'Iron Team'])) continue;
        $disc = get_post_meta($child->ID, 'nrm_query_discipline', true);
        $spec = get_post_meta($child->ID, 'nrm_query_specialty', true);
        $tax_query = ['relation' => 'AND'];
        $tax_query[] = ['taxonomy' => 'nrm_role', 'field' => 'name', 'terms' => $role];
        if ($disc) $tax_query[] = ['taxonomy' => 'nrm_discipline', 'field' => 'name', 'terms' => $disc];
        if ($spec) $tax_query[] = ['taxonomy' => 'nrm_specialty', 'field' => 'name', 'terms' => $spec];
        $count = new WP_Query(['post_type' => 'nrm_member', 'tax_query' => $tax_query, 'fields' => 'ids', 'posts_per_page' => -1]);
        if ($count->found_posts === 0) continue; // don't render empty teams (§1.4)
      ?>
        <a href="<?php echo get_permalink($child->ID); ?>" class="card" style="text-decoration:none;color:inherit;">
          <h3 class="text-teal font-bold mb-2"><?php echo esc_html($child->post_title); ?></h3>
          <p class="text-secondary text-sm"><?php echo wp_trim_words($child->post_content, 12); ?></p>
          <p class="text-muted text-xs mt-2"><?php echo $count->found_posts . ' ' . _n('member', 'members', $count->found_posts); ?></p>
        </a>
      <?php endforeach; ?>
    </div>

    <h2 class="section-title">Member Schools</h2>
    <div class="card" style="margin-bottom:2rem;">
      <p class="text-secondary text-sm mb-4">NRM member schools across Montana, Wyoming, North Dakota, and South Dakota.</p>
      <div class="card-grid card-grid-3">
        <?php
        $schools = get_terms(['taxonomy' => 'nrm_school', 'orderby' => 'name', 'hide_empty' => false]);
        if ($schools && !is_wp_error($schools)):
          foreach ($schools as $school):
        ?>
          <a href="<?php echo get_term_link($school); ?>" style="text-decoration:none;color:inherit;padding:0.75rem;background:var(--ice);border-radius:0.5rem;display:block;">
            <span class="text-teal font-bold text-sm"><?php echo esc_html($school->name); ?></span>
            <?php if ($school->count > 0): ?>
              <span class="text-muted text-xs"> — <?php echo $school->count; ?> instructor<?php echo $school->count !== 1 ? 's' : ''; ?></span>
            <?php endif; ?>
          </a>
        <?php endforeach; endif; ?>
      </div>
    </div>

    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
