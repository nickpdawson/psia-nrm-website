<?php
/**
 * Site Content settings — Customizer panel for everything office staff
 * edit seasonally without a code deploy.
 *
 * Pattern: defaults live in code (nrm_defaults), staff overrides live in the
 * DB as theme_mods. A fresh database renders the site identically; edits
 * persist across container image rebuilds.
 */

if (!defined('ABSPATH')) exit;

function nrm_defaults() {
    return [
        'hero_heading'   => "Your journey.\nYour career.\nOur community.",
        'hero_text'      => 'PSIA-AASI Northern Rocky Mountain supports professional snow sports instructors across Montana, Wyoming, North Dakota, and South Dakota — helping you grow your career, connect with peers, and share the stoke.',
        'hero_cta1_label'=> 'Find Your Next Clinic →',
        'hero_cta1_url'  => '/events/',
        'hero_cta2_label'=> 'Explore Disciplines',
        'hero_cta2_url'  => '/disciplines/',
        'hero_image'     => 0, // attachment ID; 0 = shipped default file
        'stat1_number'   => '1,200+',
        'stat1_label'    => 'Members',
        'stat2_number'   => '20',
        'stat2_label'    => 'Member Schools',
        'stat3_number'   => '12+',
        'stat3_label'    => 'Events This Season',
        'footer_blurb'   => 'Serving ~1,200 snow sports professionals across Montana, Wyoming, North Dakota, and South Dakota.',
        'office_phone'   => '406-581-6139',
        'office_email'   => 'info@psia-nrm.org',
        'gallery_1'      => 0, 'gallery_2' => 0, 'gallery_3' => 0,
        'gallery_4'      => 0, 'gallery_5' => 0, 'gallery_6' => 0,
    ];
}

function nrm_setting($key) {
    $defaults = nrm_defaults();
    return get_theme_mod($key, $defaults[$key] ?? '');
}

function nrm_customize_register($wp_customize) {
    $wp_customize->add_panel('nrm_site_content', [
        'title'    => 'Site Content',
        'priority' => 10,
        'description' => 'Homepage, footer, and contact details. Changes publish instantly — no developer needed.',
    ]);

    // ── Hero ──
    $wp_customize->add_section('nrm_hero', ['title' => 'Homepage Hero', 'panel' => 'nrm_site_content']);
    $fields = [
        ['hero_heading',    'Heading (line breaks respected)', 'textarea', 'sanitize_textarea_field'],
        ['hero_text',       'Intro paragraph',                 'textarea', 'sanitize_textarea_field'],
        ['hero_cta1_label', 'Button 1 label',                  'text',     'sanitize_text_field'],
        ['hero_cta1_url',   'Button 1 link',                   'url',      'esc_url_raw'],
        ['hero_cta2_label', 'Button 2 label',                  'text',     'sanitize_text_field'],
        ['hero_cta2_url',   'Button 2 link',                   'url',      'esc_url_raw'],
    ];
    foreach ($fields as $i => $f) {
        $wp_customize->add_setting($f[0], ['default' => nrm_defaults()[$f[0]], 'sanitize_callback' => $f[3]]);
        $wp_customize->add_control($f[0], ['label' => $f[1], 'section' => 'nrm_hero', 'type' => $f[2], 'priority' => $i]);
    }
    $wp_customize->add_setting('hero_image', ['default' => 0, 'sanitize_callback' => 'absint']);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'hero_image', [
        'label' => 'Hero background photo', 'section' => 'nrm_hero', 'mime_type' => 'image', 'priority' => 20,
    ]));

    // ── Stats ──
    $wp_customize->add_section('nrm_stats', ['title' => 'Homepage Stats', 'panel' => 'nrm_site_content']);
    foreach ([1, 2, 3] as $n) {
        foreach (['number' => 'Number', 'label' => 'Label'] as $part => $nice) {
            $key = "stat{$n}_{$part}";
            $wp_customize->add_setting($key, ['default' => nrm_defaults()[$key], 'sanitize_callback' => 'sanitize_text_field']);
            $wp_customize->add_control($key, ['label' => "Stat $n $nice", 'section' => 'nrm_stats', 'type' => 'text']);
        }
    }

    // ── Photo gallery ──
    $wp_customize->add_section('nrm_gallery', [
        'title' => 'Homepage Photo Gallery', 'panel' => 'nrm_site_content',
        'description' => 'Up to 6 photos. Empty slots are skipped. Edit alt text in the Media Library.',
    ]);
    for ($n = 1; $n <= 6; $n++) {
        $wp_customize->add_setting("gallery_$n", ['default' => 0, 'sanitize_callback' => 'absint']);
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, "gallery_$n", [
            'label' => "Photo $n", 'section' => 'nrm_gallery', 'mime_type' => 'image',
        ]));
    }

    // ── Footer / contact ──
    $wp_customize->add_section('nrm_footer', ['title' => 'Footer & Contact', 'panel' => 'nrm_site_content']);
    $wp_customize->add_setting('footer_blurb', ['default' => nrm_defaults()['footer_blurb'], 'sanitize_callback' => 'sanitize_textarea_field']);
    $wp_customize->add_control('footer_blurb', ['label' => 'Footer blurb', 'section' => 'nrm_footer', 'type' => 'textarea']);
    $wp_customize->add_setting('office_phone', ['default' => nrm_defaults()['office_phone'], 'sanitize_callback' => 'sanitize_text_field']);
    $wp_customize->add_control('office_phone', ['label' => 'Office phone', 'section' => 'nrm_footer', 'type' => 'text']);
    $wp_customize->add_setting('office_email', ['default' => nrm_defaults()['office_email'], 'sanitize_callback' => 'sanitize_email']);
    $wp_customize->add_control('office_email', ['label' => 'Office email', 'section' => 'nrm_footer', 'type' => 'email']);
}
add_action('customize_register', 'nrm_customize_register');

// Direct shortcut in the admin sidebar so staff don't hunt through Appearance.
add_action('admin_menu', function () {
    add_menu_page(
        'Site Content', 'Site Content', 'edit_theme_options',
        'customize.php?autofocus[panel]=nrm_site_content', '', 'dashicons-edit-large', 59
    );
});

/**
 * Hero image URL helper — staff-selected attachment, else the shipped file.
 */
function nrm_hero_image_url() {
    $id = (int) nrm_setting('hero_image');
    if ($id && ($url = wp_get_attachment_image_url($id, 'full'))) return $url;
    return home_url('/wp-content/uploads/images/bw hero 2.jpeg');
}

/**
 * Gallery images — staff-selected attachments if ANY slot is set, else the
 * shipped default five.
 */
function nrm_gallery_images() {
    $picked = [];
    for ($n = 1; $n <= 6; $n++) {
        $id = (int) nrm_setting("gallery_$n");
        if (!$id) continue;
        $url = wp_get_attachment_image_url($id, 'medium_large');
        if (!$url) continue;
        $picked[] = [
            'src' => $url,
            'alt' => get_post_meta($id, '_wp_attachment_image_alt', true) ?: get_the_title($id),
            'pos' => 'center 30%',
        ];
    }
    if ($picked) return $picked;

    $defaults = [
        ['src' => '/wp-content/uploads/images/successful exam.jpeg', 'alt' => 'Alpine Level III certification celebration', 'pos' => 'center 20%'],
        ['src' => '/wp-content/uploads/images/Grand Targhee.jpeg', 'alt' => 'Instructors at Grand Targhee', 'pos' => 'center 35%'],
        ['src' => '/wp-content/uploads/images/Brenna K training.png', 'alt' => 'Training on the mountain', 'pos' => 'center 20%'],
        ['src' => '/wp-content/uploads/images/instructors training.jpeg', 'alt' => 'Instructor training session', 'pos' => 'center 40%'],
        ['src' => '/wp-content/uploads/images/instructors having fun.jpeg', 'alt' => 'NRM instructors celebrating', 'pos' => 'center 25%'],
    ];
    foreach ($defaults as &$d) { $d['src'] = home_url($d['src']); }
    return $defaults;
}
