$(document).ready(function() {

    //parametro de usuario
    $(this).dataTable_ajax_params_es('#url_datatable','#tabla_usuario_1',1);
    $(this).dataTable_ajax_params_es('#url_datatable','#tabla_usuario_2',2);

    $.fn.dell_data_preguntas = function (id_data, url_delete) {
        return this.each(function () {
            let url_dell = $(`#${url_delete}`).val();
            if (id_data != '' && url_dell != '') {

                Swal.fire({
                    title: "Deseas Borrar este contenido?",
                    text: "¡No podrá recuperar este información despues de aceptar!",
                    type: "warning",
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Retroceder',
                    denyButtonText: "Si, elimínalo!",
                    cancelButtonText: "Cancelar Operación",
                }).then((result) => {

                    if (result.isConfirmed) {
                        $(this).delete_crud(id_data,1, url_dell);
                    } else if (result.isDenied) {
                        $(this).delete_crud(id_data,2, url_dell);
                    }
                });

            } else {
                $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
            }
            return false;
        })
    }

    $.fn.delete_crud = function(id = '',estado = '',url_dell = '') {
        return this.each(function () {
            $.ajax({
                async: false,
                cache: false,
                type: 'post',
                dataType: 'text', // json...just for example sake
                data: {id,estado},
                url: url_dell,
                success: function (data) {
                    var result = JSON.parse(data);
                    if (result.response.success == 1) {
                        window.location.reload();
                    } else {
                        $(this).mensaje_alerta(1, "No se pudo realizar el Proceso");
                    }
                }
            })
        })
    }


});