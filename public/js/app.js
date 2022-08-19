$(document).ready(function () {
    $("#enlace_inicio").mouseover(function(){
        $('#menu_logo').attr("src","img/logo_titulo_hover.png");;
      });

    $("#enlace_inicio").mouseout(function(){
        $('#menu_logo').attr("src","img/logo_titulo.png");;
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });
});