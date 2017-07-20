$(document).ready(function () {
    $('.data').mask('00/00/0000');
});


$(document).ready(function () {
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000');
    $('.phone').mask('0000-0000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.phone_us').mask('(000) 000-0000');
    $('.mixed').mask('AAA 000-S0S');


        
    $(".cep").focusin(function () {
        $('.cep').mask('00000-000');
    });
    $(".cep").focusout(function () {
        $('.cep').unmask();
    });

    $(".cellphone").focusin(function () {
        $('.cellphone').mask('(00) 00000-0000');
    });
    $(".cellphone").focusout(function () {
        $('.cellphone').unmask();
    });

    $(".phone").focusin(function () {
        $('.phone').mask('0000-0000');
    });
    $(".phone").focusout(function () {
        $('.phone').unmask('0000-0000');
    });

    $(".cpf").focusin(function () {
        $('.cpf').mask('000.000.000-00');
    });

    $(".cpf").focusout(function () {
        $('.cpf').unmask();
    });

    $(".cnpj").focusin(function () {
        $('.cnpj').mask('00.000.000/0000-00');
    });

    $(".cnpj").focusout(function () {
        $('.cnpj').unmask();
    });

    $('.money').mask('000.000.000.000.000,00', {
        reverse: true
    });

});


function swapText(control, texto) {
    document.getElementById(control).innerText = texto;
}


function postPartialView(url, elementId) {

    // if (!form.valid())
    // 	return false;

    $.ajax({
        type: "GET",
        url: url,
        success: function (retorno) {
            $("#" + elementId).html(retorno);
        }
    });

    return false;
};

function postForm(form, elementId, elementResultId, redirect = '0') {

    $('#spin').css("display", "block");
    // alert('elmeri');
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: new FormData(form[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (retorno) {
            // retorno = retorno.toString();
            // console.log(retorno);
         
            try 
            {
                retorno = JSON.parse(retorno);
                
                if (retorno.Status && retorno.Do != '')
                {   
                    location.href = retorno.Do;
                }
                else if(retorno.Status && retorno.Do == '')
                {   
                    $('#' + elementId).html(retorno.Mensagem);
                    $('#' + elementResultId).show();
                }
                
            }
            catch (e) {
                $('#' + elementId).html(retorno);
                $('#' + elementResultId).show();
            }

            $('#spin').css("display", "none");

        }
    });
    return false;
}

function postFormLogin(form, elementId, elementResultId, redirect) {
    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: form.serialize(),
        success: function (retorno) {
                      
            retorno = JSON.parse(retorno);
            
            if (retorno.Status) {
                location.href = retorno.Do;
            }
            else {
                $('#txtsenha').val('');
                $('#txtlogin').focus();                
                $('#' + elementId).html(retorno.Mensagem);
                $('#' + elementResultId).show();
            }
        }
    });
    return false;
}