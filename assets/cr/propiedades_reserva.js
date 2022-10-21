$(document).ready(function() {

    $('#frm_update').validator().on('submit', function(e) {
        if (e.isDefaultPrevented()) {
            $(this).mensaje_alerta(1, "El campo es obligatorio");
            return false;
        } else {
            var url_accion = $("#url_update_save").val();
            if(url_accion.length  > 5) {

                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'text',
                    data: $("#frm_update").serialize(),
                    url: url_accion,
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

    if ($("#fullCalendar").length) {

        var url_accion = $("#url_get_all").val();
        if(url_accion.length  > 5) {


            $.ajax({
                async: false,
                cache: false,
                type: 'post',
                dataType: 'json',
                data: {'token':'12343'},
                url: url_accion,
                success: function(result) {
                    if (result.response.success == 1) {

                        // var calendarEl = document.getElementById('fullCalendar');
                        $("#fullCalendar").fullCalendar({
                            header: {
                                left: "prev,next today",
                                center: "title",
                                right: "month,agendaWeek,agendaDay"
                            },
                            selectable: true,
                            selectHelper: true,
                            // editable: false,
                            displayEventTime : false,
                            events: result.response.data,
                            initialView: 'dayGridMonth',
                            selectConstraint: {
                                start: $.fullCalendar.moment().subtract(1, 'days'),
                                end: $.fullCalendar.moment().startOf('month').add(999, 'month')
                            },
                            eventClick: function(calEvent, jsEvent, view) {
                                $(this).event_data(calEvent.id);
                            },
                            select: function(start, end, jsEvent, view) {
                                let propiedad_defualt = $("#id").val();
                                var fecha_inicial = moment(start).format();
                                var fecha_final = moment(end-1).format();
                                $(this).forms_modal({'page' : 'f_reserva','data1' : propiedad_defualt, 'data2' : fecha_inicial.substring(0,10),'data3' : fecha_final.substring(0,10),'title' : 'Reelizar Reserva Propiedades'})

                             //   $(this).seleccionar_dias(fecha_inicial.substring(0,10),fecha_final.substring(0,10));
                            }
                        });
                        // calendar.render();
                        // console.log(result.response.data)

                    }
                }
            });
        }


    }

    $.fn.clonar_hora = function(input,output){
        let entrada = $(`#${input}`).val();
        if(entrada != ''){
            $(`.${output}`).val(entrada).change();
        }
    }

    $.fn.eliminar_hora = function(id){
            $(`#id_${id}`).remove();
            $(this).evento_dinero();
     }


    $.fn.seleccionar_propiedad = function() {
        return this.each(function(){
            let id = $(this).val();
            let url_edit = $('#url_propiedad_get').val();

            if(id != ''&& url_edit != ''){
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
                            $("#m_cantidad").val(rest.data.p_cantidad).change();
                            $(this).asignacion_numeric('#m_valor',rest.data.p_valor).change();
                            $("#cantidad").val('').prop('readonly',false)
                            $(this).evento_dinero()
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

    $.fn.event_data = function(id) {
        return this.each(function(){
            let url_edit = $('#url_reserva_data').val();

            var fecha_modal = $(".fecha_modal");
            var hora_modal1 = $(".hora_modal1");
            var hora_modal2 = $(".hora_modal2");
            var modal_footer = $("#update_footer");

            if(id != ''&& url_edit != ''){
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

                            $("#evento_data").modal('show');

                            $("#m_f_id").val(rest.data.id);
                            $("#m_titulo").val(rest.data.titulo);
                            $("#m_f_fecha").val(rest.data.fecha);
                            $("#m_f_inicio").val(rest.data.start).change();
                            $("#m_f_final").val(rest.data.end).change();

                            if(rest.data.estado == 1 && rest.data.mod == 1){
                                modal_footer.show();
                                fecha_modal.prop( "disabled", false ).pickadate({format: 'yyyy-mm-dd',container: '#root-picker-outlet'})
                                hora_modal1.prop( "disabled", false ).pickatime({format: 'h:i A',container: '#root-picker-outlet'})
                                hora_modal2.prop( "disabled", false ).pickatime({format: 'h:i A',container: '#root-picker-outlet'})

                            }else{
                                //destroy
                                modal_footer.hide();
                                fecha_modal.prop( "disabled", true )
                                hora_modal1.prop( "disabled", true )
                                hora_modal2.prop( "disabled", true )
                            }

                            //validar si pueden editar o no la reserva


                            // $(this).edit_data($("#data_id").val());
                            // $(this).mensaje_alerta(2, 'Se Actualizo el estado');
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

    $.fn.evento_eliminar = function() {
        return this.each(function(){
            let url_edit = $('#url_delete').val();
            var id = $("#m_f_id").val();

            if(id != ''&& url_edit != ''){
                $(this).dell_data(id    ,'url_delete');
            }else{
                $(this).mensaje_alerta(1,  "Los datos o coinciden ");
            }
            return false;
        })
    }

    $.fn.evento_dinero = function() {
        return this.each(function(){
            let precior   = $('#m_valor').val().replaceAll('₡ ','').replaceAll(',','');
            var cantidad  = $(".reset_table_hora").length;
            $(this).asignacion_numeric("#precio",parseFloat(precior) * parseInt(cantidad));
            return false;
        })
    }


})