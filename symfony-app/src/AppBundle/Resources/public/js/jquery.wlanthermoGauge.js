(function ($) {
    var methods = {
        init : function(options) {
            var settings = $.extend($.fn.wlanthermoGauge.defaults, options);

            return this.each(function() {
                var $elm = $(this);

                var chart = c3.generate({
                    bindto: '#' + $elm.attr('id'),
                    data: {
                        columns: [
                            [settings.label, settings.value]
                        ],
                        type: 'gauge',
                        width: 40
                    },
                    gauge: {
                        label: {
                            format: function(value, ratio) {
                                return value;
                            }
                        },
                        min: settings.min,
                        max: settings.max,
                        units: settings.units
                    },
                    color: {
                        pattern: ['#FF0000', '#F97600', '#F6C600', '#60B044'],
                        threshold: {
                            unit: 'value',
                            values: [30, 100, 200, 280]
                        }
                    },
                    size: {
                        height: 110
                    }
                });

                $elm.data('wlanthermoGaugeChart', chart);
                $elm.data('wlanthermoGaugeLabel', settings.label);
            });
        },

        load: function(value) {
            var $elm = $(this);
            var chart = $elm.data('wlanthermoGaugeChart');

            if (!chart) {
                $.error('Chart is not initialized yet');
            }

            chart.load({
                columns: [[$elm.data('wlanthermoGaugeLabel'), value]]
            });
        }
    };


    $.fn.wlanthermoGauge = function(options) {
        if (methods[options]) {
            return methods[options].apply(this, Array.prototype.slice.call(arguments, 1 ));
        } else if (typeof options === 'object' || ! options ) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  options + ' does not exist on jQuery.tooltip');
        }
    };

    $.fn.wlanthermoGauge.defaults = {
        label: 'Temperatur',
        value: 0,
        min: 0,
        max: 300,
        units: ' °C'
    };

}(jQuery));