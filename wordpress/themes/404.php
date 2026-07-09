<?php get_header(); ?>
<section class="hero" style="padding:3.5rem 0;">
  <div class="container">
    <h1 style="font-size:2.25rem;font-weight:700;">Page not found</h1>
    <p style="color:rgba(255,255,255,0.85);max-width:560px;font-size:1.125rem;">
      We couldn't find that page. It may have moved when we redesigned the site. Try a search or one of the links below.
    </p>
  </div>
</section>

<section class="section" style="padding-top:2rem;">
  <div class="container" style="max-width:800px;">
    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="display:flex;gap:0.5rem;max-width:480px;margin-bottom:2rem;">
      <label for="nrm-404-s" class="screen-reader-text" style="position:absolute;left:-9999px;">Search</label>
      <input type="search" id="nrm-404-s" name="s" placeholder="Search the site…" value="<?php echo esc_attr(get_search_query()); ?>"
             style="flex:1;padding:0.6rem 0.85rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:16px;min-height:44px;">
      <button type="submit" class="btn btn-primary" style="min-height:44px;"><?php echo nrm_icon('search', 18); ?> Search</button>
    </form>

    <h2 class="section-title" style="font-size:1.25rem;">Popular destinations</h2>
    <div class="card-grid card-grid-3">
      <?php
      $dest = [
        ['Events & Clinics', get_post_type_archive_link('nrm_event'), 'clipboard-list'],
        ['Membership', home_url('/membership/'), 'credit-card'],
        ['Disciplines', home_url('/disciplines/'), 'book-open'],
        ['Scholarships', home_url('/scholarships/'), 'file-text'],
        ["Who's Who", home_url('/whos-who/'), 'users'],
        ['Contact', home_url('/contact/'), 'messages-square'],
      ];
      foreach ($dest as $d): ?>
        <a href="<?php echo esc_url($d[1]); ?>" class="card" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:0.75rem;">
          <span style="color:var(--psia-teal);display:inline-flex;"><?php echo nrm_icon($d[2], 22); ?></span>
          <span class="text-teal font-bold"><?php echo esc_html($d[0]); ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php get_footer(); ?>
