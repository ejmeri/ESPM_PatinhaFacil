function BuscarCep() {
    var cep = endereco.EnderecoCep.value;
    if (cep == '') {
        document.getElementById('infocep').innerHTML = 'Informe um CEP';
    } else if (cep.length < 8) {
        document.getElementById('infocep').innerHTML = 'O campo CEP deve conter 8 números.';
    } else {
        document.getElementById('infocep').innerHTML = '';

        $.ajax({
            url: 'https://viacep.com.br/ws/' + cep + '/json/',
            dataType: 'jsonp',
            crossDomain: true,
            contentType: "application/json",
            success: function (data) {
                if (data.erro)
                    document.getElementById('infocep').innerHTML = 'CEP não encontrado!';
                else {
                    console.log(data);
                    $('#EnderecoLogradouro').val(data.logradouro);
                    $('#EnderecoBairro').val(data.bairro);
                    $('#EnderecoCidade').val(data.localidade);
                    $('#EnderecoEstado').val(data.uf.toLowerCase());
                    $("#SelectEstados").val(data.uf.toLowerCase()).change();
                    $('#carregaCep').show();
                }
            } // Ok
        });

    }
}

function CarregaDDD(uf) {
    postPartialView('pessoa/DDD/' + uf, 'RetornoDDD');
};

function CarregaDDDbyUfId(ufid) {
    postPartialView('pessoa/DDDByUFId/' + ufid, 'RetornoDDD');
};

// open tabs 

function openInfo(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    $('#' + tabName).css("display", "block");
    // evt.currentTarget.className += " active";
}

// close tabs


// PERFIL
function startperfil(formulario, divedit = 0) {
    $('#' + formulario).find('#save').hide();
    $('#' + formulario).find('#cancel').hide();
    $('#' + formulario).find('#' + divedit).hide();
    $('#' + formulario).validate({
        errorClass: 'text-errors'
    });
}

function showlabels(form) {
    $(form).find("strong").show();
    $(form).find("input").hide();
}

function showtexts(form) {
    $(form).find("strong").hide();
    $(form).find("input").show();
}


function editpeople(formulario) {
    $('#' + formulario).find('input').attr('readonly', false);
    $('#' + formulario).find('#edit').hide();
    $('#' + formulario).find('#cancel').show();
    $('#' + formulario).find('#save').show();
}

function edititempeople(formulario, divedit = 0) {

    var id = $('#' + formulario).find('#Id').val();

    if (id == 0) {
        $('#' + formulario).find('#warning').text('Selecione uma opção para editar.');
        return false;
    } else {
        $('#' + formulario).find('#warning').text('');
    }

    // showtexts(formulario);
    $('#' + formulario).find('input').attr('readonly', false);
    $('#' + formulario).find('#' + divedit).show();
    $('#' + formulario).find('#edit').hide();
    $('#' + formulario).find('#cancel').show();
    $('#' + formulario).find('#save').show();
}

function editEndereco(formulario, divedit = 0) {

    var id = $('#' + formulario).find('#Id').val();

    if (id == 0) {
        $('#' + formulario).find('#warning').text('Selecione uma opção para editar.');
        return false;
    } else {
        $('#' + formulario).find('#warning').text('');
    }

    // showtexts(formulario);

    $('#' + formulario).find('#' + divedit).show();
    $('#' + formulario).find('#edit').hide();
    $('#' + formulario).find('#cancel').show();
    $('#' + formulario).find('#save').show();
}

function canceleditend(formulario) {
    $('#' + formulario).find('#edit').show();
    $('#' + formulario).find('#cancel').hide();
    $('#' + formulario).find('#save').hide();
    $('#' + formulario).find('#divedit').hide();
}


function canceleditpeople(formulario) {
    // showlabels(formulario);
    $('#' + formulario).find('input').attr('readonly', true);
    $('#' + formulario).find('#edit').show();
    $('#' + formulario).find('#cancel').hide();
    $('#' + formulario).find('#save').hide();
}

function canceledititempeople(formulario, divedit = 0) {
    // showlabels(formulario);
    $('#' + formulario).find('input').attr('readonly', true);
    $('#' + formulario).find('select').val('0').change();
    $('#' + formulario).find('#' + divedit).hide();
    $('#' + formulario).find('#edit').show();
    $('#' + formulario).find('#cancel').hide();
    $('#' + formulario).find('#save').hide();
}

function savepeople(formulario, divedit = 0) {
    // showtexts(formulario);
    $('#' + formulario).find('#' + divedit).show();
    $('#' + formulario).find('input').attr('readonly', false);
    $('#' + formulario).find('#edit').hide();
    $('#' + formulario).find('#cancel').show();
    $('#' + formulario).find('#save').show();
    $('#' + formulario).find('#txtpeoplenome').val('');
    $('#' + formulario).find('#Id').val('');
    $('#' + formulario).find('#fieldlogin').attr('readonly', true);
}

function saveend(formulario, divedit = 0) {
    // showtexts(formulario);
    $('#' + formulario).find('input').attr('value', '');
    $('#' + formulario).find('#save').attr('value', 'Salvar');
    $('#' + formulario).find('#' + divedit).show();
    $('#' + formulario).find('#edit').hide();
    $('#' + formulario).find('#cancel').show();
    $('#' + formulario).find('#save').show();
    $('#' + formulario).find('#txtpeoplenome').val('');
    $('#' + formulario).find('#Id').val('');
}

// FIM PERFIL

// cpfcnpj EVENTO

function ValidateCPFCNPJ(str) {

    str = str.replace('.', '').replace('.', '').replace('-', '').replace('/', '');


    if (str.length == 0) {
        document.getElementById("txtCPF").innerHTML = "";
        return;
    } else if (str.length >= 11) {
        postPartialView('login/ValidarCpfCnpj/' + str, 'txtCPF');
    }

}

// cpfcnpj NAVEGACAO


// INSERIR PESSOA
//    // init bootpag
//         $('#page-selection').bootpag({
//             total: 10
//         }).on("page", function(event, /* page number here */ num){
//              $("#content").html("Insert content"); // some ajax content loading...
//         });


function ListaPetsPessoa(url, form, elementId) {

    var formulario = $('#' + form);

    if (!formulario.valid())
        return false;

    
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(formulario[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (retorno) {
            $('#' + elementId).html(retorno);
        }
    });
    return false;
}

function ListaPetsPagina(url, form, elementId) {

    var formulario = $('#' + form);
    var estado = $('#estado option:selected').text();

    url = url.replace("estado", estado.substring(1));

    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(formulario[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (retorno) {
            $('#' + elementId).html(retorno);
        }
    });
    return false;
}

function ListaPets(url, form, elementId) {

    var formulario = $('#' + form);

    if (!formulario.valid())
        return false;
    
    var estado = $('#estado option:selected').text();

    url = url.replace("estado", estado.substring(1));
    
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(formulario[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (retorno) {
            $('#' + elementId).html(retorno);
        }
    });
    return false;
}

function ListaAdotaveisPagina(url, form, elementId) {

    var formulario = $('#' + form);

    console.log(url);
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(formulario[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (retorno) {
            $('#' + elementId).html(retorno);
        }
    });
    return false;
}


function ListaAdotaveis(url, form, elementId) {


    var formulario = $('#' + form);

    if (!formulario.valid())
        return false;

    
    $.ajax({
        type: "POST",
        url: url,
        data: new FormData(formulario[0]),
        cache: false,
        contentType: false,
        processData: false,
        success: function (retorno) {
            $('#' + elementId).html(retorno);
        }
    });
    return false;
}

function SubmitPartialForm(form, elementId) {

    if (!form.valid())
        return false;

    form.find('#botao').attr('name', 'save');
    form.find('#botao').val('Salvar');

    postForm(form, elementId, '', '');
}

function SubmitPartialFormAcesso(form, elementId, elementResultId = 0) {

    var pass = document.getElementById('password').value;
    var repass = document.getElementById('repassword').value;
    var oldpass = document.getElementById('oldpass').innerText;

    if (oldpass.length != 0)
        return false;

    if (pass != repass)
        return false;

    if (!form.valid())
        return false;

    form.find('#botao').attr('name', 'save');
    form.find('#botao').val('Salvar');

    postForm(form, elementId, elementResultId, '');
}

function ValidatePass(str) {

    if (str.length == 0) {
        document.getElementById("oldpass").innerHTML = "";
        return;
    } else if (str.length >= 1) {
        postPartialView('login/ValidarPassword/' + str, 'oldpass');
    }

}

function validatepassword(field) {
    var pass = document.getElementById('password').value;

    if (pass != field)
        document.getElementById('passretorno').innerHTML = "Senhas não coincidem.";
    else
        document.getElementById('passretorno').innerHTML = "";
}

// FIM INSERIR PESSOA

// PERFIL 

function ChangeEmail(id) {
    if (id != 0) postPartialView('pessoa/email/' + id, 'retornoEmail');
    else canceledititempeople('email', 'divedit');
}

function ChangeTelefone(id) {
    if (id != 0) postPartialView('pessoa/telefone/' + id, 'retornoTelefone');
    else canceledititempeople('telefone', 'divedit');
}

function ChangeEndereco(id) {
    if (id != 0) postPartialView('pessoa/endereco/' + id, 'retornoEndereco');
    else canceleditend('endereco', 'divedit');
}

// FIM


// VALIDAR LOGIN EXISTENTE


function showLogin(str) {
    if (str.length == 0) {
        document.getElementById("returnlogin").innerHTML = "";
        return;
    } else {
        postPartialView('login/Validar/' + str, 'returnlogin');
    }

}


// FIM VALIDAR LOGIN EXISTENTE


// cadastra pet


function CadastrarPet(form) {

    form.validate({
        errorClass: 'text-errors'
    });

    if (!form.valid())
        return false;


    postForm(form, 'txtretorno', 'result', 'login');
    $("#descricao").val('');
    $("#nome").val('');
}

function ConfirmaAdocao(form) {
    var formulario = $('#' + form);

    postForm(formulario, 'txtretorno', 'result', 'login');
}
// fim cadastra pet


// contar len text area


function countChar(val, label) {
    var len = val.value.length;
    if (len >= 501) {
        val.value = val.value.substring(0, 500);
    } else {
        $('#' + label).text(500 - len);
    }
};

// fim contar

// lista pet

function showAchadosPerdidos(uf) {
    postPartialView('ajuda/ListaPet/' + uf, 'ListAchadosPerdidos');
}

function showPet() {
    postPartialView('pets/ListaPet', 'ListPet');
}

function showPeople() {
    postPartialView('home/ListaAdotaveis', 'ListPeople');    
}

function BuscarCepAdotar(cep) {

    if(cep.length != 0) {
        $.ajax({
            url: 'https://viacep.com.br/ws/' + cep + '/json/',
            dataType: 'jsonp',
            crossDomain: true,
            contentType: "application/json",
            success: function (data) {
                $('#Logradouro').text(data.logradouro);
                $('#Bairro').text(data.bairro);
                $('#Cidade').text(data.localidade);
            } // ok
        });
    }
}


// lista pet fim