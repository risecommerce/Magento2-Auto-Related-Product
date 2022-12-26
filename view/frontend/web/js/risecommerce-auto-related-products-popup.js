require(['jquery'], function ($) {
	$('.risecommerce-auto-related-products-popup .popup-action .remove-popup').click(function () {
		$(this).parent().parent().hide();
	});
	$('.risecommerce-auto-related-products-popup .popup-action .close-popup').click(function () {
		var opened = $('.risecommerce-auto-related-products-popup .popup-action').attr('data-opened');
		if (opened == "true") {
			$('.risecommerce-auto-related-products-popup .popup-action').attr('data-opened', "false");				
		} else {
			$('.risecommerce-auto-related-products-popup .popup-action').attr('data-opened', "true");				
		}
		$(this).parent().siblings().toggle();
	});
});