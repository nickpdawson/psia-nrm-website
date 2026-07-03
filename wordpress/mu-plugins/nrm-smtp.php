<?php
/*
Plugin Name: NRM SMTP
Description: Routes wp_mail through SMTP using environment variables (App Service application settings). No-op when NRM_SMTP_HOST is unset — entries still persist in the DB, only email delivery is skipped.
Version: 1.0
*/

if (!defined('ABSPATH')) exit;

add_action('phpmailer_init', function ($phpmailer) {
    $host = getenv('NRM_SMTP_HOST');
    if (!$host) return;

    $phpmailer->isSMTP();
    $phpmailer->Host       = $host;
    $phpmailer->Port       = (int) (getenv('NRM_SMTP_PORT') ?: 587);
    $phpmailer->SMTPSecure = getenv('NRM_SMTP_SECURE') ?: 'tls';
    $user = getenv('NRM_SMTP_USER');
    if ($user) {
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username = $user;
        $phpmailer->Password = getenv('NRM_SMTP_PASS') ?: '';
    }
    $from = getenv('NRM_SMTP_FROM');
    if ($from) {
        $phpmailer->setFrom($from, getenv('NRM_SMTP_FROM_NAME') ?: 'PSIA-NRM Website');
    }
});
