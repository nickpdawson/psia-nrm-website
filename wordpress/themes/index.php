<?php get_header(); ?>
<main class="section">
  <div class="container">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <article>
        <h1 class="section-title"><?php the_title(); ?></h1>
        <div class="entry-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; else: ?>
      <p>No content found.</p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
