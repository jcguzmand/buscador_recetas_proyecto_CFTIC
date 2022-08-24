$(document).ready(function () {
    initSearchRecetas();
});

function initSearchRecetas() {
    $('#searchRecetas').tagsinput({
        focusClass: 'my-focus-class'
    });
    $('#searchRecetas').off('itemAdded');
    $('#searchRecetas').on('itemAdded', function (event) {
        var items = $(this).val();
        search(items);
    });
    $('#searchRecetas').off('itemRemoved');
    $('#searchRecetas').on('itemRemoved', function (event) {
        var items = $(this).val();
        search(items);
    });
}

function search(value) {
    var formData = {
        'searchRecetas': value,
        'page': 1
    };

    $.ajax({
        type: 'POST',
        url: '/buscar',
        dataType: 'html',
        data: formData,
        success: function (data, textStatus, xhr) {
            $('#container_searchRecetas').html(data);
            console.log(data);
        },
        error: function (datos) {
            console.log('No ha sido posible contactar. Inténtelo de nuevo más tarde');
        }
    });
}

