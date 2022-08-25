$(document).ready(function () {
    initValidacionFormReg();
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
                required: "Por favor, acepte las políticas de privacidad"
            } 
        }
    });
}

