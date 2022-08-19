$(document).ready(function () {
    initMostrarErrorLogin();
});

function initMostrarErrorLogin() {
    $('#form_login').off('submit');
    $('#form_login').on('submit', function (event) {
        event.preventDefault();
        MostrarErrorLogin();
    });
}




function MostrarErrorLogin() {
    var email       = $("#email").val();
    var password    = $("#password").val();
    var formData = {
        'email': email,
        'password': password
    };

    $.ajax({
        type: 'POST',
        url: '/comprobar_credenciales',
        dataType: 'json',
        data: formData,
        success: function (data, textStatus, xhr) {
            console.log('Respuesta de Ajax en formato json');
            console.log(data);
            if(data.resultado){
                window.location.href = '/inicio';
            }else{
                $('.message--error').show();
            }

        },
        error: function (datos) {
            console.log('Datos del error');
            console.log(datos.responseText);
            console.log('Error al añadir la valoracion');
        }
    });
}