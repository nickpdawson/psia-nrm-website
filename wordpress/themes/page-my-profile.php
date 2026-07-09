<?php
/* Template Name: My Profile */
get_header();

// Mock logged-in user data (Nick Dawson's real data from PSIA API)
$user = [
    'name' => 'Nick Dawson',
    'email' => 'nd@nickdawson.net',
    'accountCode' => '402831',
    'school' => 'Jackson Hole Mountain Resort',
    'memberSince' => 2020,
    'membershipType' => 'Northern Rocky Mountain Certified',
    'membershipStart' => '2025-07-01',
    'membershipExpiry' => '2026-06-30',
    'status' => 'Active',
];

$certs = [
    ['name' => 'Alpine Level 2', 'designation' => 'A2', 'date' => '2022-04-07', 'group' => 'Alpine'],
    ['name' => 'Alpine Level 1', 'designation' => 'A1', 'date' => '2021-02-08', 'group' => 'Alpine'],
    ['name' => "Children's Specialist 1", 'designation' => 'CS1', 'date' => '2022-12-17', 'group' => "Children's Specialist"],
];

$ceu = [
    ['year' => '25-26', 'earned' => 6, 'required' => 6, 'satisfied' => true],
    ['year' => '24-25', 'earned' => 36, 'required' => 6, 'satisfied' => true],
    ['year' => '23-24', 'earned' => 12, 'required' => 6, 'satisfied' => true],
    ['year' => '22-23', 'earned' => 13, 'required' => 6, 'satisfied' => true],
    ['year' => '21-22', 'earned' => 55, 'required' => 6, 'satisfied' => true],
];

$initials = 'ND';
?>

<section class="hero" style="padding:2rem 0;">
  <div class="container" style="max-width:1000px;">
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
      <div style="display:flex;align-items:center;gap:1.25rem;">
        <img src="<?php echo home_url("/wp-content/uploads/images/nick-dawson.jpeg"); ?>" alt="Nick Dawson" style="width:96px;height:96px;border-radius:50%;object-fit:cover;object-position:center 20%;border:3px solid rgba(255,255,255,0.3);flex-shrink:0;">
        <div>
          <h1 style="font-size:1.75rem;font-weight:700;margin:0;"><?php echo $user['name']; ?></h1>
          <p style="opacity:0.7;margin:0.125rem 0 0;font-size:0.9375rem;"><?php echo $user['school']; ?></p>
          <p style="opacity:0.5;margin:0.125rem 0 0;font-size:0.8125rem;">Member #<?php echo $user['accountCode']; ?> · Since <?php echo $user['memberSince']; ?></p>
        </div>
      </div>
      <div style="display:flex;gap:0.5rem;">
        <a href="<?php echo home_url("/people/nick-dawson/"); ?>" class="btn btn-secondary" style="font-size:0.8125rem;padding:0.5rem 1rem;">View Public Profile</a>
        <a href="#" class="btn btn-primary" style="font-size:0.8125rem;padding:0.5rem 1rem;">Upload Photo</a>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:1.5rem;">
  <div class="container" style="max-width:1000px;">

    <!-- Tab nav -->
    <div style="display:flex;gap:0.25rem;border-bottom:2px solid var(--border-light);margin-bottom:1.5rem;">
      <button onclick="showTab('overview')" class="profile-tab active-tab" id="tab-overview"
              style="padding:0.75rem 1.25rem;font-size:0.875rem;font-weight:600;border:none;background:none;cursor:pointer;color:var(--psia-teal);border-bottom:2px solid var(--psia-teal);margin-bottom:-2px;">
        Overview
      </button>
      <button onclick="showTab('edit')" class="profile-tab" id="tab-edit"
              style="padding:0.75rem 1.25rem;font-size:0.875rem;font-weight:600;border:none;background:none;cursor:pointer;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-2px;">
        Edit Profile
      </button>
      <button onclick="showTab('pathway')" class="profile-tab" id="tab-pathway"
              style="padding:0.75rem 1.25rem;font-size:0.875rem;font-weight:600;border:none;background:none;cursor:pointer;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-2px;">
        Certifications & CEUs
      </button>
      <button onclick="showTab('settings')" class="profile-tab" id="tab-settings"
              style="padding:0.75rem 1.25rem;font-size:0.875rem;font-weight:600;border:none;background:none;cursor:pointer;color:var(--text-muted);border-bottom:2px solid transparent;margin-bottom:-2px;">
        Settings
      </button>
    </div>

    <!-- OVERVIEW TAB -->
    <div id="panel-overview" class="profile-panel">
      <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;">
        <div>
          <!-- Membership status -->
          <div class="card mb-4" style="background:var(--psia-teal-light);border-color:var(--psia-teal-mid);">
            <div style="display:flex;justify-content:space-between;align-items:center;">
              <div>
                <h3 class="text-teal font-bold">Membership Status</h3>
                <p class="text-secondary text-sm"><?php echo $user['membershipType']; ?></p>
              </div>
              <span style="background:var(--psia-teal);color:white;padding:0.25rem 0.75rem;border-radius:9999px;font-size:0.75rem;font-weight:600;">
                <?php echo $user['status']; ?>
              </span>
            </div>
            <p class="text-muted text-xs" style="margin-top:0.5rem;">
              Current period: <?php echo date('M Y', strtotime($user['membershipStart'])); ?> – <?php echo date('M Y', strtotime($user['membershipExpiry'])); ?>
            </p>
          </div>

          <!-- Certifications summary -->
          <div class="card mb-4">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem;">
              <h3 class="text-teal font-bold">My Certifications</h3>
              <span class="text-muted text-xs">Data from PSIA-AASI national</span>
            </div>
            <?php foreach ($certs as $cert): ?>
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem;background:var(--ice);border-radius:0.5rem;margin-bottom:0.5rem;">
              <div style="width:36px;height:36px;border-radius:50%;background:var(--psia-teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="color:white;font-size:0.6875rem;font-weight:700;"><?php echo $cert['designation']; ?></span>
              </div>
              <div style="flex:1;">
                <div style="font-size:0.875rem;font-weight:600;color:var(--slate-text);"><?php echo $cert['name']; ?></div>
                <div class="text-muted text-xs">Earned <?php echo date('M j, Y', strtotime($cert['date'])); ?></div>
              </div>
            </div>
            <?php endforeach; ?>
            <div style="display:flex;align-items:center;gap:0.75rem;padding:0.625rem;background:#FFFBEB;border:1px solid #FCD34D;border-radius:0.5rem;margin-top:0.5rem;">
              <div style="width:36px;height:36px;border-radius:50%;background:#FBBF24;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="color:white;font-size:0.75rem;">→</span>
              </div>
              <div>
                <div style="font-size:0.875rem;font-weight:600;color:#92400E;">Alpine Level III</div>
                <div style="font-size:0.6875rem;color:#B45309;">Next goal</div>
              </div>
              <a href="<?php echo home_url('/alpine/'); ?>" style="margin-left:auto;font-size:0.75rem;color:var(--shield-blue);">View requirements →</a>
            </div>
          </div>

          <!-- CEU at a glance -->
          <div class="card mb-4">
            <h3 class="text-teal font-bold" style="margin-bottom:0.75rem;">CEU Status</h3>
            <?php $current = $ceu[0]; ?>
            <div style="display:flex;align-items:center;gap:1rem;margin-bottom:0.75rem;">
              <div style="flex:1;">
                <div style="display:flex;justify-content:space-between;font-size:0.8125rem;margin-bottom:0.25rem;">
                  <span class="text-secondary"><?php echo $current['year']; ?> Season</span>
                  <span class="text-teal font-bold"><?php echo $current['earned']; ?>/<?php echo $current['required']; ?> CEUs</span>
                </div>
                <div style="height:8px;background:var(--border-light);border-radius:4px;overflow:hidden;">
                  <div style="height:100%;width:100%;background:var(--psia-teal);border-radius:4px;"></div>
                </div>
              </div>
              <span style="background:#D1FAE5;color:#065F46;padding:0.125rem 0.5rem;border-radius:9999px;font-size:0.6875rem;font-weight:600;">Satisfied</span>
            </div>
            <a href="#" onclick="showTab('pathway');return false;" style="font-size:0.8125rem;">View full CEU history →</a>
          </div>
        </div>

        <!-- Sidebar -->
        <div>
          <!-- Quick actions -->
          <div class="card mb-4">
            <h3 class="text-teal font-bold" style="margin-bottom:0.75rem;font-size:0.9375rem;">Quick Actions</h3>
            <div style="display:flex;flex-direction:column;gap:0.5rem;">
              <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" class="profile-quick-link">
                <?php echo nrm_icon('clipboard-list', 16); ?> Register for a Clinic
              </a>
              <a href="<?php echo home_url('/alpine/'); ?>" class="profile-quick-link">
                <?php echo nrm_icon('book-open', 16); ?> Level III Prep Materials
              </a>
              <a href="https://members.thesnowpros.org" target="_blank" rel="noopener" class="profile-quick-link">
                <?php echo nrm_icon('credit-card', 16); ?> Pay Dues / Manage Account
              </a>
              <a href="https://discord.gg/khuz6TYKX3" target="_blank" rel="noopener" class="profile-quick-link">
                <?php echo nrm_icon('messages-square', 16); ?> Discord Community
              </a>
            </div>
          </div>

          <!-- Upcoming relevant events -->
          <div class="card mb-4">
            <h3 class="text-teal font-bold" style="margin-bottom:0.75rem;font-size:0.9375rem;">Recommended Events</h3>
            <?php
            $events = new WP_Query([
                'post_type' => 'nrm_event', 'posts_per_page' => 3,
                'meta_key' => 'nrm_event_start', 'orderby' => 'meta_value', 'order' => 'ASC',
                'tax_query' => [['taxonomy' => 'nrm_discipline', 'field' => 'name', 'terms' => 'Alpine']],
            ]);
            if ($events->have_posts()): while ($events->have_posts()): $events->the_post();
              $start = get_post_meta(get_the_ID(), 'nrm_event_start', true);
              $location = get_post_meta(get_the_ID(), 'nrm_event_location', true);
            ?>
              <a href="<?php echo get_post_type_archive_link('nrm_event'); ?>" style="display:block;padding:0.5rem 0;border-bottom:1px solid var(--border-light);text-decoration:none;">
                <div style="font-size:0.8125rem;font-weight:600;color:var(--psia-teal);"><?php the_title(); ?></div>
                <div class="text-muted text-xs"><?php echo $start ? date('M j', strtotime($start)) : ''; ?> · <?php echo esc_html($location); ?></div>
              </a>
            <?php endwhile; wp_reset_postdata(); endif; ?>
          </div>

          <div class="card" style="background:var(--ice);">
            <h3 class="text-teal font-bold" style="margin-bottom:0.5rem;font-size:0.9375rem;">Your Public Page</h3>
            <p class="text-secondary text-sm">Share this link with clients:</p>
            <code style="display:block;background:white;padding:0.5rem;border-radius:0.375rem;font-size:0.75rem;margin-top:0.375rem;word-break:break-all;color:var(--shield-blue);">psia-nrm.org/people/nick-dawson</code>
          </div>
        </div>
      </div>
    </div>

    <!-- EDIT PROFILE TAB -->
    <div id="panel-edit" class="profile-panel" style="display:none;">
      <div class="card" style="max-width:700px;">
        <h2 class="text-teal font-bold" style="margin-bottom:1.5rem;">Edit Your Profile</h2>
        <div style="display:flex;flex-direction:column;gap:1.25rem;">
          <div style="display:flex;align-items:center;gap:1.5rem;">
            <img src="<?php echo home_url("/wp-content/uploads/images/nick-dawson.jpeg"); ?>" alt="Nick Dawson" style="width:80px;height:80px;border-radius:50%;object-fit:cover;object-position:center 20%;">
            <div>
              <button class="btn btn-teal" style="font-size:0.8125rem;padding:0.375rem 1rem;">Upload Photo</button>
              <p class="text-muted text-xs" style="margin-top:0.375rem;">JPG or PNG, max 2MB</p>
            </div>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
              <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.25rem;">Display Name</label>
              <input type="text" value="Nick Dawson" style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:0.875rem;">
            </div>
            <div>
              <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.25rem;">Home Resort</label>
              <select style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:0.875rem;">
                <?php
                $schools = get_terms(['taxonomy' => 'nrm_school', 'orderby' => 'name', 'hide_empty' => false]);
                if ($schools && !is_wp_error($schools)):
                  foreach ($schools as $school):
                ?>
                  <option <?php echo $school->name === 'Jackson Hole Mountain Resort' ? 'selected' : ''; ?>><?php echo esc_html($school->name); ?></option>
                <?php endforeach; endif; ?>
              </select>
            </div>
          </div>

          <div>
            <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.25rem;">Bio <span class="text-muted">(500 characters max)</span></label>
            <textarea style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:0.875rem;min-height:80px;" maxlength="500">Board member focused on member experience. Alpine instructor at Jackson Hole, currently pursuing Level III.</textarea>
            <div class="text-muted text-xs" style="text-align:right;margin-top:0.25rem;">127/500</div>
          </div>

          <div>
            <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.25rem;">How to Book a Lesson With Me</label>
            <textarea style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:0.875rem;min-height:60px;" placeholder="e.g. Contact Jackson Hole Ski School at 307-733-2292"></textarea>
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div>
              <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.25rem;">Primary Discipline</label>
              <select style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:0.875rem;">
                <option selected>Alpine</option>
                <option>Snowboard</option>
                <option>Telemark</option>
                <option>Nordic</option>
                <option>Adaptive</option>
              </select>
            </div>
            <div>
              <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.25rem;">Currently Working Toward</label>
              <input type="text" value="Alpine Level III" style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:0.875rem;">
            </div>
          </div>

          <div>
            <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.5rem;">Open To <span class="text-muted">(select all that apply)</span></label>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
              <?php foreach (['Mentoring', 'Study groups', 'Connecting', 'Gear exchange'] as $tag): ?>
                <label style="display:flex;align-items:center;gap:0.375rem;padding:0.375rem 0.75rem;border:1px solid var(--border-light);border-radius:2rem;font-size:0.8125rem;cursor:pointer;">
                  <input type="checkbox" <?php echo in_array($tag, ['Connecting', 'Study groups']) ? 'checked' : ''; ?>>
                  <?php echo $tag; ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div style="display:flex;gap:0.75rem;padding-top:0.5rem;border-top:1px solid var(--border-light);">
            <button class="btn btn-teal">Save Changes</button>
            <button class="btn btn-secondary" style="background:white;color:var(--text-muted);border:1px solid var(--border-light);">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <!-- CERTIFICATIONS & CEU TAB -->
    <div id="panel-pathway" class="profile-panel" style="display:none;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
        <!-- Cert pathway -->
        <div class="card">
          <h2 class="text-teal font-bold" style="margin-bottom:1rem;">Certification Pathway</h2>
          <p class="text-muted text-xs" style="margin-bottom:1rem;">Synced from PSIA-AASI national database</p>

          <?php
          $pathway = [
              ['name' => 'Alpine Level I', 'code' => 'A1', 'status' => 'earned', 'date' => 'Feb 8, 2021'],
              ['name' => 'Alpine Level II', 'code' => 'A2', 'status' => 'earned', 'date' => 'Apr 7, 2022'],
              ['name' => "Children's Specialist 1", 'code' => 'CS1', 'status' => 'earned', 'date' => 'Dec 17, 2022'],
              ['name' => 'Alpine Level III', 'code' => 'A3', 'status' => 'next', 'date' => null],
          ];
          foreach ($pathway as $step):
            $bg = $step['status'] === 'earned' ? 'var(--psia-teal)' : ($step['status'] === 'next' ? '#FBBF24' : 'var(--border-light)');
          ?>
          <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;background:<?php echo $step['status'] === 'next' ? '#FFFBEB' : 'var(--ice)'; ?>;border-radius:0.5rem;margin-bottom:0.5rem;<?php echo $step['status'] === 'next' ? 'border:1px solid #FCD34D;' : ''; ?>">
            <div style="width:40px;height:40px;border-radius:50%;background:<?php echo $bg; ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <span style="color:white;font-size:0.75rem;font-weight:700;"><?php echo $step['code']; ?></span>
            </div>
            <div style="flex:1;">
              <div style="font-size:0.875rem;font-weight:600;color:<?php echo $step['status'] === 'next' ? '#92400E' : 'var(--slate-text)'; ?>;"><?php echo $step['name']; ?></div>
              <?php if ($step['date']): ?>
                <div class="text-muted text-xs">Earned <?php echo $step['date']; ?></div>
              <?php else: ?>
                <div style="font-size:0.6875rem;color:#B45309;">Next goal · <a href="<?php echo home_url('/alpine/'); ?>" style="color:var(--shield-blue);">View requirements</a></div>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- CEU History -->
        <div class="card">
          <h2 class="text-teal font-bold" style="margin-bottom:1rem;">CEU History</h2>
          <p class="text-muted text-xs" style="margin-bottom:1rem;">6 CEUs required per season · Excess carries over one season</p>

          <?php foreach ($ceu as $record):
            $pct = min(100, ($record['earned'] / $record['required']) * 100);
          ?>
          <div style="padding:0.625rem 0;border-bottom:1px solid var(--border-light);">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.375rem;">
              <span style="font-size:0.875rem;font-weight:600;color:var(--psia-teal);"><?php echo $record['year']; ?></span>
              <div style="display:flex;align-items:center;gap:0.5rem;">
                <span class="text-secondary text-sm"><?php echo $record['earned']; ?>/<?php echo $record['required']; ?></span>
                <?php if ($record['satisfied']): ?>
                  <span style="color:#059669;display:inline-flex;"><?php echo nrm_icon('check', 14); ?></span>
                <?php endif; ?>
              </div>
            </div>
            <div style="height:6px;background:var(--border-light);border-radius:3px;overflow:hidden;">
              <div style="height:100%;width:<?php echo $pct; ?>%;background:var(--psia-teal);border-radius:3px;"></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- SETTINGS TAB -->
    <div id="panel-settings" class="profile-panel" style="display:none;">
      <div class="card" style="max-width:600px;">
        <h2 class="text-teal font-bold" style="margin-bottom:1.5rem;">Account Settings</h2>

        <div style="display:flex;flex-direction:column;gap:1.25rem;">
          <div>
            <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.25rem;">Email Address</label>
            <input type="email" value="nd@nickdawson.net" style="width:100%;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font-size:0.875rem;" disabled>
            <p class="text-muted text-xs" style="margin-top:0.25rem;">Managed through your PSIA-AASI national account</p>
          </div>

          <div>
            <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.5rem;">Profile Visibility</label>
            <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;cursor:pointer;margin-bottom:0.375rem;">
              <input type="radio" name="visibility" checked> Visible in member directory (recommended)
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;cursor:pointer;">
              <input type="radio" name="visibility"> Hidden from directory
            </label>
          </div>

          <div>
            <label style="display:block;font-size:0.8125rem;font-weight:600;color:var(--slate-text);margin-bottom:0.5rem;">Contact Preference</label>
            <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;cursor:pointer;margin-bottom:0.375rem;">
              <input type="radio" name="contact" checked> Show email on profile
            </label>
            <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.875rem;cursor:pointer;">
              <input type="radio" name="contact"> Contact form only (hide email)
            </label>
          </div>

          <div style="padding-top:0.75rem;border-top:1px solid var(--border-light);">
            <button class="btn btn-teal">Save Settings</button>
          </div>
        </div>
      </div>

      <div class="card mt-4" style="max-width:600px;background:var(--ice);">
        <h3 class="text-teal font-bold" style="margin-bottom:0.5rem;">PSIA-AASI Account</h3>
        <p class="text-secondary text-sm">Your NRM profile is linked to your PSIA-AASI national account. Certifications and membership status sync automatically.</p>
        <a href="https://members.thesnowpros.org" target="_blank" rel="noopener" style="display:inline-block;margin-top:0.5rem;font-size:0.8125rem;">Manage your national account at thesnowpros.org →</a>
      </div>
    </div>

  </div>
</section>

<script>
function showTab(name) {
  document.querySelectorAll('.profile-panel').forEach(function(p) { p.style.display = 'none'; });
  document.querySelectorAll('.profile-tab').forEach(function(t) {
    t.style.color = 'var(--text-muted)';
    t.style.borderBottomColor = 'transparent';
  });
  var panel = document.getElementById('panel-' + name);
  var tab = document.getElementById('tab-' + name);
  if (panel) panel.style.display = 'block';
  if (tab) { tab.style.color = 'var(--psia-teal)'; tab.style.borderBottomColor = 'var(--psia-teal)'; }
}
</script>

<?php get_footer(); ?>
