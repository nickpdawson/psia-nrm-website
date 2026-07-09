<?php
/**
 * Discipline Page Settings — staff-friendly editor for discipline pages.
 *
 * Certification levels are stored as the same JSON meta (`nrm_cert_levels`)
 * that page-discipline.php already renders; only the editing UI changed from
 * a raw JSON textarea to repeatable rows with a media-library document picker.
 */

if (!defined('ABSPATH')) exit;

add_action('init', function () {
    register_post_meta('page', 'nrm_hero_image', ['show_in_rest' => true, 'single' => true, 'type' => 'string']);
});

add_action('add_meta_boxes', function ($post_type, $post) {
    if ($post_type !== 'page') return;
    // Only show on pages using the Discipline template (or its landing variant).
    $template = get_post_meta($post->ID, '_wp_page_template', true);
    if (!in_array($template, ['page-discipline.php', 'page-disciplines.php'], true)) return;
    add_meta_box('nrm_discipline_page', 'Discipline Page Settings', 'nrm_discipline_meta_callback', 'page', 'normal', 'high');
}, 10, 2);

add_action('admin_enqueue_scripts', function ($hook) {
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) return;
    $screen = get_current_screen();
    if (!$screen || $screen->post_type !== 'page') return;
    wp_enqueue_media();
    wp_enqueue_script('nrm-cert-levels', get_template_directory_uri() . '/js/admin-cert-levels.js', ['jquery'], '1.0', true);
});

function nrm_discipline_meta_callback($post) {
    wp_nonce_field('nrm_discipline_meta', 'nrm_discipline_nonce');

    $discipline = get_post_meta($post->ID, 'nrm_page_discipline', true);
    $specialty  = get_post_meta($post->ID, 'nrm_page_specialty', true);
    $books      = get_post_meta($post->ID, 'nrm_recommended_books', true);
    $discord    = get_post_meta($post->ID, 'nrm_discord_channel', true);
    $standards  = get_post_meta($post->ID, 'nrm_national_standards_url', true);
    $levels_json = get_post_meta($post->ID, 'nrm_cert_levels', true);
    $levels = json_decode($levels_json, true);
    if (!is_array($levels)) $levels = [];
    ?>
    <table class="form-table"><tbody>
      <tr><th><label>Discipline</label><br><small style="color:#999;">Must match a discipline term exactly</small></th>
        <td><input type="text" name="nrm_page_discipline" value="<?php echo esc_attr($discipline); ?>" class="regular-text" placeholder="e.g. Alpine, Snowboard, Telemark"></td></tr>
      <tr><th><label>Specialty (if applicable)</label></th>
        <td><input type="text" name="nrm_page_specialty" value="<?php echo esc_attr($specialty); ?>" class="regular-text" placeholder="e.g. Children's Specialist, Freestyle"></td></tr>
      <tr><th><label>National Standards URL</label></th>
        <td><input type="url" name="nrm_national_standards_url" value="<?php echo esc_attr($standards); ?>" class="large-text" placeholder="https://www.thesnowpros.org/..."></td></tr>
      <tr><th><label>Recommended Books</label><br><small style="color:#999;">One per line</small></th>
        <td><textarea name="nrm_recommended_books" class="large-text" rows="3"><?php echo esc_textarea($books); ?></textarea></td></tr>
      <tr><th><label>Discord Channel</label></th>
        <td><input type="text" name="nrm_discord_channel" value="<?php echo esc_attr($discord); ?>" class="regular-text" placeholder="e.g. #alpine-level-3-prep"></td></tr>
    </tbody></table>

    <h3 style="margin:1em 0 0.5em;">Certification Levels</h3>
    <p class="description">Add a row per level. Documents can be picked from the Media Library (upload PDFs there first) or pasted as links.</p>

    <div id="nrm-levels">
      <?php foreach ($levels as $i => $lv): ?>
        <?php nrm_render_level_row($i, $lv); ?>
      <?php endforeach; ?>
    </div>
    <p><button type="button" class="button" id="nrm-add-level">+ Add level</button></p>

    <template id="nrm-level-template"><?php nrm_render_level_row('__i__', []); ?></template>
    <template id="nrm-doc-template"><?php nrm_render_doc_row('__i__', '__j__', []); ?></template>

    <details style="margin-top:1em;"><summary style="cursor:pointer;color:#999;">Advanced: stored JSON (read-only)</summary>
      <pre style="font-size:11px;overflow:auto;max-height:200px;background:#f6f7f7;padding:8px;"><?php echo esc_html($levels_json ?: '[]'); ?></pre>
    </details>
    <?php
}

function nrm_render_level_row($i, $lv) {
    $f = function ($k) use ($lv) { return esc_attr($lv[$k] ?? ''); };
    ?>
    <div class="nrm-level postbox" style="padding:12px;margin-bottom:10px;">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
        <strong class="nrm-level-heading"><?php echo $lv ? esc_html($lv['name'] ?? 'Level') : 'New level'; ?></strong>
        <span>
          <button type="button" class="button-link nrm-move-up" title="Move up">▲</button>
          <button type="button" class="button-link nrm-move-down" title="Move down">▼</button>
          <button type="button" class="button-link-delete nrm-remove-level">Remove</button>
        </span>
      </div>
      <table class="form-table" style="margin:0;"><tbody>
        <tr><th style="width:160px;"><label>Name</label></th>
          <td><input type="text" name="nrm_levels[<?php echo $i; ?>][name]" value="<?php echo $f('name'); ?>" class="regular-text nrm-level-name" placeholder="e.g. Alpine Level II"></td></tr>
        <tr><th><label>Description</label></th>
          <td><textarea name="nrm_levels[<?php echo $i; ?>][description]" class="large-text" rows="2"><?php echo esc_textarea($lv['description'] ?? ''); ?></textarea></td></tr>
        <tr><th><label>Prerequisites</label></th>
          <td><textarea name="nrm_levels[<?php echo $i; ?>][prerequisites]" class="large-text" rows="2"><?php echo esc_textarea($lv['prerequisites'] ?? ''); ?></textarea></td></tr>
        <tr><th><label>Modules</label></th>
          <td><textarea name="nrm_levels[<?php echo $i; ?>][modules]" class="large-text" rows="2"><?php echo esc_textarea($lv['modules'] ?? ''); ?></textarea></td></tr>
        <tr><th><label>Exam registration URL</label></th>
          <td><input type="url" name="nrm_levels[<?php echo $i; ?>][exam_url]" value="<?php echo $f('exam_url'); ?>" class="large-text"></td></tr>
        <tr><th><label>Exam note</label></th>
          <td><input type="text" name="nrm_levels[<?php echo $i; ?>][exam_note]" value="<?php echo $f('exam_note'); ?>" class="large-text"></td></tr>
        <tr><th><label>Documents</label></th>
          <td>
            <div class="nrm-docs">
              <?php foreach (($lv['documents'] ?? []) as $j => $doc): ?>
                <?php nrm_render_doc_row($i, $j, $doc); ?>
              <?php endforeach; ?>
            </div>
            <button type="button" class="button button-small nrm-add-doc">+ Add document</button>
          </td></tr>
      </tbody></table>
    </div>
    <?php
}

function nrm_render_doc_row($i, $j, $doc) {
    ?>
    <div class="nrm-doc" style="display:flex;gap:6px;margin-bottom:6px;align-items:center;">
      <input type="text" name="nrm_levels[<?php echo $i; ?>][documents][<?php echo $j; ?>][name]" value="<?php echo esc_attr($doc['name'] ?? ''); ?>" placeholder="Document name" style="flex:1;">
      <input type="url" name="nrm_levels[<?php echo $i; ?>][documents][<?php echo $j; ?>][url]" value="<?php echo esc_attr($doc['url'] ?? ''); ?>" placeholder="https://…" style="flex:2;">
      <button type="button" class="button button-small nrm-pick-doc">Media Library</button>
      <button type="button" class="button-link-delete nrm-remove-doc">✕</button>
    </div>
    <?php
}

add_action('save_post', function ($post_id) {
    if (!isset($_POST['nrm_discipline_nonce']) || !wp_verify_nonce($_POST['nrm_discipline_nonce'], 'nrm_discipline_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (get_post_type($post_id) !== 'page' || !current_user_can('edit_page', $post_id)) return;

    foreach (['nrm_page_discipline' => 'sanitize_text_field',
              'nrm_page_specialty' => 'sanitize_text_field',
              'nrm_recommended_books' => 'sanitize_textarea_field',
              'nrm_discord_channel' => 'sanitize_text_field',
              'nrm_national_standards_url' => 'esc_url_raw'] as $k => $sanitize) {
        if (isset($_POST[$k])) update_post_meta($post_id, $k, $sanitize(wp_unslash($_POST[$k])));
    }

    if (isset($_POST['nrm_levels'])) {
        $levels = [];
        foreach ((array) wp_unslash($_POST['nrm_levels']) as $lv) {
            if (!is_array($lv)) continue;
            $name = sanitize_text_field($lv['name'] ?? '');
            if ($name === '') continue; // drop empty rows
            $docs = [];
            foreach ((array) ($lv['documents'] ?? []) as $doc) {
                $dn = sanitize_text_field($doc['name'] ?? '');
                $du = esc_url_raw($doc['url'] ?? '');
                if ($dn === '' && $du === '') continue;
                $docs[] = ['name' => $dn, 'url' => $du];
            }
            $levels[] = [
                'name'          => $name,
                'slug'          => sanitize_title($name),
                'description'   => sanitize_textarea_field($lv['description'] ?? ''),
                'prerequisites' => sanitize_textarea_field($lv['prerequisites'] ?? ''),
                'modules'       => sanitize_textarea_field($lv['modules'] ?? ''),
                'documents'     => $docs,
                'exam_url'      => esc_url_raw($lv['exam_url'] ?? ''),
                'exam_note'     => sanitize_text_field($lv['exam_note'] ?? ''),
            ];
        }
        // wp_slash so update_post_meta's wp_unslash doesn't strip the JSON's
        // backslash-escapes (the "\n" → "n" corruption).
        update_post_meta($post_id, 'nrm_cert_levels', wp_slash(wp_json_encode($levels, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)));
    }
});
