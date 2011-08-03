jQuery(document).ready(function() {

	// xhtml 1.0 strict way of using target _blank
	jQuery('.sexybookmarks a.external').attr("target", "_blank");

	// set the title tag to the link
	jQuery('.sexybookmarks a').each(function(){
		if (! jQuery(this).attr('title')) {
			jQuery(this).attr('title', jQuery(this).text());
		}
	});

	// autocentering
	if (jQuery('.sexybookmarks-center') || jQuery('.sexybookmarks-spaced')) {
		var sexyFullWidth = jQuery('.sexybookmarks').width();
		var sexyBookmarkWidth = jQuery('.sexybookmarks:first ul.socials li').width();
		var sexyBookmarkCount = jQuery('.sexybookmarks:first ul.socials li').length;
		var numPerRow = Math.floor(sexyFullWidth/sexyBookmarkWidth);
		var sexyRowWidth = Math.min(numPerRow, sexyBookmarkCount)*sexyBookmarkWidth;
		if (jQuery('.sexybookmarks-spaced').length>0) {
			var sexyLeftMargin = Math.floor((sexyFullWidth-sexyRowWidth)/(Math.min(numPerRow, sexyBookmarkCount)+1));
			jQuery('.sexybookmarks ul.socials li').css('margin-left', sexyLeftMargin+'px');
		} else if (jQuery('.sexybookmarks-center'.length>0)) {
			var sexyLeftMargin = (sexyFullWidth-sexyRowWidth)/2;
			jQuery('.sexybookmarks-center').css('margin-left', sexyLeftMargin+'px');
		}
	}

	// add a "rel" attrib if Opera 7+
	if (window.opera) {
		jQuery(".sexy-favorite a").attr("rel", "sidebar");
	}
	jQuery(".sexy-favorite a").click(function(event) {
		event.preventDefault();
		var url = this.href;
		var title = this.title;
		if (window.sidebar) { // Mozilla Firefox Bookmark
			window.sidebar.addPanel(title, url,"");
		} else if (window.external) { // IE Favorite
			window.external.AddFavorite( url, title);
		} else if (window.opera) { // Opera 7+
			return false; // do nothing - the rel="sidebar" should do the trick
		} else { // for Safari, Konq etc - browsers who do not support bookmarking scripts (that i could find anyway)
			 alert('Unfortunately, this browser does not support the requested action,'
			 + ' please bookmark this page manually.');
		}
	});
});