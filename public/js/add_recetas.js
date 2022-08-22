$(document).ready(function () {
    initAddTags();
    initAddIngredientes();
});

function initAddTags() {
    $('#addTags').tagsinput();
    $('#addTags').off('itemAdded');
    $('#addTags').on('itemAdded', function(event) {
    });
    $('#addTags').off('itemRemoved');
    $('#addTags').on('itemRemoved', function(event) {
    });
}

function initAddIngredientes() {
    $('#addIngredientes').tagsinput();
    $('#addIngredientes').off('itemAdded');
    $('#addIngredientes').on('itemAdded', function(event) {
    });
    $('#addIngredientes').off('itemRemoved');
    $('#addIngredientes').on('itemRemoved', function(event) {
    });
}