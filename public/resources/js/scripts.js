$(document).ready(function(){
	
 	// alignMenu();

    $(window).resize(function() {
        $("#horizontal").append($("#horizontal li.hideshow ul").html());
        $("#horizontal li.hideshow").remove();
        // alignMenu();
    });

    function alignMenu() {
        var w = 0;
        var mw = $("#horizontal").width() - 70;
        var i = -1;
        var menuhtml = '';
        jQuery.each($("#horizontal").children(), function() {
            i++;
            w += $(this).outerWidth(true);
            if (mw < w) {
                menuhtml += $('<div>').append($(this).clone()).html();
                $(this).remove();
            }
        });
        if (menuhtml) {
        	
	        $("#horizontal").append(
	                '<li  style="position:relative;" href="#" class="hideshow">'
	                        + '<a href="#"><i class="fa fa-bars" aria-hidden="true"></i> More'
	                        + '<span style="font-size:13px">&#8595;</span>'
	                        + '</a><ul>' + menuhtml + '</ul></li>');

	        var y2 = $("#horizontal").width() + $("#horizontal").offset().left;
	        var mY2 = $("#horizontal li.hideshow").offset().left + 196;

	        if (mY2 > y2) {
	        	$("#horizontal li.hideshow ul").css("left", -(mY2-y2) + "px");
	        }

	        $("#horizontal li.hideshow ul").css("top", ($("#horizontal li.hideshow").outerHeight(true) + 2) + "px");
	        $("#horizontal li.hideshow").click(function() {
	            $(this).children("ul").toggle();
	        });

	    }
    }

 	$('.parent-row').click(function(){
 		$(this).next('.child-row').show();
 	});


});