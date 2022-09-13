$(document).ready(function () {
  $("#enlace_inicio").mouseover(function () {
    $('#menu_logo').attr("src", "img/logo_titulo_hover.png");;
  });

  $("#enlace_inicio").mouseout(function () {
    $('#menu_logo').attr("src", "img/logo_titulo.png");;
  });

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });
});

//Mostrar ventanas de confirmacion de acciones
function alertWarningHtml(title, text, callbackFunction, confirmButtonText, cancelButton, cancelButtonText, callbackParams, confirmButtonColor, cancelButtonColor, reverseButtons, onCloseFunction) {
  confirmButtonText = typeof confirmButtonText !== 'undefined' ? confirmButtonText : 'Continuar';
  cancelButton = typeof cancelButton !== 'undefined' ? cancelButton : false;
  cancelButtonText = typeof cancelButtonText !== 'undefined' ? cancelButtonText : 'Cancelar';
  callbackParams = typeof callbackParams !== 'undefined' ? callbackParams : null;
  confirmButtonColor = typeof confirmButtonColor !== 'undefined' ? confirmButtonColor : '#42B361';
  cancelButtonColor = typeof cancelButtonColor !== 'undefined' ? cancelButtonColor : '#d33';
  reverseButtons = typeof reverseButtons !== 'undefined' ? reverseButtons : true;


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
    onClose: (element, param2, param3) => {
      if (typeof onCloseFunction !== 'undefined') {
        onCloseFunction(element);
      }
      this.close();
    },
  }).then((result) => {
    if (typeof callbackFunction !== 'undefined') {
      if (callbackParams !== 'undefined') {
        callbackFunction(result, callbackParams);
      } else {
        callbackFunction(result);
      }
    }
  });
}

//Mensajes flotantes de confirmación o error
function notifySuccess(message){
  toastr.success(message, '', { 
                          "closeButton": false,
                          "debug": false,
                          "newestOnTop": false,
                          "progressBar": false,
                          "positionClass": "toast-top-center",
                          "preventDuplicates": false,
                          "onclick": null,
                          "showDuration": "300",
                          "hideDuration": "1000",
                          "timeOut": "6000",
                          "extendedTimeOut": "1000",
                          "showEasing": "swing",
                          "hideEasing": "linear",
                          "showMethod": "fadeIn",
                          "hideMethod": "fadeOut",
                          iconClasses: {
                            success: 'fa-solid fa-check',
                            
                        }, });
}

function notifyError(message){
  toastr.error(message, '', { 
                          "closeButton": false,
                          "debug": false,
                          "newestOnTop": false,
                          "progressBar": false,
                          "positionClass": "toast-top-center",
                          "preventDuplicates": false,
                          "onclick": null,
                          "showDuration": "300",
                          "hideDuration": "1000",
                          "timeOut": "6000",
                          "extendedTimeOut": "1000",
                          "showEasing": "swing",
                          "hideEasing": "linear",
                          "showMethod": "fadeIn",
                          "hideMethod": "fadeOut" });
}
