const headerClass = jQuery('#masthead').attr('class');
jQuery(window).scroll(function () {
	if (jQuery(window).scrollTop() >= 36) {
		jQuery('#masthead')
			.removeClass(headerClass)
			.addClass('sticky site-header');
	} else if (jQuery(window).scrollTop() <= 36) {
		jQuery('#masthead')
			.removeClass('sticky site-header')
			.addClass(headerClass);
	}
});
