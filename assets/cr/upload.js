$(document).ready(function() {

    $('#frm_data').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            var url_accion = $("#url_comprobante").val();
            if(url_accion.length  > 5) {

                var datastring = new FormData(document.getElementById('frm_data'));
                datastring.append("clave", "valor");
                $.ajax({
                    async: true,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
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
                                $('#progressbar').text('Carga '+percent+'%') ;
                                $('#progressbar').attr('aria-valuenow',percent);
                                $('#progressbar').css("width", percent+"%");
                            }, false);
                        }
                        return xhr;
                    },
                    success: function(result) {
                        if (result.response.success == 1) {
                            window.location.href= result.response.file;
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

    $.fn.importar_documento = function(documento = ''){
        return this.each(function(){

            if(documento != ''){
                Swal.fire({
                    position: 'center-center',
                    // icon: 'success',
                    title: 'Importante la información',
                    timerProgressBar: true,
                    showConfirmButton: false,
                    // timer: 1500
                })

                setTimeout(function(){

                    $.ajax({
                        async: false,
                        cache: false,
                        type: 'post',
                        dataType: 'json',
                        data: {documento},
                        url: $("#url_import").val(),
                        success: function(result) {
                            var res = result.response;
                            if (res.success == 2) {
                                $(this).mensaje_alerta(1, res.msg);
                            } else{
                                $(this).mensaje_alerta(2,'Finalizo EL proceso de importacion');
                                setTimeout(function(){
                                    window.location.reload();
                                },1300)

                            }
                        },
                        error(){
                            $(this).mensaje_alerta(1,  "Error del sistema");
                        }
                    });



                },600);



            }

        })
    }

})