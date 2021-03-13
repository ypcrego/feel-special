
  $(document).ready(function(){
    $('.sidenav').sidenav();
  });


 /* $(document).ready(function(){

 $(".dropdown-trigger").dropdown({ hover: true, constrainWidth: false });
}); */


 $(document).ready(function(){
 $('#modalDeletarPerfil').modal();
});

$(document).ready(function(){
    $('#modalDeletarPostagem').modal();
   });
  
function newPopup() {
    varWindow = window.open('popup.html', 'popup')
}


function chamarPopupUsuarioRepetido() {
    $(document).ready(function () {
        $("#modalUsuarioEmUso").modal().modal('open');
    });
}

function chamarPopupEmailRepetido() {
    $(document).ready(function () {
        $("#modalEmailEmUso").modal().modal('open');
    });
}

function chamarPopupSenhaIncorreta() {
    $(document).ready(function () {
        $("#PopUpSenhaIncorreta").modal().modal('open');
    });
}

function chamarPopupDenunciaSucesso(){
    $(document).ready(function () {
        $("#modalDenunciaSucesso").modal().modal('open');
    });
}

/*function chamarPopupEmailUsuarioRepetidos() {
    $(document).ready(function () {
        $("#PopUpEmailUsuarioRepetidos").modal("show");
    });


} */

function chamarPopupLogout() {
    $(document).ready(function () {
        $("#modalLoginNovamente").modal({onCloseEnd() {
            window.location.href = '../etc/logout.php';

        }}).modal('open');

       

        
    });


}



function enviarFormulario(action) {
    var form = document.getElementById('formularioBotoesComentarios');
    form.action = action;
    form.submit();
  }
