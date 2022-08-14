$(document).ready(function () {
    initStarRating();
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
            console.log('Contenido del data en formato json');
            console.log(data);
            console.log(data.votosTotal);
            console.log(data.valMedia);
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

