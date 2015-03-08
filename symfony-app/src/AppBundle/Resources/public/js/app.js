$(function() {
    $('body').on('click', '[data-confirm]', function(e) {
        e.preventDefault();

        var $elm = $(this);
        var confirm = $elm.data('confirm');
        bootbox.confirm($elm.data('confirm'), function(res) {
            if (res && $elm.is('a')) {
                window.location = $elm.attr('href');
            } else if (res) {
                $elm.parents('form').first().trigger('submit');
            }
        });
    });
});

$(function() {
    $('.js-tabs a').click(function (e) {
        e.preventDefault()
        $(this).tab('show');
    });
});

$(function() {
    $('table.js-collection').each(function() {
        var $table = $(this);
        var $tbody = $table.find('tbody');
        var $buttonNew = $table.find('.js-button-new');
        var min = $table.data('min') ? $table.data('min') : 0;
        var max = $table.data('max') ? $table.data('max') : 999;

        console.log(min, max);

        var update = function() {
            var c = count();
            $buttonNew.attr('disabled', c >= max);
            $tbody.find('.js-button-deletem').attr('disabled', c <= max);
        };

        var count = function() {
            return $tbody.find('tr').length;
        };

        $tbody.data('index', $tbody.find('tr').length);

        $buttonNew.on('click', function(e) {
            e.preventDefault();

            if (count() >= max) {
                return;
            }

            var prototype = $table.data('prototype');
            var index = $tbody.data('index');
            var form = prototype.replace(/__name__/g, index);

            $tbody.append(form);
            $tbody.data('index', index + 1);

            update();
        });

        $tbody.on('click', '.js-button-delete', function(e) {
            e.preventDefault();

            if (count() > min) {
                $(this).closest('tr').remove();
                update();
            }
        });
    });
});