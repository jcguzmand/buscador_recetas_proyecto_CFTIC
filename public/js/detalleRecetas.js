$(document).ready(function () {
    initStarRating();
    initAddComentarios();
});

function initStarRating() {
    $(".my-rating-4").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 25,
        emptyColor: 'lightgray',
        hoverColor: '#D07351',
        activeColor: '#bd461b',
        useGradient: false,
        callback: function(currentRating){
            addValoracion(currentRating);
        }
    });
}

function addValoracion(currentRating) {
    var idReceta = $("#idReceta").val();
    var formData = {
        'valoracion': currentRating,
        'idReceta': idReceta
    };

    $.ajax({
        type: 'POST',
        url: '/add_valoracion',
        dataType: 'json',
        data: formData,
        success: function (data, textStatus, xhr) {
            console.log('Respuesta de Ajax en formato json');
            console.log(data);
            $('#numVal').html(data.votosTotal + ' votos');
            $('#valPromedio').html(data.valMedia + '/5');

        },
        error: function (datos) {
            console.log('Datos del error');
            console.log(datos.responseText);
            console.log('Error al añadir la valoracion');
        }
    });
}

function initAddComentarios() {
    $('#form-add-comentarios').off('submit');
    $('#form-add-comentarios').on('submit', function (event) {
        event.preventDefault();
        addComentarios();
        $('#contenido_textarea').val('');
    });
}

function addComentarios() {
    var contenido = $('#contenido_textarea').val();
    var idReceta = $('#idReceta').val();

    var formData = {
        'contenido': contenido,
        'idReceta': idReceta
    };

    $.ajax({
        type: 'POST',
        url: '/add_comentario',
        dataType: 'html',
        data: formData,
        success: function (data, textStatus, xhr) {
            $('#container_lista_comentarios').html(data);
            console.log(data);
        },
        error: function (datos) {
            console.log('No ha sido posible contactar. Inténtelo de nuevo más tarde');
        }
    });
    
}

