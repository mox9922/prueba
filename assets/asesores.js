$(document).ready(function() {

    $.fn.rellamar_turno = function(id) {
        return this.each(function(){
            let url_turno = $('#url_turno_rellamar').val();
            if(url_turno != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: id,
                    url: url_turno,
                    success: function(data) {
                        var respon = data.response;
                        if (respon.success == 1) {

                            //llamar turno
                            var turno = respon.data;
                            //$(this).llamar_turno(turno);
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

    $.fn.turno_espera = function() {
        return this.each(function(){
            let url_turno = $('#url_turno_espera').val();
            let elementos = $('#turno_solicitado').empty();
            let elementos2 = $('#turno_table').empty();
            if(url_turno != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {token : '123456'},
                    url: url_turno,
                    success: function(data) {
                        var respon = data.response;
                        if (respon.success == 1) {

                            var codigos = respon.data;
                            var asesores = respon.asesores;

                            var colores = ['','#333333','#0e4d6c'];

                            codigos.forEach(function(dd){

                                var cls = colores[dd.tipo-1]
                                var template = `
                                 <div class="col-6 col-sm-2 col-xxl-2" onclick="$(this).seleccionar_turno({id : '${dd.id}',asesor : '${dd.asesor}',tipo : '${dd.tipo}'})">
                                          <a class="element-box el-tablo border-tablero centered trend-in-corner smaller" style="background: ${cls}" href="#">
                                              <div class="value vlmargin">${dd.codigo}</div>
                                              <div class="label" style="color:white">${dd.asesor_nombre}</div>
                                          </a>
                                   </div>
                                `;
                                elementos.append(template);
                            });


                            //asesores
                            asesores.forEach(function (dd) {
                                var clienten = (dd.c_nombre != null) ? dd.c_nombre : '';
                                var template2 = `
                                 <tr>
                                    <td class="text-center">${dd.v_nombre}</td>
                                    <td class="text-center">${clienten}</td>
                                    <td class="text-center">${dd.t_turno}</td>
                                    <td class="text-center">${dd.tl_fecha}</td>
                                    <td class="text-center"><button class="btn btn-primary" onclick="$(this).rellamar_turno({id : '${dd.t_id}'})"><i class="fa fa-microphone"></i></button></td>
                                    <td class="text-center"><button class="btn btn-danger" onclick="$(this).finalizar_asesoria({id : '${dd.t_id}'})"><i class="fa fa-times"></i></button></td>
                                 </tr>
                                `;
                                elementos2.append(template2);
                            })
                        }
                    },
                    error(){
                        // $(this).mensaje_alerta(1,  "Error del sistema");
                    }
                });

            }else{
                $(this).mensaje_alerta(1,  "Los datos o coinciden ");
            }

            return false;
        })
    }

    $.fn.seleccionar_turno = function(dato) {
        return this.each(function(){
            // let url_turno = $('#url_turno_atendido').val();
            if(dato.tipo == 1){
                //se llama a la funcion que realiza el curl
                $(this).forms_modal({'page' : 1,'title' : 'Via Tiempos','data1' : dato.id,'data2' : dato.tipo})
                $(this).clear_keyboard();
                // $("#solicitud_id").val(dato.id)
                // $("#solicitud_tipo").val(dato.tipo)
            }else{

                $(this).call_curl(dato)
            }

            return false;
        })
    }

    $.fn.finalizar_asesoria = function(id = '') {
        return this.each(function(){
            let url_turno = $('#url_finalizar_asesoria').val();

            if(url_turno != '' && id != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: id,
                    url: url_turno,
                    success: function(data) {
                        var respon = data.response;
                        if (respon.success == 1) {
                           $(this).mensaje_alerta(2,  'Atendido...');



                        }else{
                            $(this).mensaje_alerta(1,  respon.msg);
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

    $.fn.solicitar_pemriso = function() {
        return this.each(function(){
            let url_turno = $('#url_asesor_solicitar_permiso').val();

            var asesor = $("#vendedor_id").val()
            var permiso = $("#permiso").val()
            var tipo = $("#tipo").val()

            if(url_turno != '' && asesor != '' && permiso != '' && tipo != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {asesor,permiso,tipo},
                    url: url_turno,
                    success: function(data) {
                        var respon = data.response;
                        if (respon.success == 1) {

                            $("#modal_data").modal('hide');



                        }else{
                            $(this).mensaje_alerta(1,  respon.msg);
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


    $.fn.buscar_asesor = function() {
        return this.each(function(){
            let url_turno = $('#url_asesor_busqueda').val();

            var asesor = $("#cajon_input").val()

            if(url_turno != '' && asesor != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {asesor},
                    url: url_turno,
                    success: function(data) {
                        var respon = data.response;
                        if (respon.success == 1) {

                            $("#solicitar_permiso").show();
                            $("#buscar_asesor").hide();

                            //campos
                            $("#vendedor_id").val(respon.data.id)
                            $("#vendedor_nombre").val(respon.data.nombre)
                            $("#vendedor_estado").val(respon.data.estado)


                        }else{
                            $(this).mensaje_alerta(1,  respon.msg);
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

    $.fn.buscar_documento = function() {
        return this.each(function(){
            let url_turno = $('#url_atender_busqueda').val();


            var id = $("#solicitud_id").val()
            var asesor = $("#cajon_input").val()
            var tipo = $("#solicitud_tipo").val()


            if(url_turno != '' && id != '' && tipo != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {id,asesor,tipo},
                    url: url_turno,
                    success: function(data) {
                        var respon = data.response;
                        if (respon.success == 1) {
                           // $(this).mensaje_alerta(2,  'Atendido...');
                            $("#modal_data").modal('hide')

                            //llamar turno
                            var turno = respon.data;
                            //$(this).llamar_turno(turno);

                        }else{
                            $(this).mensaje_alerta(1,  respon.msg);
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

    $.fn.llamar_turno = function(dato) {
        return this.each(function(){

            var sonido = `${url_sitio}/assets/mp3/91926__corsica-s__ding.wav`
            var sound = new Howl({
                src: [sonido],
                volume: 1.0,
                onend: function () {
                }
            });
            sound.play()

             setTimeout(function () {

                 let utter = new SpeechSynthesisUtterance();
                 utter.lang = 'es-VE';
                 utter.voiceURI = "native";
                 utter.text = dato;
                 utter.pitch  = 1;
                 utter.rate  = 0.8;
                 utter.volume = 1;

                 utter.onend = function() {}

                 window.speechSynthesis.speak(utter);
             },1300)

            return false;
        })
    }

    $.fn.call_curl = function(dato) {
        return this.each(function(){
            let url_turno = $('#url_atender_turno').val();



            if(url_turno != ''){
                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: dato,
                    url: url_turno,
                    success: function(data) {
                        var respon = data.response;
                        if (respon.success == 1) {

                            //llamar turno
                            var turno = respon.data;
                            //$(this).llamar_turno(turno);
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

    setInterval(function(){

        $(this).turno_espera();
        // $(this).turno_solicitado();
        return false;
    },1000)
})