$(document).ready(function() {

    $('#frm_data').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            var url_accion = $("#url_lectura_save").val();
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
                            percent = 0;
                            $(this).mensaje_alerta(2, 'Lectura Guardada');
                            $("#modal_data").modal('hide');
                        }
                        else {
                            $(this).mensaje_alerta(1, result.response.msg);
                        }

                        $('#progressbar_mascota').text('Barra de progreso') ;
                        $('#progressbar_mascota').attr('aria-valuenow',100);
                        $('#progressbar_mascota').css("width", "20%");
                    }
                });
            } else {
                $(this).mensaje_alerta(1, "No se pudo realizar el proceso");
            }
            return false;
        }
    })


    $.fn.data_propiedad = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_lectura_get').val();
            if(id_data != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'id':id_data},
                    url: url_edit,
                    success: function(result) {
                        var res = result.response;
                        if (res.success == 2) {
                            $(this).mensaje_alerta(1, res.msg);
                        } else{

                            $("#modal_data").modal('show');
                            $("#data_id").val(id_data);
                            $("#lectura_anterior").val(res.anterior);
                            $("#lectura").val(res.actual);
                            $(".single_image").attr('href',res.imagen)

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

    $.fn.buscar_apartamentos = function() {
        return this.each(function(){
            var id_data  = $(this).val();
            let url_edit = $('#url_search').val();
            var template = $("#lista_casas").empty();

            if(id_data != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'data':id_data},
                    url: url_edit,
                    success: function(result) {
                        var res = result.response;
                        if (res.success == 1) {

                            var tmp_data = "";
                            let dataset = res.data;

                            //visitantes
                            dataset.forEach(function (dd) {
                                tmp_data += `
                                            <div class='col-sm-3 mb-3'>
                                                <a class='element-box el-tablo centered trend-in-corner padded bold-label' href='javascript:void(0)'  onclick='$(this).forms_modal({"page" : "lectura_medidor","data1" : "${dd.id}","title" : "Lectura de medidor"})'' >
                                                    <div class='avatar'>
                                                        <img src='${url_sitio}assets/img/casa.jpg' class='img-responsive' style='border-radius: 50px; width: 100px; height: auto;' />
                                                    </div>
                                                    <div class='value'>
                                                        ${dd.letra}
                                                    </div>
                                                </a>
                                            </div>
`;
                            });
                            template.html(tmp_data)
                        }
                    },
                    error(){
                        $(this).mensaje_alerta(1,  "Error del sistema");
                    }
                });

            }

            return false;
        })
    }

    $('.single_image').fancybox({
        'zoomSpeedIn': 300,
        'zoomSpeedOut': 300,
        'overlayShow': false});



})