$(document).ready(function () {
    initAddTags();
    initAddIngredientes();
    initSummernoteInputs();
    initShowFileNameReceta();
    initInputTiempoPrep();
    initInputNumPersonas();
    initChangeImagenMiniatura();
});

function initAddTags() {
    $('#addTags').tagsinput({
        focusClass: 'my-focus-class'
    });
    $('#addTags').off('itemAdded');
    $('#addTags').on('itemAdded', function (event) {
    });
    $('#addTags').off('itemRemoved');
    $('#addTags').on('itemRemoved', function (event) {
    });
}

function initAddIngredientes() {
    $('#addIngredientes').tagsinput({
        focusClass: 'my-focus-class'
    });
    $('#addIngredientes').off('itemAdded');
    $('#addIngredientes').on('itemAdded', function (event) {
    });
    $('#addIngredientes').off('itemRemoved');
    $('#addIngredientes').on('itemRemoved', function (event) {
    });
}

function initSummernoteInputs() {
    if ($('.summernote-input-air').length > 0) {
        $('.summernote-input-air').summernote({
            lang: 'es-ES',
            placeholder: 'Introduzca aquí paso a paso la elaboración de su receta...',
            focus: false,
            height: 200,
            airMode: false,
            codemirror: {
                mode: 'text/html',
                htmlMode: true,
                lineNumbers: true,
                theme: 'monokai'
            },
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['note-view', ['codeview']]
            ]
        });
    }
}

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

function initInputTiempoPrep() {
    $("#tiempo").TouchSpin({
        min: 0,
        max: 1000,
        step: 15,
        decimals: 0,
        boostat: 5,
        maxboostedstep: false,
        postfix: 'min.'
    });
}

function initInputNumPersonas() {
    $("#numPersonas").TouchSpin({
        min: 0,
        max: 100,
        step: 1,
        decimals: 0,
        boostat: 5,
        maxboostedstep: false,
        postfix: 'personas'
    });
}

function initChangeImagenMiniatura() {
    $('#customFile').on('change', function (event) {

        addImage(event);

        function addImage(event){
            var file = event.target.files[0];
            imageType = /image.*/;

            if(!file.type.match(imageType))
            return;

            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
        }

        function fileOnload(event){
            var result = event.target.result;
            $('#img_receta_miniatura').attr("src", result);
        }
    });
}