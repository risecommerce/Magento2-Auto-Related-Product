define(['jquery'], function($) {
	'use strict';
	return function(config) {
		$.ajax({
		    url: config.baseUrl + 'risecommerce_auto_related_products/index/ajax',
		    method: 'POST',
		    data: {
		    	'id': config.id,
		    	'type': config.type,
		    	'rule_id': config.rule_id
		    },
		    success: function (response) {
		    	response = response.data;
		    	if (response.success = true && response.hasRules == true) {
			    	$.each(response.rules, function (key, value) {
			    		if (value.position) {
			    		    if($("#risecommerce-auto-related-products-custom-"+config.rule_id).length)
                            {
                                $("#risecommerce-auto-related-products-custom-"+config.rule_id).append(value.html).trigger('contentUpdated');
                            }else {
                                var position = '#risecommerce-auto-related-products-' + value.position.replace('_', '-');
                                if (position.search('replace') > 0) {
                                    var replacePosition = value.position.split('_');
                                    if ($('.block.'+replacePosition[1]).length > 0) {
                                        $('.block.'+replacePosition[1]).html(value.html).trigger('contentUpdated');
                                    } else {
                                        position = '#risecommerce-auto-related-products-after-' + replacePosition[1];
                                        $(position).append(value.html).trigger('contentUpdated');
                                    }
                                } else {
                                    $(position).append(value.html).trigger('contentUpdated');
                                }
                            }
					    }
			    	});
		    	}
		    }
		});
	};
});
