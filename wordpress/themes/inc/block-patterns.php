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
<a class="nrm-doc-link" href="#"><span class="nrm-doc-ico"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg></span><span><span class="nrm-doc-title">Document title</span><br><span class="nrm-doc-note">PDF — short note</span></span></a>
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
