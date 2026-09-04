var tabla;
//Función que se ejecuta al inicio
function init() {
}

//Función limpiar
function limpiar() {
  $("#newclave").val("");
  $("#idusuariosusu").val("");
}


//Función cancelarform
function cancelarform() {
  limpiar();
 

}

//Función para activar registros
function CambiarClave(){

    var usuariosusu   = $("#usuariosusu").val();
	var newclave      = $("#newclave").val();
 if(newclave==="") bootbox.alert('Calve no puede ser vacia');
  $.ajax({
      url:"../ajax/usuario.php?op=cambiarclave",
      type:"POST",
      data:{usuariosusu:usuariosusu,newclave:newclave},
      success: function(datos)
      { 
        bootbox.alert(datos);
        $('#modal-default').modal('hide');
      }
  });
  limpiar();
}

init();
