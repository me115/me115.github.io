		var $j = jQuery.noConflict();
	$j(document).ready(function(){
				$j('#sidebar .widget h2').click(function(){
					
					// gets the parent div
					var parent = $j(this).parents('#sidebar .widget');
					
					// checks if it's active
					var active = $j(parent).hasClass('active');
					
					// if it's active, it slides the div down, otherwise it slides it up
					if(active == true){
						$j(this).next().slideDown('fast');
						$j(parent).removeClass('active');
					} else {
						$j(this).next().slideUp('fast');
						$j(parent).addClass('active');
					};					
				});
			});

