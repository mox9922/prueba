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
                            window.location.reload();
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

    $.fn.actualizar_estado_reserva = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_reserva_estado').val();
            let estado = $(this).val();

            if(id_data != '' && estado != ''&& url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'id':id_data,'estado':estado},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            $(this).mensaje_alerta(2, 'Se Actualizo el estado');
                        }
                    },
                    error(){
                        $(this).mensaje_alerta(1,  "Error del sistema");
                    }
                });

            }else{
                $(this).mensaje_alerta(1,  "Los datos o coinciden ");
            }

            return false;
        })
    }


    //parametro de usuario
    $(this).dataTable_ajax_params_es('#url_datatable','#tabla_usuario');


})