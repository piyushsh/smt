// JavaScript Document
//Regular expersions
var whitespace=/^\s*$/;
var reg_numeric=/^\d+$/;
var reg_email=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var reg_contact_no=/^\d{10,11}$/;
var reg_username=/^[a-zA-Z0-9\W]{6,12}$/;
var reg_url=/^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
var reg_vendor_url=/^((ftp|http|https):\/\/)*(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
var total_country_no=1;

jQuery(document).ready(function($){
	//Writing Script for Index Page
	
	$(".register_div").hide();
	
	$("#register_user").click(function(){
		$(".login_div,.server_msg").fadeOut(200);
		$(".register_div").delay(200).fadeIn(200);
	});
	$("#user_login").click(function(){		
		$(".register_div,.server_msg").fadeOut(200);
		$(".login_div").delay(200).fadeIn(200);
	});	
	
	$("#login").click(function(){
		$(".server_msg").fadeOut(200);	
		$.validate_Login_Details();
	});
	
	$("#sign_in").click(function(){
		$(".server_msg").fadeOut(200);
		$.validate_Sign_In_Details();
	});
	
	//Script for Index page Ends

	//Scripts for Survey Page
	$("input[type='radio'][name='survey_type']").click(function(){
		if($(this).val()=='single' && $(this).prop('checked'))
		{
			$("#multiple_link").val('');
			$("#link_2,#link_3").fadeOut(200);
			$("#link_1").delay(250).fadeIn(200);			
		}
		else if($(this).val()=='multi')
		{
			$("#single_link").val('');
			$("#link_1,#link_3").fadeOut(200);
			$("#link_2").delay(250).fadeIn(200);			
		}
		else if($(this).val()=='re_contact')
		{
			$("#single_link").val('');
			$("#link_1,#link_2").fadeOut(200);
			$("#link_3").delay(250).fadeIn(200);
		}
		else
		{
			$("#multiple_link").val('');
			$("#single_link").val('');
			$("#link_1,#link_2,#link_3").fadeOut(200);
		}
	});
	
	$("#create_survey").click(function(){
		$(".server_msg").hide();
		$.validate_Create_Survey_Fields();
	});
	//Scripts for Survey Page ENDS
	
	
	//Scripts for Vendor Page
	
	$("#add_vendor").click(function(){
		$(".server_msg").hide();
		$.validate_Add_Vendor_Form_Fields();
	});
	
	total_country_no=$("#country_selected_no").val();
	
	$("#add_new_country").click(function(){
		if(!confirm("Are you sure that you want to add new country?"))
		{
			return false;
		}
		total_country_no++;
		var html_select_country='<select name="country_'+total_country_no+'" id="country_'+total_country_no+'"><option value="">-- Select Country --</option><option value="Afghanistan">Afghanistan</option><option value="Aland Islands">Aland Islands</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option><option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Brazil">Brazil</option><option value="British Virgin Islands">British Virgin Islands</option><option value="Brunei">Brunei</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burma">Burma</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Caribbean Netherlands">Caribbean Netherlands</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option><option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Colombia">Colombia</option><option value="Comoros">Comoros</option><option value="Cook Islands">Cook Islands</option><option value="Costa Rica">Costa Rica</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Curaçao">Curaçao</option><option value="Cyprus">Cyprus</option><option value="Czech Republic">Czech Republic</option><option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option><option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option><option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands">Falkland Islands</option><option value="Faroe Islands">Faroe Islands</option><option value="Federated States of Micronesia">Federated States of Micronesia</option><option value="Fiji">Fiji</option><option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option><option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option><option value="Guatemala">Guatemala</option><option value="Guernsey">Guernsey</option><option value="Guinea">Guinea</option><option value="Guinea-Bissau">Guinea-Bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option><option value="Indonesia">Indonesia</option><option value="Iran">Iran</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Isle of Man">Isle of Man</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Ivory Coast">Ivory Coast</option><option value="Jamaica">Jamaica</option><option value="Japan">Japan</option><option value="Jersey">Jersey</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Kosovo">Kosovo</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Laos">Laos</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option><option value="Libya">Libya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macau">Macau</option><option value="Macedonia">Macedonia</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option><option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Moldova">Moldova</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montenegro">Montenegro</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option><option value="Netherlands">Netherlands</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="North Korea">North Korea</option><option value="Northern Cyprus">Northern Cyprus</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option><option value="Palau">Palau</option><option value="Palestine">Palestine</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option><option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn Islands">Pitcairn Islands</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Republic of the Congo">Republic of the Congo</option><option value="Réunion">Réunion</option><option value="Romania">Romania</option><option value="Russia">Russia</option><option value="Rwanda">Rwanda</option><option value="Saint Barthélemy">Saint Barthélemy</option><option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Martin">Saint Martin</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option><option value="São Tomé and Príncipe">São Tomé and Príncipe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia">Serbia</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Sint Maarten">Sint Maarten</option><option value="Slovakia">Slovakia</option><option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option><option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Korea">South Korea</option><option value="South Sudan">South Sudan</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option><option value="Suriname">Suriname</option><option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option><option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syria">Syria</option><option value="Taiwan">Taiwan</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania">Tanzania</option><option value="Thailand">Thailand</option><option value="Timor-Leste">Timor-Leste</option><option value="Togo">Togo</option><option value="Tokelau">Tokelau</option><option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option><option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option><option value="United States Virgin Islands">United States Virgin Islands</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Vatican City">Vatican City</option><option value="Venezuela">Venezuela</option><option value="Vietnam">Vietnam</option><option value="Wallis and Futuna">Wallis and Futuna</option><option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select>';
		
		var html_size_country='<input type="text" name="country_size_'+total_country_no+'" id="country_size_'+total_country_no+'" title="Enter Panel Size">';
		
		
		var final_html='<div class="row"><br><div class="col-xs-5">'+html_select_country+'</div><div class="col-xs-2">'+html_size_country+'</div><div class="col-xs-5"><img src="../assets/images/delete_icon.png" id="delete_country_'+total_country_no+'" class="delete_country" onclick="delete_Country_Detail(this);"></div></div>';
		
		$("#vendor_country_list").append(final_html);
		
		$("#country_selected_no").val(total_country_no);
	});
	
	
	
	//Scripts for Vendor Page ENDS
	
	//Scripts for Identifier Uploading Page
	
	$("#vendor_id").change(function(){
		if($(this).val()>0)
		{
			$("#redirection_link_container").fadeIn(100);
		}
		else
		{
			$("#redirection_link_container").fadeOut(100);
			$("#redirection_link").val('');
		}
	});
	
	$("#upload_identifier").click(function(){
		$(".server_msg").hide();
		$.validate_Upload_Identifiers_Form_Fields();
	});
	//Scripts for Identifier Uploading Page ENDS
	
	
	
	//Modify Survey Details Script
	
	$("#modify_survey").click(function(){
		$.validate_Form_Fields_Modify_Survey();
	});
	
	//Modify Survey Details Script ENDS
	
	
	
	
	/*Raise Invoice of the Survey Script*/
	
	$("#raise_invoice").click(function(){
		if(confirm("Are you sure to raise the invoice of the survey?"))
		{
			$.validate_Form_Raise_Invoice();
		}
		
	});
	/*Raise Invoice of the Survey Script ENDS*/
	
	
	
	
	/*Script for Setting the survey type*/
	$("input[type='radio'][name='set_survey_type']").click(function(){
		if($(this).val()=='single' && $(this).prop('checked'))
		{
			$("#multiple_link").val('');
			$("#link_2").fadeOut(200);
			$("#link_1").delay(250).fadeIn(200);			
		}
		else if($(this).val()=='multi')
		{
			$("#single_link").val('');
			$("#link_1").fadeOut(200);
			$("#link_2").delay(250).fadeIn(200);			
		}
		else
		{
			$("#multiple_link").val('');
			$("#single_link").val('');
			$("#link_1,#link_2").fadeOut(200);
		}
	});
	
	$("#set_survey_type_but").click(function(){
		$.validate_Set_Survey_Type_Form();
	});
	
	
	/*Script for Setting the survey type ENDS*/
	
	
	
	
	
	/*Script for Reporting Page*/
	
	$("#download_report").click(function(){
		$.validate_Report_Form_Data();
	});
	
	/*Script for Reporting Page ENDS*/
	
	
	
	
	/*Script For Modify Vendor Form*/
	$("#modify_vendor").click(function(){
		
		$.validate_Modify_Vendor_Form();
	});
	
	
	
	
	
	/*Script to Submit the Survey Form --- To check/uncheck ALLOW TRAFFIC checkbox --- SURVEY OPERATIONS PAGE*/
	$("input[type='checkbox'][name='allow_stop_traffic']").click(function(){
		var form=$(this).parent("form");
		$(".fade_background,.pop_up").show();
		if($(this).prop('checked'))
		{
			if(confirm('Are you sure to allow the traffic for this survey?'))
			{
				form.submit();
			}
			else
			{
				$(this).prop('checked',false);
				$(".fade_background,.pop_up").hide();
			}
		}
		else
		{
			if(confirm('Are you sure to dis-allow the traffic for this survey?'))
			{
				form.submit();
			}
			else
			{
				$(this).prop('checked',true);
				$(".fade_background,.pop_up").hide();
			}
		}
	});
	
	
	
	$("#change_password_but").click(function(){
		$.validate_Change_Password_Form();
	});
	
	
	
	
	/*Close Survey*/
	$("#close_survey_but").click(function(){
		if(confirm('Are you sure to close this survey?\n\nOnce closed, the survey won\'t be available!'))
		{
			document.close_survey_but.submit();
		}
		return false;		
	});
	
	
	
	
	/*Remove Vendor*/
	$(".remove_vendor").click(function(){
		var point_form=$(this).data('formtarget');
		if(confirm('Are you sure to remove this vendor?\n\nOnce removed, the vendor won\'t be available and repondent cannot take survey from the vendor\'s side!!'))
		{
			$(point_form).submit();
		}
		return false;
	});
	
	
	
	
	/*Exclude Vendor from survey*/
	/*Script to Exclude Vendor from survey --- View Detail Survey Page*/
	$("input[type='checkbox'][name='include_vendor']").click(function(){
		var form=$(this).parent("form");
		$(".fade_background,.pop_up").show();
		if($(this).prop('checked'))
		{
			if(confirm('Are you sure to allow links from this vendor?'))
			{
				$(this).val('1');
				form.submit();
			}
			else
			{
				$(this).prop('checked',false);
				$(".fade_background,.pop_up").hide();
			}
		}
		else
		{
			if(confirm('Are you sure not to allow links from this vendor?'))
			{
				$(this).val('0');
				form.submit();
			}
			else
			{
				$(this).prop('checked',true);
				$(".fade_background,.pop_up").hide();
			}
		}
	});
	
	
});


/*Function to validate USER Login Fields*/
jQuery.validate_Login_Details=function()
{
	var username=$("#username").val();
	var password=$("#password").val();
	
	if(whitespace.test(username))
	{
		$(".login_div .validations,.login_div .validations .error").fadeOut(200);
		$(".login_div .validations,.login_div .validations #err_username").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test(password) && password.length==0)
	{
		$(".login_div .validations,.login_div .validations .error").fadeOut(200);
		$(".login_div .validations,.login_div .validations #err_password").delay(200).fadeIn(200);
		return false;
	}
	$(".login_div .validations,.login_div .validations .error").fadeOut(200);
	$("form[name='login']").submit();
	return true;
};


/*Function to validate USER Sign Up Fields*/
jQuery.validate_Sign_In_Details=function()
{	
	if(whitespace.test($("#full_name").val()))
	{
		$(".register_div .validations,.register_div .validations .error").fadeOut(200);
		$(".register_div .validations,.register_div .validations #err_name").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#email").val()) || !reg_email.test($("#email").val()))
	{
		$(".register_div .validations,.register_div .validations .error").fadeOut(200);
		$(".register_div .validations,.register_div .validations #err_email").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#contact_no").val()) || !reg_contact_no.test($("#contact_no").val()))
	{
		$(".register_div .validations,.register_div .validations .error").fadeOut(200);
		$(".register_div .validations,.register_div .validations #err_contact_no").delay(200).fadeIn(200);
		return false;
	}
	else if(!reg_username.test($("#sign_in_username").val()))
	{
		$(".register_div .validations,.register_div .validations .error").fadeOut(200);
		$(".register_div .validations,.register_div .validations #err_username").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#sign_in_password").val()) && $("#sign_in_password").val().length<5)
	{
		$(".register_div .validations,.register_div .validations .error").fadeOut(200);
		$(".register_div .validations,.register_div .validations #err_password").delay(200).fadeIn(200);
		return false;
	}
	else if($("#sign_in_confirm_pass").val()!=$("#sign_in_password").val())
	{
		$(".register_div .validations,.register_div .validations .error").fadeOut(200);
		$(".register_div .validations,.register_div .validations #err_confirm_pass").delay(200).fadeIn(200);
		return false;
	}
	$(".register_div .validations,.validations .error").fadeOut(200);
	
	$("form[name='sign_up']").submit();
	return true;
};


//Function to validate the form fields while creating survey
jQuery.validate_Create_Survey_Fields=function(){
	
	if(whitespace.test($("#client_name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_client").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#survey_name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#country").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_country").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#survey_description").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_description").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#survey_manager").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_manager").delay(200).fadeIn(200);
		return false;
	}
	else if(($("input[type='radio'][name='survey_type']:checked")).length==0)
	{		
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_link_type").delay(200).fadeIn(200);
		return false;
	}
	
	else if($("input[type='radio'][name='survey_type']:checked").length > 0 && $("input[type='radio'][name='survey_type']:checked").val()=='single')
	{
		if(whitespace.test($("#single_link").val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link").delay(200).fadeIn(200);
			return false;
		}
		else if(!reg_url.test($("#single_link").val()) || check_IDENTIFIER_Parmeter($("#single_link").val())==0)
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link_format").delay(200).fadeIn(200);
			return false;
		}
		
	}
	else if($("input[type='radio'][name='survey_type']:checked").length > 0 && $("input[type='radio'][name='survey_type']:checked").val()=='multi')
	{
		
		if(whitespace.test($("#multiple_link").val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link").delay(200).fadeIn(200);
			return false;
		}
	}
	else if($("input[type='radio'][name='survey_type']:checked").length > 0 && $("input[type='radio'][name='survey_type']:checked").val()=='re_contact')
	{

		if(whitespace.test($("#re_contact_file").val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link").delay(200).fadeIn(200);
			return false;
		}
	}


	if(whitespace.test($("#survey_quota").val()) || !reg_numeric.test($("#survey_quota").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_quota").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#survey_respondent_click_quota").val()) || !reg_numeric.test($("#survey_respondent_click_quota").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_click_quota").delay(200).fadeIn(200);
		return false;
	}

	/*
	 Validating Survey Filters
	 */
	//Validating Country Filter
	if($($("input[type='checkbox'][name='country_filter']")[0]).prop('checked'))
	{
		if(!($($("select[name='country_ip_filter_countries[]']")[0]).val() instanceof Array))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_country_filter").delay(200).fadeIn(200);
			return false;
		}
	}
	//Validating Duplicate IP Filter
	if($($("input[type='checkbox'][name='duplicate_ip']")[0]).prop('checked'))
	{
		console.log("Whitespace",whitespace.test($($("select[name='duplicate_ip_limit']")[0]).val()));
		console.log("Numeric",!reg_numeric.test($($("select[name='duplicate_ip_limit']")[0]).val()));
		if(whitespace.test($($("input[name='duplicate_ip_limit']")[0]).val()) || !reg_numeric.test($($("input[name='duplicate_ip_limit']")[0]).val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_duplicate_ip_filter").delay(200).fadeIn(200);
			return false;
		}
	}
	
	$(".validations,.validations .error").fadeOut(200);
	$("form[name='create_survey']").submit();
	return true;
};


//Function to validate the form fields for adding vendor
jQuery.validate_Add_Vendor_Form_Fields=function()
{
	
	if(whitespace.test($("#vendor_name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_name").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_site").val()) || !reg_vendor_url.test($("#vendor_site").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_website_link").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_contact_no").val()) || !reg_contact_no.test($("#vendor_contact_no").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_contact_no").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_email").val()) || !reg_email.test($("#vendor_email").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_email").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_panel_size").val()) || !reg_numeric.test($("#vendor_panel_size").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_panel_size").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_description").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_description").delay(200).fadeIn(200);
		return false;
	}

	if($("#country_selected_no").val()>0)
	{
		for(i=0;i<$("#country_selected_no").val();i++)
		{
			var id_1="#country_"+(i+1);
			var id_2="#country_size_"+(i+1);

			if(whitespace.test($(id_1).val()) || whitespace.test($(id_2).val()))
			{
				$(".validations,.validations .error").fadeOut(200);
				$(".validations,.validations #err_vendor_country").delay(200).fadeIn(200);
				return false;
			}
			else if(!reg_numeric.test($(id_2).val()))
			{
				$(".validations,.validations .error").fadeOut(200);
				$(".validations,.validations #err_vendor_country_panel_size").delay(200).fadeIn(200);
				$(id_2).focus();
				return false;				
			}
		}
		
	}
	
	if(whitespace.test($("#redirect_complete").val()) || !reg_url.test($("#redirect_complete").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_redirection_links").delay(200).fadeIn(200);
		return false;
	}
	
	if(whitespace.test($("#redirect_terminate").val()) || !reg_url.test($("#redirect_terminate").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_redirection_links").delay(200).fadeIn(200);
		return false;
	}
	
	if(whitespace.test($("#redirect_quotafull").val()) || !reg_url.test($("#redirect_quotafull").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_redirection_links").delay(200).fadeIn(200);
		return false;
	}
	
	$(".validations,.validations .error").fadeOut(200);
	$("form[name='add_vendor']").submit();
	return true;
};

//Function To delete Country Detail when clicked on Delete icon
var delete_Country_Detail=function(e){
		if(!confirm("Are you sure that you want to delete this country?"))
		{
			return false;
		}
		var id=($(e).attr('id')).split("_");
		id=id[2];
		var parent=$("#country_"+id).parent().parent();

		$(parent).remove();
		
		var select_ar=$("#vendor_country_list select");
		var text_box_ar=$("#vendor_country_list input[type='text']");
		var delete_img_ar=$("#vendor_country_list .delete_country");
		var count=1;
		
		$(select_ar).each(function(i,e){
			$(e).attr('id',"country_"+count);
			$(e).attr('name',"country_"+count);
			count++;
		});
		count=1;
		$(text_box_ar).each(function(i,e){
			$(e).attr('id',"country_size_"+count);
			$(e).attr('name',"country_size_"+count);
			count++;
		});
		count=2;
		$(delete_img_ar).each(function(i,e){
			$(e).attr('id',"delete_country_"+count);
			count++;
		});
		total_country_no--;
		
		$("#country_selected_no").val(total_country_no);
		
	};




//Function to validate the form fields for Uploading of Identifiers
jQuery.validate_Upload_Identifiers_Form_Fields=function(){
	if(whitespace.test($("#survey_id").val()) || $("#survey_id").val()=='')
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_identifier_survey").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_id").val()) || $("#vendor_id").val()=='')
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_identifier_vendor").delay(200).fadeIn(200);
		return false;
	}
	else if($("#vendor_id").val()>0)
	{
		if((whitespace.test($("#redirection_link_complete").val()) || !reg_url.test($("#redirection_link_complete").val()) || ($("#redirection_link_complete").val()).search("TRAVERSER_IDENTIFIER")<0) || (whitespace.test($("#redirection_link_screened").val()) || !reg_url.test($("#redirection_link_screened").val()) || ($("#redirection_link_screened").val()).search("TRAVERSER_IDENTIFIER")<0) || (whitespace.test($("#redirection_link_quotafull").val()) || !reg_url.test($("#redirection_link_quotafull").val()) || ($("#redirection_link_quotafull").val()).search("TRAVERSER_IDENTIFIER")<0))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_identifier_redirection_link").delay(200).fadeIn(200);
			return false;
		}
	}
	if(whitespace.test($("#identifier_file").val()) || $("#identifier_file").val()=='')
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_identifier_file").delay(200).fadeIn(200);
		return false;
	}
	
	$(".validations,.validations .error").fadeOut(200);
	$("form[name='upload_identifiers']").submit();
	return true;
};








//Function To modify details of a Survey
jQuery.validate_Form_Fields_Modify_Survey=function()
{
	
	if(whitespace.test($("#m_client_name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_client").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#m_survey_name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#m_country").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_country").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#m_survey_description").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_description").delay(200).fadeIn(200);
		return false;
	}
	else if(($("input[type='radio'][name='m_survey_type']:checked")).length==0)
	{		
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_link_type").delay(200).fadeIn(200);
		return false;
	}
	else if($("#m_survey_type_hidden").val()=='single')
	{
		if(whitespace.test($("#m_single_link").val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link").delay(200).fadeIn(200);
			return false;
		}
		else if(!reg_url.test($("#m_single_link").val()) || check_IDENTIFIER_Parmeter($("#m_single_link").val())==0)
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link_format").delay(200).fadeIn(200);
			return false;
		}
		
	}
	
	if(whitespace.test($("#survey_quota").val()) || !reg_numeric.test($("#survey_quota").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_quota").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#survey_respondent_click_quota").val()) || !reg_numeric.test($("#survey_respondent_click_quota").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_click_quota").delay(200).fadeIn(200);
		return false;
	}

	/*
	 Validating Survey Filters
	 */
	//Validating Duplicate IP Filter
	if($($("input[type='checkbox'][name='country_filter']")[0]).prop('checked'))
	{
		if(!($($("select[name='country_ip_filter_countries[]']")[0]).val() instanceof Array))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_country_filter").delay(200).fadeIn(200);
			return false;
		}
	}
	//Validating Duplicate IP Filter
	if($($("input[type='checkbox'][name='duplicate_ip']")[0]).prop('checked'))
	{
		console.log("Whitespace",whitespace.test($($("select[name='duplicate_ip_limit']")[0]).val()));
		console.log("Numeric",!reg_numeric.test($($("select[name='duplicate_ip_limit']")[0]).val()));
		if(whitespace.test($($("input[name='duplicate_ip_limit']")[0]).val()) || !reg_numeric.test($($("input[name='duplicate_ip_limit']")[0]).val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_duplicate_ip_filter").delay(200).fadeIn(200);
			return false;
		}
	}


	$(".validations,.validations .error").fadeOut(200);
	$("form[name='modify_survey']").submit();
	return true;
};




var filter_Survey_Form=function(){
	if(whitespace.test($("#manager_name_filter").val()) && whitespace.test($("#survey_status").val()))
	{
		alert("Please select a manager or survey status field.\n\nThen click on Go");	
		return false;
	}

	$("form[name='filter_survey_form']").submit();
	return true;
};



//Form for validating before raising Invoice of the survey
jQuery.validate_Form_Raise_Invoice=function(){
	if(whitespace.test($("#raise_invoice_file").val()) && whitespace.test($("#raise_invoice_comments").val()))
	{
		alert("Please either upload a file or enter your comments!");
		return false;
	}
	$("form[name='update_survey_staus']").submit();
};




/*Form to Validate the form for setting the survey Type*/
jQuery.validate_Set_Survey_Type_Form=function(){
	
	if(($("input[type='radio'][name='set_survey_type']:checked")).length==0)
	{		
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey_link_type").delay(200).fadeIn(200);
		return false;
	}
	else if($("input[type='radio'][name='set_survey_type']:checked").length > 0 && $("input[type='radio'][name='set_survey_type']:checked").val()=='single')
	{
		if(whitespace.test($("#single_link").val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link").delay(200).fadeIn(200);
			return false;
		}
		else if(!reg_url.test($("#single_link").val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link_format").delay(200).fadeIn(200);
			return false;
		}
		
	}
	else if($("input[type='radio'][name='set_survey_type']:checked").length > 0 && $("input[type='radio'][name='set_survey_type']:checked").val()=='multi')
	{
		
		if(whitespace.test($("#multiple_link").val()))
		{
			$(".validations,.validations .error").fadeOut(200);
			$(".validations,.validations #err_survey_link_file").delay(200).fadeIn(200);
			return false;
		}
	}
	
	$(".validations,.validations .error").fadeOut(200);
	$("form[name='set_survey_type']").submit();
	return true;
};





/*Function to validate report form data*/
jQuery.validate_Report_Form_Data=function()
{
	if(whitespace.test($("#select_survey").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_survey").delay(200).fadeIn(200);
		return false;
	}
	/*if(($("input[type='radio'][name='report_type']:checked")).length==0)
	{		
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_report_type").delay(200).fadeIn(200);
		return false;
	}*/
	$(".validations,.validations .error").fadeOut(200);
	$("form[name='get_report']").submit();
	return true;
};




/*Function to validate Vendor Modification Form*/
jQuery.validate_Modify_Vendor_Form=function(){
	if(whitespace.test($("#vendor_name").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_name").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_site").val()) || !reg_url.test($("#vendor_site").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_website_link").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_contact_no").val()) || !reg_contact_no.test($("#vendor_contact_no").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_contact_no").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_email").val()) || !reg_email.test($("#vendor_email").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_email").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_panel_size").val()) || !reg_numeric.test($("#vendor_panel_size").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_panel_size").delay(200).fadeIn(200);
		return false;
	}
	else if(whitespace.test($("#vendor_description").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_description").delay(200).fadeIn(200);
		return false;
	}

	if($("#country_selected_no").val()>0)
	{
		for(i=0;i<$("#country_selected_no").val();i++)
		{
			var id_1="#country_"+(i+1);
			var id_2="#country_size_"+(i+1);

			if(whitespace.test($(id_1).val()) || whitespace.test($(id_2).val()))
			{
				$(".validations,.validations .error").fadeOut(200);
				$(".validations,.validations #err_vendor_country").delay(200).fadeIn(200);
				return false;
			}
			else if(!reg_numeric.test($(id_2).val()))
			{
				$(".validations,.validations .error").fadeOut(200);
				$(".validations,.validations #err_vendor_country_panel_size").delay(200).fadeIn(200);
				$(id_2).focus();
				return false;				
			}
		}
		
	}
	
	if(whitespace.test($("#redirect_complete").val()) || !reg_url.test($("#redirect_complete").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_redirection_links").delay(200).fadeIn(200);
		return false;
	}
	
	if(whitespace.test($("#redirect_terminate").val()) || !reg_url.test($("#redirect_terminate").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_redirection_links").delay(200).fadeIn(200);
		return false;
	}
	
	if(whitespace.test($("#redirect_quotafull").val()) || !reg_url.test($("#redirect_quotafull").val()))
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_vendor_redirection_links").delay(200).fadeIn(200);
		return false;
	}
	
	$(".validations,.validations .error").fadeOut(200);
	$("form[name='modify_vendor']").submit();
	return true;
};




/*Function to validate New Password of user*/
jQuery.validate_Change_Password_Form=function(){
	if(whitespace.test($("#new_password").val()) || $("#new_password").val().length<6)
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_new_password").delay(200).fadeIn(200);
		return false;
	}
	if($("#confirm_password").val()!=$("#new_password").val())
	{
		$(".validations,.validations .error").fadeOut(200);
		$(".validations,.validations #err_confirm_pass").delay(200).fadeIn(200);
		return false;
	}
	document.change_password_form.submit();
};






var check_IDENTIFIER_Parmeter=function(url){
	
  var vars = [], hash;
  var hashes = url.slice(url.indexOf('?') + 1).split('&');
  var flag=0;
  /*for(var i = 0; i < hashes.length; i++)
  {
      hash = hashes[i].split('=');
	  if(hash[1]=="[IDENTIFIER]")
	  {
		  flag=1;
		  break;
		}
  }*/
  if(url.search("IDENTIFIER")>=0)
  {
	  flag=1;
	}
  return flag;
};