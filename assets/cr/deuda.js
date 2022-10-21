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

//     $.fn.edit_data = function(id_data) {
//         return this.each(function(){
//             let url_edit = $('#url_get').val();
//             if(id_data != '' && url_edit != ''){
//                 $.ajax({
//                     async: false,
//                     cache: false,
//                     type: 'post',
//                     dataType: 'text',
//                     data: {'id':id_data},
//                     url: url_edit,
//                     success: function(data) {
//                         let result = jQuery.parseJSON(data);
//                         if (result.response.success == 2) {
//                             $(this).mensaje_alerta(1, result.response.msg);
//                         } else{
//                             $('#modal_data').modal('show');//abrir el modal
//                             $(this).clear_form();
//                             let dataset = result.response.data;
//
//                             var fecha = $("#fecha_cobro");
//
//                             $("#data_id").val(dataset.id).change();
//                             $("#nombre").val(dataset.nombre).change().attr('readonly', true);
//
//                             $("#apartamento").select2();
//                             $("#apartamento").select2('destroy');
//                             $("#apartamento").val(dataset.apartamento).attr('readonly',true);
//
//                             $("#saldo").val(dataset.cuota).change().attr('readonly', true);
//                             $("#n_pagos").val(dataset.n_pagos).change().attr('readonly', true);
//                             fecha.removeClass('fecha_calendar_min');
//                             fecha.val(dataset.fecha_deuda).change().attr('readonly', true)
//                             $("#observacion").val(dataset.observacion).change();
//                             $("#estado").val(dataset.estado).change();
//
//                             let template = $("#tabla_modal").empty();
//                             let tmp_data1 = '';
//                             let dataset1 = result.response.data.cuotas;
//
//                             let i = 1;
//                             dataset1.forEach(function (dd) {
//
//                                 tmp_data1 += `<tr>
//                                                 <td class="bolder nowrap">${i}</td>
//                                                 <td class="bolder nowrap"><span class="text-success">₡ ${dd.deuda}</span></td>
// <!--                                                <td class="bolder nowrap"><span class="text-success">₡ ${dd.consignado}</span></td>-->
//                                                 <td class="bolder nowrap">${dd.fecha}</td>
//                                                 <td class="bolder nowrap">${dd.estado}</td>
//                                                 <td class="bolder nowrap">${dd.comprobante}</td>
//                                                 <td class="bolder nowrap">${dd.select}</td>
//
//                                             </tr>`;
//                                 i++;
//                             });
//                             template.html(tmp_data1)
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

    //cuota,pagos

    // if ($('.fecha_calendar_min').length > 0) {
    //     var dateToday = new Date();
    //     $('.fecha_calendar_min').datepicker({
    //         minDate: 0,
    //         startDate: '-0m',
    //         autoclose: true,
    //         todayHighlight: true,
    //         format: 'yyyy-mm',
    //         startView: "months",
    //         minViewMode: "months",
    //         language: 'es'
    //
    //     });
    // }
    //
    // if ($('.ajax_apartamento').length > 0) {
    //     $(".ajax_apartamento").select2({
    //         placeholder: "Buscar Apartamento",
    //         allowClear: true,
    //         ajax: {
    //             type: "post",
    //             url: $("#url_ajax_apartamento").val(),
    //             dataType: 'json',
    //             delay: 250,
    //             data: function(params) {
    //                 var query={
    //                     message:params.term,
    //                     data: params
    //                 }
    //                 return {
    //                     q: params.term, // search term
    //                     page: params.page,
    //                 }
    //             },
    //             processResults: function (response) {
    //                 return {
    //                     results: response
    //                 };
    //             },
    //             cache: true
    //
    //         }
    //     });
    // }

    $.fn.actualizar_estado_comprobantes = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_cambiar_estado').val();
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

                            let id = $("#data_id").val();
                            $(this).forms_modal2({"page" : "admin_deuda","data1" : id,"title" : "Deuda Filial"});
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
    $(this).dataTable_ajax_es('#url_datatable','#tabla_usuario');


})