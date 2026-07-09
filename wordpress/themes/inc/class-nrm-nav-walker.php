<?php
/**
 * Minimal nav walker — emits bare <a> tags so the existing
 * `.header-nav a` flex styling applies unchanged (no ul/li wrappers).
 */

if (!defined('ABSPATH')) exit;

class NRM_Nav_Walker extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {}
    public function end_lvl(&$output, $depth = 0, $args = null) {}

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = (array) $item->classes;
        $is_current = array_intersect($classes, ['current-menu-item', 'current_page_item', 'current-menu-ancestor']);
        $output .= sprintf(
            '<a href="%s"%s%s>%s',
            esc_url($item->url),
            $is_current ? ' class="current"' : '',
            $is_current ? ' aria-current="page"' : '',
            esc_html($item->title)
        );
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</a>';
    }
}

/**
 * Fallback when no menu is assigned to the location (e.g. a fresh database):
 * renders the same links the theme shipped with, so navigation never vanishes.
 */
function nrm_nav_fallback() {
    ?>
    <nav id="primary-nav" class="header-nav" aria-label="Primary">
      <a href="<?php echo esc_url(home_url()); ?>"<?php echo is_front_page() ? ' class="current" aria-current="page"' : ''; ?>>Home</a>
      <a href="<?php echo esc_url(get_post_type_archive_link('nrm_event')); ?>">Events &amp; Clinics</a>
      <a href="<?php echo esc_url(home_url('/disciplines')); ?>">Disciplines</a>
      <a href="<?php echo esc_url(home_url('/membership')); ?>">Membership</a>
      <a href="<?php echo esc_url(home_url('/scholarships')); ?>">Scholarships</a>
      <a href="<?php echo esc_url(get_post_type_archive_link('nrm_member')); ?>">Find a Member</a>
      <a href="<?php echo esc_url(home_url('/whos-who')); ?>">Who's Who</a>
    </nav>
    <?php
}
