$(document).ready(function() {

    //parametro de usuario
    var tipo_usuario = $("#tipo_usuario").val();
    $(this).dataTable_ajax_params_es('#url_datatable','#tabla_usuario',tipo_usuario);


})