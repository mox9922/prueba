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

    $.fn.calcular = function() {
        return this.each(function(){

            $("#total,#monto").on('change',function () {
                let total = $('#total').val().replaceAll('₡ ','').replaceAll(',','');
                let monto = $('#monto').val().replaceAll('₡ ','').replaceAll(',','');


                let calculo =(monto/total);

                var res = 0;
                if(calculo != 'NAN'){
                    res = calculo;
                }
                let dollarUS = Intl.NumberFormat("es-CR", {
                    style: "currency",
                    currency: "CRC",
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                });


                $(this).asignacion_numeric('#m3',res,2);
                // $().val().change();

            })

            return false;
        })
    }

    $.fn.cambio_anno = function() {
        return this.each(function(){
            let url_edit = $('#url_report').val();
            let id_data = $(this).val();
            if(id_data != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'anno':id_data},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{

                            var table = $('#tabla_x').DataTable().clear().draw();
                            table.destroy();

                            var t = $('#tabla_x').DataTable(
                                {
                                    "scrollX": true,
                                    "language": {"url": url_sitio +"assets/data_spanish.json"},
                                    "order": [[ 1, "asc" ]],
                                    "displayLength": 25,
                                    "ordering": false,
                                    "bLengthChange" : false,
                                    "bInfo":false,
                                }
                            );
                            let dataset1 = rest.data;


                            dataset1.forEach(function (dd) {
                                var total = 0;
                                var monto = 0;
                                var m3 = 0;
                                var id = '';
                                // let mes = dd.mes;
                                // let anno = dd.anno;


                                console.log( dd.nombre)

                            t.row.add( [
                                    dd.nombre,
                                `<span class="text-success fw-bold">${dd.d1}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d2}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d3}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d4}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d7}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d5}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d8}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d6}</span>`,
                                `<span class="text-success font-weight-bold">${dd.d9}</span>`,
                                `<span class="text-success font-weight-bold">${dd.btn}</span>`,
                                  ``,
                                  ``,
                                  ``,
                                  ``,
                                  ``,
                                  ``,
                                  /*
                                    ,
                                    `<span class="text-success">${monto}</span>`,
                                    `<span class="text-success">${m3}</span>`,
                                    `<button type="button" class="btn btn-primary waves-effect waves-light" onclick="$(this).view_report_general('${dd.mes}','${dd.anno}','${id_data}')"><i class="fas fa-tasks text-white"></i></button>
                                                <button type='button' {$disabled} onclick='$(this).dell_data("${id}","dell_pago","si",function(){
                                                window.location.reload();
                                            })' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar {$mes_val}'><i class='fas fa-times text-white'></i></button>`,
                                   ,
                                   */

                                ] ).draw( false );




                            });




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

    $.fn.comprobantes_estado_simple = function(id,modulo) {
        return this.each(function(){
            let url_edit = $('#url_cambiar_estado').val();
            let dato = $(this).val();

            if(id != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {id,modulo,dato},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            $(this).mensaje_alerta(2,  "Se Proceso el comprobante");
                            $(this).forms_modal2({'page' : 'admin_control_pagos_comprobantes','title' : 'Comprobantes'})
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

    $.fn.comprobantes_estado = function(id,modulo) {
        return this.each(function(){
            let url_edit = $('#url_cambiar_estado').val();
            let dato = $(this).val();
            let filial = $("#modal_fiial").val();
            let fecha = $("#modal_fecha").val();

            if(id != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {id,modulo,dato},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            let fcom = fecha.split('-');
                            $(this).view_report_general(Number(fcom[1]),fcom[0],fcom[0])
                            $(this).mensaje_alerta(2,  "Se Proceso el comprobante");

                            $(this).forms_modal2({"page" : "admin_pagos_comprobantes","data1" : filial,"data2" : fecha,"title" : "Pagos Comprobantes"})

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


    $.fn.actualizar_estado_deuda = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_cambiar_estado_deuda').val();
            let comp_id = $('#comp_data_id').val();
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
                            let mes = $("#mes").val();
                            let anno = $("#anno").val();
                            $(this).view_report_general(mes,anno,anno);
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


    $.fn.actualizar_estado_comprobantes = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_cambiar_estado_comprobante').val();
            let comp_id = $('#comp_data_id').val();
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
                            var mes = $("#mes").val();
                            var anno = $("#anno").val();
                            var id = $("#comp_data_id").val();
                            $(this).forms_modal2({"page" : "admin_pagos_comprobantes","data1" : id,"title" : "Pagos Comprobantes"})
                            $(this).view_report_general(mes,anno)
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

    $.fn.pagar_deuda_saldo_favor = function(id_data = '') {
        return this.each(function(){
            let url_edit = $('#url_pagar_saldo_favor').val();

            if(id_data != '' &&  url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'id':id_data},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success  == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            var mes = $("#mes").val();
                            var anno = $("#anno").val();
                            $(this).view_report_general(mes,anno,anno)
                            $(this).mensaje_alerta(2, 'Se Registro el pago con saldo a favor');
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

    $.fn.view_report_general = function(mes,anno) {
        return this.each(function(){

            let url_edit = $('#url_estadistica').val();
            if(mes != '' && anno != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {mes,anno},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{
                            //
                            // $('#modal_pagos').modal('show');
                            $(this).forms_modal({'page' : 'admin_pagos','data1' : mes,'data2' : anno,'title' : 'Control de Pagos'})

                            Swal.fire({
                                position: 'center-center',
                                // icon: 'success',
                                title: 'Cargando Reporte',
                                timerProgressBar: true,
                                showConfirmButton: false,
                            })

                            setTimeout(function() {
                                let dataset1 = rest.data;

                                var table = $('#tabla_modal').DataTable().clear().draw();
                                table.destroy();

                                var t = $('#tabla_modal').DataTable(
                                    {
                                        "scrollX": true,
                                        "language": {"url": url_sitio +"assets/data_spanish.json"},
                                    }
                                );

                                dataset1.forEach(function (dd) {


                                    t.row.add( [


                                        dd.apartamento,


                                        `<b class="text-danger">${dd.d_deuda}</b>`,
                                        `<b class="text-danger">${dd.d_acomulado}</b>`,
                                        `<b class="text-danger">${dd.d_resultado}</b>`,

                                        `<b class="text-success">${dd.d_favor}</b>`,

                                        dd.agua_comprobante,


                                        // `<b class="text-success">${dd.total1}</b>`,
                                        `<b class="text-success">${dd.pago1}</b>`,
                                        `<b class="text-success">${dd.saldo1}</b>`,
                                        dd.padof1,
                                   //     dd.compro1,
                                        // `<b class="text-success">${dd.favor1}</b>`,

                                        dd.estado1,

                                        // `<b class="text-success">${dd.total2}</b>`,
                                        `<b class="text-success">${dd.pago2}</b>`,
                                        `<b class="text-success">${dd.saldo2}</b>`,
                                        dd.padof2,
                                     //   dd.compro2,
                                        // `<b class="text-success">${dd.favor2}</b>`,
                                        dd.estado2,

                                        // `<b class="text-success">${dd.total3}</b>`,
                                        `<b class="text-success">${dd.pago3}</b>`,
                                        `<b class="text-success">${dd.saldo3}</b>`,
                                        dd.padof3,
                                       // dd.compro3,
                                        // `<b class="text-success">${dd.favor3}</b>`,
                                        dd.estado3,

                                        `<b class="text-success">${dd.du_deuda}</b>`,
                                        `<b class="text-success">${dd.du_pagado}</b>`,

                                       `<b class="text-success">${dd.comprobante}</b>`,




                                    ] ).draw( false );
                                });

                                setTimeout(function () {
                                    $($.fn.dataTable.tables( true ) ).css('width', '100%');
                                    // $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                                },200);

                                Swal.close();
                            },1000)

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
    };

    $.fn.asinar_saldo_favor = function(id,tipo) {
        return this.each(function(){

            let url_edit = $('#url_asinar_saldo_favor').val();
            if(id != '' &&  url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {id,tipo},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success == 2) {
                            $(this).mensaje_alerta(1, rest.msg);
                        } else{

                            let mes = $('#mes').val();
                            let anno = $('#anno').val();

                            $(this).view_report_general(mes,anno);
                            $(this).mensaje_alerta(2,  "Saldo Asignado");
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
    };

})