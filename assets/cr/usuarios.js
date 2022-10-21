$(document).ready(function() {

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

                            $(this).clear_form();


                            let dataset = result.response.data;
                            $("#data_id").val(dataset.id).change();
                            $("#nombre").val(dataset.nombre).change();
                            $("#usuario").val(dataset.usuario).change();
                            $("#apartamento").val(dataset.apartamento).change();
                            $("#perfil").val(dataset.perfil).change();
                            $("#estado").val(dataset.estado).change();
                            $("#telefono1").val(dataset.telefono1).change();
                            $("#telefono2").val(dataset.telefono2).change();
                            $("#correo1").val(dataset.correo1).change();
                            $("#correo2").val(dataset.correo2).change();
                            $("#tipo_vehiculo").val(dataset.tipo_vehiculo).change();
                            $("#placa").val(dataset.placa).change();

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

    //vehiculos
    $.fn.edit_vehiculo = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_get_vehiculo').val();
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
                            $(this).clear_form();

                            let dataset = result.response.data;
                            $("#v_data_id").val(dataset.id).change();
                            $("#propietario_id").val(dataset.propietario).change();
                            $("#v_tipo_vehiculo").val(dataset.tipo).change();
                            $("#v_placa").val(dataset.placa).change();
                            $("#v_marca").val(dataset.marca).change();
                            $("#v_color").val(dataset.color).change();

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

    //Macotas
    $.fn.edit_mascotas = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_get_mascotas').val();
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
                            $(this).clear_form();

                            let dataset = result.response.data;
                            $("#m_data_id").val(dataset.id).change();
                            $("#m_apartamento").val(dataset.apartamento).change();
                            $("#m_nombre").val(dataset.nombre).change();
                            $("#m_tipo").val(dataset.tipo).change();
                            $("#m_color").val(dataset.color).change();
                            $("#m_raza").val(dataset.raza).change();
                            $("#m_descripcion").val(dataset.observacion).change();

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

    //invitado
    $.fn.edit_invitados = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_get_invitados').val();
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
                            $(this).clear_form();

                            let dataset = result.response.data;
                            $("#mi_data_id").val(dataset.id).change();
                            $("#mi_apartamento").val(dataset.apartamento).change();
                            $("#mi_nombre").val(dataset.nombre).change();
                            $("#mi_cedula").val(dataset.cedula).change();
                            $("#mi_tipo").val(dataset.tipo).change();
                            $("#mi_placa").val(dataset.placa).change();
                            $("#mi_estado").val(dataset.estado).change();

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

    //Quick
    $.fn.edit_quick = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_get_qickpass').val();
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
                            $(this).clear_form();

                            let dataset = result.response.data;
                            $("#q_data_id").val(dataset.id).change();
                            $("#q_propietario").val(dataset.propietario).change();
                            $("#m_quick").val(dataset.codigo).change();
                            $("#m_placa").val(dataset.placa).change();

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
    var tipo_usuario = $("#tipo_usuario").val();
    $(this).dataTable_ajax_params_es('#url_datatable','#tabla_usuario',tipo_usuario);


})