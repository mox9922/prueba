$(document).ready(function() {


    $.fn.data_propiedad = function(id_data) {
        return this.each(function(){
            let url_edit = $('#url_get').val();
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
                            var template_propietario = $("#tabla_propietario").empty();
                            var template_vehiculo = $("#tabla_propietario_vehiculo").empty();
                            var template = $("#tabla_invitados").empty();
                            var template_mascotas = $("#tabla_mascotas").empty();
                            var tmp_data4 = '';
                            var tmp_data3 = '';
                            var tmp_data1 = '';
                            var tmp_data = '';
                            let dataset1 = res.propietario;
                            let dataset2 = res.visitante;
                            let dataset3 = res.vehiculos;
                            let dataset4 = res.mascotas;

                            //propietarios
                            dataset1.forEach(function (dd) {
                                tmp_data1 += `<tr>
                                                <td class="bolder">${dd.nombre}</td>
                                                <td class="bolder"><a href="tel:${dd.telefono1}">${dd.telefono1}</a></td>
                                                <td class="bolder"><a href="tel:${dd.telefono2}">${dd.telefono2}</a></td>
                                                <td class="bolder">${dd.propietario}</td>
                                            </tr>`;
                            });
;
                            //Mascotas
                            dataset4.forEach(function (dd) {
                                tmp_data4 += `<tr>
                                                <td class="cell-with-media">${dd.imagen}</td>
                                                <td class="bolder">${dd.nombre}</td>
                                                <td class="bolder">${dd.tipo}</td>
                                                <td class="bolder nowrap">${dd.color}</td>
                                                <td class="bolder">${dd.raza}</td>
                                            </tr>`;
                            });


                            //visitantes
                            dataset2.forEach(function (dd) {
                                tmp_data += `<tr>
                                                <td class="cell-with-media">${dd.imagen}</td>
                                                <td class="bolder">${dd.nombre}</td>
                                                <td class="bolder">${dd.cedula}</td>
                                                <td class="bolder">${dd.tipo}</td>
                                                <td class="bolder">${dd.placa}</td>
                                                <td class="bolder">${dd.estado}</td>
                                            </tr>`;
                            });


                            //vehiculo
                            dataset3.forEach(function (dd) {
                                tmp_data3 += `<tr>
                                                <td class="bolder">${dd.tipo}</td>
                                                <td class="bolder nowrap">${dd.placa}</td>
                                                <td class="bolder nowrap">${dd.color}</td>
                                            </tr>`;
                            })

                            tmp_data += `
                            <script type="text/javascript">
                              $("a.single_image").fancybox({
                                    'zoomSpeedIn': 300,
                                    'zoomSpeedOut': 300,
                                    'overlayShow': false
                                });
                            </script>
                            `
                            template_propietario.html(tmp_data1)
                            template_vehiculo.html(tmp_data3)
                            template_mascotas.html(tmp_data4)
                            template.html(tmp_data)
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

    $.fn.buscar_apartamentos = function(id_data) {
        return this.each(function(){
            // var id_data  = $(this).val();
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
                                            <div class='col-sm-3'>
                                                <a class='element-box el-tablo centered trend-in-corner padded bold-label' href='javascript:void(0)'  onclick='$(this).data_propiedad("${dd.id}")' >
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


    $.fn.data_filial = function() {
        return this.each(function(){
            var id  = $(this).val();
            let url_edit = $('#url_data_filial').val();


            $(".reset_all").val('').change();
            if(id != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {id},
                    url: url_edit,
                    success: function(result) {
                        var res = result.response;
                        if (res.success == 1) {
                            let dt = res.data;
                            if(dt.length > 0){
                                let i = 1;
                                dt.forEach(function(d){
                                    $(`#c_data_nombre_${i}`).val(d.nombre);
                                    $(`#c_data_telefono_${i}`).val(d.telefono);
                                    i++;
                                });
                            }
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

    $.fn.buscar_usuario = function(usuario) {
        return this.each(function(){
            var id_data  = $(this).val();
            let url_edit = $('#url_buscar_usuario').val();

            //Vars
            let nombre = $("#s_nombre");
            let id = $("#s_id");
            let tipo = $("#s_tipo");
            let filial = $("#s_filial");
            let placa = $("#s_placa");
            let invitado = $("#s_tipo_invitado");
            let tipo_ingreso = $("#s_tipo_ingreso");

            if(usuario != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {usuario},
                    url: url_edit,
                    success: function(result) {
                        var res = result.response;
                        if (res.success == 1) {
                            let dt = res.data;
                            id.val(dt.id)
                            tipo.val(dt.tipo2).change();
                            nombre.val(dt.nombre).attr('readonly',true).change();;
                            invitado.val(dt.tipo_invitado).attr('tipo_invitado',true).change();
                            filial.val(dt.apt_id).attr('readonly',true).change();
                            placa.val(dt.placa).change();;
                            tipo_ingreso.val(dt.tipo_ingreso).change();;
                        }else{
                            id.val('')
                            tipo.val('2').change();
                            nombre.val('').attr('readonly',false).change();
                            invitado.val('Llamar..').attr('',true).change();
                            placa.val('').change().change();
                            filial.val('').attr('readonly',false).change();
                            tipo_ingreso.val('').change();
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

    $.fn.validar_acceso = function(apt,tipo) {
        return this.each(function(){
            var id_data  = $(this).val();
            let url_edit = $('#url_validar_acceso').val();

            if(apt != '' && tipo  != '' && url_edit != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {'documento' : apt,tipo,'placa' : 'no','nombre' : 'no','filial' : 'no', 'cono' : 'no'},
                    url: url_edit,
                    success: function(result) {
                        var res = result.response;
                        if (res.success == 1) {

                            window.location.reload();
                        }else{
                            $(this).mensaje_alerta(1, res.msg);
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


    $('#buscador').keyup(debounce(function(){
        var $this=$(this);
        var n1 = $this.val();
        $(this).buscar_apartamentos(n1);
    },500));

    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };


    $(this).dataTable_ajax_es('#url_search_visitante','#tabla_visitante');

})