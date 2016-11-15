/**
 * Created by Piyush_Sharma5 on 11/15/2016.
 */

var whitespace=/^\s*$/;
var reg_numeric=/^\d+$/;
var reg_email=/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var reg_contact_no=/^\d{10,11}$/;
var reg_username=/^[a-zA-Z0-9\W]{6,12}$/;
var reg_url=/^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
var reg_vendor_url=/^((ftp|http|https):\/\/)*(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/;
var total_country_no=1;


//Function to validate the form fields for Mapping Hash identifier to survey Ids
var validate_map_hash_identifier_form = function(){

    if(whitespace.test($("#hash_identifier_file").val()))
    {
        $(".validations,.validations .error").fadeOut(200);
        $(".validations,.validations #err_hash_identifier_file").delay(200).fadeIn(200);
        return false;
    }

    $(".validations,.validations .error").fadeOut(200);
    $("form[name='map_identifier_to_surveyId_form']").submit();
    return true;
};