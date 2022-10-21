$(document).ready(function() {

    // $('.fecha_reporte').datepicker({
    //     // Strings and translations
    //     autoclose: true,
    //     todayHighlight: true,
    //     format: 'yyyy-mm',
    //     startView: "months",
    //     minViewMode: "months"
    // });
    $(this).fecha_func('.fecha_reporte','yyyy-mm');

    $.fn.reporte_data = function() {
        return this.each(function(){
            let tipo_reporte = $('#tipo_reporte').val();
            let fecha = $('#fecha_reporte').val();
            let final = $("#fecha_final").val();

            let url_edit = $('#url_reporte_data').val();
            let template = $('#data_reporte').empty();

            var template_html = '';


            // $(this).forms_modal({'page' : 'admin_reporte_pagos','data1' : fecha,'data2' : final,'title' : 'Informe de pagos'})

            if(tipo_reporte != '' && fecha != ''&& url_edit != ''){


                $.ajax({
                    async: false,
                    cache: false,
                    type: 'post',
                    dataType: 'json',
                    data: {fecha,final},
                    url: url_edit,
                    success: function(result) {
                        let rest = result.response;
                        if (rest.success == 2) {
                            $(this).mensaje_alerta(1, rest.msg);

                        } else {
                            // let response = result.response;
                            let datas = rest.data;
                            let fechas = rest.fechas;
                            // let dollarUSLocale = Intl.NumberFormat('en-US');

                            $('#tabla_usuario').DataTable();
                            // return false;
                            var table = $('#tabla_usuario').DataTable().clear().draw();
                            table.destroy();

                            var template = '<tr><th rowspan="3" class="text-center">Filial</th>';
                            var head = $("#tabla_head").empty();
                            var body = '';
                            var bodyt = $("#tabla_body").empty();
                            // var head_tr = `<tr><td>Filial</td><td>Febereo 23 -15</td></tr>`;
                            var tmp1 = '<tr>';
                            var tmp2 = '<tr>';
                            var fase1 = '<th colspan="5" class="text-center">Agua</th><th colspan="4" class="text-center">Condominal</th><th colspan="4" class="text-center">Extraordinaria</th><th colspan="3" class="text-center">Deuda</th><th colspan="4" class="text-center">Acomulado</th>';
                            var fase2 = ` <th>Lectura</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                `;



                            fechas.forEach((dd) => {

                                template += `<th colspan='20' class='text-center'>${dd}</th>`;
                                tmp1  += fase1;
                                tmp2  += fase2;
                            })
                            tmp1 += '</tr>';
                            tmp2 += '</tr>';
                            template += `</tr> ${tmp1} ${tmp2}`;
                            head.append(template);


                            //body

                            for(let i = 0 ;i < datas.length;i++){
                                dd = datas[i];
                                body += "<tr>";

                                for(let i2 = 0 ;i2 < datas[0].length;i2++){
                                    dd2 = dd[i2];
                                    body += `<td class="text-center">${dd2}</td>`;
                                }
                                body += "</tr>";

                            }

                            bodyt.append(body);

                            //
                            //
                            // var t = $('#tabla_modal').DataTable(
                            //     {
                            //         "language": {"url": url_sitio +"assets/data_spanish.json"},
                            //         dom: 'Bfrtip',
                            //         buttons: [
                            //             'copy', 'csv', 'excel', 'pdf', 'print'
                            //         ]
                            //     }
                            // );
                            $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-facebook mr-1');

                            $('#tabla_usuario').DataTable(
                                { "language": {"url": url_sitio +"assets/data_spanish.json"},
                                    "order": [[ 1, "asc" ]],
                                    "ordering": false,
                                    "scrollX": true,
                                    "bLengthChange" : false,
                                    "bInfo":false,
                                }
                            );




                            setTimeout(function () {
                                $($.fn.dataTable.tables( true ) ).css('width', '100%');
                                // $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
                            },200);
                            // template.append(template_html)
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

    $.fn.reporte_exportar = function(){
     return this.each(function(){
         $('<table>')
             .append(
                 $("#tabla_usuario thead").html()
             )
             .append(
                 $("#tabla_usuario").DataTable().$('tr').clone()
             )
             .table2excel({
                 exclude: ".xls",
                 name: "casting",
                 filename: "ExportedData.xls"
             });
     })
    }

    $.fn.build_table_header = function (fechas = []) {
        return this.each(function(){
            let head = $("#reporte_head").empty();
            let template  = '<tr><th rowspan="3">Filial</th>';
            let tmp1 = '<tr>';
            let tmp2 = '<tr>';

            let fase1 = ` <th colspan="3">Agua</th><th colspan="2">Extraordinaria</th><th colspan="2">Condominal</th><th colspan="2">Deuda</th>`;
            let fase2 = `<th>Lectura</th><th>Deuda</th><th>Pago</th><th>Deuda</th><th>Pago</th><th>Deuda</th><th>Pago</th><th>Deuda</th><th>Pago</th>`;
            fechas.forEach(function(dd){
                template += `<th colspan="9">${dd}</th>`;
                tmp1  += fase1;
                tmp2  += fase2;
            });
            tmp1 += '</tr>';
            tmp2 += '</tr>';
            // console.log(tmp1)
            template += `</tr>${tmp1}${tmp2}`;
            head.append(template)
            $("#template_css").append("<style>div.dataTables_wrapper {  width: 100%; margin: 0 auto; }</style>");
            // console.log(template);
        })
    }

    $.fn.filtrar_informacion_tipo = function () {
        return this.each(function(){
            let tipo = $("#tipo_reporte").val();
            let inicial = $("#fecha_reporte");
            let final = $("#fecha_final");
             if(tipo == 1){
                 //simplificado
                 final.val('').attr('disabled',true);
             }else{
                 final.val('').attr('disabled',false);
             }

        })
    }

    //entrando valide la informacion
    $(this).filtrar_informacion_tipo();
    $(this).reporte_data();
});



