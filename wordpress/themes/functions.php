<?php
/**
 * PSIA-NRM Theme Functions
 */

require_once get_template_directory() . '/inc/class-nrm-nav-walker.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/block-patterns.php';
require_once get_template_directory() . '/inc/cert-levels-metabox.php';

function nrm_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);
    register_nav_menus(['primary' => 'Primary Navigation', 'footer' => 'Footer Navigation']);
}
add_action('after_setup_theme', 'nrm_theme_setup');

function nrm_enqueue_styles() {
    wp_enqueue_style('nrm-style', get_stylesheet_uri(), [], '2026.1');
}
add_action('wp_enqueue_scripts', 'nrm_enqueue_styles');

// ── Favicon + social (Open Graph / Twitter) meta ──
function nrm_head_meta() {
    $brand    = home_url('/wp-content/uploads/brand');
    $og_img   = $brand . '/nrm-logo-2026.png';
    $b = ABSPATH . 'wp-content/uploads/brand/';

    // Favicons use the cropped monogram (the full lockup is illegible at tab size).
    if (file_exists($b . 'favicon-32.png'))  echo '<link rel="icon" type="image/png" sizes="32x32" href="' . esc_url($brand . '/favicon-32.png') . '">' . "\n";
    if (file_exists($b . 'favicon-512.png')) echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url($brand . '/favicon-512.png') . '">' . "\n";
    if (file_exists($b . 'favicon-180.png')) echo '<link rel="apple-touch-icon" href="' . esc_url($brand . '/favicon-180.png') . '">' . "\n";

    // Title/description/url per page.
    if (is_singular()) { $title = get_the_title(); $desc = wp_strip_all_tags(get_the_excerpt()); $url = get_permalink(); }
    else { $title = wp_get_document_title(); $desc = get_bloginfo('description'); $url = home_url(add_query_arg(null, null)); }
    $desc = $desc ?: 'Professional Ski Instructors of America — Northern Rocky Mountain Division.';
    $site = get_bloginfo('name');

    echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($site) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($og_img) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($desc) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($og_img) . '">' . "\n";
}
add_action('wp_head', 'nrm_head_meta', 5);

// ── Custom Post Types ──

function nrm_register_post_types() {
    register_post_type('nrm_member', [
        'labels' => [
            'name' => 'Members', 'singular_name' => 'Member',
            'add_new' => 'Add New Member', 'add_new_item' => 'Add New Member',
            'edit_item' => 'Edit Member', 'view_item' => 'View Member',
            'all_items' => 'All Members', 'search_items' => 'Search Members',
        ],
        'public' => true, 'has_archive' => true,
        'rewrite' => ['slug' => 'people'],
        'menu_icon' => 'dashicons-groups', 'menu_position' => 5,
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);

    register_post_type('nrm_event', [
        'labels' => [
            'name' => 'Events', 'singular_name' => 'Event',
            'add_new' => 'Add New Event', 'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event', 'view_item' => 'View Event',
            'all_items' => 'All Events', 'search_items' => 'Search Events',
        ],
        'public' => true, 'has_archive' => true,
        'rewrite' => ['slug' => 'events'],
        'menu_icon' => 'dashicons-calendar-alt', 'menu_position' => 6,
        'supports' => ['title', 'editor', 'thumbnail'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'nrm_register_post_types');

// ── Taxonomies ──

function nrm_register_taxonomies() {
    // Roles: Board Member, Education Staff, Discipline Chair, Specialty Chair, Office Staff, Iron Team, etc.
    register_taxonomy('nrm_role', 'nrm_member', [
        'labels' => ['name' => 'Roles', 'singular_name' => 'Role', 'all_items' => 'All Roles', 'add_new_item' => 'Add New Role'],
        'hierarchical' => false, 'public' => true, 'rewrite' => ['slug' => 'role'],
        'show_in_rest' => true, 'show_admin_column' => true,
    ]);

    // Disciplines: Alpine, Snowboard, Adaptive, Telemark, Nordic
    register_taxonomy('nrm_discipline', ['nrm_member', 'nrm_event'], [
        'labels' => ['name' => 'Disciplines', 'singular_name' => 'Discipline', 'all_items' => 'All Disciplines'],
        'hierarchical' => false, 'public' => true, 'rewrite' => ['slug' => 'discipline'],
        'show_in_rest' => true, 'show_admin_column' => true,
    ]);

    // Specialties: Children's Specialist, Freestyle, Senior Teaching
    register_taxonomy('nrm_specialty', 'nrm_member', [
        'labels' => ['name' => 'Specialties', 'singular_name' => 'Specialty', 'all_items' => 'All Specialties'],
        'hierarchical' => false, 'public' => true, 'rewrite' => ['slug' => 'specialty'],
        'show_in_rest' => true, 'show_admin_column' => true,
    ]);

    // Schools: taxonomy for member schools (replaces free-text resort field)
    register_taxonomy('nrm_school', 'nrm_member', [
        'labels' => ['name' => 'Member Schools', 'singular_name' => 'School', 'all_items' => 'All Schools', 'add_new_item' => 'Add New School'],
        'hierarchical' => false, 'public' => true, 'rewrite' => ['slug' => 'schools'],
        'show_in_rest' => true, 'show_admin_column' => true,
    ]);

    // Event types
    register_taxonomy('nrm_event_type', 'nrm_event', [
        'labels' => ['name' => 'Event Types', 'singular_name' => 'Event Type'],
        'hierarchical' => false, 'public' => true, 'rewrite' => ['slug' => 'event-type'],
        'show_in_rest' => true, 'show_admin_column' => true,
    ]);
}
add_action('init', 'nrm_register_taxonomies');

// ── Meta Fields ──

function nrm_register_meta() {
    // Member meta
    $member_fields = [
        'nrm_member_since' => 'integer',
        'nrm_bio' => 'string',
        'nrm_certifications' => 'string',  // JSON
        'nrm_specialties_text' => 'string', // legacy compat
        'nrm_open_to' => 'string',
        'nrm_goals' => 'string',
        'nrm_how_to_book' => 'string',
        'nrm_board_title' => 'string',
        'nrm_staff_title' => 'string',
        'nrm_chair_title' => 'string',
        'nrm_in_progress' => 'string',
        'nrm_email' => 'string',
    ];
    foreach ($member_fields as $key => $type) {
        register_post_meta('nrm_member', $key, ['show_in_rest' => true, 'single' => true, 'type' => $type]);
    }

    // Event meta
    $event_fields = [
        'nrm_event_start' => 'string', 'nrm_event_end' => 'string',
        'nrm_event_location' => 'string', 'nrm_event_price' => 'string',
        'nrm_event_reg_url' => 'string',
    ];
    foreach ($event_fields as $key => $type) {
        register_post_meta('nrm_event', $key, ['show_in_rest' => true, 'single' => true, 'type' => $type]);
    }

    // Page meta for dynamic queries
    register_post_meta('page', 'nrm_query_role', ['show_in_rest' => true, 'single' => true, 'type' => 'string']);
    register_post_meta('page', 'nrm_query_discipline', ['show_in_rest' => true, 'single' => true, 'type' => 'string']);
    register_post_meta('page', 'nrm_query_specialty', ['show_in_rest' => true, 'single' => true, 'type' => 'string']);

    // Seed marker — set on every post created by nrm-import.php so the
    // production purge script can identify and delete prototype data.
    foreach (['nrm_member', 'nrm_event', 'page'] as $pt) {
        register_post_meta($pt, '_nrm_seeded', [
            'show_in_rest' => false, 'single' => true, 'type' => 'integer',
            'auth_callback' => function () { return current_user_can('manage_options'); },
        ]);
    }
}
add_action('init', 'nrm_register_meta');

// ── Meta Boxes ──

function nrm_add_meta_boxes() {
    add_meta_box('nrm_member_details', 'Member Details', 'nrm_member_meta_box', 'nrm_member', 'normal', 'high');
    add_meta_box('nrm_event_details', 'Event Details', 'nrm_event_meta_box', 'nrm_event', 'normal', 'high');
    add_meta_box('nrm_page_query', 'Dynamic People Query (optional)', 'nrm_page_query_meta_box', 'page', 'side', 'default');
}
add_action('add_meta_boxes', 'nrm_add_meta_boxes');

function nrm_member_meta_box($post) {
    wp_nonce_field('nrm_member_meta', 'nrm_member_nonce');
    $fields = [
        'nrm_email' => ['label' => 'Contact Email', 'type' => 'email'],
        'nrm_member_since' => ['label' => 'Member Since (Year)', 'type' => 'number'],
        'nrm_bio' => ['label' => 'Bio', 'type' => 'textarea'],
        'nrm_board_title' => ['label' => 'Board Title', 'type' => 'text', 'hint' => 'e.g. Secretary, Vice Chair, Treasurer'],
        'nrm_staff_title' => ['label' => 'Staff Title', 'type' => 'text', 'hint' => 'e.g. NRM CEO, Education Director'],
        'nrm_chair_title' => ['label' => 'Chair Title', 'type' => 'text', 'hint' => 'e.g. Alpine Discipline Chair'],
        'nrm_in_progress' => ['label' => 'Certification In Progress', 'type' => 'text'],
        'nrm_how_to_book' => ['label' => 'How to Book a Lesson', 'type' => 'textarea'],
        'nrm_certifications' => ['label' => 'Certifications (JSON)', 'type' => 'textarea'],
    ];
    echo '<table class="form-table"><tbody>';
    foreach ($fields as $key => $f) {
        $v = get_post_meta($post->ID, $key, true);
        echo '<tr><th><label for="'.$key.'">'.$f['label'].'</label>';
        if (!empty($f['hint'])) echo '<br><small style="color:#999;">'.$f['hint'].'</small>';
        echo '</th><td>';
        if ($f['type'] === 'textarea') echo '<textarea name="'.$key.'" id="'.$key.'" class="large-text" rows="3">'.esc_textarea($v).'</textarea>';
        else echo '<input type="'.$f['type'].'" name="'.$key.'" id="'.$key.'" value="'.esc_attr($v).'" class="regular-text">';
        echo '</td></tr>';
    }
    echo '</tbody></table>';
}

function nrm_event_meta_box($post) {
    wp_nonce_field('nrm_event_meta', 'nrm_event_nonce');
    $fields = [
        'nrm_event_start' => ['label' => 'Start Date', 'type' => 'date'],
        'nrm_event_end' => ['label' => 'End Date', 'type' => 'date'],
        'nrm_event_location' => ['label' => 'Location', 'type' => 'text'],
        'nrm_event_price' => ['label' => 'Price', 'type' => 'text'],
        'nrm_event_reg_url' => ['label' => 'Registration URL', 'type' => 'url'],
    ];
    echo '<table class="form-table"><tbody>';
    foreach ($fields as $key => $f) {
        $v = get_post_meta($post->ID, $key, true);
        echo '<tr><th><label for="'.$key.'">'.$f['label'].'</label></th><td>';
        echo '<input type="'.$f['type'].'" name="'.$key.'" id="'.$key.'" value="'.esc_attr($v).'" class="regular-text">';
        echo '</td></tr>';
    }
    echo '</tbody></table>';
}

function nrm_page_query_meta_box($post) {
    wp_nonce_field('nrm_page_query', 'nrm_page_query_nonce');
    $role = get_post_meta($post->ID, 'nrm_query_role', true);
    $disc = get_post_meta($post->ID, 'nrm_query_discipline', true);
    $spec = get_post_meta($post->ID, 'nrm_query_specialty', true);
    echo '<p><small>Set these to auto-generate a people listing on this page.</small></p>';
    echo '<p><label>Role:<br><input type="text" name="nrm_query_role" value="'.esc_attr($role).'" class="widefat" placeholder="e.g. Board Member, Education Staff"></label></p>';
    echo '<p><label>Discipline:<br><input type="text" name="nrm_query_discipline" value="'.esc_attr($disc).'" class="widefat" placeholder="e.g. Alpine"></label></p>';
    echo '<p><label>Specialty:<br><input type="text" name="nrm_query_specialty" value="'.esc_attr($spec).'" class="widefat" placeholder="e.g. Children\'s Specialist"></label></p>';
}

// Save meta
function nrm_save_member_meta($post_id) {
    if (!isset($_POST['nrm_member_nonce']) || !wp_verify_nonce($_POST['nrm_member_nonce'], 'nrm_member_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach (['nrm_email','nrm_member_since','nrm_bio','nrm_board_title','nrm_staff_title','nrm_chair_title','nrm_in_progress','nrm_how_to_book','nrm_certifications'] as $k) {
        if (isset($_POST[$k])) update_post_meta($post_id, $k, sanitize_textarea_field($_POST[$k]));
    }
}
add_action('save_post_nrm_member', 'nrm_save_member_meta');

function nrm_save_event_meta($post_id) {
    if (!isset($_POST['nrm_event_nonce']) || !wp_verify_nonce($_POST['nrm_event_nonce'], 'nrm_event_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach (['nrm_event_start','nrm_event_end','nrm_event_location','nrm_event_price','nrm_event_reg_url'] as $k) {
        if (isset($_POST[$k])) update_post_meta($post_id, $k, sanitize_text_field($_POST[$k]));
    }
}
add_action('save_post_nrm_event', 'nrm_save_event_meta');

function nrm_save_page_query_meta($post_id) {
    if (!isset($_POST['nrm_page_query_nonce']) || !wp_verify_nonce($_POST['nrm_page_query_nonce'], 'nrm_page_query')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    foreach (['nrm_query_role','nrm_query_discipline','nrm_query_specialty'] as $k) {
        if (isset($_POST[$k])) update_post_meta($post_id, $k, sanitize_text_field($_POST[$k]));
    }
}
add_action('save_post_page', 'nrm_save_page_query_meta');

// ── Helper: Get person's display title ──
function nrm_get_person_title($post_id) {
    $titles = [];
    $board = get_post_meta($post_id, 'nrm_board_title', true);
    $staff = get_post_meta($post_id, 'nrm_staff_title', true);
    $chair = get_post_meta($post_id, 'nrm_chair_title', true);
    if ($board) $titles[] = $board;
    if ($staff) $titles[] = $staff;
    if ($chair) $titles[] = $chair;
    return implode(' · ', $titles);
}

// ── Helper: Render a member card ──
function nrm_member_card($post_id, $show_title = true) {
    $name = get_the_title($post_id);
    $initials = implode('', array_map(function($w) { return strtoupper($w[0] ?? ''); }, explode(' ', $name)));
    $link = get_permalink($post_id);
    $title = nrm_get_person_title($post_id);
    $disciplines = get_the_terms($post_id, 'nrm_discipline');
    $roles = get_the_terms($post_id, 'nrm_role');
    $schools = get_the_terms($post_id, 'nrm_school');
    $email = get_post_meta($post_id, 'nrm_email', true);

    echo '<a href="'.esc_url($link).'" class="card" style="text-decoration:none;color:inherit;">';
    echo '<div class="member-card">';
    echo '<div class="member-avatar">'.esc_html($initials).'</div>';
    echo '<div style="min-width:0;">';
    echo '<h3 class="text-teal font-bold" style="margin:0;">'.esc_html($name).'</h3>';
    if ($show_title && $title) echo '<p class="text-secondary text-sm" style="margin:0.125rem 0 0;">'.esc_html($title).'</p>';
    if ($schools && !is_wp_error($schools)) {
        echo '<p class="text-muted text-xs" style="margin:0.125rem 0 0;">'.esc_html($schools[0]->name).'</p>';
    }
    echo '</div></div>';
    // Badges
    echo '<div class="flex flex-wrap gap-2 mt-2">';
    if ($disciplines && !is_wp_error($disciplines)) {
        foreach ($disciplines as $d) {
            $cls = 'badge-' . sanitize_title($d->name);
            echo '<span class="badge '.$cls.'">'.esc_html($d->name).'</span>';
        }
    }
    if ($roles && !is_wp_error($roles)) {
        foreach ($roles as $r) {
            if ($r->name === 'Education Staff') continue; // Too common to badge
            echo '<span class="badge badge-teal">'.esc_html($r->name).'</span>';
        }
    }
    echo '</div>';
    if ($email) echo '<p class="text-muted text-xs mt-1">'.esc_html($email).'</p>';
    echo '</a>';
}

// Hide the "My Profile" item (→ /pathway/) from nav for logged-out visitors —
// the member dashboard is gated on PSIA OAuth, which isn't wired yet.
add_filter('wp_nav_menu_objects', function ($items) {
    if (is_user_logged_in()) return $items;
    foreach ($items as $k => $it) {
        $path = trim((string) parse_url($it->url ?? '', PHP_URL_PATH), '/');
        if (in_array($path, ['pathway', 'my-profile'], true)) unset($items[$k]);
    }
    return $items;
});

// ── Helper: inline SVG icons (Lucide, MIT) — replaces emoji as UI icons ──
// Silent for screen readers (aria-hidden); inherits color via currentColor.
function nrm_icon($name, $size = 20, $stroke = 2) {
    $paths = [
        'clipboard-list' => '<rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/>',
        'book-open' => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        'messages-square' => '<path d="M14 9a2 2 0 0 1-2 2H6l-4 4V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2z"/><path d="M18 9h2a2 2 0 0 1 2 2v11l-4-4h-6a2 2 0 0 1-2-2v-1"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'map-pin' => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'file-text' => '<path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/>',
        'credit-card' => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
        'check' => '<path d="M20 6 9 17l-5-5"/>',
        'menu' => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
        'x' => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'search' => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'arrow-right' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
        'calendar' => '<rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
        'external-link' => '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
    ];
    if (!isset($paths[$name])) return '';
    return sprintf(
        '<svg class="nrm-icon nrm-icon-%s" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
        esc_attr($name), (int)$size, (int)$size, esc_attr($stroke), $paths[$name]
    );
}

// Swap the emoji "document icon" spans in editor content for real SVGs, so
// existing and future staff-created .nrm-doc-link rows lose the emoji tell.
add_filter('the_content', function ($html) {
    if (strpos($html, 'font-size:1.25rem') === false) return $html;
    $map = ['📄' => 'file-text', '📝' => 'clipboard-list', '🔗' => 'external-link', '📋' => 'clipboard-list'];
    // WP/KSES may drop the trailing ";" on save, so make it optional.
    return preg_replace_callback('/<span style="font-size:1\.25rem;?">(.{1,4}?)<\/span>/u', function ($m) use ($map) {
        $emoji = trim($m[1]);
        if (!isset($map[$emoji])) return $m[0];
        return '<span class="nrm-doc-ico">' . nrm_icon($map[$emoji], 20) . '</span>';
    }, $html);
}, 20);

// ── Helper: render a newline-separated field as semantic lists/paragraphs ──
// Blank-line-separated blocks; multi-line block → <ul>, single line → <p>.
function nrm_render_lines($text) {
    $text = (string) $text;
    if (trim($text) === '') return '';
    $out = '';
    foreach (preg_split('/\n\s*\n/', trim($text)) as $block) {
        $lines = array_values(array_filter(array_map('trim', explode("\n", $block)), 'strlen'));
        if (count($lines) > 1) {
            $out .= '<ul class="nrm-req-list">';
            foreach ($lines as $l) $out .= '<li>' . esc_html($l) . '</li>';
            $out .= '</ul>';
        } elseif ($lines) {
            $out .= '<p>' . esc_html($lines[0]) . '</p>';
        }
    }
    return $out;
}

// ── Helper: is an event in the past? ──
function nrm_event_is_past($post_id) {
    $start = get_post_meta($post_id, 'nrm_event_start', true);
    return $start && strtotime($start) < strtotime('today');
}

// ── Helper: query upcoming events (with fallback to most-recent past) ──
// Returns [WP_Query $query, bool $is_upcoming]. $extra_tax lets callers scope
// by discipline etc. Used by home + discipline pages + archive.
function nrm_events_query($limit = 6, $extra_tax = null) {
    $today = date('Y-m-d');
    $base = [
        'post_type' => 'nrm_event', 'posts_per_page' => $limit,
        'meta_key' => 'nrm_event_start', 'orderby' => 'meta_value',
    ];
    if ($extra_tax) $base['tax_query'] = [$extra_tax];
    $upcoming = new WP_Query($base + ['order' => 'ASC',
        'meta_query' => [['key' => 'nrm_event_start', 'value' => $today, 'compare' => '>=', 'type' => 'DATE']]]);
    if ($upcoming->have_posts()) return [$upcoming, true];
    wp_reset_postdata();
    $recent = new WP_Query($base + ['order' => 'DESC',
        'meta_query' => [['key' => 'nrm_event_start', 'value' => $today, 'compare' => '<', 'type' => 'DATE']]]);
    return [$recent, false];
}

// ── Admin Dashboard ──
function nrm_dashboard_widgets() {
    wp_add_dashboard_widget('nrm_welcome', 'PSIA-NRM Site Management', function() {
        $members = wp_count_posts('nrm_member');
        $events = wp_count_posts('nrm_event');
        echo '<div style="padding:10px;font-size:14px;">';
        echo '<p><strong>'.($members->publish ?? 0).'</strong> member profiles · <strong>'.($events->publish ?? 0).'</strong> events</p>';
        echo '<ul style="list-style:disc;margin-left:20px;">';
        echo '<li><a href="'.admin_url('edit.php?post_type=nrm_member').'">Manage Members</a></li>';
        echo '<li><a href="'.admin_url('edit.php?post_type=nrm_event').'">Manage Events</a></li>';
        echo '<li><a href="'.admin_url('edit.php?post_type=page').'">Edit Pages</a></li>';
        echo '</ul></div>';
    });
}
add_action('wp_dashboard_setup', 'nrm_dashboard_widgets');

// ── Discipline Page Meta ── (moved to inc/cert-levels-metabox.php)
