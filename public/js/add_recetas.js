$(document).ready(function () {
    initAddTags();
    initAddIngredientes();
    initSummernoteInputs();
    initShowFileNameReceta();
    initInputTiempoPrep();
    initInputNumPersonas();
    initChangeImagenMiniatura();

    initValidacionFormJqueryValidate();
    initvalidacionSubmit();
    initvalidacionInputEventos();
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

function initValidacionFormJqueryValidate() {

    $("#form_add_receta").validate({
        rules: {
            nombre: {
                required: true
            },
            categoria: {
                required: true
            },
            dificultad: {
                required: true
            },
            imagen: {
                required: true
            }
        },
        messages: {
            nombre: {
                required: "Por favor, introduzca un nombre"
            },
            categoria: {
                required: "Por favor, seleccione una categoría"
            },
            dificultad: {
                required: "Por favor, seleccione el nivel de dificultad"
            },
            imagen: {
                required: "Por favor, seleccione una imagen"
            }
        }
    });
}

function initvalidacionSubmit() {
    $('#form_add_receta').on('submit', function (event) {
        event.preventDefault();
        if($(".form-group-tags .bootstrap-tagsinput span").length == 0){
            $("#custom-tags-error").show();
            $('#custom-tags-error').text('Por favor, introduzca los tags de búsqueda');
            tags = false;
        }else{
            tags = true;
        }

        if($(".form-group-ingredientes .bootstrap-tagsinput span").length == 0){
            $("#custom-ingredientes-error").show();
            $('#custom-ingredientes-error').text('Por favor, introduzca los tags de búsqueda');
            ingredientes = false;
        }else{
            ingredientes = true;
        }
        
        if($(".note-editable br").length == 1 && $(".note-editable > p").text() == "" ){
            $("#custom-elaboracion-error").show();
            $('#custom-elaboracion-error').text('Por favor, introduzca la elaboración');
            elaboracion = false;
        }else{
            elaboracion = true;
        }
        

        if($("#tiempo").val() == '0'){
            $("#custom-tiempo-error").show();
            $('#custom-tiempo-error').text('Por favor, introduzca el tiempo de preparación');
            tiempo = false;
        }else{
            tiempo = true;
        }
        

        if($("#numPersonas").val() == '0'){
            $("#custom-numPersonas-error").show();
            $('#custom-numPersonas-error').text('Por favor, introduzca el número de comensales');
            numPersonas = false;
        }else{
            numPersonas = true;
        }
        
        if(tags && ingredientes && elaboracion && tiempo && numPersonas){
            $('#form_add_receta').unbind('submit').submit();
        }
    });
}

function initvalidacionInputEventos() {
    $('.form-group-tags .bootstrap-tagsinput').on('focusout', function (event) {
        if($(".form-group-tags .bootstrap-tagsinput span").length == 0){
            $("#custom-tags-error").show();
            $('#custom-tags-error').text('Por favor, introduzca los ingredientes');
        }else{
            $("#custom-tags-error").hide();
        }
    });


    $('.form-group-ingredientes .bootstrap-tagsinput').on('focusout', function (event) {
        if($(".form-group-ingredientes .bootstrap-tagsinput span").length == 0){
            $("#custom-ingredientes-error").show();
            $('#custom-ingredientes-error').text('Por favor, introduzca los ingredientes');
        }else{
            $("#custom-ingredientes-error").hide();
        }
    });

    $('.note-editable').on('focusout', function (event) {
        if($(".note-editable br").length == 1 && $(".note-editable > p").text() == "" ){
            $("#custom-elaboracion-error").show();
            $('#custom-elaboracion-error').text('Por favor, introduzca la elaboración');
            console.log($(".note-editable br").length);     
        }else{
            $("#custom-elaboracion-error").hide();
            console.log($(".note-editable br").length);
        }
    });

    $('.form-group-tiempo span').on('click', function (event) {
        if($("#tiempo").val() == '0'){
            $("#custom-tiempo-error").show();
            $('#custom-tiempo-error').text('Por favor, introduzca el tiempo de preparación');
        }else{
            $("#custom-tiempo-error").hide();
        }
    });

    $('.form-group-numPersonas span').on('click', function (event) {
        if($("#numPersonas").val() == '0'){
            $("#custom-numPersonas-error").show();
            $('#custom-numPersonas-error').text('Por favor, introduzca el número de comensales');
        }else{
            $("#custom-numPersonas-error").hide();
        }
    });

}


