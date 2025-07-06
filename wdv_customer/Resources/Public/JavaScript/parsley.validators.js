/*
 * Powermail Meldungen in Parsley konvertieren
 */

$("[data-powermail-error-message]").each(function(){
        const data = $(this).data("powermail-error-message");
        $(this).attr("data-parsley-error-message",data);
});
$("[data-powermail-required-message]").each(function(){
        const data = $(this).data("powermail-required-message");
        $(this).attr("data-parsley-required-message",data);
});


/*
 * Parslay Validator für Mindestalter. 
 * Im Powermail Feld muss die Variable geburtsdatum_ab_xx lauten, wobei xx für das gewünschte Mindestalter steht.
 */


$("[id^=powermail_field_geburtsdatum_ab_]").each(function(){
    var minage = $(this).attr("id").replace("powermail_field_geburtsdatum_ab_", "");
    $(this).attr('data-parsley-minage', minage);
});

/**
window.Parsley.addValidator('minage', {
  validateString: function(value, requirement) {
    var dob = new Date(value);
    
    //calculate month difference from current date in time
    var month_diff = Date.now() - dob.getTime();
    
    //convert the calculated difference in date format
    var age_dt = new Date(month_diff); 
    
    //extract year from date    
    var year = age_dt.getUTCFullYear();
    
    //now calculate the age of the user
    var age = Math.abs(year - 1970);
    
    if(age >= requirement) return true
    else return false
  },
  messages: {
    de: 'Zur Anmeldung müssen Sie das Alter von %s Jahren erreicht haben.',
    en: 'Zur Anmeldung müssen Sie das Alter von %s Jahren erreicht haben.',
  }
});
*/


$("[id=powermail_field_ewe_1]").each(function(){
    var fields = {
            "powermail_field_vorname":[],
            "powermail_field_name":[],
            "powermail_field_geburtsdatum":[],
            "powermail_field_email":[],
            "powermail_field_strasse":[],
            "powermail_field_hausnummer":[],
            "powermail_field_plz":[],
            "powermail_field_ort":[],
            "powermail_field_mobilfunknummer":[]
    }

    $.each(fields, function(key, value){
            fields[key]["req"] = $("#"+key).attr("required");
    });

    checkRequiredFields($(this));
    
    $(this).on("change", function(){
        checkRequiredFields($(this));
    });

    function checkRequiredFields(ewe_field){
        if(ewe_field.is(':checked')){
            $.each(fields, function(key, value){
                    if(!value["req"]){
                            $("#"+key).attr("required", "required");
                            $("label[for='"+key+"']").append('<span class="mandatory"></span>');
                    }
            });
        }else{
            $.each(fields, function(key, value){
                    if(!value["req"]){
                            $("#"+key).attr("required", false);
                            $("label[for='"+key+"'] .mandatory").remove();
                    }
            });
        }
    }
    
});

var delay = 200;
var offset = 150;
var invalidscroller;

document.addEventListener('invalid', function(e){
        $(e.target).addClass("invalid");
        clearTimeout(invalidscroller);
        invalidscroller = window.setTimeout(scrollToInvalid,100);
}, true);
document.addEventListener('change', function(e){
        name = $(e.target).attr("name");
        $("input[name='"+name+"']").removeClass("invalid");
}, true);
function scrollToInvalid(){
        $('html, body').animate({scrollTop: $($(".invalid")[0]).offset().top - offset }, delay);
}
