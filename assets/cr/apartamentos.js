$(document).ready(function() {

    $('#frm_data').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            var url_accion = $("#url_save").val();
            if(url_accion.length  > 5) {


                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: $('#frm_data').serialize(),
                    url: url_accion,
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
                            $("#torre").val(dataset.torre).change();
                            $("#apartamento").val(dataset.apartamento).change();
                            $("#estado").val(dataset.estado).change();
                            $("#cuota").val(dataset.cuota).change();
                            $("#tipo").val(dataset.tipo).change();
                            $("#tipo_estado").val(dataset.estado_afilial).change();

                            let template = $("#tabla_modal").empty();
                            let tmp_data1 = '';
                            let dataset1 = result.response.data.cuotas;

                            let i = 1;
                            dataset1.forEach(function (dd) {
                                tmp_data1 += `<tr>
                                                <td class="bolder nowrap">${i}</td>
                                                <td class="bolder nowrap"><span class="text-success">₡ ${dd.ce_valor}</span></td>
                                                <td class="bolder nowrap">${dd.ce_cuota}</td>
                                                <td class="bolder nowrap">${dd.ce_fecha}</td>
                                                <td class="bolder nowrap">${dd.ce_atcreate}</td>
                                                <td class="bolder nowrap">
                                                <button type="button" class="btn btn-danger" onclick="$(this).dell_data('${dd.ce_id}','url_dell_saldo_extrordinario',false,function(){
                                                    let id_data = $('#data_id').val();
                                                    $(this).edit_data(id_data);
                                                })"><i class="fa fa-times"></i></button>
                                                </td>
                                            </tr>`;
                                i++;
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

    //cuota,pagos
    $("#cuota_ex,#n_pagos").on('change',function () {

        var couta = $("#cuota_ex").val().replaceAll('₡ ','').replaceAll(',','');
        var pagos = $("#n_pagos").val();

        var resp = 0;
        if(couta >= 1 && pagos >= 1){
            resp = couta/pagos;
        }

        $(this).asignacion_numeric('#m_pagar',resp)
    })



    $('#cuota,#cuota_ex,#n_pagos').on('change keyup click  input paste',function () {
        var couta = $("#cuota").val().replaceAll('₡','').replaceAll(' ','').replaceAll(',','');
        var pagos2 = $("#m_pagar").val().replaceAll('₡','').replaceAll(' ','').replaceAll(',','');

        var pagos = (pagos2 >= 1) ? pagos2 : 0;

        var resp = 0;
        if(parseFloat(couta) >= 1 && parseFloat(pagos) >= 0){
            resp = parseFloat(couta)+parseFloat(pagos);
        }

      $(this).asignacion_numeric('#p_total',resp)


    })

    if ($('.fecha_calendar_min').length > 0) {
        var dateToday = new Date();
        $('.fecha_calendar_min').datepicker({
            minDate: 0,
            startDate: '-0m',
            autoclose: true,
            todayHighlight: true,
            format: 'yyyy-mm',
            startView: "months",
            minViewMode: "months",
            language: 'es'

        });
    }
    
    //parametro de usuario
    $(this).dataTable_ajax_es('#url_datatable','#tabla_usuario');


})