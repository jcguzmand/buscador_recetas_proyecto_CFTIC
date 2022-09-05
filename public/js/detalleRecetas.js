$(document).ready(function () {
    initStarRating();
    initStarRatingSoloLectura();
    initAddComentarios();
    initAddFavorita();
    initDeleteFavorita();
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
        callback: function (currentRating) {
            addValoracion(currentRating);
        }
    });
}

function initStarRatingSoloLectura() {
    $(".my-rating-4-read-only").starRating({
        totalStars: 5,
        starShape: 'rounded',
        starSize: 25,
        emptyColor: 'lightgray',
        hoverColor: '#D07351',
        activeColor: '#bd461b',
        useGradient: false,
        readOnly: true
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

function initAddFavorita() {
    $('#edit_favorita').on('click', function (event) {
        event.preventDefault();
        var idReceta = $('#idReceta').val();
        var formData = {
            'idReceta': idReceta
        };

        if ($(this).data('estado') == '0') {
            $.ajax({
                type: 'POST',
                url: '/add-favorita',
                dataType: 'json',
                data: formData,
                success: function (data, textStatus, xhr) {
                    $('#no_select').removeClass('div_visible').addClass('div_novisible');
                    $('#select').removeClass('div_novisible').addClass('div_visible');
                    console.log('Favorita agregada');

                },
                error: function (datos) {
                    console.log('No ha sido posible añadir la receta a sus favoritos');
                    console.log('Datos del error');
                    console.log(datos.responseText);
                }
            });

        }
    });
}

function initDeleteFavorita() {
    $('#edit_favorita_1').on('click', function (event) {
        event.preventDefault();
        var idReceta = $('#idReceta').val();
        var formData = {
            'idReceta': idReceta
        };
        if ($(this).data('estado') == '1') {
            $.ajax({
                type: 'POST',
                url: '/delete-favorita',
                dataType: 'json',
                data: formData,
                success: function (data, textStatus, xhr) {
                    $('#no_select').removeClass('div_novisible').addClass('div_visible');
                    $('#select').removeClass('div_visible').addClass('div_novisible');
                    console.log('Favorita eliminada');
                },
                error: function (datos) {
                    console.log('No ha sido posible eliminar la receta de sus favoritos');
                    console.log('Datos del error');
                    console.log(datos.responseText);
                }
            });

        }
    });
}