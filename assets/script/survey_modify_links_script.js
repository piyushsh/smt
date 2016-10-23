// JavaScript Document
jQuery(document).ready(function($){
	$(".survey_multi_link_ops .button, .survey_re_contact_link_ops .button").click(function(){
		$(".show_unshow").hide();
		$($(this).data('showdiv')).show();
	});
	
	//Multiple Survey Link Functionality
	$("#add_links_but").click(function(){
		if($("#add_links").val()=="")
		{
			alert("Please select a file to Add more links");
			return false;
		}
		document.add_multi_survey_links.submit();
	});
	
	
	$("#modify_links_but").click(function(){
		if($("#modify_links").val()=="")
		{
			alert("Please select a file to modify links");
			return false;
		}
		document.modify_multi_survey_links.submit();
	});
	
	$("#delete_links_but").click(function(){
		if($("#delete_links").val()=="")
		{
			alert("Please select a file to delete links");
			return false;
		}
		document.delete_multi_survey_links.submit();
	});

	//Re-Contact Survey Link Functionality
	$("#re_contact_add_links_but").click(function(){
		if($("#re_contact_add_links").val()=="")
		{
			alert("Please select a file to Add more Re-Contact links");
			return false;
		}
		document.add_re_contact_survey_links.submit();
	});

	$("#modify_re_contact_links_but").click(function(){
		if($("#modify_re_contact_links").val()=="")
		{
			alert("Please select a file to modify links");
			return false;
		}
		document.modify_re_contact_survey_links.submit();
	});

	$("#delete_re_contact_links_but").click(function(){
		if($("#delete_re_contact_links").val()=="")
		{
			alert("Please select a file to delete links");
			return false;
		}
		document.delete_re_contact_survey_links.submit();
	});
});