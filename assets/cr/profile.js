$(document).ready(function() {

    $('#frm_data').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            var url_accion = $("#url_save").val();
            if(url_accion.length  > 5) {

                var datastring = new FormData(document.getElementById("frm_data"));
                datastring.append("clave", "valor");


                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'text',
                    data: datastring,
                    url: url_accion,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result.response.success == 1) {
                            $(this).mensaje_alerta(2, 'Se Actualizaron los datos');
                            // window.location.reload();
                        }
                        else {
                            $(this).mensaje_alerta(1, result.response.msg);
                        }
                    }
                });
            } else {
                $(this).mensaje_alerta(1, "No se pudo realizar el proceso");
            }
            return false;
        }
    })


})