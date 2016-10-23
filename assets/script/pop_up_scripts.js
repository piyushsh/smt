//Script of POP UPs

$(document).ready(function(){
	$(".close,.fade_background").click(function(){
		$(".fade_background").fadeOut(200);
		$(".pop_up_container,.pop_up_container .pop_up_content").fadeOut(200);
	});
	
	$(".button").click(function(){
		var id="#"+$(this).data('popUpId');
		if($(this).data('showPopUp')==1)
		{
			$(".fade_background,.pop_up_container").fadeIn(200);
			$(id).fadeIn(200);
		}
	});
	
	$("#create_survey_pop_up").click(function(){
		$(".pop_up_container .pop_up_content").fadeOut(200);
		$(".fade_background").fadeIn(200);
		$(".pop_up_container,.pop_up_container #pop_up_create_survey_form").fadeIn(200);
	});
});