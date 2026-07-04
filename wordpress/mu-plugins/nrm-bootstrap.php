<?php
/*
Plugin Name: NRM Bootstrap
Description: Ensures the psia-nrm theme and the NRM Data Import plugin are active. Idempotent — makes fresh deploys (Azure container, local docker) converge without manual wp-admin steps.
Version: 1.0
*/

if (!defined('ABSPATH')) exit;

add_action('init', function () {
    if (!is_blog_installed()) return;

    if (get_option('stylesheet') !== 'psia-nrm' && wp_get_theme('psia-nrm')->exists()) {
        switch_theme('psia-nrm');
    }

    if (!function_exists('is_plugin_active')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    foreach (['nrm-import.php', 'nrm-forms.php'] as $plugin) {
        if (file_exists(WP_PLUGIN_DIR . '/' . $plugin) && !is_plugin_active($plugin)) {
            activate_plugin($plugin);
        }
    }
}, 1);

// Pretty permalinks are required (templates and seeded links assume /slug/
// paths). WP only auto-enables them when its install-time self-request test
// succeeds, which fails in containers behind port mappings — so enforce it.
// Also flush CPT rewrite rules once after the post types exist (they register
// on init 10); bump the version to force a re-flush on a deploy.
add_action('init', function () {
    if (!is_blog_installed()) return;
    $needs_flush = false;
    if (!get_option('permalink_structure')) {
        update_option('permalink_structure', '/%postname%/');
        $needs_flush = true;
    }
    if ($needs_flush || get_option('nrm_rewrite_flush_v') !== '1') {
        flush_rewrite_rules();
        update_option('nrm_rewrite_flush_v', '1');
    }
}, 99);
