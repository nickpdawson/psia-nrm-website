<?php
/**
 * Block patterns — designed elements office staff can insert from the
 * editor's pattern library (search "NRM") without touching HTML.
 */

if (!defined('ABSPATH')) exit;

add_action('init', function () {
    register_block_pattern_category('nrm', ['label' => 'PSIA-NRM']);

    register_block_pattern('psia-nrm/document-link', [
        'title'       => 'Document link',
        'description' => 'A styled row linking to a PDF or document.',
        'categories'  => ['nrm'],
        'content'     => '<!-- wp:html -->
<a class="nrm-doc-link" href="#"><span style="font-size:1.25rem;">📄</span><span><span class="nrm-doc-title">Document title</span><br><span class="nrm-doc-note">PDF — short note</span></span></a>
<!-- /wp:html -->',
    ]);

    register_block_pattern('psia-nrm/deadline-badge', [
        'title'       => 'Deadline badge',
        'description' => 'A red highlight badge for deadlines.',
        'categories'  => ['nrm'],
        'content'     => '<!-- wp:html -->
<span class="nrm-deadline">Deadline: November 15</span>
<!-- /wp:html -->',
    ]);

    register_block_pattern('psia-nrm/faq-item', [
        'title'       => 'FAQ item (expandable)',
        'description' => 'A question that expands to show its answer.',
        'categories'  => ['nrm'],
        'content'     => '<!-- wp:details -->
<details class="wp-block-details"><summary>Question goes here?</summary><!-- wp:paragraph --><p>Answer goes here.</p><!-- /wp:paragraph --></details>
<!-- /wp:details -->',
    ]);
});
