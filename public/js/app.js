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

//Mostrar ventanas de confirmacion de acciones
function alertWarningHtml(title, text, callbackFunction, confirmButtonText, cancelButton, cancelButtonText, callbackParams, confirmButtonColor, cancelButtonColor, reverseButtons, onCloseFunction){
  confirmButtonText = typeof confirmButtonText !== 'undefined' ?  confirmButtonText : 'Continuar';
  cancelButton = typeof cancelButton !== 'undefined' ?  cancelButton : false;
  cancelButtonText = typeof cancelButtonText !== 'undefined' ?  cancelButtonText : 'Cancelar';
  callbackParams = typeof callbackParams !== 'undefined' ?  callbackParams : null;
  confirmButtonColor = typeof confirmButtonColor !== 'undefined' ?  confirmButtonColor : '#42B361';
  cancelButtonColor = typeof cancelButtonColor !== 'undefined' ?  cancelButtonColor : '#d33';
  reverseButtons = typeof reverseButtons !== 'undefined' ?  reverseButtons : true;
  
  
  Swal.fire({
      title: title,
      html: text,
      icon: "warning",
      confirmButtonColor: confirmButtonColor,
      confirmButtonText: confirmButtonText,
      closeOnConfirm: true,
      showCancelButton: cancelButton,
      cancelButtonColor: cancelButtonColor,
      cancelButtonText: cancelButtonText,
      reverseButtons: reverseButtons,
      showLoaderOnConfirm: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      }).then((result) => {
        callbackFunction(callbackParams);
      });
}
