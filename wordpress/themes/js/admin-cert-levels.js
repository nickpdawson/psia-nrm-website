/* Cert-levels repeater — add/remove/reorder level rows and document rows,
 * with a Media Library picker for documents. Row indexes are re-numbered
 * on every change so PHP receives a clean nrm_levels[i][documents][j] tree. */
jQuery(function ($) {
    var $wrap = $('#nrm-levels');
    if (!$wrap.length) return;

    function reindex() {
        $wrap.find('.nrm-level').each(function (i) {
            $(this).find('[name]').each(function () {
                this.name = this.name.replace(/nrm_levels\[[^\]]*\]/, 'nrm_levels[' + i + ']');
            });
            $(this).find('.nrm-doc').each(function (j) {
                $(this).find('[name]').each(function () {
                    this.name = this.name.replace(/\[documents\]\[[^\]]*\]/, '[documents][' + j + ']');
                });
            });
        });
    }

    function tpl(id, i, j) {
        var html = $(id).html().replace(/__i__/g, i);
        if (j !== undefined) html = html.replace(/__j__/g, j);
        return html;
    }

    $('#nrm-add-level').on('click', function () {
        $wrap.append(tpl('#nrm-level-template', $wrap.find('.nrm-level').length));
        reindex();
    });

    $wrap.on('click', '.nrm-remove-level', function () {
        if (!confirm('Remove this level?')) return;
        $(this).closest('.nrm-level').remove();
        reindex();
    });

    $wrap.on('click', '.nrm-move-up, .nrm-move-down', function () {
        var $row = $(this).closest('.nrm-level');
        if ($(this).hasClass('nrm-move-up')) $row.prev('.nrm-level').before($row);
        else $row.next('.nrm-level').after($row);
        reindex();
    });

    $wrap.on('click', '.nrm-add-doc', function () {
        var $docs = $(this).siblings('.nrm-docs');
        $docs.append(tpl('#nrm-doc-template', 0, $docs.find('.nrm-doc').length));
        reindex();
    });

    $wrap.on('click', '.nrm-remove-doc', function () {
        $(this).closest('.nrm-doc').remove();
        reindex();
    });

    $wrap.on('input', '.nrm-level-name', function () {
        $(this).closest('.nrm-level').find('.nrm-level-heading').text(this.value || 'New level');
    });

    var frame;
    $wrap.on('click', '.nrm-pick-doc', function () {
        var $row = $(this).closest('.nrm-doc');
        frame = wp.media({ title: 'Select document', multiple: false });
        frame.on('select', function () {
            var att = frame.state().get('selection').first().toJSON();
            $row.find('input[type="url"]').val(att.url);
            var $name = $row.find('input[type="text"]');
            if (!$name.val()) $name.val(att.title);
        });
        frame.open();
    });
});
