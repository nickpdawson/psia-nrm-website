<?php get_header();
$term = get_queried_object();
?>
<section class="section">
  <div class="container">
    <nav class="text-sm text-muted mb-4">
      <a href="<?php echo home_url('/whos-who/'); ?>" style="color:var(--shield-blue);">Member Schools</a>
      <span style="margin:0 0.5rem;">/</span>
      <span><?php echo esc_html($term->name); ?></span>
    </nav>

    <h1 class="section-title" style="font-size:2rem;"><?php echo esc_html($term->name); ?></h1>
    <p class="text-secondary mb-4"><?php echo $term->count; ?> instructor<?php echo $term->count !== 1 ? 's' : ''; ?> at this school</p>

    <div class="card-grid card-grid-3">
      <?php if (have_posts()): while (have_posts()): the_post();
        nrm_member_card(get_the_ID());
      endwhile; else: ?>
        <p class="text-muted">No instructors listed at this school yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
