$("#form_session_user").submit(function () {
    if (($("#correo").val().length < 1)|| ($("#password").val().length < 1)) {
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
  
  $("#form_session_user").submit(function () {
    if (($("#correo").val().length < 5)||($("#password").val().length<4)) {
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
  
  $("#form_updatePass").submit(function () {
    var valido=false;
    var str1=$("#recoveryPass");
     var str2=$("#confirmPass");
    if (($("#recoveryPass").val().length < 5)) {
      alert("Su contraseña debe ser mayor a 5 caracteres");
    }
    else if (($("#confirmPass").val().length < 5)) {
       alert("Debe llenar ambos campos");	
    }else{
  var pass=document.getElementById("recoveryPass").value;
  var passconf=document.getElementById("confirmPass").value;
  if (pass!=passconf){
    alert("Las contraseñas son diferentes. Corriga por favor");
  }else{
    valido=true;
  }
    }   
    return valido;
  });

