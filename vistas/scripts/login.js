function validarEntrada(inputElement) {

    // Expresión regular que busca comillas simples, comillas dobles, plecas, asteriscos y barras diagonales

    var regex = /['"\\*|/]/g;

    

    // Obtén el valor ingresado por el usuario

    var valor = inputElement.value;

    

    // Reemplaza los caracteres no permitidos por una cadena vacía

    valor = valor.replace(regex, '');

    

    // Actualiza el valor en el campo de entrada

    inputElement.value = valor;

  }

  

  $("#frmAcceso").on('submit', function(e) {

    e.preventDefault();

    logina = $("#logina").val();

    clavea = $("#clavea").val();

    

    $.post("../ajax/usuario.php?op=verificar", {"logina": logina, "clavea": clavea}, function(data) {

      if (data) {

        $(location).attr("href", "menu.php");

      } else {

        bootbox.alert({

          message: "Usuario y/o Password incorrectos",

          callback: function () {

            // Regresar a la página de login

            window.location.href = 'index.html';  // Asumiendo que tu página de login es index.html

          }

        });

      }

    });

    /*$("#frmAcceso").on('submit', function(e) {
      e.preventDefault();

      let logina = $("#logina").val();
      let clavea = $("#clavea").val();

      $.post("../ajax/usuario.php?op=verificar",
          { logina: logina, clavea: clavea },
          function(data) {

              // Convertir respuesta a JSON
              let resp = JSON.parse(data);

              if (resp) {

                  // 👉 SI existe redirección guardada (caso correo)
                  if (resp.redirect) {
                      window.location.href = resp.redirect;
                  } else {
                      // 👉 Comportamiento normal
                      window.location.href = "menu.php";
                  }

              } else {

                  bootbox.alert({
                      message: "Usuario y/o Password incorrectos",
                      callback: function () {
                          window.location.href = 'index.html';
                      }
                  });

              }
          }
      );
    });*/

  });