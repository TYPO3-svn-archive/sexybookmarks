
<!-- ###TEMPLATE_JS### begin -->
jQuery(document).ready(function() {
	var sexyBaseHeight = jQuery('####KEY###.sexybookmarks').height();
	var sexyFullHeight = jQuery('####KEY###.sexybookmarks ul.socials').height();
	if (sexyFullHeight>sexyBaseHeight) {
		jQuery('####KEY###.sexybookmarks-expand').hover(
			function() {
				jQuery(this).stop().animate({
					height: sexyFullHeight+'px'
				}, {###ANIMATION###});
			},
			function() {
				jQuery(this).stop().animate({
					height: sexyBaseHeight+'px'
				}, {###ANIMATION###});
			}
		);
	}
});
<!-- ###TEMPLATE_JS### end -->
