$(document).ready(function() {

    $('#frm_data_reserva').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            var url_accion = $("#url_save").val();
            if(url_accion.length  > 5) {

                var datastring = new FormData(document.getElementById("frm_data_reserva"));
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


    $.fn.file_upload_file = function() {
        return this.each(function() {
            let favor = 0;
            // let favor = $(".favor").val().replaceAll('₡ ','').replaceAll(',','');
            let saldo = $(".saldo").val().replaceAll('₡ ','').replaceAll(',','');
            let img   =   $(".custom-file-input ")
            if(favor != '' && saldo == ''){
                img.attr('required',false);
            }else{
                img.attr('required',true);
            }
            return false;
        })
    }

    $('#frm_deuda_total').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            var url_accion = $("#url_save_deuda_lista").val();
            if(url_accion.length  > 5) {

                var datastring = new FormData(document.getElementById("frm_deuda_total"));
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
                                $('#progressbar_deuda_pago').text('Carga '+percent+'%') ;
                                $('#progressbar_deuda_pago').attr('aria-valuenow',percent);
                                $('#progressbar_deuda_pago').css("width", percent+"%");
                            }, false);
                        }
                        return xhr;
                    },
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result.response.success == 1) {
                            percent = 0;

                            $(this).reloadmin(6);
                        }
                        else {
                            $('#progressbar_deuda_pago').removeClass("progress-bar-warning");
                            $('#progressbar_deuda_pago').addClass("progress-bar-danger");
                            $(this).mensaje_alerta(1, result.response.msg);
                        }
                        $('#progressbar_deuda_pago').text('Barra de progreso') ;
                        $('#progressbar_deuda_pago').attr('aria-valuenow',100);
                        $('#progressbar_deuda_pago').css("width", "20%");
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
                            $("#password").attr('required',false);// required

                            $(this).clear_form();

                            let dataset = result.response.data;
                            $("#data_id").val(dataset.id).change();
                            $("#nombre").val(dataset.nombre).change();
                            $("#cedula").val(dataset.cedula).change();
                            $("#telefono").val(dataset.telefono).change();
                            $("#tipo").val(dataset.tipo).change();
                            $("#placa").val(dataset.placa).change();
                            $("#apartamento").val(dataset.apartamento).change();
                            $("#tipo_invitado").val(dataset.tipo_invitado).change();
                            $("#modal_ingreso").val(dataset.ingreso).change();
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

    $.fn.edit_filial = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_aflial_get').val();
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
                            $('#modal_afilial').modal('show');//abrir el modal
                            $("#af_password").attr('required',false);// required

                            $(this).clear_form();

                            let dataset = result.response.data;
                            $("#af_data_id").val(dataset.id).change();
                            $("#af_nombre").val(dataset.nombre).change();
                            $("#af_usuario").val(dataset.usuario).change();
                            $("#af_apartamento").val(dataset.apartamento).change();
                            $("#af_estado").val(dataset.estado).change();
                            $("#af_telefono1").val(dataset.telefono1).change();
                            $("#af_telefono2").val(dataset.telefono2).change();
                            $("#af_tipo").val(dataset.tipo).change();
                            $("#af_tipo_vehiculo").val(dataset.tipo_vehiculo).change();
                            $("#af_placa").val(dataset.placa).change();


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

    $.fn.reloadmin = function(type) {
        return this.each(function(){
            if(type == 1){
                let id = $('#propietario_id').val();
                $(this).get_vehiculos_all(id);
            }else if(type == 2){
                let id = $('#m_apartamento').val();
                $(this).get_mascotas_all(id);
            }else if(type == 3){
                //Comprobante pago
                let id = $('#cpm_id').val();
                let mes = $('#saldo_mes').val();
                let anno = $('#saldo_anno').val();

                $(this).get_saldos();
                $(this).asignar_pagos_facturas(id,mes,anno);

            }else if(type == 3){
                let id = $('#cpm_id').val();
                $('#modal_pago_comprobante').modal('hide');//abrir el modal
                $(this).get_saldos();
            }else if(type == 4){
                let id = $('#tipo_deuda').val();
                // $('#modal_deudas_total').modal('hide');//abrir el modal
                $(this).pago_total_deudas(id);
            }else{
                let id = $('#deuda_data').val();
                // $('#modal_deudas_total').modal('hide');//abrir el modal
                $(this).get_deudas()
                $(this).deuda_obtener_pagos(id);

            }

            return false;
        })
    }

    $.fn.sumar_saldo_favor = function(calcular  = true) {
        return this.each(function() {

            let salor_favor = $("#saldo_favor").val().replaceAll('₡ ','').replaceAll(',','');
            let favor = $("#favor_uso").val().replaceAll('₡ ','').replaceAll(',','');


            //limpiar
            let favor_input = (salor_favor >= 1) ? parseFloat(salor_favor) : 0;
            let favor_clear = (favor >= 1) ?  parseFloat(favor) : 0;

            let operacion = 0;
            let operacion_resta = 0;
            //validar que no ingre mas de lo indicado
            if(favor_clear > favor_input){
                $("#favor_uso").val('').change();
                $(this).mensaje_alerta(1,  "El Valor Ingresado No puede superar el saldo a favor");
                favor_clear = 0;
            }

            if(calcular == true){
                $(this).operar_sados();
            }

            return false;
        })
    }

    $.fn.operar_sados = function() {
        return this.each(function() {

            let favor = 0;
            let pagar       = $("#saldo").val().replaceAll('₡ ','').replaceAll(',','');//valor a pagar
            let cancelar    = $("#saldo_cancelar").text().replaceAll('₡ ','').replaceAll(',','');
            let cancelado   = $("#saldo_cancelado").text().replaceAll('₡ ','').replaceAll(',','');

            //limpiar
            let favor_clear = (favor >= 1) ?  parseFloat(favor) : 0;
            let pagar_clear = (pagar >= 1) ?  parseFloat(pagar) : 0;



            operacion = (favor_clear)+pagar_clear;
            let suma_datos = parseFloat(cancelar)-parseFloat(cancelado);
            operacion_resta = (parseFloat(suma_datos)-parseFloat(operacion));
            $("#saldo_restante").val('');

            $(this).asignacion_numeric('#saldo_total_cancelar',operacion)
            $(this).asignacion_numeric('#saldo_restante',operacion_resta)

            return false;
        })
    }

    //Data Users
    $.fn.asignar_pagos_facturas = function(id = '',mes = '',anno = '') {
        return this.each(function(){
            let url_edit = $('#url_get_pagos_comprobante').val();
            if(url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {id},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            $('#modal_pago_comprobante').modal('show');//abrir el modal

                            $(this).clear_form();
                            $("#cpm_id").val(id);

                            //validar si ya se subio el comprobante no permitir mostrar

                            let template_form = $("#div_form");

                            let dd = rest.data;
                            $("#saldo_cancelar").text('₡ ' + dd.deuda);
                            $("#saldo_cancelado").text('₡ ' + dd.consignado);
                            $("#saldo_mes").val(mes);
                            $("#saldo_anno").val(anno);
                            $("#saldo_deuda").val(dd.deuda);
                            $("#saldo_tipo").val(dd.tipo);

                            $(this).asignacion_numeric('#saldo_favor',dd.saldo_favor)

                            if(dd.estado != 1){
                                template_form.hide();
                            }else{
                                template_form.show();
                            }


                            let uso_input = $("#favor_uso");
                            let salor_favor = $("#saldo_favor").val().replaceAll('₡ ','').replaceAll(',','');
                            let favor_input = (salor_favor >= 1) ? parseFloat(salor_favor) : 0;
                            if(favor_input == 0){
                                uso_input.prop('readonly',true).val('');
                            }else{
                                uso_input.prop('readonly',false).val('');
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

    $.fn.get_saldos = function() {
        return this.each(function(){
            let url_edit = $('#url_saldos').val();
            if(url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'token':'123456'},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            $('#modal_saldo').modal('show');//abrir el modal

                            $(this).clear_form();

                            let template = $("#tabla_modal_saldo").empty();
                            let tmp_data1 = '';
                            let dataset1 = rest.data;

                            dataset1.forEach(function (dd) {
                                let nombre = (dd.comprobante.length >= 10) ? `<a class="single_image" href='${dd.comprobante}'>${dd.nombre}</a>` : dd.nombre;
                                tmp_data1 += `<tr>
                                                <td class="bolder">${nombre}</td>
                                                <td class="bolder nowrap">${dd.m3}</td>
                                                <td class="bolder nowrap"><span class="text-success">₡ ${dd.deuda1.deuda}</span></td>
                                                <td class="bolder nowrap">${dd.deuda1.estado}</td>
                                                <td class="bolder nowrap"><span class="text-success">₡ ${dd.deuda2.deuda}</span></td>
                                                <td class="bolder nowrap">${dd.deuda2.estado}</td>
                                                <td class="bolder nowrap"><span class="text-success">₡ ${dd.deuda3.deuda}</span></td>
                                                <td class="bolder nowrap">${dd.deuda3.estado}</td>
                                                <td class="bolder nowrap"><span class="text-success">₡ ${dd.total}</span></td>
                                            </tr>`;
                            });
                            template.html(tmp_data1)



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

    //Deuda data
//     $.fn.get_deudas = function() {
//         return this.each(function(){
//             let url_edit = $('#url_deudas').val();
//             if(url_edit != ''){
//                 $.ajax({
//                     async: false,
//                     cache: false,
//                     type: 'post',
//                     dataType: 'json',
//                     data: {'token':'123456'},
//                     url: url_edit,
//                     success: function(result) {
//                         let rest = result.response;
//                         if (rest.success  == 2) {
//                             $(this).mensaje_alerta(1, rest.msg);
//                         } else{
//                             $('#modal_deudas_listas').modal('show');//abrir el modal
//
//                             $(this).clear_form();
//
//                             let template = $("#tabla_modal_saldo_deudas_lista").empty();
//                             let tmp_data1 = '';
//                             let dataset1 = rest.data;
//                             $("#saldo_modal_deuda").val(rest.saldo);
//                             $("#saldo_modal_cancelado").val(rest.saldado);
//
//                             dataset1.forEach(function (dd) {
//                                 tmp_data1 += `<tr>
//                                                 <td class="bolder nowrap">${dd.nombre}</td>
//                                                 <td class="bolder nowrap"><span class="text-success">₡ ${dd.saldo}</span></td>
//                                                 <td class="bolder nowrap"><span class="text-success">₡ $${dd.pagos_saldado}</span></td>
//                                                 <td class="bolder nowrap">${dd.fecha}</td>
//                                                 <td class="bolder nowrap">${dd.estado}</td>
//                                                 <td class="bolder nowrap">${dd.creacion}</td>
// <!--                                                <td class="bolder nowrap"><button type="button" class="btn btn-primary" onclick="$(this).forms_modal({'page' : 2,'data1' : '${dd.id}','title' : 'Via Tiempos'})" onclick="$(this).deuda_obtener_pagos()"><i class="fa fa-eye"></i></button></td>-->
//                                                 <td class="bolder nowrap"><button type="button" class="btn btn-primary" onclick="$(this).deuda_obtener_pagos('${dd.id}')"><i class="fa fa-eye"></i></button></td>
//
//                                             </tr>`;
//                             });
//                             template.html(tmp_data1)
//
//
//
//                         }
//                     },
//                     error(){
//                         $(this).mensaje_alerta(1,  "Error del sistema");
//                     }
//                 });
//
//             }else{
//                 $(this).mensaje_alerta(1,  "Los datos o coinciden ");
//             }
//
//             return false;
//         })
//     }

    $.fn.deuda_obtener_pagos = function(id) {
        return this.each(function(){
            let url_edit = $('#url_deudas_data').val();
            if(url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {id},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            $('#modal_deudas_total_pagar').modal('show');//abrir el modal

                            $(this).clear_form();

                            let hiden_inputs = $('.deuda_estado').hide();
                            let template = $("#modal_deudas_pagar").empty();
                            let tmp_data1 = '';
                            let dataset1 = rest.data;
                            $("#deuda_data").val(id);
                            $("#deuda_data_id").val(rest.deuda.id);
                            $("#deuda_nombre").val(rest.deuda.nombre);
                            $("#deuda_saldo").val(`₡ ${rest.deuda.saldo}`);
                            $("#deuda_saldo_pago").val(`₡ ${rest.deuda.saldo_pago}`);
                            $("#deuda_saldo_total").val(`₡ ${rest.deuda.saldo_total}`);
                            $("#deuda_pagos").val(rest.deuda.pagos);
                            $("#deuda_fecha").val(rest.deuda.fecha);
                            $("#deuda_observacion").val(rest.deuda.observacion);

                            //validar el estado de la deuda
                            if(rest.deuda.estado == 1){
                                hiden_inputs.show();
                            }

                            dataset1.forEach(function (dd) {
                                var estado = (dd.estadoold == 1) ? `<input type="checkbox" id="${dd.id}" value="campo[${dd.id}]" class="form-control form-control-lg checkbox_all" onclick="$(this).pago_deuda_check()" style="width: 30px; height: 30px;" name="">` : '-';
                                tmp_data1 += `<tr>
                                                <td class="bolder">${dd.fecha}</td>
                                                <td class="bolder nowrap"><span class="text-success" id="deuda_${dd.id}">₡ ${dd.monto}</span></td>
                                                <td class="bolder nowrap">${dd.estado}</td>
                                                <td class="bolder nowrap"><a href="${dd.comprobante}" class="btn btn-primary" target="_blank"><i class="fa fa-download text-white"></i></a></td>
                                            </tr>`;
                            });
                            template.html(tmp_data1)



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

    //Vehiculos

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
                            $("#v_color").val(dataset.color).change();
                            $("#v_marca").val(dataset.marca).change();

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

    $.fn.get_vehiculos_all = function() {
        return this.each(function(){
            let url_edit = $('#url_get_vehiculos_all').val();
            if(url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'token':'123456'},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            $('#modal_vehiculo').modal('show');//abrir el modal

                            $(this).clear_form();

                            let template = $("#tabla_vehiculos").empty();
                            let tmp_data1 = '';
                            let dataset1 = rest.data;




                            dataset1.forEach(function (dd) {
                                tmp_data1 += `<tr>
                                                <td class="bolder">${dd.tipo}</td>
                                                <td class="bolder nowrap">${dd.marca}</td>
                                                <td class="bolder nowrap"><span class="text-success">${dd.placa}</span></td>
                                                <td class="bolder nowrap">${dd.color}</td>
                                                <td class="bolder nowrap">
                                                <button type="button" class="btn btn-success" onclick="$(this).edit_vehiculo('${dd.id}')"><i class="fa fa-pencil"></i></button>
                                                <button type="button" class="btn btn-danger" onclick="$(this).dell_data('${dd.id}','url_dell_vehiculo',false,function(){
                                                    $(this).reloadmin(1)
                                                })"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>`;
                            });
                            template.html(tmp_data1)



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

    $.fn.pago_total_deudas = function(tipo = '') {
        return this.each(function(){
            let url_edit = $('#url_deudas_pendientes_all').val();
            console.log(tipo);
            if(url_edit != '' && tipo != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {tipo},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        }
                        else if(rest.success  == 3){
                            $(this).mensaje_alerta(3,  "Esperando la aceptacion de los pagos");
                            $('#modal_deudas_total').modal('hide');//abrir el modal
                        }else{
                            $('#modal_deudas_total').modal('show');//abrir el modal

                            $(this).clear_form();

                            //inputs
                            $("#tipo_deuda").val(tipo)
                            $("#md_tipo").val(rest.tipo_nombre)
                            $("#md_saldo").val(rest.deuda_total)
                            //
                            let template = $("#tabla_modal_saldo_deudas").empty();
                            let tmp_data1 = '';
                            let dataset1 = rest.data;


                            dataset1.forEach(function (dd) {
                                tmp_data1 += `<tr>
                                                <td class="bolder">${dd.mes}</td>
                                                <td class="bolder nowrap"><span class="text-success" id="deuda_${dd.id}">₡ ${dd.deuda}</span></td>
                                                <td class="bolder nowrap"><input type="checkbox" id="${dd.id}" value="campo[${dd.id}]" class="form-control form-control-lg checkbox_all" onclick="$(this).pago_totlal_check()" style="width: 30px; height: 30px;" name=""></td>
                                            </tr>`;
                            });
                            template.html(tmp_data1)
                            //


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
    $.fn.pago_totlal_check = function() {
        return this.each(function(){
            console.log('check')
            let div_inpus = $(".input_hidden").empty();
            let inputs = '';
            let valor = 0;
            $('.checkbox_all:checked').each(function () {
                let id = $(this).attr("id");

                inputs += `<input type='hidden' name='deuda[]' value="${id}">`;

                let valor2 = $(`#deuda_`+id).text().replaceAll('₡ ','').replaceAll(',','');
                valor += parseFloat(valor2);
            });
            div_inpus.append(inputs)
            $(this).asignacion_numeric("#m_raza",valor);
            return false;
        })
    }

    $.fn.pago_deuda_check = function() {
        return this.each(function(){

            let div_inpus = $(".deudda_input_hidden").empty();
            let inputs = '';
            let valor = 0;
            $('.checkbox_all:checked').each(function () {
                let id = $(this).attr("id");

                inputs += `<input type='hidden' name='afilial[]' value="${id}">`;

                let valor2 = $(`#deuda_`+id).text().replaceAll('₡ ','').replaceAll(',','');
                valor += parseFloat(valor2);
            });

            div_inpus.append(inputs)
            $(this).asignacion_numeric("#m_suma_deuda_valor",valor);
            return false;
        })
    }

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

    $.fn.get_mascotas_all = function() {
        return this.each(function(){
            let url_edit = $('#url_get_mascotas_all').val();
            if(url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'token':'123456'},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            $('#modal_mascota').modal('show');//abrir el modal

                            $(this).clear_form();

                            let template = $("#tabla_mascotas").empty();
                            let tmp_data1 = '';
                            let dataset1 = rest.data;

                            dataset1.forEach(function (dd) {
                                tmp_data1 += `<tr>
                                                <td class="bolder nowrap"><a class='single_image' href="${dd.imagen}"><img  class='rounded-circle' style='height: 40px;width: 40px;' src="${dd.imagen}"></a></td>
                                                <td class="bolder nowrap"><span class="text-success">${dd.nombre}</span></td>
                                                <td class="bolder">${dd.tipo}</td>
                                                <td class="bolder nowrap"><span class="text-success">${dd.raza}</span></td>
                                                <td class="bolder nowrap">${dd.color}</td>
                                                <td class="bolder nowrap">
                                                <button type="button" class="btn btn-success" onclick="$(this).edit_mascotas('${dd.id}')"><i class="fa fa-pencil"></i></button>
                                                <button type="button" class="btn btn-danger" onclick="$(this).dell_data('${dd.id}','url_dell_mascotas',false,function(){
                                                    $(this).reloadmin(2)
                                                })"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>`;
                            });
                            tmp_data1 += `
                            <script>   
                            $('.single_image').fancybox({
                                'zoomSpeedIn': 300,
                                'zoomSpeedOut': 300,
                                'overlayShow': false
                            });
                            </script>`;
                            template.html(tmp_data1)



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


    $.fn.condiciones = function() {
        return this.each(function(){
            let url_edit = $('#url_condiciones').val();
            if(url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'token':'123456'},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            window.location.reload();
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


    $(".single_image").fancybox({
        'zoomSpeedIn': 300,
        'zoomSpeedOut': 300,
        'overlayShow': false
    });


    let terminos = $("#terminos").val();
    if(terminos == 1){
        $("#moda_terminos").modal('show');
    }

})