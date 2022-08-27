$(document).ready(function () {
    initValidacionFormReg();
    initValidacionNombreUsuario();
    
});

function initValidacionFormReg() {

    $("#form_registro").validate({
        rules: {
            nombre: {
                required: true
              },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            politicasPriv: {
                required: true,
            }  
        },
        messages: {
            nombre: {
                required: "Por favor, introduzca un nombre"
            },
            email: {
                required: "Por favor, introduzca un correo electrónico",
                email: "Por favor, introduzca un correo electrónico válido"
            },
            password: {
                required: "Por favor, introduzca una contraseña",
                minlength: "Su contraseña debe tener al menos 8 caracteres"
            },
            politicasPriv: {
                required: "Por favor, acepte nuestras políticas de privacidad"
            } 
        }
    });
}

function initValidacionNombreUsuario() {
    $('#nombre').off('keyup');
    $('#nombre').on('keyup', function (event) {
        var items = $(this).val();
        var formData = {
            'nombre': items
        };
    
        $.ajax({
            type: 'POST',
            url: '/comprobar_nombre_user',
            dataType: 'json',
            data: formData,
            success: function (data, textStatus, xhr) {
                console.log('Respuesta de Ajax en formato json');
                console.log(data);
                if(data.resultado == true){
                    $('#custom-nombre-error').hide();
                    $('#btn_submit_registro').attr('disabled', false);
                      
                }else{
                    $('#custom-nombre-error').show();
                    $('#custom-nombre-error').text('El nombre introducido ya está en uso');
                    $('#btn_submit_registro').attr('disabled', true);
                }
            },
            error: function (datos) {
                console.log('Datos del error');
                console.log(datos.responseText);
                console.log('Error comprobar las credenciales');
            }
        });
    });
}


