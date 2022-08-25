$(document).ready(function () {
    initClickDeleteRecetas();
});

//Función que lanza la ventana de confirmación en la eliminación de recetas
function initClickDeleteRecetas() {

    $('.eliminar-recetas-link').off('click');
    $('.eliminar-recetas-link').on('click', function (event) {
        event.preventDefault();
        //.data('receta-id') hace referecia al atributo del enlace: data-receta-id = "{{ receta.id }}"
        var idReceta = $(this).data('receta-id');
        //LLamamos a funcion con el contenido las funciones de la biblioteca swetalert2, en app.js
        alertWarningHtml(
                'Eliminar receta',
                '¿Estas seguro que quiere eliminar la receta?',
                eliminarReceta, 'Si', true, 'No', idReceta, '#4bd396', '#f5707a');
                console.log(idReceta);
    }); 
}

//Ejecuta la acción callback para borrar una receta tras confirmar en el cuadro 
function eliminarReceta(idReceta) {
    var formData = {
        'idReceta': idReceta
    };

    $.ajax({
        type: 'POST',
        url: '/delete-receta',
        dataType: 'json',
        data: formData,
        success: function (data) { 
            location.href = '/usuario-recetas';

            console.log('Contenido de data en borrado de recetas');
            console.log(data);               
        },
        error: function (datos) {

            console.log('Datos del error en borrado de recetas');
            console.log(datos.responseText);
            console.log('No se ha podido borrar la receta');
        }
    }); 
}