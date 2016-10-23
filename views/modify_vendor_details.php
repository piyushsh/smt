<?php
define("CONTROLLER_PATH","../controller/");
define("MODEL_PATH","../models/");
define("VIEW_PATH","../views/");
define("ASSETS_PATH","../assets/");
define("INCLUDES_PATH","../includes/");
define("PLUGIN_PATH","../plugin/");

$active_menu=3;

include_once(INCLUDES_PATH."basic_config_site.php");
include_once(CONTROLLER_PATH."get_data/vendor_operations_read.php");

$vendor_id=0;
if(isset($_REQUEST["vendor_id"]))
{
	$vendor_id=$_REQUEST["vendor_id"];
}

$vendor_data=new Vendor_Data_Read();
$vender_data=$vendor_data->get_Vendor_Details_by_ID($vendor_id);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Survey Management Tool -- Vendor Modify Details</title>
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/bootstrap.css">
<link rel="stylesheet" href="<?php echo ASSETS_PATH;?>css/style.css">

<script src="<?php echo ASSETS_PATH;?>script/jquery_1.11.js"></script>
<script src="<?php echo ASSETS_PATH;?>script/bootstrap.js"></script>

<script src="<?php echo ASSETS_PATH;?>script/config_scripts.js"></script>

<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<?php include_once(INCLUDES_PATH."header.php");?>
<?php include_once(INCLUDES_PATH."main_menu.php");?>

<div class="main_container">
	<div class="container">
    	<div class="row">
            <div class="col-xs-12">
                    <h5>Vendors Details</h5>
                    <br>
                    <div class="server_msg text-align-left">
						<?php
                        if(isset($_REQUEST))
                        {
                            if(isset($_REQUEST["vendor_modify_result"]) && $_REQUEST["vendor_modify_result"]=="sucess")
                            {
                                echo "<p class='sucess'>Vendor data has been updated.</p>";
                            }
							else if(isset($_REQUEST["vendor_modify_result"]) && $_REQUEST["vendor_modify_result"]=="error")
							{
								echo "<p class='error'>Some error occured while updating the vendor.</p>";
							}
							else if(isset($_REQUEST["vendor_modify_err"]))
							{
								switch($_REQUEST["vendor_modify_err"])
								{
									case 'ERR_VENDOR_NAME_NO_VALUE':	echo "<p class='error'>Please provide some vendor Name!</p>";
														break;
									case 'ERR_VENDOR_WEBSITE_IN_VALID':	echo "<p class='error'>Please provide the vendor website in correct format!</p>";
														break;
									case 'ERR_VENDOR_CONTACT_NO_VALUE':	echo "<p class='error'>Please enter a valid contact number.</p>";
														break;
									case 'ERR_VENDOR_EMAIL_IN_VALID':	echo "<p class='error'>Email format is not correct. Please enter a valid email address.</p>";
														break;
									case 'ERR_VENDOR_DESCRIPTION_NO_VALUE':	echo "<p class='error'>Please provide some vendor description.</p>";
														break;
									case 'ERR_VENDOR_PANEL_SIZE_NO_VALUE':	echo "<p class='error'>Please enter the panel size of the vendor!</p>";
														break;
									case 'ERR_VENDOR_COUNTRY_NO_VALUE':		echo "<p class='error'>Country and corresponding panel is not specified!</p>";
														break;
								}
							}
                        }
                        ?>
                    </div>
                    <form action="<?php echo CONTROLLER_PATH."set_data/vendor_operations.php";?>" method="post" name="modify_vendor">
                    
                    <input type="hidden" name="modify_vendor" value="1">
                    <input type="hidden" name="vendor_id" value="<?php echo $vendor_id;?>">
                    
                    <div class="validations text-align-left">                    	
                        <p class="error" id="err_vendor_name">Please provide the Vendor Name!</p>
                        <p class="error" id="err_vendor_website_link">Please provide correct website link of vendor!</p>
                        <p class="error" id="err_vendor_contact_no">Please provide a valid contact number!</p>
                        <p class="error" id="err_vendor_email">Please provide a valid email address!</p>                        
                        <p class="error" id="err_vendor_panel_size">Please provide a numeric value for approx panel size!</p>
                        <p class="error" id="err_vendor_description">Please provide some description about vendor!</p>
                        <p class="error" id="err_vendor_country">Please select a country and corresponding panel size!</p>
                        <p class="error" id="err_vendor_country_panel_size">Please enter numeric value for country panel!</p>
                        <p class="error" id="err_vendor_redirection_links">Please provide a valid vendor redirection links!</p>
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                                <div class="label">Vendor Name<span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="vendor_name" id="vendor_name" value="<?php echo $vender_data["vendor_name"];?>"></div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                            	<div class="label">Vendor Website<span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="vendor_site" id="vendor_site" value="<?php echo $vender_data["vendor_website"];?>"><br>
                                <span class="instruction_note">Link eg. https://www.google.co.in/</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xs-2 col-xs-offset-1">
                            <div class="form_field">
                                <div class="label">Contact Number<span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="vendor_contact_no" id="vendor_contact_no" value="<?php echo $vender_data["vendor_contact_no"];?>"></div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                            	<div class="label">Email ID<span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="vendor_email" id="vendor_email"  value="<?php echo $vender_data["vendor_email_id"];?>"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-2">
                            <div class="form_field">
                                <div class="label">Approx. Panel Size<span class="mandatory">*</span></div>
                                <div class="input_field"><input type="text" name="vendor_panel_size" id="vendor_panel_size"  value="<?php echo $vender_data["vendor_panel_size"];?>"></div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form_field">
                                <div class="label">About Vendor<span class="mandatory">*</span></div>
                                <div class="input_field"><textarea name="vendor_description" id="vendor_description"><?php echo $vender_data["vendor_description"];?></textarea></div>
                            </div>
                        </div>
                        
                        <div class="col-xs-4 col-xs-offset-1">
                            <div class="form_field">
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form_field">
                                <div class="label">Countries Covered<span class="mandatory">*</span></div>
                                
                                
                                
                                
                                <div class="row"> 
                                	<div class="col-xs-6">
                                    	<div id="vendor_country_list">
                                        		<?php
												$vendor_countries=explode(';',$vender_data["vendor_country"]);
												$vendor_country_count=count($vendor_countries);
												$vendor_countries_panel=array();
												for($i=0;$i<$vendor_country_count;$i++)
												{
													$temp=explode('#',$vendor_countries[$i]);
													$vendor_countries_panel[$i]["country"]=trim($temp[0]);
													$vendor_countries_panel[$i]["number"]=trim($temp[1]);
												}
												?>
                                        		<input type="hidden" value="<?php echo $vendor_country_count;?>" name="country_selected_no" id="country_selected_no">
                                        		<img src="../assets/images/plus_icon.png" id="add_new_country" title="Add New Country"><br><br>
                                                
												<?php 
												
												for($i=0;$i<$vendor_country_count;$i++)
												{
													
												?>
                                                	<script>
														$(document).ready(function(){
															var select_field=$("#country_<?php echo ($i+1);?>");
															$(select_field).val(<?php echo "'".$vendor_countries_panel[$i]["country"]."'";?>);
														});
													</script>
                                                    <div class="row">
                                                    	<?php 
															if($i>0)
															{
																echo "<br>";
															}
														?>
                                                        <div class="col-xs-5">
                                                        	<select name="<?php echo "country_".($i+1); ?>" id="<?php echo "country_".($i+1); ?>">
                                                                <option value="">-- Select Country --</option>
                                                            	<option value="Afghanistan">Afghanistan</option>
                                                                <option value="Aland Islands">Aland Islands</option>
                                                                <option value="Albania">Albania</option>
                                                                <option value="Algeria">Algeria</option>
                                                                <option value="American Samoa">American Samoa</option>
                                                                <option value="Andorra">Andorra</option>
                                                                <option value="Angola">Angola</option>
                                                                <option value="Anguilla">Anguilla</option>
                                                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                                                <option value="Argentina">Argentina</option>
                                                                <option value="Armenia">Armenia</option>
                                                                <option value="Aruba">Aruba</option>
                                                                <option value="Australia">Australia</option>
                                                                <option value="Austria">Austria</option>
                                                                <option value="Azerbaijan">Azerbaijan</option>
                                                                <option value="Bahamas">Bahamas</option>
                                                                <option value="Bahrain">Bahrain</option>
                                                                <option value="Bangladesh">Bangladesh</option>
                                                                <option value="Barbados">Barbados</option>
                                                                <option value="Belarus">Belarus</option>
                                                                <option value="Belgium">Belgium</option>
                                                                <option value="Belize">Belize</option>
                                                                <option value="Benin">Benin</option>
                                                                <option value="Bermuda">Bermuda</option>
                                                                <option value="Bhutan">Bhutan</option>
                                                                <option value="Bolivia">Bolivia</option>
                                                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                                                <option value="Botswana">Botswana</option>
                                                                <option value="Brazil">Brazil</option>
                                                                <option value="British Virgin Islands">British Virgin Islands</option>
                                                                <option value="Brunei">Brunei</option>
                                                                <option value="Bulgaria">Bulgaria</option>
                                                                <option value="Burkina Faso">Burkina Faso</option>
                                                                <option value="Burma">Burma</option>
                                                                <option value="Burundi">Burundi</option>
                                                                <option value="Cambodia">Cambodia</option>
                                                                <option value="Cameroon">Cameroon</option>
                                                                <option value="Canada">Canada</option>
                                                                <option value="Cape Verde">Cape Verde</option>
                                                                <option value="Caribbean Netherlands">Caribbean Netherlands</option>
                                                                <option value="Cayman Islands">Cayman Islands</option>
                                                                <option value="Central African Republic">Central African Republic</option>
                                                                <option value="Chad">Chad</option>
                                                                <option value="Chile">Chile</option>
                                                                <option value="China">China</option>
                                                                <option value="Christmas Island">Christmas Island</option>
                                                                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                                                <option value="Colombia">Colombia</option>
                                                                <option value="Comoros">Comoros</option>
                                                                <option value="Cook Islands">Cook Islands</option>
                                                                <option value="Costa Rica">Costa Rica</option>
                                                                <option value="Croatia">Croatia</option>
                                                                <option value="Cuba">Cuba</option>
                                                                <option value="Curaçao">Curaçao</option>
                                                                <option value="Cyprus">Cyprus</option>
                                                                <option value="Czech Republic">Czech Republic</option>
                                                                <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
                                                                <option value="Denmark">Denmark</option>
                                                                <option value="Djibouti">Djibouti</option>
                                                                <option value="Dominica">Dominica</option>
                                                                <option value="Dominican Republic">Dominican Republic</option>
                                                                <option value="Ecuador">Ecuador</option>
                                                                <option value="Egypt">Egypt</option>
                                                                <option value="El Salvador">El Salvador</option>
                                                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                                                <option value="Eritrea">Eritrea</option>
                                                                <option value="Estonia">Estonia</option>
                                                                <option value="Ethiopia">Ethiopia</option>
                                                                <option value="Falkland Islands">Falkland Islands</option>
                                                                <option value="Faroe Islands">Faroe Islands</option>
                                                                <option value="Federated States of Micronesia">Federated States of Micronesia</option>
                                                                <option value="Fiji">Fiji</option>
                                                                <option value="Finland">Finland</option>
                                                                <option value="France">France</option>
                                                                <option value="French Guiana">French Guiana</option>
                                                                <option value="French Polynesia">French Polynesia</option>
                                                                <option value="Gabon">Gabon</option>
                                                                <option value="Gambia">Gambia</option>
                                                                <option value="Georgia">Georgia</option>
                                                                <option value="Germany">Germany</option>
                                                                <option value="Ghana">Ghana</option>
                                                                <option value="Gibraltar">Gibraltar</option>
                                                                <option value="Greece">Greece</option>
                                                                <option value="Greenland">Greenland</option>
                                                                <option value="Grenada">Grenada</option>
                                                                <option value="Guadeloupe">Guadeloupe</option>
                                                                <option value="Guam">Guam</option>
                                                                <option value="Guatemala">Guatemala</option>
                                                                <option value="Guernsey">Guernsey</option>
                                                                <option value="Guinea">Guinea</option>
                                                                <option value="Guinea-Bissau">Guinea-Bissau</option>
                                                                <option value="Guyana">Guyana</option>
                                                                <option value="Haiti">Haiti</option>
                                                                <option value="Honduras">Honduras</option>
                                                                <option value="Hong Kong">Hong Kong</option>
                                                                <option value="Hungary">Hungary</option>
                                                                <option value="Iceland">Iceland</option>
                                                                <option value="India">India</option>
                                                                <option value="Indonesia">Indonesia</option>
                                                                <option value="Iran">Iran</option>
                                                                <option value="Iraq">Iraq</option>
                                                                <option value="Ireland">Ireland</option>
                                                                <option value="Isle of Man">Isle of Man</option>
                                                                <option value="Israel">Israel</option>
                                                                <option value="Italy">Italy</option>
                                                                <option value="Ivory Coast">Ivory Coast</option>
                                                                <option value="Jamaica">Jamaica</option>
                                                                <option value="Japan">Japan</option>
                                                                <option value="Jersey">Jersey</option>
                                                                <option value="Jordan">Jordan</option>
                                                                <option value="Kazakhstan">Kazakhstan</option>
                                                                <option value="Kenya">Kenya</option>
                                                                <option value="Kiribati">Kiribati</option>
                                                                <option value="Kosovo">Kosovo</option>
                                                                <option value="Kuwait">Kuwait</option>
                                                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                                                <option value="Laos">Laos</option>
                                                                <option value="Latvia">Latvia</option>
                                                                <option value="Lebanon">Lebanon</option>
                                                                <option value="Lesotho">Lesotho</option>
                                                                <option value="Liberia">Liberia</option>
                                                                <option value="Libya">Libya</option>
                                                                <option value="Liechtenstein">Liechtenstein</option>
                                                                <option value="Lithuania">Lithuania</option>
                                                                <option value="Luxembourg">Luxembourg</option>
                                                                <option value="Macau">Macau</option>
                                                                <option value="Macedonia">Macedonia</option>
                                                                <option value="Madagascar">Madagascar</option>
                                                                <option value="Malawi">Malawi</option>
                                                                <option value="Malaysia">Malaysia</option>
                                                                <option value="Maldives">Maldives</option>
                                                                <option value="Mali">Mali</option>
                                                                <option value="Malta">Malta</option>
                                                                <option value="Marshall Islands">Marshall Islands</option>
                                                                <option value="Martinique">Martinique</option>
                                                                <option value="Mauritania">Mauritania</option>
                                                                <option value="Mauritius">Mauritius</option>
                                                                <option value="Mayotte">Mayotte</option>
                                                                <option value="Mexico">Mexico</option>
                                                                <option value="Moldova">Moldova</option>
                                                                <option value="Monaco">Monaco</option>
                                                                <option value="Mongolia">Mongolia</option>
                                                                <option value="Montenegro">Montenegro</option>
                                                                <option value="Montserrat">Montserrat</option>
                                                                <option value="Morocco">Morocco</option>
                                                                <option value="Mozambique">Mozambique</option>
                                                                <option value="Namibia">Namibia</option>
                                                                <option value="Nauru">Nauru</option>
                                                                <option value="Nepal">Nepal</option>
                                                                <option value="Netherlands">Netherlands</option>
                                                                <option value="New Caledonia">New Caledonia</option>
                                                                <option value="New Zealand">New Zealand</option>
                                                                <option value="Nicaragua">Nicaragua</option>
                                                                <option value="Niger">Niger</option>
                                                                <option value="Nigeria">Nigeria</option>
                                                                <option value="Niue">Niue</option>
                                                                <option value="Norfolk Island">Norfolk Island</option>
                                                                <option value="North Korea">North Korea</option>
                                                                <option value="Northern Cyprus">Northern Cyprus</option>
                                                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                                                <option value="Norway">Norway</option>
                                                                <option value="Oman">Oman</option>
                                                                <option value="Pakistan">Pakistan</option>
                                                                <option value="Palau">Palau</option>
                                                                <option value="Palestine">Palestine</option>
                                                                <option value="Panama">Panama</option>
                                                                <option value="Papua New Guinea">Papua New Guinea</option>
                                                                <option value="Paraguay">Paraguay</option>
                                                                <option value="Peru">Peru</option>
                                                                <option value="Philippines">Philippines</option>
                                                                <option value="Pitcairn Islands">Pitcairn Islands</option>
                                                                <option value="Poland">Poland</option>
                                                                <option value="Portugal">Portugal</option>
                                                                <option value="Puerto Rico">Puerto Rico</option>
                                                                <option value="Qatar">Qatar</option>
                                                                <option value="Republic of the Congo">Republic of the Congo</option>
                                                                <option value="Réunion">Réunion</option>
                                                                <option value="Romania">Romania</option>
                                                                <option value="Russia">Russia</option>
                                                                <option value="Rwanda">Rwanda</option>
                                                                <option value="Saint Barthélemy">Saint Barthélemy</option>
                                                                <option value="Saint Helena, Ascension and Tristan da Cunha">Saint Helena, Ascension and Tristan da Cunha</option>
                                                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                                                <option value="Saint Lucia">Saint Lucia</option>
                                                                <option value="Saint Martin">Saint Martin</option>
                                                                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                                                <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                                                <option value="Samoa">Samoa</option>
                                                                <option value="San Marino">San Marino</option>
                                                                <option value="São Tomé and Príncipe">São Tomé and Príncipe</option>
                                                                <option value="Saudi Arabia">Saudi Arabia</option>
                                                                <option value="Senegal">Senegal</option>
                                                                <option value="Serbia">Serbia</option>
                                                                <option value="Seychelles">Seychelles</option>
                                                                <option value="Sierra Leone">Sierra Leone</option>
                                                                <option value="Singapore">Singapore</option>
                                                                <option value="Sint Maarten">Sint Maarten</option>
                                                                <option value="Slovakia">Slovakia</option>
                                                                <option value="Slovenia">Slovenia</option>
                                                                <option value="Solomon Islands">Solomon Islands</option>
                                                                <option value="Somalia">Somalia</option>
                                                                <option value="South Africa">South Africa</option>
                                                                <option value="South Korea">South Korea</option>
                                                                <option value="South Sudan">South Sudan</option>
                                                                <option value="Spain">Spain</option>
                                                                <option value="Sri Lanka">Sri Lanka</option>
                                                                <option value="Sudan">Sudan</option>
                                                                <option value="Suriname">Suriname</option>
                                                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                                                <option value="Swaziland">Swaziland</option>
                                                                <option value="Sweden">Sweden</option>
                                                                <option value="Switzerland">Switzerland</option>
                                                                <option value="Syria">Syria</option>
                                                                <option value="Taiwan">Taiwan</option>
                                                                <option value="Tajikistan">Tajikistan</option>
                                                                <option value="Tanzania">Tanzania</option>
                                                                <option value="Thailand">Thailand</option>
                                                                <option value="Timor-Leste">Timor-Leste</option>
                                                                <option value="Togo">Togo</option>
                                                                <option value="Tokelau">Tokelau</option>
                                                                <option value="Tonga">Tonga</option>
                                                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                                                <option value="Tunisia">Tunisia</option>
                                                                <option value="Turkey">Turkey</option>
                                                                <option value="Turkmenistan">Turkmenistan</option>
                                                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                                                <option value="Tuvalu">Tuvalu</option>
                                                                <option value="Uganda">Uganda</option>
                                                                <option value="Ukraine">Ukraine</option>
                                                                <option value="United Arab Emirates">United Arab Emirates</option>
                                                                <option value="United Kingdom">United Kingdom</option>
                                                                <option value="United States">United States</option>
                                                                <option value="United States Virgin Islands">United States Virgin Islands</option>
                                                                <option value="Uruguay">Uruguay</option>
                                                                <option value="Uzbekistan">Uzbekistan</option>
                                                                <option value="Vanuatu">Vanuatu</option>
                                                                <option value="Vatican City">Vatican City</option>
                                                                <option value="Venezuela">Venezuela</option>
                                                                <option value="Vietnam">Vietnam</option>
                                                                <option value="Wallis and Futuna">Wallis and Futuna</option>
                                                                <option value="Western Sahara">Western Sahara</option>
                                                                <option value="Yemen">Yemen</option>
                                                                <option value="Zambia">Zambia</option>
                                                                <option value="Zimbabwe">Zimbabwe</option>
	                    
                                                            </select>
                                                        </div>
                                                        <div class="col-xs-2">                                                        
                                                            <input type="text" name="country_size_<?php echo ($i+1);?>" id="country_size_<?php echo ($i+1);?>" title="Enter Panel Size" value="<?php echo $vendor_countries_panel[$i]["number"];?>">
                                                        </div>
                                                        <div class="col-xs-5">
                                                        	<?php 
																if($i>0)
																{
																	echo '<img src="'.ASSETS_PATH.'images/delete_icon.png" id="delete_country_'.($i+1).'" class="delete_country" onclick="delete_Country_Detail(this);">';
																}
															?>
                                                        </div>
                                                   </div>
                                               <?php
												}
											   ?>
                                        </div>

                                    </div>
                                    
                                    
                                
                                </div>
                                
                                
                            </div>                        
                        </div>
                    </div>
                    
                    
                    <br>
                    <h6>Vendor Redirection Links</h6>
                    <div class="row">
                            <div class="col-xs-4">
                                <div class="form_field">
                                    <div class="label">Completes<span class="mandatory">*</span></div>
                                    <div class="input_field"><input type="text" name="redirect_complete" id="redirect_complete"  value="<?php echo $vender_data["redirect_complete"];?>"></div>
                                    <span class="instruction_note">Link eg. https://www.vendor_site.com?identifier=<strong>[IDENTIFIER]</strong></span>
                                </div>
                            </div>
                            
                            <div class="col-xs-4">
                                <div class="form_field">
                                    <div class="label">Terminate<span class="mandatory">*</span></div>
                                    <div class="input_field"><input type="text" name="redirect_terminate" id="redirect_terminate"  value="<?php echo $vender_data["redirect_terminate"];?>"></div>
                                    <span class="instruction_note">Link eg. https://www.vendor_site.com?identifier=<strong>[IDENTIFIER]</strong></span>
                                </div>
                            </div>
                            
                            <div class="col-xs-4">
                                <div class="form_field">
                                    <div class="label">Quotafull<span class="mandatory">*</span></div>
                                    <div class="input_field"><input type="text" name="redirect_quotafull" id="redirect_quotafull"  value="<?php echo $vender_data["redirect_quotafull"];?>"></div>
                                    <span class="instruction_note">Link eg. https://www.vendor_site.com?identifier=<strong>[IDENTIFIER]</strong></span>
                                </div>
                            </div>
                    </div>
                    
                    
                    <br>
                    <input type="button" value="Modify Vendor" id="modify_vendor" class="button" >
                    
                    
                    </form>
                    
                    
                    <hr>
            	
                
            </div>
        </div>
    </div>
</div>


<?php include_once(INCLUDES_PATH."footer.php");?>
</body>
</html>
