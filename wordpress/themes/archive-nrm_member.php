<?php get_header();
$disc_terms   = get_terms(['taxonomy'=>'nrm_discipline','hide_empty'=>true]);
$school_terms = get_terms(['taxonomy'=>'nrm_school','hide_empty'=>true]);
?>
<section class="section">
  <div class="container">
    <h1 class="section-title" style="font-size:2rem;margin-bottom:0.25rem;">Find a Member</h1>
    <p class="text-secondary mb-4">Search NRM instructors across the region.</p>

    <div class="card mb-4" style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;">
      <label class="screen-reader-text" for="dir-search" style="position:absolute;left:-9999px;">Search members</label>
      <input type="search" id="dir-search" placeholder="Search by name…" style="flex:1;min-width:200px;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:16px;min-height:40px;">
      <select id="dir-disc" aria-label="Filter by discipline" style="padding:0.45rem 0.6rem;border:1px solid var(--border-light);border-radius:0.5rem;min-height:40px;">
        <option value="">All disciplines</option>
        <?php foreach ($disc_terms as $t) echo '<option value="'.esc_attr($t->slug).'">'.esc_html($t->name).'</option>'; ?>
      </select>
      <select id="dir-school" aria-label="Filter by school" style="padding:0.45rem 0.6rem;border:1px solid var(--border-light);border-radius:0.5rem;min-height:40px;">
        <option value="">All schools</option>
        <?php foreach ($school_terms as $t) echo '<option value="'.esc_attr($t->slug).'">'.esc_html($t->name).'</option>'; ?>
      </select>
    </div>

    <p id="dir-count" class="text-muted text-sm mb-4"></p>

    <div class="card-grid card-grid-3" id="dir-grid">
      <?php
      $members = new WP_Query([
        'post_type' => 'nrm_member', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC',
        'meta_query' => [['key' => 'nrm_public', 'value' => '1']],
      ]);
      if ($members->have_posts()): while ($members->have_posts()): $members->the_post();
        $id = get_the_ID();
        $schools = get_the_terms($id, 'nrm_school');
        $resort = ($schools && !is_wp_error($schools)) ? $schools[0]->name : '';
        $school_slugs = ($schools && !is_wp_error($schools)) ? implode(' ', wp_list_pluck($schools,'slug')) : '';
        $since = get_post_meta($id, 'nrm_member_since', true);
        $disciplines = get_the_terms($id, 'nrm_discipline');
        $disc_slugs = ($disciplines && !is_wp_error($disciplines)) ? implode(' ', wp_list_pluck($disciplines,'slug')) : '';
        $roles = get_the_terms($id, 'nrm_role');
        $initials = implode('', array_map(function($w){ return strtoupper($w[0] ?? ''); }, explode(' ', get_the_title())));
      ?>
      <a href="<?php the_permalink(); ?>" class="card dir-item" style="text-decoration:none;color:inherit;"
         data-name="<?php echo esc_attr(strtolower(get_the_title())); ?>"
         data-disc="<?php echo esc_attr($disc_slugs); ?>" data-school="<?php echo esc_attr($school_slugs); ?>">
        <div class="member-card">
          <?php if (has_post_thumbnail()): ?>
            <?php the_post_thumbnail('thumbnail', ['class'=>'member-avatar','style'=>'object-fit:cover;','alt'=>get_the_title()]); ?>
          <?php else: ?>
            <div class="member-avatar"><?php echo esc_html($initials); ?></div>
          <?php endif; ?>
          <div style="min-width:0;">
            <h3 class="text-teal font-bold" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php the_title(); ?></h3>
            <p class="text-secondary text-sm" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo esc_html($resort); ?></p>
          </div>
        </div>
        <div class="flex flex-wrap gap-2 mt-2">
          <?php if ($disciplines && !is_wp_error($disciplines)): foreach ($disciplines as $d): ?>
            <span class="badge badge-<?php echo sanitize_title($d->name); ?>"><?php echo esc_html($d->name); ?></span>
          <?php endforeach; endif; ?>
          <?php if ($roles && !is_wp_error($roles)): foreach ($roles as $r): if ($r->name !== 'Member' && $r->name !== 'Education Staff'): ?>
            <span class="badge" style="background:#FEF3C7;color:#92400E;"><?php echo esc_html($r->name); ?></span>
          <?php endif; endforeach; endif; ?>
        </div>
        <?php if ($since): ?><p class="text-muted text-xs mt-1">Member since <?php echo esc_html($since); ?></p><?php endif; ?>
      </a>
      <?php endwhile; wp_reset_postdata(); else: ?>
        <p class="text-muted">No public member profiles yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>
<script>
(function(){
  var s=document.getElementById('dir-search'),d=document.getElementById('dir-disc'),sc=document.getElementById('dir-school');
  var items=[].slice.call(document.querySelectorAll('.dir-item')),count=document.getElementById('dir-count');
  function apply(){
    var q=(s.value||'').toLowerCase().trim(),dv=d.value,scv=sc.value,n=0;
    items.forEach(function(el){
      var ok=(!q||el.getAttribute('data-name').indexOf(q)>=0)
        &&(!dv||(' '+el.getAttribute('data-disc')+' ').indexOf(' '+dv+' ')>=0)
        &&(!scv||(' '+el.getAttribute('data-school')+' ').indexOf(' '+scv+' ')>=0);
      el.style.display=ok?'':'none'; if(ok)n++;
    });
    count.textContent=n+(n===1?' member':' members');
  }
  [s,d,sc].forEach(function(el){el.addEventListener('input',apply);el.addEventListener('change',apply);});
  apply();
})();
</script>
<?php get_footer(); ?>
