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
                    dataType: 'json',
                    data: datastring,
                    url: url_accion,
                    contentType: false,
                    processData: false,
                    success: function(result) {
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
                            $(this).clear_form();
                            let dataset = result.response.data;
                            $("#data_id").val(dataset.id).change();
                            $("#nombre").val(dataset.nombre).change();
                            $("#torre").val(dataset.torre).change();
                            $("#apartamento").val(dataset.apartamento).change();
                            $("#telefono").val(dataset.telefono).change();
                            $("#estado").val(dataset.estado).change();

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


    $.fn.edit_data = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_get').val();
            let tablax = $('#modal_table').empty();
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

                            $(this).view_asesor(1)

                            let dataset = result.response.data;
                            $("#data_id").val(dataset.id).change();
                            // $("#nombre").val(dataset.nombre).change();
                            // $("#cedula").val(dataset.cedula).change();
                            // $("#codigo").val(dataset.codigo).change();
                            // $("#tipo").val(dataset.tipo).change();
                            // $("#estado").val(dataset.estado).change();

                            var asesoresd = dataset.asesores;
                            if(asesoresd.length > 0){
                                asesoresd.forEach(function(dd){

                                    var tabla = "" +
                                        "<tr>" +
                                        "<td class='text-center'>"+dd.v_nombre+"</td>" +
                                        "<td class='text-center'>"+dd.v_codigo+"</td>" +
                                        "<td class='text-center'><button type='button' class='btn btn-danger' onclick='$(this).crud_asesor(\"0\",\""+dataset.id+"\",\""+dd.v_id+"\")'><i class='fas fa-times'></i></button></td>" +
                                        "</tr>";


                                    tablax.append(tabla);
                                })
                            }



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
    $(this).dataTable_ajax_es('#url_datatable','#tabla_usuario');


})