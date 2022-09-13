$(document).ready(function () {
    initDeleteFavoritaDelListado();
});

$(document).ajaxComplete(function () {
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
                    notifySuccess('La receta se ha eliminado de tus favoritos');
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

