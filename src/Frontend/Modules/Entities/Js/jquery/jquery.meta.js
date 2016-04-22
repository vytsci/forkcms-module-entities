(function ($) {
    $.fn.doMeta = function (options) {
        var defaults = {};

        options = $.extend(defaults, options);

        return this.each(function () {
            var $element = $(this);
            var $pageTitle = $('#pageTitle');
            var $pageTitleOverwrite = $('#pageTitleOverwrite');
            var $navigationTitle = $('#navigationTitle');
            var $navigationTitleOverwrite = $('#navigationTitleOverwrite');
            var $metaDescription = $('#metaDescription');
            var $metaDescriptionOverwrite = $('#metaDescriptionOverwrite');
            var $metaKeywords = $('#metaKeywords');
            var $metaKeywordsOverwrite = $('#metaKeywordsOverwrite');
            var $urlOverwrite = $('#urlOverwrite');

            $element.bind('keyup', calculateMeta).trigger('keyup');

            if ($pageTitle.length > 0 && $pageTitleOverwrite.length > 0) {
                $pageTitleOverwrite.change(function (e) {
                    if (!$element.is(':checked')) $pageTitle.val($element.val());
                });
            }

            if ($navigationTitle.length > 0 && $navigationTitleOverwrite.length > 0) {
                $navigationTitleOverwrite.change(function (e) {
                    if (!$element.is(':checked')) $navigationTitle.val($element.val());
                });
            }

            $metaDescriptionOverwrite.change(function (e) {
                if (!$element.is(':checked')) $metaDescription.val($element.val());
            });

            $metaKeywordsOverwrite.change(function (e) {
                if (!$element.is(':checked')) $metaKeywords.val($element.val());
            });

            $urlOverwrite.change(function (e) {
                if (!$element.is(':checked')) generateUrl($element.val());
            });

            function generateUrl(url) {
                $.ajax(
                    {
                        data: {
                            fork: {module: 'Core', action: 'GenerateUrl'},
                            url: url,
                            metaId: $('#metaId').val(),
                            baseFieldName: $('#baseFieldName').val(),
                            custom: $('#custom').val(),
                            className: $('#className').val(),
                            methodName: $('#methodName').val(),
                            parameters: $('#parameters').val()
                        },
                        success: function (data, textStatus) {
                            url = data.data;
                            $('#url').val(url);
                            $('#generatedUrl').html(url);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            url = utils.string.urlDecode(utils.string.urlise(url));
                            $('#url').val(url);
                            $('#generatedUrl').html(url);
                        }
                    });
            }

            function calculateMeta(e, element) {
                var title = (typeof element != 'undefined') ? element.val() : $(this).val();
                if ($pageTitle.length > 0 && $pageTitleOverwrite.length > 0) {
                    if (!$pageTitleOverwrite.is(':checked')) $pageTitle.val(title);
                }

                if ($navigationTitle.length > 0 && $navigationTitleOverwrite.length > 0) {
                    if (!$navigationTitleOverwrite.is(':checked')) $navigationTitle.val(title);
                }

                if (!$metaDescriptionOverwrite.is(':checked')) $metaDescription.val(title);

                if (!$metaKeywordsOverwrite.is(':checked')) $metaKeywords.val(title);

                if (!$urlOverwrite.is(':checked')) {
                    if (typeof pageID == 'undefined' || pageID != 1) {
                        generateUrl(title);
                    }
                }
            }
        });
    };
})(jQuery);
