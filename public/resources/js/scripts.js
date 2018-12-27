$(document).ready(function(){
	
	var primaryScroll;

	function loadedPrimaryMenu() {
		if ($('#primary-nav').length) {
			primaryScroll = new IScroll('#primary-nav', { scrollX: true, scrollY: false, mouseWheel: true });
		}
	}

 	loadedPrimaryMenu();

 	$('.parent-row').click(function(){
 		$(this).next('.child-row').show();
 	});


});