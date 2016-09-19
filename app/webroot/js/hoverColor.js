$( function() {
	$( ".tweet, .userSimple" ).hover( 
		function(){
			$(this).css("background-color", "#EEEEEE");
		},
		function(){
			$(this).css("background-color", "#FFFFFF");
		});
});
