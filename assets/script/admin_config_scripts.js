// JavaScript Document
//Regular expersions
var whitespace=/^\s*$/;
var reg_numeric=/^\d+$/;
var reg_email=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var reg_contact_no=/^\d{10,11}$/;
var reg_username=/^[a-zA-Z0-9\W]{6,}$/;


var reg_url=/^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;


$(document).ready(function($){
	$("#create_user").click(function(){
		$(".server_msg").fadeOut(200);
		$.validate_Sign_In_Details();
	});
	
	
	/*Script for Changing Password*/
	$("#change_password_but").click(function(){
		$.validate_New_Password();
	});
	
	
	$("#delete_user_account_but").click(function(){
		if(confirm('Are you sure to delete the user account?'))
		{
			$.validate_Reason_Deleting_Account();
		}
	});
	
	
	
	$(".recover_user_but").click(function(){
		var form_point=$(this).parent();
		if(confirm('Are you sure to recover this user?'))
		{
			$(form_point).submit();
		}
	});
	
	
	
	$("#modify_user_detail").click(function(){
		$.validate_Modify_User_Detils_Form();
	});
});




/*Function to validate USER Sign Up Fields*/
jQuery.validate_Sign_In_Details=function()
{	
	if(whitespace.test($("#full_name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_name").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#email").val()) || !reg_email.test($("#email").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_email").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#contact_no").val()) || !reg_contact_no.test($("#contact_no").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_contact_no").delay(200).fadeIn(200);
		return false;
	}
	else if(!reg_username.test($("#sign_in_username").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_username").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#sign_in_password").val()) && $("#sign_in_password").val().length<5)
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_password").delay(200).fadeIn(200);
		return false;
	}
	else if($("#sign_in_confirm_pass").val()!=$("#sign_in_password").val())
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_confirm_pass").delay(200).fadeIn(200);
		return false;
	}
	$(".validations,.validations .error").fadeOut(200);
	
	$("form[name='create_user']").submit();
	return true;
};




/*Function to validate New Password*/
jQuery.validate_New_Password=function()
{	
	if($("#new_password").val()=='' || $("#new_password").val().length<5)
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_new_password").delay(200).fadeIn(200);
		return false;
	}
	if($("#new_password").val()!=$("#confirm_password").val())
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_confirm_pass").delay(200).fadeIn(200);
		return false;
	}
	$(".validations,.validations .error").fadeOut(200);
	
	$("form[name='change_password_form']").submit();
	return true;
};


/*Function to validate the reason for delete the user account*/
jQuery.validate_Reason_Deleting_Account=function()
{	
	if(whitespace.test($("#reason").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_delete_reason").delay(200).fadeIn(200);
		return false;
	}
	$(".validations,.validations .error").fadeOut(200);
	
	$("form[name='delete_user_account_form']").submit();
	return true;
};



jQuery.validate_Modify_User_Detils_Form=function(){
	if(whitespace.test($("#name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_name").delay(200).fadeIn(200);
		return false;
	}
	if(whitespace.test($("#contact").val()) || !reg_contact_no.test($("#contact").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_contact").delay(200).fadeIn(200);
		return false;
	}
	$(".validations,.validations .error").fadeOut(200);
	
	$("form[name='modify_user_details_form']").submit();
	return true;
};