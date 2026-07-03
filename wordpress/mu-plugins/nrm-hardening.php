<?php
/*
Plugin Name: NRM Hardening
Description: Baseline hardening — disables file editing, XML-RPC, version disclosure. Loaded automatically as a must-use plugin.
Version: 1.0
*/

if (!defined('ABSPATH')) exit;

// Block in-admin file editor and plugin/theme installation from the dashboard.
// Updates and code changes happen via WP-CLI / SFTP only.
if (!defined('DISALLOW_FILE_EDIT')) define('DISALLOW_FILE_EDIT', true);
if (!defined('DISALLOW_FILE_MODS')) define('DISALLOW_FILE_MODS', true);

// XML-RPC is a brute-force surface; the prototype doesn't use it.
add_filter('xmlrpc_enabled', '__return_false');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');

// Hide WP version from <meta generator> and from RSS feeds.
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

// Disable user enumeration via /?author=N (returns 404 instead of redirecting).
add_action('parse_request', function ($wp) {
    if (!is_admin() && !empty($wp->query_vars['author'])) {
        wp_die('', '', ['response' => 404]);
    }
});
