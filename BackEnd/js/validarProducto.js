$("#form_productos").submit(function () {
    if ($("#nombre_prod").val().length < 5) {
      alert("El nombre del producto esta vacío o es demaciado corto");
      return false;
    }else if ($("#talla_prod").val().length < 2) {
       alert("La talla y no. de productos esta vacía o incorrecta");
      return false;
    }else if ($("#descripcion_prod").val().length < 5) {
      alert("La descripción esta vacía o es demaciado corta");
      return false;
    }else if ($("#precio_prod").val().length < 1) {
      alert("El precio del producto esta vacío");
      return false;
    }else if ($("#img1_prod").val().length < 1) {
      alert("La imagen 1 del producto no se cargo");
      return false;
    }else if ($("#img2_prod").val().length < 1) {
      alert("La imagen 2 del producto no se cargo");
      return false;
    }else{
      alert("Producto registrado");
      return true;
    }
  });