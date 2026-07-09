<?php
/*
Plugin Name: NRM Data Import
Description: Import real NRM people, events, and schools
Version: 2.0
*/

function nrm_maybe_import_data() {
    if (get_option('nrm_data_v2_imported')) return;
    if (!taxonomy_exists('nrm_role')) return;

    // ── Create taxonomy terms ──

    $roles = ['Member', 'Board Member', 'Education Staff', 'Discipline Chair', 'Specialty Chair',
              'Office Staff', 'Iron Team', 'Examiner', 'National Team Member',
              'School Director', 'School Contact', 'School Maintainer'];
    foreach ($roles as $r) { if (!term_exists($r, 'nrm_role')) wp_insert_term($r, 'nrm_role'); }

    $disciplines = ['Alpine', 'Snowboard', 'Adaptive', 'Telemark', 'Nordic'];
    foreach ($disciplines as $d) { if (!term_exists($d, 'nrm_discipline')) wp_insert_term($d, 'nrm_discipline'); }

    $specialties = ["Children's Specialist", 'Freestyle', 'Senior Teaching'];
    foreach ($specialties as $s) { if (!term_exists($s, 'nrm_specialty')) wp_insert_term($s, 'nrm_specialty'); }

    $event_types = ['Clinic', 'Assessment', 'Community'];
    foreach ($event_types as $t) { if (!term_exists($t, 'nrm_event_type')) wp_insert_term($t, 'nrm_event_type'); }

    // Schools (real list, Jackson Hole excluded)
    $schools = [
        'Antelope Butte Mountain Recreation Area', 'Big Sky Bravery', 'Big Sky Resort',
        'Blacktail Mountain', 'Bridger Bowl Ski Area', 'Cross Cut Ranch XC Center',
        'Discovery Ski Area', 'Eagle Mount – Billings', 'Eagle Mount – Bozeman',
        'Eagle Mount – Great Falls', 'Edelweiss Lodge and Resort', 'Glacier Nordic Club',
        'Grand Targhee Resort', 'Great Divide Ski Area', 'Hogadon Basin',
        'Lone Mountain Ranch', 'Lost Trail Powder Mountain', 'Maverick Mountain',
        'Moonlight Basin', 'Montana Snowbowl', 'Red Lodge Mountain',
        'Showdown Montana', 'Terry Peak', 'Teton Pass Ski Area',
        'Turner Mountain', 'Whitefish Mountain Resort',
        'Huff Hills', 'Bottineau Winter Park', 'Lookout Pass',
    ];
    foreach ($schools as $s) { if (!term_exists($s, 'nrm_school')) wp_insert_term($s, 'nrm_school'); }

    // ── Delete old sample members ──
    $old = get_posts(['post_type' => 'nrm_member', 'numberposts' => -1, 'fields' => 'ids']);
    foreach ($old as $id) { wp_delete_post($id, true); }

    // ── Import real people ──
    $people_json = file_get_contents('/var/www/people.json');
    if (!$people_json) { error_log('NRM Import: people.json not found'); return; }
    $people = json_decode($people_json, true);

    foreach ($people as $p) {
        $post_id = wp_insert_post([
            'post_type'   => 'nrm_member',
            'post_title'  => $p['name'],
            'post_name'   => $p['id'],
            'post_status' => 'publish',
            'post_content' => '',
        ]);

        if (is_wp_error($post_id)) continue;

        // Tag every seeded post so the production purge script can identify
        // and delete prototype data before launch.
        update_post_meta($post_id, '_nrm_seeded', 1);

        // Meta fields
        if (!empty($p['email'])) update_post_meta($post_id, 'nrm_email', $p['email']);
        if (!empty($p['bio'])) update_post_meta($post_id, 'nrm_bio', $p['bio']);
        if (!empty($p['boardTitle'])) update_post_meta($post_id, 'nrm_board_title', $p['boardTitle']);
        if (!empty($p['staffTitle'])) update_post_meta($post_id, 'nrm_staff_title', $p['staffTitle']);
        if (!empty($p['chairTitle'])) update_post_meta($post_id, 'nrm_chair_title', $p['chairTitle']);
        if (!empty($p['inProgress'])) update_post_meta($post_id, 'nrm_in_progress', $p['inProgress']);
        if (!empty($p['certifications'])) update_post_meta($post_id, 'nrm_certifications', json_encode($p['certifications']));

        // Taxonomies
        if (!empty($p['roles'])) wp_set_object_terms($post_id, $p['roles'], 'nrm_role');
        if (!empty($p['disciplines'])) wp_set_object_terms($post_id, $p['disciplines'], 'nrm_discipline');
        if (!empty($p['specialties'])) wp_set_object_terms($post_id, $p['specialties'], 'nrm_specialty');
    }

    // ── Import events (keep existing or re-import) ──
    $old_events = get_posts(['post_type' => 'nrm_event', 'numberposts' => -1, 'fields' => 'ids']);
    if (count($old_events) === 0) {
        $events_json = file_get_contents('/var/www/events.json');
        if ($events_json) {
            $events = json_decode($events_json, true);
            foreach ($events as $e) {
                $post_id = wp_insert_post([
                    'post_type' => 'nrm_event', 'post_title' => $e['title'],
                    'post_name' => $e['id'], 'post_status' => 'publish',
                    'post_content' => $e['description'] ?? '',
                ]);
                if (!is_wp_error($post_id)) {
                    update_post_meta($post_id, '_nrm_seeded', 1);
                    update_post_meta($post_id, 'nrm_event_start', $e['date'] ?? '');
                    update_post_meta($post_id, 'nrm_event_end', $e['endDate'] ?? '');
                    update_post_meta($post_id, 'nrm_event_location', $e['location'] ?? '');
                    update_post_meta($post_id, 'nrm_event_price', $e['price'] ?? '');
                    update_post_meta($post_id, 'nrm_event_reg_url', $e['registration'] ?? '');
                    if (!empty($e['discipline'])) wp_set_object_terms($post_id, $e['discipline'], 'nrm_discipline');
                    if (!empty($e['type'])) wp_set_object_terms($post_id, ucfirst($e['type']), 'nrm_event_type');
                }
            }
        }
    }

    // ── Create dynamic "Who's Who" pages ──
    $pages_to_create = [
        ['title' => "Who's Who", 'slug' => 'whos-who', 'content' => '', 'parent' => 0, 'role' => '', 'disc' => '', 'spec' => ''],
    ];

    $parent_page = wp_insert_post([
        'post_type' => 'page', 'post_title' => "Who's Who",
        'post_name' => 'whos-who', 'post_status' => 'publish',
        'post_content' => '<p>Meet the people who make PSIA-NRM run — from the board of directors to education teams across every discipline.</p>',
    ]);
    if (!is_wp_error($parent_page)) {
        update_post_meta($parent_page, '_nrm_seeded', 1);
    }

    $sub_pages = [
        ['title' => 'Board of Directors', 'slug' => 'board-of-directors', 'role' => 'Board Member', 'disc' => '', 'spec' => '',
         'content' => 'The NRM Board of Directors is elected by the membership to guide the division\'s strategic direction.'],
        ['title' => 'Discipline Chairs', 'slug' => 'discipline-chairs', 'role' => 'Discipline Chair', 'disc' => '', 'spec' => '',
         'content' => 'Discipline Chairs are responsible for building and maintaining the curriculum of events and assessments in their discipline.'],
        ['title' => 'Specialty Chairs', 'slug' => 'specialty-chairs', 'role' => 'Specialty Chair', 'disc' => '', 'spec' => '',
         'content' => 'Specialty Chairs manage curriculum and assessments for specialty certificate programs.'],
        ['title' => 'Office Staff', 'slug' => 'office-staff', 'role' => 'Office Staff', 'disc' => '', 'spec' => '',
         'content' => 'The office staff works tirelessly behind the scenes to make sure the Northern Rocky Mountain Region runs smoothly.'],
        ['title' => 'Alpine Education Team', 'slug' => 'alpine-education-team', 'role' => 'Education Staff', 'disc' => 'Alpine', 'spec' => '',
         'content' => 'The Alpine Education Team is responsible for running Alpine clinics and assessments across the NRM region.'],
        ['title' => 'Snowboard Education Team', 'slug' => 'snowboard-education-team', 'role' => 'Education Staff', 'disc' => 'Snowboard', 'spec' => '',
         'content' => 'The Snowboard Education Team runs Snowboard clinics and assessments across the NRM region.'],
        ['title' => 'Telemark Education Team', 'slug' => 'telemark-education-team', 'role' => 'Education Staff', 'disc' => 'Telemark', 'spec' => '',
         'content' => 'The Telemark Education Team runs Telemark clinics and assessments across the NRM region.'],
        ['title' => 'Cross Country Education Team', 'slug' => 'cross-country-education-team', 'role' => 'Education Staff', 'disc' => 'Nordic', 'spec' => '',
         'content' => 'The Cross Country Education Team runs Nordic clinics and assessments across the NRM region.'],
        ['title' => 'Adaptive Education Team', 'slug' => 'adaptive-education-team', 'role' => 'Education Staff', 'disc' => 'Adaptive', 'spec' => '',
         'content' => 'The Adaptive Education Team runs Adaptive clinics and assessments across the NRM region.'],
        ['title' => "Children's Specialist Education Team", 'slug' => 'childrens-education-team', 'role' => 'Education Staff', 'disc' => '', 'spec' => "Children's Specialist",
         'content' => 'The Children\'s Specialist Education Team manages Children\'s Specialist clinics and assessment-based certificate programs.'],
        ['title' => 'Senior Teaching Education Team', 'slug' => 'senior-teaching-education-team', 'role' => 'Education Staff', 'disc' => '', 'spec' => 'Senior Teaching',
         'content' => 'The Senior Teaching Education Team runs clinics focused on how older adults move, learn, and engage in the sport.'],
        ['title' => 'Iron Team (Freestyle)', 'slug' => 'iron-team', 'role' => 'Iron Team', 'disc' => '', 'spec' => '',
         'content' => 'The Iron Team is dedicated to maximizing fun and safety while giving members an opportunity to grow as freestyle professionals.'],
    ];

    foreach ($sub_pages as $sp) {
        $pid = wp_insert_post([
            'post_type' => 'page', 'post_title' => $sp['title'],
            'post_name' => $sp['slug'], 'post_status' => 'publish',
            'post_parent' => $parent_page, 'post_content' => '<p>'.$sp['content'].'</p>',
        ]);
        if (!is_wp_error($pid)) {
            update_post_meta($pid, '_nrm_seeded', 1);
            if ($sp['role']) update_post_meta($pid, 'nrm_query_role', $sp['role']);
            if ($sp['disc']) update_post_meta($pid, 'nrm_query_discipline', $sp['disc']);
            if ($sp['spec']) update_post_meta($pid, 'nrm_query_specialty', $sp['spec']);
        }
    }

    update_option('nrm_data_v2_imported', true);
}
add_action('admin_init', 'nrm_maybe_import_data');

// ── Content pages (disciplines, membership, pathway, …) ──
// Exported from the prototype (wordpress/pages.json). Runs after the main
// import so parent lookups (e.g. whos-who) resolve. Idempotent per slug.
function nrm_maybe_import_pages() {
    if (!get_option('nrm_data_v2_imported')) return; // main import first

    $json = file_get_contents('/var/www/pages.json');
    if (!$json) { error_log('NRM Import: pages.json not found'); return; }
    $pages = json_decode($json, true);
    if (!is_array($pages)) { error_log('NRM Import: pages.json invalid'); return; }

    // Re-run whenever the seed file changes OR the sync logic version bumps.
    // Bump the version suffix when the content-sync algorithm changes so the
    // corrected logic re-runs against already-seeded sites.
    $sig = md5($json . '|sync-v2');
    if (get_option('nrm_pages_seed_sig') === $sig) return;

    foreach ($pages as $pg) {
        $parent_id = 0;
        if (!empty($pg['parent'])) {
            $parent = get_page_by_path($pg['parent']);
            if ($parent) $parent_id = $parent->ID;
        }
        $path = ($parent_id ? $pg['parent'] . '/' : '') . $pg['slug'];
        $existing = get_page_by_path($path);

        if ($existing) {
            // Content-sync: update from the seed ONLY if a human hasn't edited
            // the page since we last applied a seed. We compare like-for-like:
            //   _nrm_seed_applied = md5 of the STORED post_content right after our
            //                       last apply (WP munges content on save, so this
            //                       must be the stored form, not the seed string).
            //   _nrm_seed_src     = md5 of the seed SOURCE string we last applied.
            $src     = md5($pg['content']);
            $applied = get_post_meta($existing->ID, '_nrm_seed_applied', true);
            $srcprev = get_post_meta($existing->ID, '_nrm_seed_src', true);
            $current = md5($existing->post_content);

            // Human edited since our last apply → leave their version alone.
            if ($applied && $applied !== $current) continue;
            // Seed unchanged since last apply, and already applied → nothing to do.
            if ($applied && $srcprev === $src) continue;

            wp_update_post(['ID' => $existing->ID, 'post_content' => $pg['content']]);
            $fresh = get_post($existing->ID);
            update_post_meta($existing->ID, '_nrm_seed_applied', md5($fresh->post_content));
            update_post_meta($existing->ID, '_nrm_seed_src', $src);
            continue;
        }

        $pid = wp_insert_post([
            'post_type' => 'page', 'post_title' => $pg['title'],
            'post_name' => $pg['slug'], 'post_status' => 'publish',
            'post_parent' => $parent_id, 'post_content' => $pg['content'],
        ]);
        if (is_wp_error($pid)) continue;
        update_post_meta($pid, '_nrm_seeded', 1);
        $fresh = get_post($pid);
        update_post_meta($pid, '_nrm_seed_applied', md5($fresh->post_content));
        update_post_meta($pid, '_nrm_seed_src', md5($pg['content']));
        foreach (($pg['meta'] ?? []) as $k => $v) {
            update_post_meta($pid, $k, $v);
        }
    }

    // Remove WordPress's default placeholder content.
    foreach (['sample-page' => 'page', 'hello-world' => 'post'] as $slug => $type) {
        $ph = get_page_by_path($slug, OBJECT, $type);
        if ($ph) wp_delete_post($ph->ID, true);
    }

    update_option('nrm_pages_seed_sig', $sig);
    update_option('nrm_pages_v1_imported', true); // kept for menu-seed ordering
}
add_action('admin_init', 'nrm_maybe_import_pages');

// Ensure the school-relationship role tags exist even on already-imported
// sites (the main role seed is guarded by the v2-imported flag).
function nrm_ensure_school_roles() {
    if (!taxonomy_exists('nrm_role')) return;
    if (get_option('nrm_school_roles_seeded')) return;
    foreach (['School Director', 'School Contact', 'School Maintainer'] as $r) {
        if (!term_exists($r, 'nrm_role')) wp_insert_term($r, 'nrm_role');
    }
    update_option('nrm_school_roles_seeded', true);
}
add_action('admin_init', 'nrm_ensure_school_roles');

// One-time: the seeded members are curated org people already shown publicly on
// Who's Who, so default them to public so the directory isn't empty. New members
// added later default to private (opt-in) until a member/staff ticks the box.
// NOTE: the final public-profile default/consent model is a Nick/board decision.
function nrm_backfill_public_members() {
    if (get_option('nrm_public_backfill_done')) return;
    if (!post_type_exists('nrm_member')) return;
    $ids = get_posts(['post_type' => 'nrm_member', 'numberposts' => -1, 'fields' => 'ids', 'post_status' => 'publish']);
    if (!$ids) return; // wait until members are imported
    foreach ($ids as $id) {
        if (get_post_meta($id, 'nrm_public', true) === '') update_post_meta($id, 'nrm_public', '1');
    }
    update_option('nrm_public_backfill_done', true);
}
add_action('admin_init', 'nrm_backfill_public_members');

// ── Certification-level data updates ──
// Curated corrections to discipline pages' nrm_cert_levels meta
// (wordpress/cert-updates.json). Applied whenever the file changes.
// NOTE: this REPLACES the meta wholesale — it is the source of truth until
// staff take over editing via the Discipline Page Settings box; after that,
// ship an empty {} to stop overwriting.
function nrm_maybe_apply_cert_updates() {
    $json = @file_get_contents('/var/www/cert-updates.json');
    if (!$json) return;
    $updates = json_decode($json, true);
    if (!is_array($updates) || !$updates) return;

    // Bump the version suffix to force re-apply after a save-path fix.
    $sig = md5($json . '|apply-v2');
    if (get_option('nrm_cert_updates_sig') === $sig) return;

    foreach ($updates as $slug => $meta) {
        $page = get_page_by_path($slug);
        if (!$page) continue;
        if (isset($meta['nrm_cert_levels'])) {
            // wp_slash: update_post_meta runs wp_unslash on the value, which would
            // otherwise strip the JSON's \n / \" backslash-escapes (turning "\n"
            // into a literal "n" — the "paidnComplete" bug). Slashing first cancels it.
            update_post_meta($page->ID, 'nrm_cert_levels', wp_slash(wp_json_encode($meta['nrm_cert_levels'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)));
        }
        if (!empty($meta['nrm_national_standards_url'])) {
            update_post_meta($page->ID, 'nrm_national_standards_url', esc_url_raw($meta['nrm_national_standards_url']));
        }
    }
    update_option('nrm_cert_updates_sig', $sig);
}
add_action('admin_init', 'nrm_maybe_apply_cert_updates');

// ── Primary nav menu ──
// Seeds a real, staff-editable menu (Appearance → Menus) matching the theme's
// hardcoded fallback, and assigns it to the 'primary' location. One-time.
function nrm_maybe_seed_menu() {
    if (get_option('nrm_menu_v1_seeded')) return;
    if (!get_option('nrm_pages_v1_imported')) return; // pages must exist for links

    $menu_name = 'Primary';
    $menu = wp_get_nav_menu_object($menu_name);
    if (!$menu) {
        $menu_id = wp_create_nav_menu($menu_name);
        if (is_wp_error($menu_id)) return;

        $items = [
            ['title' => 'Home',             'url' => home_url('/')],
            ['title' => 'My Profile',       'url' => home_url('/pathway/')],
            ['title' => 'Events & Clinics', 'url' => get_post_type_archive_link('nrm_event')],
            ['title' => 'Disciplines',      'url' => home_url('/disciplines/')],
            ['title' => 'Membership',       'url' => home_url('/membership/')],
            ['title' => 'Scholarships',     'url' => home_url('/scholarships/')],
            ['title' => "Who's Who",        'url' => home_url('/whos-who/')],
        ];
        foreach ($items as $i => $item) {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title'  => $item['title'],
                'menu-item-url'    => $item['url'],
                'menu-item-status' => 'publish',
                'menu-item-position' => $i + 1,
            ]);
        }
    } else {
        $menu_id = $menu->term_id;
    }

    $locations = get_theme_mod('nav_menu_locations', []);
    if (empty($locations['primary'])) {
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    update_option('nrm_menu_v1_seeded', true);
}
add_action('admin_init', 'nrm_maybe_seed_menu');
