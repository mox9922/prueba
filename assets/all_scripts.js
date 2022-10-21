$(document).ready(function () {


    $.fn.select2_form = function() {
        return this.each(function() {
            $(".select2").val("").change();
        })
    }

    $.fn.clear_form = function() {
        return this.each(function() {
            $('.imput_reset').trigger("reset");
            $('.imput_reset').val("");
            $('.imput_reset').prop('checked',false);
            $('.imput_reset_check').prop('checked',false);
            $('.frm_data select').attr('readonly',false).val("");
            $('.select2').val("").change();
            $(".custom-control-input").prop('checked',false);
        })
    }

    $.fn.clear_keyboard = function() {
        return this.each(function() {
            var cajon_texto = $(".caja_texto");
            var cajon_input = $("#cajon_input");

            cajon_texto.html('');
            cajon_input.val('');
        })
    }

    $.fn.clear_form_modal = function() {
        return this.each(function() {
            $('#frm_modal .imput_reset_modal:input').trigger("reset");
            $('#frm_modal .imput_reset_modal:input').val("");
            $('.frm_data select2').val("").change();
            // $(".custom-control-input").prop('checked',false);
        })
    }


    if ($('.dataTable_report1').length > 0) {
        $('.dataTable_report1').DataTable(
            { "language": {"url": url_sitio +"assets/data_spanish.json"},
                "displayLength": 25,
                // "lengthMenu": [[5], [5]],
            }
        );
    }



    if ($('.dataTable_es').length > 0) {
        $('.dataTable_es').DataTable(
            { "language": {"url": url_sitio +"assets/data_spanish.json"},
                "displayLength": 25
            }
        );
    }

    $.fn.dataTable_ajax_es = function(url_tag,table_tag) {
        return this.each(function() {
            if ($(table_tag).length > 0) {
                // var hide_data = $("#url_datatable").val();
                var hide_data = $(url_tag).val();
                $(table_tag).DataTable({
                    "language": {"url": url_sitio +"assets/data_spanish.json"},
                    "displayLength": 25,
                    "processing": true,
                    "retrieve": true,
                    "serverSide": true,
                    "columnDefs": [{"className": "text-center", "targets": "_all"}],
                    "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    "ajax": {
                        "url": hide_data,
                        "type": "POST",
                        'data': {
                           'status' :  'ddd'
                        }
                    }
                })

            }
        })
    }

    $.fn.dataTable_ajax_params_es = function(url_tag,table_tag,params1 = '',params2 = '',params3 = '') {
        return this.each(function() {
            if ($(table_tag).length > 0) {
                // var hide_data = $("#url_datatable").val();
                var hide_data = $(url_tag).val();
                $(table_tag).DataTable({
                    "language": {"url": url_sitio +"assets/data_spanish.json"},
                    "displayLength": 25,
                    "processing": true,
                    "retrieve": true,
                    "serverSide": true,
                    "columnDefs": [{"className": "text-center", "targets": "_all"}],
                    "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    "ajax": {
                        "url": hide_data,
                        "type": "POST",
                        'data': {
                           'dato' :  params1,
                           'dato2' :  params2,
                           'dato3' :  params3
                        }
                    }
                })

            }
        })
    }

    if ($('.pickadate').length > 0) {
        $('.pickadate').pickadate({
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyymmdd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            selectMonths: true,
            selectYears: 100, // Puedes cambiarlo para mostrar más o menos años
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        });
    }

    if ($('.fecha_input').length > 0) {
            $('.fecha_input').datepicker({
                // Strings and translations
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd',


            });
    }

    if ($('.fecha_calendar').length > 0) {
        $('.fecha_calendar').daterangepicker({
            autoApply: true,
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        });
    }


    if ($('#picker_from').length > 0) {
    var from_$input = $('#picker_from').pickadate({
            min: 0,
            format: 'yyyy-mm-dd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        }),
        from_picker = from_$input.pickadate('picker');

    var to_$input = $('#picker_to').pickadate({
            format: 'yyyy-mm-dd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        }),
        to_picker = to_$input.pickadate('picker');

        if (from_picker.get('value')) {
            to_picker.set('min', from_picker.get('select'));
        }
        if (to_picker.get('value')) {
            from_picker.set('max', to_picker.get('select'));
        }

        from_picker.on('set', function (event) {
            if (event.select) {
                to_picker.set('min', from_picker.get('select'));
            } else if ('clear' in event) {
                to_picker.set('min', false);
            }
        });
        to_picker.on('set', function (event) {
            if (event.select) {
                from_picker.set('max', to_picker.get('select'));
            } else if ('clear' in event) {
                from_picker.set('max', false);
            }
        });
    }

    if ($('#fecha_inicio').length > 0) {

        let configDate = {
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyymmdd',
            monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
            selectMonths: true,
            selectYears: 100, // Puedes cambiarlo para mostrar más o menos años
            today: 'Hoy',
            clear: 'Limpiar',
            close: 'Ok',
            labelMonthNext: 'Siguiente mes',
            labelMonthPrev: 'Mes anterior',
            labelMonthSelect: 'Selecciona un mes',
            labelYearSelect: 'Selecciona un año',
        };

        var from_$input = $('#fecha_inicio').pickadate(configDate),
            from_picker = from_$input.pickadate('picker');

        var to_$input = $('#fecha_final').pickadate(configDate),
            to_picker = to_$input.pickadate('picker');


        if (from_picker.get('value')) {
            to_picker.set('min', from_picker.get('select'));
        }
        if (to_picker.get('value')) {
            from_picker.set('max', to_picker.get('select'));
        }

        from_picker.on('set', function (event) {
            if (event.select) {
                to_picker.set('min', from_picker.get('select'));
            } else if ('clear' in event) {
                to_picker.set('min', false);
            }
        });
        to_picker.on('set', function (event) {
            if (event.select) {
                from_picker.set('max', to_picker.get('select'));
            } else if ('clear' in event) {
                from_picker.set('max', false);
            }
        });

        // $('#fecha_final').bootstrapMaterialDatePicker({
        //     weekStart: 0,lang: 'es', format: 'YYYY-MM-DD HH:mm'
        // });
        //
        // $('#fecha_inicio').bootstrapMaterialDatePicker({
        //     weekStart: 0,lang: 'es', format: 'YYYY-MM-DD HH:mm', shortTime : true
        // }).on('change', function(e, date) {
        //     $('#fecha_final').bootstrapMaterialDatePicker('setMinDate', date);
        // });
    }

    if ($('.select2').length > 0) {
        $(".select2").select2({
            placeholder: "Seleccionar",
            allowClear: true,
            width: "100%",
        });
    }

    if ($('.dinero').length > 0) {
        $(".dinero").autoNumeric('init',{
            aSign:'$ ',mDec: '2'
        });
    }

    if ($('.panel_form').length > 0) {

            $(".panel_form").block({
                message: '<i class="fas fa-spin fa-sync text-white"></i>',
                // timeout: 2000, //unblock after 2 seconds
                overlayCSS: {
                    backgroundColor: '#000',
                    opacity: 0.5,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });

    }


    $.fn.panel_unblock = function () {
        return this.each(function () {
            $(".panel_form").unblock();
        })
    }


    if ($('.numeros').length > 0) {
        $(".numeros").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                $(this).mensaje_alerta(1,"Solo se aceptan numeros");
                return false;
            }
        });
    }


    $.fn.mensaje_alerta = function (tipo = '', descripcion = '') {
        return this.each(function () {

            if (tipo != '' && descripcion != '') {

                switch (tipo) {
                    case 1:
                        tipo = "error";
                        break;
                    case 2:
                        tipo = "success";
                        break;
                    case 3:
                        tipo = "info";
                        break;
                    case 4:
                        tipo = "warning";
                        break;
                    default:
                        tipo = "warning";
                        break;
                }
            }
            else {
                tipo = "warning";
                descripcion = "Error de Aplicacion";
            }

            Lobibox.notify("" + tipo + "", {
                title: project_title,
                msg: "" + descripcion + "",
            });
        })
    }


    // delegate calls to data-toggle="lightbox"
    $(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
        event.preventDefault();
        return $(this).ekkoLightbox({
            onShown: function() {
                if (window.console) {
                    return console.log('Checking our the events huh?');
                }
            },
            onNavigate: function(direction, itemIndex) {
                if (window.console) {
                    return console.log('Navigating ' + direction + '. Current item: ' + itemIndex);
                }
            }
        });
    });
    //Programatically call
    $('#open-image').click(function(e) {
        e.preventDefault();
        $(this).ekkoLightbox();
        $(this).ekkoLightbox();
    });
    $('#open-youtube').click(function(e) {
        e.preventDefault();
        $(this).ekkoLightbox();
    });
    // navigateTo
    $(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
        event.preventDefault();
        var lb;
        return $(this).ekkoLightbox({
            onShown: function() {
                lb = this;
                $(lb.modal_content).on('click', '.modal-footer a', function(e) {
                    e.preventDefault();
                    lb.navigateTo(2);
                });
            }
        });
    });

    $.fn.forms_modal = function(dataObject) {
        return this.each(function(){

            var url_form = $("#url_modals").val();

            if(Object.keys(dataObject).length >= 1){
                $.post(url_form,dataObject,function(data){

                    // $('#modal_data').modal('hide');
                    $(".modal-body-view").empty().append(data);
                    $('#modal_data').modal('show');
                });
            }else{
                //no se puede procesar
                $(this).mensaje_alerta(1,  "Error al cargar la vista");
            }

            return false;
        })
    }


    $.fn.clear_modal_view = function(size = 'modal-500') {
        return this.each(function(){
            $(".modal-dialog").removeClass("modal-500");
            $(".modal-dialog").removeClass("modal-600");
            $(".modal-dialog").removeClass("modal-700");
            $(".modal-dialog").removeClass("modal-800");
            $(".modal-dialog").removeClass("modal-900");
            $(".modal-dialog").removeClass("modal-1000");
            $(".modal-dialog").removeClass("modal-1100");
            $(".modal-dialog").removeClass("modal-1200");
            $(".modal-dialog").addClass(size);
            return false;
        })
    }



})