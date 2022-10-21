$(document).ready(function() {

    // $('#frm_data').validator().on('submit', function(e) {
    //     if (e.isDefaultPrevented()) {
    //         $(this).mensaje_alerta(1, "El campo es obligatorio");
    //         return false;
    //     } else {
    //         var url_accion = $("#url_save").val();
    //         if(url_accion.length  > 5) {
    //
    //
    //             var datastring = new FormData(document.getElementById("frm_data"));
    //             datastring.append("clave", "valor");
    //
    //
    //             $.ajax({
    //                 async: false,
    //                 cache: false,
    //                 type: 'post',
    //                 dataType: 'text',
    //                 data: datastring,
    //                 url: url_accion,
    //                 contentType: false,
    //                 processData: false,
    //                 success: function(data) {
    //                     var result = JSON.parse(data);
    //                     if (result.response.success == 1) {
    //                         window.location.reload();
    //                     }
    //                     else {
    //                         $(this).mensaje_alerta(1, result.response.msg);
    //                     }
    //                 }
    //             });
    //         } else {
    //             $(this).mensaje_alerta(1, "No se pudo realizar el proceso");
    //         }
    //         return false;
    //     }
    // })
    //
    // $.fn.edit_data = function(id_data) {
    //     return this.each(function(){
    //         let url_edit = $('#url_get').val();
    //         if(id_data != '' && url_edit != ''){
    //             $.ajax({
    //                 async: false,
    //                 cache: false,
    //                 type: 'post',
    //                 dataType: 'text',
    //                 data: {'id':id_data},
    //                 url: url_edit,
    //                 success: function(data) {
    //                     let result = jQuery.parseJSON(data);
    //                     if (result.response.success == 2) {
    //                         $(this).mensaje_alerta(1, result.response.msg);
    //                     } else{
    //                         $('#modal_data').modal('show');//abrir el modal
    //                         $("#password").attr('required',false);// required
    //
    //                         $(this).clear_form();
    //
    //                         let dataset = result.response.data;
    //                         $("#data_id").val(dataset.id).change();
    //                         $("#nombre").val(dataset.nombre).change();
    //                         // $("#precio").val(dataset.valor).change();
    //                         $(this).asignacion_numeric('#precio',dataset.valor)
    //                         $("#descripcion").val(dataset.descripcion).change();
    //                         $('#color').minicolors('value', dataset.color);
    //                         $("#estado").val(dataset.estado).change();
    //
    //
    //                     }
    //                 },
    //                 error(){
    //                     $(this).mensaje_alerta(1,  "Error del sistema");
    //                 }
    //             });
    //
    //         }else{
    //             $(this).mensaje_alerta(1,  "Los datos o coinciden ");
    //         }
    //
    //         return false;
    //     })
    // }





    $(this).dataTable_ajax_es('#url_datatable','#tabla_usuario');

})