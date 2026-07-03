<?php
/*
Plugin Name: NRM Forms
Description: Native forms (contact, scholarships, grants, credits) — no third-party dependency. Entries are stored in the database (Forms menu in wp-admin) and emailed to the office. Render with [nrm_form name="contact"].
Version: 1.0
*/

if (!defined('ABSPATH')) exit;

// ── Form definitions ─────────────────────────────────────────────────────
// Field types: text, email, tel, date, number, textarea. required: bool.

function nrm_forms_definitions() {
    return [
        'contact' => [
            'title' => 'Contact the NRM Office',
            'success' => 'Thanks — your message is on its way to the NRM office.',
            'fields' => [
                'name'          => ['label' => 'Your name', 'type' => 'text', 'required' => true],
                'email'         => ['label' => 'Email', 'type' => 'email', 'required' => true],
                'phone'         => ['label' => 'Phone', 'type' => 'tel', 'required' => false],
                'membership_id' => ['label' => 'PSIA-AASI member number (if applicable)', 'type' => 'text', 'required' => false],
                'message'       => ['label' => 'Message', 'type' => 'textarea', 'required' => true],
            ],
        ],
        'scholarship-individual' => [
            'title' => 'Individual Scholarship Application',
            'success' => 'Application received. The Educational Foundation will review it after the deadline — remember to have your director submit a recommendation.',
            'fields' => [
                'name'          => ['label' => 'Full name', 'type' => 'text', 'required' => true],
                'email'         => ['label' => 'Email', 'type' => 'email', 'required' => true],
                'phone'         => ['label' => 'Phone', 'type' => 'tel', 'required' => false],
                'membership_id' => ['label' => 'PSIA-AASI member number', 'type' => 'text', 'required' => true],
                'school'        => ['label' => 'Snowsports school', 'type' => 'text', 'required' => true],
                'event_name'    => ['label' => 'NRM event you plan to attend', 'type' => 'text', 'required' => true],
                'event_date'    => ['label' => 'Event date', 'type' => 'date', 'required' => false],
                'statement'     => ['label' => 'How will this event support your professional development goals?', 'type' => 'textarea', 'required' => true],
            ],
        ],
        'scholarship-director-rec' => [
            'title' => 'Director Recommendation (Individual Scholarship)',
            'success' => 'Recommendation received — thank you.',
            'fields' => [
                'director_name'  => ['label' => 'Director name', 'type' => 'text', 'required' => true],
                'director_email' => ['label' => 'Director email', 'type' => 'email', 'required' => true],
                'school'         => ['label' => 'Snowsports school', 'type' => 'text', 'required' => true],
                'applicant_name' => ['label' => 'Applicant being recommended', 'type' => 'text', 'required' => true],
                'recommendation' => ['label' => 'Recommendation', 'type' => 'textarea', 'required' => true],
            ],
        ],
        'school-grant' => [
            'title' => 'School Grant Application',
            'success' => 'Application received. The Educational Foundation will review it after the December 1 deadline.',
            'fields' => [
                'school_name'   => ['label' => 'Member school', 'type' => 'text', 'required' => true],
                'contact_name'  => ['label' => 'Contact name', 'type' => 'text', 'required' => true],
                'contact_email' => ['label' => 'Contact email', 'type' => 'email', 'required' => true],
                'phone'         => ['label' => 'Phone', 'type' => 'tel', 'required' => false],
                'event_name'    => ['label' => 'Event or program the grant supports', 'type' => 'text', 'required' => false],
                'statement'     => ['label' => 'Written statement — school goals and staff development plans', 'type' => 'textarea', 'required' => true],
            ],
        ],
        'member-school-inquiry' => [
            'title' => 'Member School Inquiry',
            'success' => 'Thanks — the office will follow up with your school.',
            'fields' => [
                'school_name'   => ['label' => 'School / organization', 'type' => 'text', 'required' => true],
                'contact_name'  => ['label' => 'Contact name', 'type' => 'text', 'required' => true],
                'contact_email' => ['label' => 'Contact email', 'type' => 'email', 'required' => true],
                'phone'         => ['label' => 'Phone', 'type' => 'tel', 'required' => false],
                'message'       => ['label' => 'How can we help?', 'type' => 'textarea', 'required' => true],
            ],
        ],
        'non-psia-credit' => [
            'title' => 'Non-PSIA Event Education Credit Request',
            'success' => 'Request received. Note the $12.50-per-CEU processing fee — the office will contact you with payment instructions.',
            'fields' => [
                'name'          => ['label' => 'Full name', 'type' => 'text', 'required' => true],
                'email'         => ['label' => 'Email', 'type' => 'email', 'required' => true],
                'membership_id' => ['label' => 'PSIA-AASI member number', 'type' => 'text', 'required' => true],
                'event_name'    => ['label' => 'Event name', 'type' => 'text', 'required' => true],
                'event_date'    => ['label' => 'Event date', 'type' => 'date', 'required' => true],
                'provider'      => ['label' => 'Organization that ran the event', 'type' => 'text', 'required' => true],
                'hours'         => ['label' => 'Education hours', 'type' => 'number', 'required' => true],
                'description'   => ['label' => 'Describe the event and its educational content', 'type' => 'textarea', 'required' => true],
            ],
        ],
    ];
}

function nrm_forms_office_email() {
    if (function_exists('nrm_setting')) {
        $email = nrm_setting('office_email');
        if (is_email($email)) return $email;
    }
    return get_option('admin_email');
}

// ── Entries CPT ──────────────────────────────────────────────────────────

add_action('init', function () {
    register_post_type('nrm_form_entry', [
        'label' => 'Form Entries',
        'labels' => ['name' => 'Form Entries', 'singular_name' => 'Form Entry', 'menu_name' => 'Form Entries'],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title'],
        'capabilities' => ['create_posts' => 'do_not_allow'],
        'map_meta_cap' => true,
    ]);
});

// ── Shortcode ────────────────────────────────────────────────────────────

add_shortcode('nrm_form', function ($atts) {
    $atts = shortcode_atts(['name' => ''], $atts);
    $defs = nrm_forms_definitions();
    if (!isset($defs[$atts['name']])) return '';
    $form = $defs[$atts['name']];
    $name = $atts['name'];

    $out = '';
    if (isset($_GET['nrm_form']) && $_GET['nrm_form'] === $name) {
        if (($_GET['nrm_status'] ?? '') === 'ok') {
            return '<div class="nrm-panel-teal" style="padding:1.25rem;background:var(--psia-teal-light);border-radius:0.75rem;"><strong>✓</strong> ' . esc_html($form['success']) . '</div>';
        }
        $out .= '<div class="nrm-panel" style="padding:1rem;background:rgba(227,22,54,0.08);border-radius:0.75rem;margin-bottom:1rem;">Something was missing or invalid — please check the highlighted fields and try again.</div>';
    }

    $out .= '<form class="nrm-form" method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    $out .= '<input type="hidden" name="action" value="nrm_form_submit">';
    $out .= '<input type="hidden" name="nrm_form_name" value="' . esc_attr($name) . '">';
    $out .= '<input type="hidden" name="nrm_form_return" value="' . esc_url(get_permalink()) . '">';
    $out .= wp_nonce_field('nrm_form_' . $name, 'nrm_form_nonce', true, false);
    // Honeypot — hidden from humans, bots fill it.
    $out .= '<p style="position:absolute;left:-9999px;" aria-hidden="true"><label>Leave this field empty<input type="text" name="nrm_website" tabindex="-1" autocomplete="off"></label></p>';

    foreach ($form['fields'] as $key => $f) {
        $req = !empty($f['required']);
        $out .= '<p style="margin-bottom:1rem;"><label style="display:block;font-size:0.875rem;font-weight:600;margin-bottom:0.25rem;">' . esc_html($f['label']) . ($req ? ' <span style="color:var(--shield-red);">*</span>' : '') . '</label>';
        $attrs = 'name="nrm_fields[' . esc_attr($key) . ']"' . ($req ? ' required' : '') . ' style="width:100%;max-width:480px;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font:inherit;"';
        if ($f['type'] === 'textarea') {
            $out .= '<textarea ' . $attrs . ' rows="6" style="width:100%;max-width:640px;padding:0.5rem 0.75rem;border:1px solid var(--border-light);border-radius:0.5rem;font:inherit;"></textarea>';
        } else {
            $out .= '<input type="' . esc_attr($f['type']) . '" ' . $attrs . '>';
        }
        $out .= '</p>';
    }

    $out .= '<p><button type="submit" class="btn btn-primary">Submit</button></p></form>';
    return $out;
});

// ── Submission handling ──────────────────────────────────────────────────

function nrm_form_handle_submit() {
    $name = sanitize_key($_POST['nrm_form_name'] ?? '');
    $defs = nrm_forms_definitions();
    $return = esc_url_raw($_POST['nrm_form_return'] ?? home_url('/'));
    $fail = add_query_arg(['nrm_form' => $name, 'nrm_status' => 'error'], $return);
    $ok   = add_query_arg(['nrm_form' => $name, 'nrm_status' => 'ok'], $return);

    if (!isset($defs[$name])) { wp_safe_redirect(home_url('/')); exit; }
    if (!wp_verify_nonce($_POST['nrm_form_nonce'] ?? '', 'nrm_form_' . $name)) { wp_safe_redirect($fail); exit; }
    if (!empty($_POST['nrm_website'])) { wp_safe_redirect($ok); exit; } // honeypot: pretend success

    // Rate limit: 5 submissions per 10 minutes per IP.
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
    $ip = trim(explode(',', $ip)[0]);
    $rl_key = 'nrm_form_rl_' . md5($ip);
    $count = (int) get_transient($rl_key);
    if ($count >= 5) { wp_safe_redirect($fail); exit; }
    set_transient($rl_key, $count + 1, 10 * MINUTE_IN_SECONDS);

    $form = $defs[$name];
    $raw = (array) wp_unslash($_POST['nrm_fields'] ?? []);
    $clean = [];
    foreach ($form['fields'] as $key => $f) {
        $v = trim((string) ($raw[$key] ?? ''));
        if ($f['type'] === 'textarea')   $v = sanitize_textarea_field($v);
        elseif ($f['type'] === 'email')  $v = sanitize_email($v);
        else                             $v = sanitize_text_field($v);
        if (!empty($f['required']) && $v === '') { wp_safe_redirect($fail); exit; }
        if ($f['type'] === 'email' && $v !== '' && !is_email($v)) { wp_safe_redirect($fail); exit; }
        $clean[$key] = $v;
    }

    // Store the entry first — email delivery can fail without losing data.
    $who = $clean['name'] ?? $clean['contact_name'] ?? $clean['director_name'] ?? $clean['school_name'] ?? 'Anonymous';
    $entry_id = wp_insert_post([
        'post_type'   => 'nrm_form_entry',
        'post_status' => 'publish',
        'post_title'  => $form['title'] . ' — ' . $who . ' — ' . wp_date('M j, Y g:ia'),
    ]);
    if (!is_wp_error($entry_id)) {
        update_post_meta($entry_id, '_nrm_form_name', $name);
        foreach ($clean as $k => $v) update_post_meta($entry_id, 'nrm_' . $k, $v);
        update_post_meta($entry_id, '_nrm_submitted_ip', $ip);
    }

    // Notify the office.
    $lines = ["New submission: {$form['title']}", ''];
    foreach ($form['fields'] as $key => $f) {
        $lines[] = $f['label'] . ':';
        $lines[] = '  ' . ($clean[$key] !== '' ? $clean[$key] : '—');
    }
    $lines[] = '';
    $lines[] = 'View all entries: ' . admin_url('edit.php?post_type=nrm_form_entry');
    $headers = [];
    $reply = $clean['email'] ?? $clean['contact_email'] ?? $clean['director_email'] ?? '';
    if ($reply) $headers[] = 'Reply-To: ' . $reply;
    wp_mail(nrm_forms_office_email(), '[NRM Website] ' . $form['title'], implode("\n", $lines), $headers);

    wp_safe_redirect($ok);
    exit;
}
add_action('admin_post_nopriv_nrm_form_submit', 'nrm_form_handle_submit');
add_action('admin_post_nrm_form_submit', 'nrm_form_handle_submit');

// ── Admin: entry detail, columns, CSV export ─────────────────────────────

add_action('add_meta_boxes_nrm_form_entry', function ($post) {
    add_meta_box('nrm_entry_detail', 'Submission', function ($post) {
        $form_name = get_post_meta($post->ID, '_nrm_form_name', true);
        $defs = nrm_forms_definitions();
        $fields = $defs[$form_name]['fields'] ?? [];
        echo '<table class="form-table"><tbody>';
        foreach ($fields as $key => $f) {
            $v = get_post_meta($post->ID, 'nrm_' . $key, true);
            echo '<tr><th style="width:220px;">' . esc_html($f['label']) . '</th><td>' . nl2br(esc_html($v)) . '</td></tr>';
        }
        echo '<tr><th>Submitted from IP</th><td>' . esc_html(get_post_meta($post->ID, '_nrm_submitted_ip', true)) . '</td></tr>';
        echo '</tbody></table>';
    }, 'nrm_form_entry', 'normal', 'high');
});

add_filter('manage_nrm_form_entry_posts_columns', function ($cols) {
    return ['cb' => $cols['cb'], 'title' => 'Entry', 'nrm_form' => 'Form', 'date' => $cols['date']];
});
add_action('manage_nrm_form_entry_posts_custom_column', function ($col, $post_id) {
    if ($col === 'nrm_form') {
        $name = get_post_meta($post_id, '_nrm_form_name', true);
        $defs = nrm_forms_definitions();
        echo esc_html($defs[$name]['title'] ?? $name);
    }
}, 10, 2);

// CSV export: Form Entries → Export CSV (per form or all).
add_action('admin_menu', function () {
    add_submenu_page('edit.php?post_type=nrm_form_entry', 'Export CSV', 'Export CSV', 'edit_pages', 'nrm-form-export', function () {
        $defs = nrm_forms_definitions();
        echo '<div class="wrap"><h1>Export form entries</h1><ul>';
        foreach ($defs as $name => $form) {
            $url = wp_nonce_url(admin_url('admin-post.php?action=nrm_form_export&form=' . $name), 'nrm_form_export');
            echo '<li><a class="button" style="margin:4px 0;" href="' . esc_url($url) . '">' . esc_html($form['title']) . '</a></li>';
        }
        echo '</ul></div>';
    });
});

add_action('admin_post_nrm_form_export', function () {
    if (!current_user_can('edit_pages') || !wp_verify_nonce($_GET['_wpnonce'] ?? '', 'nrm_form_export')) wp_die('Not allowed');
    $name = sanitize_key($_GET['form'] ?? '');
    $defs = nrm_forms_definitions();
    if (!isset($defs[$name])) wp_die('Unknown form');
    $fields = $defs[$name]['fields'];

    $entries = get_posts([
        'post_type' => 'nrm_form_entry', 'numberposts' => -1,
        'meta_key' => '_nrm_form_name', 'meta_value' => $name,
        'orderby' => 'date', 'order' => 'ASC',
    ]);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $name . '-entries-' . wp_date('Ymd') . '.csv');
    $fh = fopen('php://output', 'w');
    fputcsv($fh, array_merge(['Submitted'], wp_list_pluck($fields, 'label')));
    foreach ($entries as $e) {
        $row = [get_the_date('Y-m-d H:i', $e)];
        foreach (array_keys($fields) as $key) $row[] = get_post_meta($e->ID, 'nrm_' . $key, true);
        fputcsv($fh, $row);
    }
    exit;
});
