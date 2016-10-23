/**
 * Created by piyush sharma on 23-10-2015.
 */
$(document).ready(function(){
    /*
    * Toggle collapse of Survey Filter section
    * */
    $("h5[data-toggle='collapse']").on('click',".glyphicon",function(){
        if($(this).hasClass("glyphicon-chevron-down"))
        {
            $(this).removeClass("glyphicon-chevron-down");
            $(this).addClass("glyphicon-chevron-up");
        }
        else if($(this).hasClass("glyphicon-chevron-up"))
        {
            $(this).removeClass("glyphicon-chevron-up");
            $(this).addClass("glyphicon-chevron-down");
        }

    });


    $("input[type='checkbox'][name='country_filter']").click(function(){

        if($(this).prop( "checked"))
        {
            $("select[name='country_ip_filter_countries[]']").prop( "disabled",false);
        }
        else
        {
            $("select[name='country_ip_filter_countries[]']").prop( "disabled",true);//.val("");
        }
    });

    $("input[type='checkbox'][name='duplicate_ip']").click(function(){

        if($(this).prop( "checked"))
        {
            $("input[name='duplicate_ip_limit']").prop( "disabled",false);
        }
        else
        {
            $("input[name='duplicate_ip_limit']").prop( "disabled",true);
        }
    });


});