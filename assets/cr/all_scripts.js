$(document).ready(function () {


    $.fn.select2_form = function() {
        return this.each(function() {
            $(".select2").val("").change();
        })
    }

    $.fn.clear_form = function() {
        return this.each(function() {
            $('.imput_reset').trigger("reset");
            $('.imput_reset').val('').change();
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
                "order": [[ 1, "asc" ]],
                "displayLength": 25,
                "ordering": false,
                "scrollX": true,
                "bLengthChange" : false,
                "bInfo":false,
            }
        );

        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        // $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
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
                    "processing": false,
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

   $.fn.dataTable_ajax_reload_es = function(url_tag,table_tag) {
        return this.each(function() {
            if ($(table_tag).length > 0) {
                // var hide_data = $("#url_datatable").val();
                var hide_data = $(url_tag).val();
               var table =  $(table_tag).DataTable({
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
                });

                setInterval(
                    function(){
                        table.ajax.reload();
                    },
                    3900
                );

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
                    // scrollY: 200,
                    scrollX:        true,
                    scrollCollapse: true,
                    fixedColumns:   true,
                    // scroller: {
                    //     loadingIndicator: true
                    // },
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

                setTimeout(() => {
                    // console.log('cargo')
                    // $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    // $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                },600)

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
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm-dd',

            });
    }

    if ($('.fecha_calendar').length > 0) {
        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        var maxLimitDate = new Date(nowDate.getFullYear() + 1, nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

        $('.fecha_calendar').daterangepicker({
            autoApply: true,
            timePicker: true,

            // timePicker24Hour: true,
            minDate: today,
            locale: {
                separator: " / ",
                format: 'YYYY-MM-DD h:mm',
                daysOfWeek: [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                monthNames: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Setiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
            },

            "firstDay": 1,

            // dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            // dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            // dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        });
    }


    if ($('.fecha_calendar_time').length > 0) {
        $('.fecha_calendar_time').daterangepicker({
            autoApply: true,
            timePicker: true,
            timePicker24Hour: true,

            locale: {
                format: 'YYYY-MM-DD h:mm A'
            },
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
            dropdownParent: $('#myModal'),
            placeholder: "Seleccionar",
            allowClear: true,
            width: "100%",
        });
    }

    if ($('.dinero').length > 0) {
        $(".dinero").autoNumeric('init',{
            aSign:'₡ ',mDec: '2'
        });
    }

    $.fn.fancybox_func = function (elemento = '') {
        return this.each(function () {
            $(elemento).fancybox({
                'transitionIn'	:	'elastic',
                'transitionOut'	:	'elastic',
                'speedIn'		:	600,
                'speedOut'		:	200,
                'overlayShow'	:	false
            });

        })
    }

    $.fn.fecha_func = function (input,formato = 'yyyy-mm-dd') {
        return this.each(function () {
            $(input).pickadate({
                format: formato,
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                weekdaysFull: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
                selectMonths: true,
                container: '#root-picker-outlet',
                selectYears: 100, // Puedes cambiarlo para mostrar más o menos años
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Ok',
                labelMonthNext: 'Siguiente mes',
                labelMonthPrev: 'Mes anterior',
                labelMonthSelect: 'Selecciona un mes',
                labelYearSelect: 'Selecciona un año',
            });

        })
    }

    $.fn.fecha_min_func = function (input) {
        return this.each(function () {
            $(input).datepicker({
                // minDate: 0,
                // startDate: '-0m',
                autoclose: true,
                todayHighlight: true,
                format: 'yyyy-mm',
                startView: "months",
                minViewMode: "months",
                language: 'es'
            });

        })
    }

    $.fn.dinero_func = function (input) {
        return this.each(function () {
            $(input).autoNumeric('init',{
                aSign:'₡ ',mDec: '2'
            });

        })
    }

    $.fn.asignacion_numeric = function (input,valor,decimales = 2) {
        return this.each(function () {
            $(input).autoNumeric('destroy');
            $(input).autoNumeric('init',{
                aSign:'₡ ',mDec: decimales
            });
            $(input).autoNumeric('set', valor);

        })
    }


    $.fn.datehour_func = function (input) {
        return this.each(function () {
            $(input).pickatime({
                format: 'h:i A',
                container: '#root-picker-outlet',
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Ok',
            });

        })
    }

    $.fn.numeros_func = function (input) {
        return this.each(function () {
            $(input).keypress(function (e) {
                if (e.which != 8 && e.which != 13 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    $(this).mensaje_alerta(1,"Solo se aceptan numeros");
                    return false;
                }
            });

        })
    }


    $.fn.selected_func = function (input,valor) {
        return this.each(function () {
            $(input).val(valor).change();
        })
    }

    $.fn.select2_func = function (input) {
        return this.each(function () {
            $(input).select2({
                dropdownParent: $('#myModal'),
                placeholder: "Buscar...",
                allowClear: true,
                width: "100%",
            });
        })
    }


    $.fn.datatable_export_file_func = function (input) {
        return this.each(function () {
            $(input).DataTable({
                dom: "Bfrtip",
                buttons: ["copy", "csv", "excel", "pdf"],
            });
            $(
                ".buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel"
            ).addClass("btn btn-primary mr-1");

        })
    }



    $.fn.datatable_func = function (taghtml,tipo = 'normal',url_tag  = '') {
        return this.each(function () {

            if(tipo == 'normal'){
            $(taghtml).DataTable({ "language": {"url": url_sitio +"assets/data_spanish.json"},
                "displayLength": 10,
                "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
            }
            );
            }else{
                //table ajax
                var hide_data = $(url_tag).val();
                if($(url_tag).length > 0){
                    $(taghtml).DataTable({
                        "language": {"url": url_sitio +"assets/data_spanish.json"},
                        "displayLength": 10,
                        "processing": false,
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

                setTimeout(() => {
                    $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                },600)
            }
        })
    }

    $.fn.datatable_export_func = function (taghtml,tipo = 'normal',url_tag  = '') {
        return this.each(function () {

            if(tipo == 'normal'){
                $(taghtml).DataTable({ "language": {"url": url_sitio +"assets/data_spanish.json"},
                        "displayLength": 10,
                        "lengthMenu": [[10, 25, 50,100,1000,2000], [10, 25, 50,100,1000,2000]],
                    }
                );
            }else{
                //table ajax
                var hide_data = $(url_tag).val();
                if($(url_tag).length > 0){
                    $(taghtml).DataTable({
                        "language": {"url": url_sitio +"assets/data_spanish.json"},
                        "displayLength": 10,
                        "processing": false,
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

                setTimeout(() => {
                    $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                },600)
            }
        })
    }


    $.fn.upload_file = function () {
        return this.each(function () {
            $('.custom-file input').change(function (e) {
                var files = [];
                for (var i = 0; i < $(this)[0].files.length; i++) {
                    let nn = $(this)[0].files[i].name;
                    let nombre = (nn.length < 21) ? nn : nn.substring(0, 21) + '...';
                    files.push(nombre);
                }
                $(this).next('.custom-file-label').html(files.join(', '));
            });
        })
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
            //data titile modal
            if(typeof dataObject.title  !== 'undefined'){
                $("#label_modal").text(dataObject.title);
            }else{
                $("#label_modal").text('');
            }


            if(Object.keys(dataObject).length >= 1){
                $.post(url_form,dataObject,function(data){

                    // $('#modal_data').modal('hide');
                    $(".modal-body-view").empty().append(data);
                    $('#modal_principal').modal('show');
                });
            }else{
                //no se puede procesar
                $(this).mensaje_alerta(1,  "Error al cargar la vista");
            }

            return false;
        })
    }

    $.fn.forms_modal2 = function(dataObject) {
        return this.each(function(){
            var url_form = $("#url_modals").val();
            //data titile modal
            if(typeof dataObject.title  !== 'undefined'){
                $("#label_modal2").text(dataObject.title);
            }else{
                $("#label_modal2").text('');
            }


            if(Object.keys(dataObject).length >= 1){
                $.post(url_form,dataObject,function(data){

                    // $('#modal_data').modal('hide');
                    $(".modal-body-view2").empty().append(data);
                    $('#modal_principal2').modal('show');
                });
            }else{
                //no se puede procesar
                $(this).mensaje_alerta(1,  "Error al cargar la vista");
            }

            return false;
        })
    }

    $.fn.clear_modal_view = function(size = 'modal-500',tag = '.modal-dialog') {
        return this.each(function(){
            $(`${tag}`).removeClass("modal-500");
            $(`${tag}`).removeClass("modal-600");
            $(`${tag}`).removeClass("modal-700");
            $(`${tag}`).removeClass("modal-800");
            $(`${tag}`).removeClass("modal-900");
            $(`${tag}`).removeClass("modal-1000");
            $(`${tag}`).removeClass("modal-1100");
            $(`${tag}`).removeClass("modal-1200");
            $(`${tag}`).removeClass("modal-1300");
            $(`${tag}`).removeClass("modal-1400");
            $(`${tag}`).removeClass("modal-1500");
            $(`${tag}`).removeClass("modal-1600");
            $(`${tag}`).addClass(size);
            return false;
        })
    }

    if(('.custom-file input').length >= 1){
        $('.custom-file input').change(function (e) {
            var files = [];
            for (var i = 0; i < $(this)[0].files.length; i++) {
                let nn = $(this)[0].files[i].name;
                let nombre = (nn.length < 21) ? nn : nn.substring(0, 21) + '...';
                files.push(nombre);
            }
            $(this).next('.custom-file-label').html(files.join(', '));
        });
    }


})



