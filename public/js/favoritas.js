$(document).ready(function () {
    initDeleteFavoritaDelListado();
});

function initDeleteFavoritaDelListado() {
    $('.eliminar-link').on('click', function (event) {
        event.preventDefault();
        var idReceta = $(this).data('receta-id');
        var formData = {
            'idReceta': idReceta
        };
            $.ajax({
                type: 'POST',
                url: '/delete-favorita-list',
                dataType: 'html',
                data: formData,
                success: function (data, textStatus, xhr) {
                    console.log('Favorita eliminada');
                    $(".container_favoritas").html(data);
                    console.log(data);

                },
                error: function (datos) {
                    console.log('No ha sido posible eliminar la receta de sus favoritos');
                    console.log('Datos del error');
                    console.log(datos.responseText);
                }
            });
    });
}

