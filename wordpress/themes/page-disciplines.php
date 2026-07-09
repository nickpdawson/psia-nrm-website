<?php
/* Template Name: Disciplines Hub */
get_header(); ?>

<section class="hero" style="padding:3rem 0;">
  <div class="container">
    <h1 style="font-size:2.5rem;font-weight:700;">Disciplines & Specialties</h1>
    <p style="color:rgba(255,255,255,0.8);max-width:600px;font-size:1.125rem;">
      Standards, prep materials, and upcoming assessments for every discipline NRM teaches.
    </p>
  </div>
</section>

<section class="section" style="padding-top:2rem;">
  <div class="container">
    <h2 class="section-title">Disciplines</h2>
    <p class="text-secondary mb-4">Each discipline offers three progressive certification levels (Level I, II, III).</p>
    <div class="card-grid card-grid-3" style="margin-bottom:3rem;">
      <?php
      $disc_pages = [
          ['title' => 'Alpine', 'slug' => 'alpine', 'color' => '#DBEAFE', 'text' => '#1E40AF', 'desc' => 'Downhill skiing instruction. The most common discipline in NRM with the largest education team.'],
          ['title' => 'Snowboard', 'slug' => 'snowboard', 'color' => '#EDE9FE', 'text' => '#5B21B6', 'desc' => 'Snowboard instruction across all terrain types and ability levels.'],
          ['title' => 'Telemark', 'slug' => 'telemark-discipline', 'color' => '#FEF3C7', 'text' => '#92400E', 'desc' => 'Free-heel skiing. Modular assessment model with unified regional exams.'],
          ['title' => 'Cross Country', 'slug' => 'cross-country-discipline', 'color' => '#D1FAE5', 'text' => '#065F46', 'desc' => 'Nordic skiing covering classic and skating technique.'],
          ['title' => 'Adaptive', 'slug' => 'adaptive-discipline', 'color' => '#FFE4E6', 'text' => '#9F1239', 'desc' => 'Teaching snow sports to people with disabilities. Requires Alpine I or Snowboard I.'],
      ];
      foreach ($disc_pages as $dp):
        $page = get_page_by_path($dp['slug']);
        $url = $page ? get_permalink($page) : '#';
        // Count education staff
        $count = new WP_Query(['post_type'=>'nrm_member','tax_query'=>[['taxonomy'=>'nrm_role','field'=>'name','terms'=>'Education Staff'],['taxonomy'=>'nrm_discipline','field'=>'name','terms'=>$dp['title']]],'fields'=>'ids','posts_per_page'=>-1]);
        // Count upcoming events (truthful — see §1.3)
        $ev_count = new WP_Query(['post_type'=>'nrm_event','tax_query'=>[['taxonomy'=>'nrm_discipline','field'=>'name','terms'=>$dp['title']]],'meta_key'=>'nrm_event_start','meta_query'=>[['key'=>'nrm_event_start','value'=>date('Y-m-d'),'compare'=>'>=','type'=>'DATE']],'fields'=>'ids','posts_per_page'=>-1]);
      ?>
        <a href="<?php echo esc_url($url); ?>" class="card" style="text-decoration:none;color:inherit;border-top:4px solid <?php echo $dp['color']; ?>;">
          <span style="background:<?php echo $dp['color']; ?>;color:<?php echo $dp['text']; ?>;padding:0.125rem 0.625rem;border-radius:9999px;font-size:0.75rem;font-weight:600;"><?php echo $dp['title']; ?></span>
          <h3 class="text-teal font-bold" style="margin:0.75rem 0 0.25rem;font-size:1.125rem;"><?php echo $dp['title']; ?></h3>
          <p class="text-secondary text-sm" style="line-height:1.6;"><?php echo $dp['desc']; ?></p>
          <?php if ($count->found_posts > 0 || $ev_count->found_posts > 0): ?>
          <div class="flex gap-4 mt-2 text-muted text-xs">
            <?php if ($count->found_posts > 0): ?><span><?php echo $count->found_posts; ?> education staff</span><?php endif; ?>
            <?php if ($ev_count->found_posts > 0): ?><span><?php echo $ev_count->found_posts . ' upcoming ' . _n('event', 'events', $ev_count->found_posts); ?></span><?php endif; ?>
          </div>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
    </div>

    <h2 class="section-title">Specialties</h2>
    <p class="text-secondary mb-4">Assessment-based certificate programs that complement your discipline certification.</p>
    <div class="card-grid card-grid-3">
      <?php
      $spec_pages = [
          ['title' => "Children's Specialist", 'slug' => 'childrens-specialist', 'color' => '#FCE7F3', 'text' => '#9D174D', 'desc' => 'CS1 and CS2 certificates for instructors who specialize in teaching children.'],
          ['title' => 'Freestyle Specialist', 'slug' => 'freestyle-specialist', 'color' => '#FFEDD5', 'text' => '#9A3412', 'desc' => 'FS1, FS2, and FS3 certificates. Managed by the Iron Team. ProPark is our second-largest event.'],
          ['title' => 'Senior Teaching', 'slug' => 'senior-teaching-specialty', 'color' => '#E0E7FF', 'text' => '#3730A3', 'desc' => 'Specialty in teaching older adult learners with age-appropriate methods.'],
      ];
      foreach ($spec_pages as $sp):
        $page = get_page_by_path($sp['slug']);
        $url = $page ? get_permalink($page) : '#';
      ?>
        <a href="<?php echo esc_url($url); ?>" class="card" style="text-decoration:none;color:inherit;border-top:4px solid <?php echo $sp['color']; ?>;">
          <span style="background:<?php echo $sp['color']; ?>;color:<?php echo $sp['text']; ?>;padding:0.125rem 0.625rem;border-radius:9999px;font-size:0.75rem;font-weight:600;"><?php echo $sp['title']; ?></span>
          <h3 class="text-teal font-bold" style="margin:0.75rem 0 0.25rem;font-size:1.125rem;"><?php echo $sp['title']; ?></h3>
          <p class="text-secondary text-sm" style="line-height:1.6;"><?php echo $sp['desc']; ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
