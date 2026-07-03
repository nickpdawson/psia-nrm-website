<?php
/* Template Name: Membership Hub */
get_header();

// Each section of this page is a child page (Pages → Membership → …),
// fully editable by office staff in the normal editor. Order = Page
// Attributes → Order. The template owns the chrome (cards, jump pills).
$sections = get_pages([
    'child_of'    => get_the_ID(),
    'sort_column' => 'menu_order,post_title',
    'post_status' => 'publish',
]);
?>

<section class="hero" style="padding:3rem 0;">
  <div class="container">
    <h1 style="font-size:2.5rem;font-weight:700;"><?php the_title(); ?></h1>
    <p style="color:rgba(255,255,255,0.8);max-width:600px;font-size:1.125rem;">
      Everything you need to know about your NRM membership — dues, certification maintenance, scholarships, and more.
    </p>
  </div>
</section>

<section class="section" style="padding-top:2rem;">
  <div class="container" style="max-width:900px;">

    <?php if (get_the_content()): ?>
      <div class="entry-content mb-4"><?php the_content(); ?></div>
    <?php endif; ?>

    <?php if ($sections): ?>
    <!-- Quick jump links, generated from the section pages -->
    <div class="card mb-4" style="background:var(--psia-teal-light);border-color:var(--psia-teal-mid);">
      <div style="display:flex;flex-wrap:wrap;gap:0.75rem;justify-content:center;">
        <?php foreach ($sections as $s): ?>
          <a href="#<?php echo esc_attr($s->post_name); ?>" style="font-size:0.875rem;font-weight:600;color:var(--psia-teal);text-decoration:none;padding:0.375rem 1rem;border:1px solid var(--psia-teal-mid);border-radius:2rem;"><?php echo esc_html($s->post_title); ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php foreach ($sections as $s): ?>
      <div id="<?php echo esc_attr($s->post_name); ?>" class="card mb-4 entry-content" style="scroll-margin-top:5rem;">
        <h2 class="text-teal font-bold" style="font-size:1.25rem;margin-bottom:1rem;"><?php echo esc_html($s->post_title); ?></h2>
        <?php echo apply_filters('the_content', $s->post_content); ?>
      </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <!-- Contact -->
    <div class="card" style="background:var(--psia-teal-light);text-align:center;">
      <h3 class="text-teal font-bold" style="margin-bottom:0.5rem;">Questions?</h3>
      <p class="text-secondary text-sm">Contact the NRM office at <strong><?php echo esc_html(nrm_setting('office_phone')); ?></strong> or <a href="mailto:<?php echo esc_attr(nrm_setting('office_email')); ?>"><?php echo esc_html(nrm_setting('office_email')); ?></a>.</p>
      <a href="https://members.thesnowpros.org" target="_blank" rel="noopener" class="btn btn-teal" style="margin-top:0.75rem;">
        Manage Your Membership at thesnowpros.org →
      </a>
    </div>

  </div>
</section>

<?php get_footer(); ?>
