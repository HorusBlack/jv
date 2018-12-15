$("#form_session_bkn").submit(function () {
    if (($("#usuario").val().length < 1)|| ($("#password").val().length < 1)) {
      alert("Falta ingresar el usuario o contraseña");
      $('input:text').focus(
        function () {
          $(this).effect("highlight", { color: "#ff0000" }, 3000);
        });
      return false;
    }else{
      return true;
    }
  });
  
  $("#form_session_bkn").submit(function () {
    if (($("#usuario").val().length < 5)||($("#password").val().length<4)) {
      $('input:text').focus(
        function () {
          $(this).effect("highlight", { color: "#ff0000" }, 3000);
        });
      alert("Se necesitan más caracteres en los campos para procesar la sesión");
      return false;
    }else{
      return true;
    }
  });
  
  $("#form_Guia").submit(function () {
    if (($("#paqueteria").val().length < 3)||($("#guia").val().length<2)) {
      $('input:text').focus(
        function () {
          $(this).effect("highlight", { color: "#ff0000" }, 3000);
        });
      alert("El nombre de la mensajeria o el no. de guía son incorrectos");
      return false;
    }else{
      return true;
    }
  });