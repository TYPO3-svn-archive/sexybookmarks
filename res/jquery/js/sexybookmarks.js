jQuery(document).ready(function() {

	jQuery(".sexybookmarks a.external").attr("target", "_blank");

	jQuery('.sexybookmarks a').each(function(){
		if (! jQuery(this).attr('title')) {
			jQuery(this).attr('title', jQuery(this).text());
		}
	});

	var c = jQuery(".sexybookmarks").height(),
		d = jQuery(".sexybookmarks ul.socials").height(),
		h = jQuery(".sexybookmarks div.shr-getshr").outerHeight(true);

	d > c && jQuery(".sexybookmarks-expand").hover(function () {
		jQuery(this).animate({
			height: (d + h) + "px"
		}, {
			duration: 400,
			queue: false
		})
	}, function () {
		jQuery(this).animate({
			height: c + "px"
		}, {
			duration: 400,
			queue: false
		})
	});

	if (jQuery(".sexybookmarks-center") || jQuery(".sexybookmarks-spaced")) {
		var a = jQuery(".sexybookmarks").width(),
			b = jQuery(".sexybookmarks:first ul.socials li").width(),
			e = jQuery(".sexybookmarks:first ul.socials li").length,
			f = Math.floor(a / b);
		b = Math.min(f, e) * b;
		if (jQuery(".sexybookmarks-spaced").length > 0) {
			a = Math.floor((a - b) / (Math.min(f, e) + 1));
			jQuery(".sexybookmarks ul.socials li").attr("style", 'margin-left:' + a + 'px !important')
		} else if (jQuery(true)) {
			a = (a - b) / 2;
			jQuery(".sexybookmarks-center").attr("style", 'margin-left:' + a + 'px !important')
		}
	}

	if( h > 0 &&  (jQuery(".sexybookmarks-expand").length == 0 || !(d>c))) {
		jQuery(".sexybookmarks").height(c+h);
	}

	if (window.opera) {
		jQuery(".sexy-favorite a").attr("rel", "sidebar");
	}
	jQuery(".sexy-favorite a").click(function(event) {
		event.preventDefault();
		var url = this.href;
		var title = this.title;
		if (window.sidebar) {
			window.sidebar.addPanel(title, url,"");
		} else if (window.external) { // IE Favorite
			window.external.AddFavorite( url, title);
		} else if (window.opera) { // Opera 7+
			return false;
		} else {
			 alert('Unfortunately, this browser does not support the requested action,'
			 + ' please bookmark this page manually.');
		}
	});
});