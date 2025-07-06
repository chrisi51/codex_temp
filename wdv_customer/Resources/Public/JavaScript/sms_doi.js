let smsdoirequired = false;
let smsdoifinished = false;

let input_phones = $("#powermail_field_telefon, #powermail_field_festnetz");
let input_mobiles = $("#powermail_field_mobile, #powermail_field_handynummer, #powermail_field_mobilfunknummer");
let input_phones_mobiles = input_phones.add(input_mobiles);
let input_ewe = $('#powermail_field_ewe_1');

if(input_phones_mobiles.length > 0){
    input_phones_mobiles.attr("placeholder","+49 (0) ");
    input_phones_mobiles.inputmask({"mask": "+4\\9 (0) 9{2,4}-9{4,10}"});

    input_phones_mobiles.on("change, keyup", function(){
      let unmasked = $(this).inputmask('unmaskedvalue');
      if(unmasked.startsWith("0"))
        $(this).val($(this).inputmask('unmaskedvalue').replace(/^0+(\d+)/,"$1"));
      $(this).data("unmasked","0049"+$(this).inputmask('unmaskedvalue'));
    });
    input_mobiles.on("change", function(){
        $("#smsdoinumber").val("0049"+$(this).inputmask('unmaskedvalue'))
    });
                      
    if(input_ewe.length == 1 && input_mobiles.length==1)
        window.addEventListener("load", initSmsDoi);

}


    function smsDoiSend(e)
    {
        e.preventDefault();
        const data =new FormData();
        data.append("tx_wdvcustomer_smsdoisend[number]", smsDoiGetPhoneNumber());
        data.append("tx_wdvcustomer_smsdoisend[message]", document.querySelector('#smsdoi-message').innerHTML.trim());

        fetch('/?type=1696319024',{
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: data // body data type must match "Content-Type" header
        })
            .then((response) => response.json())
            .then((data) => smsDoiSendHandle(data));
        return false;
    }

    function smsDoiSendHandle(data){
        if(data.status==="code sent") smsDoiOpenCheckForm();
        else showDoiSendError();
    }


    function smsDoiVerify(e)
    {
        e.preventDefault();
        const data =new FormData();
        data.append("tx_wdvcustomer_smsdoiverify[number]", smsDoiGetPhoneNumber());
        data.append("tx_wdvcustomer_smsdoiverify[code]", smsDoiGetCode());

        fetch('/?type=1696326701',{
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: data // body data type must match "Content-Type" header
        })
            .then((response) => response.json())
            .then((data) => smsDoiVerifyHandle(data));
        return false;
    }


    function smsDoiVerifyHandle(data) {
        if(data.status === "verified") {
            hideDoiContainer();
            //document.querySelector('.powermail_fieldwrap_type_submit button').disabled=false;
            document.querySelector('.powermail_form').submit();
            return;
        }
        showDoiCodeError();
    }

    function smsDoiCheckVerification()
    {
        const data =new FormData();
        data.append("tx_wdvcustomer_smsdoiverify[number]", smsDoiGetPhoneNumber());

        fetch('/?type=1696326701',{
            method: 'POST', // *GET, POST, PUT, DELETE, etc.
            mode: 'cors', // no-cors, *cors, same-origin
            cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
            credentials: 'same-origin', // include, *same-origin, omit
            redirect: 'follow', // manual, *follow, error
            referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
            body: data // body data type must match "Content-Type" header
        })
            .then((response) => response.json())
            .then((data) => smsDoiCheckVerificationHandle(data));
        return false;
    }

    function smsDoiCheckVerificationHandle(data) {
        switch (data.status) {
            case "verified":
                document.querySelector('.powermail_form').submit();
                break;
            case "empty":
                smsDoiOpenSendForm()
                break;
            default:
                smsDoiOpenCheckForm()
        }
    }



    function smsDoiGetPhoneNumber() {
        value="0049"+input_mobiles.inputmask('unmaskedvalue');
        $("#smsdoinumber").val(value)
        return value;
    }
    function smsDoiGetBeautyPhoneNumber() {
        return input_mobiles.val();
    }
    function smsDoiGetCode(){
        return $("#smsdoicode").val();
    }


    function showDoiContainer(){
        $('span.doiphonenumber').text(smsDoiGetBeautyPhoneNumber());
        $("#smsdoi-container").fadeIn();
    }
    function hideDoiContainer(){
        $("#smsdoi-container").fadeOut();
    }

    function smsDoiOpenSendForm(){
        showDoiContainer();
        $("#smsdoi-start").show();
        $("#smsdoi-verify").hide();
    }
    function smsDoiOpenCheckForm() {
        showDoiContainer();
        $("#smsdoi-start").hide();
        $("#smsdoi-verify").show();
    }

    function showDoiCodeError(){
        $("#smsdoi-code-error").slideDown();
    }

    function showDoiSendError(){
        $("#smsdoi-send-error").slideDown();
    }




    function initSmsDoi() {
        smsdoirequired = input_ewe.is(':checked');
        input_ewe.on("change", function(){
            smsdoirequired = ($(this).is(':checked'))
        });

        document.querySelectorAll('.doi-close').forEach(function(e){
            e.addEventListener('click', function(){
                hideDoiContainer();
            });
        });

        document.querySelector('.sendSMS').addEventListener('click', function(e){
            smsDoiSend(e);
        });

        document.querySelector('.smsdoiverifycode').addEventListener('click', function(e){
            smsDoiVerify(e);
        });

        

        $(".powermail_fieldwrap_type_submit button").on("click", function(e){
            e.preventDefault();
            const form = $(this).closest("form");
            const check1 = form.parsley().validate();
            //const check2 = form.get(0).checkValidity();
            if( check1 ){
                if(smsdoirequired) {   
                    smsDoiCheckVerification();
                } else {
                    form.submit();
                }
            }
        });

/*        
        document.querySelector('.powermail_fieldwrap_type_submit button').addEventListener('click', function(e){
            document.querySelector('.powermail_form').reportValidity();
            if(document.querySelector('.powermail_form').checkValidity()){
                if(smsdoirequired) {   
                    smsDoiCheckVerification();
                } else {
                    document.querySelector('.powermail_form').submit();
                }
            }
        });
*/

    }
