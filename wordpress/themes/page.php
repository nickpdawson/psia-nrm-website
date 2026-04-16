<?php get_header();
while (have_posts()): the_post();
  $query_role = get_post_meta(get_the_ID(), 'nrm_query_role', true);
  $query_disc = get_post_meta(get_the_ID(), 'nrm_query_discipline', true);
  $query_spec = get_post_meta(get_the_ID(), 'nrm_query_specialty', true);
?>

<section class="section">
  <div class="container">
    <h1 class="section-title" style="font-size:2rem;"><?php the_title(); ?></h1>

    <?php if (get_the_content()): ?>
      <div class="entry-content mb-4"><?php the_content(); ?></div>
    <?php endif; ?>

    <?php if ($query_role || $query_disc || $query_spec): ?>
      <?php
      // Build the dynamic query
      $tax_query = ['relation' => 'AND'];
      if ($query_role) {
          $tax_query[] = ['taxonomy' => 'nrm_role', 'field' => 'name', 'terms' => $query_role];
      }
      if ($query_disc) {
          $tax_query[] = ['taxonomy' => 'nrm_discipline', 'field' => 'name', 'terms' => $query_disc];
      }
      if ($query_spec) {
          $tax_query[] = ['taxonomy' => 'nrm_specialty', 'field' => 'name', 'terms' => $query_spec];
      }

      $members = new WP_Query([
          'post_type' => 'nrm_member',
          'posts_per_page' => 100,
          'orderby' => 'title',
          'order' => 'ASC',
          'tax_query' => $tax_query,
      ]);

      if ($members->have_posts()):
          echo '<p class="text-muted text-sm mb-4">'.$members->found_posts.' people</p>';
          echo '<div class="card-grid card-grid-3">';
          while ($members->have_posts()): $members->the_post();
              nrm_member_card(get_the_ID());
          endwhile;
          echo '</div>';
          wp_reset_postdata();
      else:
          echo '<p class="text-muted">No members found matching this query.</p>';
      endif;
      ?>
    <?php endif; ?>
  </div>
</section>

<?php endwhile; get_footer(); ?>
