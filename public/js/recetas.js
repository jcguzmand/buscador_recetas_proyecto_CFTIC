$(document).ready(function () {
    initSearchRecetas();
});

function initSearchRecetas() {
    $('#searchRecetas').tagsinput();
    $('#searchRecetas').off('itemAdded');
    $('#searchRecetas').on('itemAdded', function(event) {
        var items = $(this).val();
        //search(items);
    });
    $('#searchRecetas').off('itemRemoved');
    $('#searchRecetas').on('itemRemoved', function(event) {
        var items = $(this).val();
        //search(items);
    });
}