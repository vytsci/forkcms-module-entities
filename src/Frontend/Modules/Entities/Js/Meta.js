if (typeof jsFrontend.Entities == 'undefined') {
    jsFrontend.Entities = {};
}

jsFrontend.Entities.Meta = {
    init: function() {
        var $baseFields = $('input[data-meta-base-field]');
        $baseFields.doMeta();
    }
};

$(jsFrontend.Entities.Meta.init);
