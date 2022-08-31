$(document).ready(function () {
    initShowFileNameReceta();
    initChangeImagenMiniatura();
    initValidacionFormJqueryValidateNombre();
    initValidacionFormJqueryValidateEmail();
    initValidacionFormJqueryValidatePassword();
});

function initShowFileNameReceta() {
    $('.custom-file-input').on('change', function (event) {
        var inputFile = event.currentTarget;
        $(inputFile).parent()
            .find('.custom-file-label')
            .html(inputFile.files[0].name);
        console.log(inputFile.files[0].name);
        validateFileType(inputFile.files[0].name);
    });
}

function validateFileType(fileName) {
    var idxDot = fileName.lastIndexOf(".") + 1;
    var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
    if (extFile == "jpg") {
        //TO DO
    } else {
        alert("Formato de archivo no permitido, seleccione una imagen en formato .jpg");
    }
}

function initChangeImagenMiniatura() {
    $('#customFile').on('change', function (event) {

        addImage(event);

        function addImage(event) {
            var file = event.target.files[0];
            imageType = /image.*/;

            if (!file.type.match(imageType))
                return;

            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
            cambiarImagen();
        }

        function fileOnload(event) {
            var result = event.target.result;
            $('#img_usuario_miniatura').attr("src", result);
        }

        function cambiarImagen() {
            //Recuperar el archivo de imagen del input file de la vista
            var file = $("#customFile").prop("files")[0];
            console.log(file);
            //Lo enviamos en formato formData
            var form_data = new FormData();
            form_data.append("file", file);

            $.ajax({
                type: 'POST',
                url: '/edit-image-user',
                dataType: 'json',
                processData: false,//NO olvidar
                contentType: false,//NO olvidar
                cache: false,//NO olvidar
                data: form_data,
                success: function (data, textStatus, xhr) {
                    console.log('Respuesta de Ajax en formato json');
                    console.log(data);
                },
                error: function (datos) {
                    console.log('Datos del error');
                    console.log(datos.responseText);
                    console.log('Error comprobar las credenciales');
                }
            });
        }
    });
}

//Validación del campo email
function initValidacionFormJqueryValidateEmail() {
    $('#email').on('keyup', function (event) {
        $('#custom-email-success').hide();
    });

    $("#form_email").validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: "Por favor, introduzca una dirección de correo electrónico",
                email: "Por favor, escribe una dirección de correo electrónica correcta"
            }
        },
        submitHandler: function () {
            // some other code
            // maybe disabling submit button
            // then:
            //$(form).submit();
            cambiarEmail();
        }
    });
}

//Funcion llamada dentro en función de jquery validate como callback
function cambiarEmail() {
    var email = $('#email').val();

    if (nombre != '') {
        var formData = {
            'email': email
        };

        $.ajax({
            type: 'POST',
            url: '/edit-email-user',
            dataType: 'json',
            data: formData,
            success: function (data, textStatus, xhr) {
                console.log('Respuesta de Ajax en formato json');
                console.log(data);
                if (data.resultado == true) {
                    $('#custom-email-success').show();
                }
            },
            error: function (datos) {
                console.log('Datos del error');
                console.log(datos.responseText);
                console.log('Error comprobar las credenciales');
            }
        });
    }
}

//Validación del campo nombre
function initValidacionFormJqueryValidateNombre() {
    $('#nombre').on('keyup', function (event) {
        $('#custom-nombre-success').hide();
    });

    $("#form_nombre").validate({
        rules: {
            nombre: {
                required: true
            }
        },
        messages: {
            nombre: {
                required: "Por favor, introduzca un nombre"
            }
        },
        submitHandler: function () {
            // some other code
            // maybe disabling submit button
            // then:
            //$(form).submit();
            //Validamos que el nombre no exista ya en la base de datos
            validacionNombreUsuario(my_callback); 
        }
    });
}

//Funcion llamada desde la llamada a ajax en validacionNombreUsuario(my_callback)
function my_callback(resultado){
    if (resultado) {
        cambiarNombre();
    }  
}

//Comprobar que el nombre no existen ya en la base de datos, como argumento enviamos función callback
function validacionNombreUsuario(my_callback) {
    var nombreActual =  $('#nombre').data("nombre");
    var nombre = $('#nombre').val();
    var formData = {
        'nombre': nombre
    };

    $.ajax({
        type: 'POST',
        url: '/comprobar_nombre_user',
        dataType: 'json',
        data: formData,
        success: function (data, textStatus, xhr) {
            console.log('Respuesta de Ajax en formato json');
            console.log(data);
            if (data.resultado == true || nombreActual == nombre) {
                $('#custom-nombre-error').hide();
                //llamada a función callback con el resultado de la consulta true
                my_callback(true);

            } else {
                $('#custom-nombre-error').show();
                $('#custom-nombre-error').text('El nombre introducido ya está en uso');
                //llamada a función callback con el resultado de la consulta false
                my_callback(false);
            }
        },
        error: function (datos) {
            console.log('Datos del error');
            console.log(datos.responseText);
            console.log('Error comprobar las credenciales');
        }
    });
}

//Método para cambiar el nombre del usuario
function cambiarNombre() {
    var nombre = $('#nombre').val();
    var formData = {
        'nombre': nombre
    };

    $.ajax({
        type: 'POST',
        url: '/edit-name-user',
        dataType: 'json',
        data: formData,
        success: function (data, textStatus, xhr) {
            console.log('Respuesta de Ajax en formato json');
            console.log(data);
            if (data.resultado == true ) {
                $('#custom-nombre-success').show();
            }
        },
        error: function (datos) {
            console.log('Datos del error');
            console.log(datos.responseText);
            console.log('Error comprobar las credenciales');
        }
    });
}

//Validación del campo password
function initValidacionFormJqueryValidatePassword() {
    $('#passwordNuevo').on('keyup', function (event) {
        $('#custom-password-success').hide();
    });

    $("#form_password").validate({
        rules: {
            passwordActual: {
                required: true
            },
            passwordNuevo: {
                required: true,
                minlength: 8
            },
            passwordRepite: {
                required: true,
                equalTo: "#passwordNuevo"
            }
        },
        messages: {
            passwordActual: {
                required: "Por favor, introduzca su contraseña actual"
            },
            passwordNuevo: {
                required: "Por favor, introduzca la nueva contraseña",
                minlength: "Su contraseña debe tener al menos 8 caracteres"
            },
            passwordRepite: {
                required: "Por favor, introduzca de nuevo la nueva contraseña",
                equalTo: "Los email introducidos deben ser iguales"
            }
        },
        submitHandler: function () {
            // some other code
            // maybe disabling submit button
            // then:
            //$(form).submit();
            //Validamos que la contraseña introducida sea la del usuario con sesión activa
            validacionPasswordUsuario(my_callback_pass); 
        }
    });
}

//Funcion llamada desde la llamada a ajax en validacionNombreUsuario(my_callback)
function my_callback_pass(resultado){
    if (resultado) {
        cambiarPassword();
    }  
}

//Comprobar que la contraseña conincide con la del usuario con sesión activa, como argumento enviamos función callback
function validacionPasswordUsuario(my_callback) {
    var password = $('#passwordActual').val();
    var formData = {
        'password': password
    };

    $.ajax({
        type: 'POST',
        url: '/comprobar_password_user',
        dataType: 'json',
        data: formData,
        success: function (data, textStatus, xhr) {
            console.log('Respuesta de Ajax en formato json');
            console.log(data);
            if (data.resultado == true) {
                $('#custom-password-error').hide();
                //llamada a función callback con el resultado de la consulta true
                my_callback_pass(true);

            } else {
                $('#custom-password-error').show();
                $('#custom-password-error').text('La contraseña es errónea');
                //llamada a función callback con el resultado de la consulta false
                my_callback_pass(false);
            }
        },
        error: function (datos) {
            console.log('Datos del error');
            console.log(datos.responseText);
            console.log('Error comprobar las credenciales');
        }
    });
}

//Método para cambiar la contraseña del usuario
function cambiarPassword() {
    var passwordNuevo = $('#passwordNuevo').val();
    var formData = {
        'passwordNuevo': passwordNuevo
    };

    $.ajax({
        type: 'POST',
        url: '/edit-password-user',
        dataType: 'json',
        data: formData,
        success: function (data, textStatus, xhr) {
            console.log('Respuesta de Ajax en formato json');
            console.log(data);
            if (data.resultado == true) {
                $('#custom-password-success').show();
            }
        },
        error: function (datos) {
            console.log('Datos del error');
            console.log(datos.responseText);
            console.log('Error comprobar las credenciales');
        }
    });
}





