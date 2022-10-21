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
                    async: true,
                    cache: false,
                    type: 'post',
                    dataType: 'text',
                    data: datastring,
                    url: url_accion,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = $.ajaxSettings.xhr();
                        if(xhr.upload){
                            xhr.upload.addEventListener('progress', function(event){
                                var percent = 0;
                                if (event.lengthComputable) {
                                    percent = Math.ceil(event.loaded / event.total * 100);
                                }
                                $('#progressbar_invitado').text('Carga '+percent+'%') ;
                                $('#progressbar_invitado').attr('aria-valuenow',percent);
                                $('#progressbar_invitado').css("width", percent+"%");
                            }, false);
                        }
                        return xhr;
                    },
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

    $(this).dataTable_ajax_es('#url_datatable','#tabla_usuario');

    $.fn.edit_data = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_get').val();
            if(id_data != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'text',
                    data: {'id':id_data},
                    url: url_edit,
                    success: function(data) {
                        let result = jQuery.parseJSON(data);
                        if (result.response.success == 2) {
                            $(this).mensaje_alerta(1, result.response.msg);
                        } else{
                            $('#modal_data').modal('show');//abrir el modal
                            $("#password").attr('required',false);// required

                            $(this).clear_form();

                            let dataset = result.response.data;
                            $("#data_id").val(dataset.id).change();
                            $("#titulo").val(dataset.titulo).change();
                            $("#descripcion").val(dataset.descripcion).change();
                            $("#documento1").html(dataset.documento1).change();
                            $("#documento2").html(dataset.documento2).change();
                            $("#documento3").html(dataset.documento3).change();
                            $("#documento4").html(dataset.documento4).change();
                            $("#estado").val(dataset.estado).change();
                            $("#prioridad").val(dataset.prioridad).change();
                            $("#mensajes").html(dataset.mensajes).change();


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

})