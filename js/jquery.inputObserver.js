jQuery.fn.inputObserver = function(options) {
    return $(this).each(function(key,obj) {
        var value = $(this).val();
        
        switch(typeof(options)) {
            case 'object':
                obj.options = jQuery.extend({
                    speed: 0.1,
                    trigger: false,
                    onChange: function() {}
                }, options);

                if (obj.options.trigger) {
                    obj.options.onChange(obj,value);
                }

                setInterval(
                    function() {
                        if (value != $(obj).val()) {
                            value = $(obj).val();
                            obj.options.onChange(obj,value);
                        }
                    },
                    obj.options.speed * 1000
                );
                break;

            case 'string':
                switch(options) {
                    case 'trigger':
                        obj.options.onChange(obj,value);
                        break;
                }
                break;
        }


    });
}