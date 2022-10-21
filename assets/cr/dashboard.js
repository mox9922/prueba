$(function () {


    $.fn.reporte = function() {
        return this.each(function(){
        
            var fecha = $("#fecha").val();

            Swal.fire({
                position: 'center-center',
                // icon: 'success',
                title: 'Cargando Reporte',
                timerProgressBar: false,
                showConfirmButton: false,
                allowOutsideClick: false

            })


           setTimeout(function(){
               $.ajax({
                   async: false,
                   cache: false,
                   type: 'post',
                   dataType: 'text',
                   data: {'token' : 111,fecha},
                   url: $("#dasboard_data").val(),
                   success: function(data) {
                       var result = JSON.parse(data);
                       if (result.response.success == 1) {
                           let ddr =result.response.data;

                           var morosos = ddr.morosos;
                           $("#rdeuda,#rpagado,#drdeuda,#drpagado").text('');
                          $("#rdeuda").text(ddr.condominal.deuda);
                          $("#rpagado").text(ddr.condominal.pagado)

                           $("#drdeuda").text(ddr.deuda.deuda);
                           $("#drpagado").text(ddr.deuda.pagado);
                           // $(this).asignacion_numeric("#drdeuda",res.deudad.rdeuda);
                           // $(this).asignacion_numeric("#drpagado",res.deudad.rpagado);

                           $("#morosos").text(ddr.moroso_cantidad);


                           $("#morris-area-chart").empty();

                           Morris.Area({
                               element: "morris-area-chart",
                               data: morosos,
                               xkey: "mes",
                               ykeys: ["agua", "cd", "ce","deuda",'moroso'],
                               labels: ["Agua", "CD", "CE","Deuda",'Morosos'],
                               pointSize: 3,
                               fillOpacity: 0,
                               pointStrokeColors: ["#fc4b6c", "#1e88e5", "#00acc1", "#28f508", "#c10000"],
                               behaveLikeLine: true,
                               gridLineColor: "#e0e0e0",
                               lineWidth: 3,
                               // hideHover: "auto",
                               lineColors: ["#fc4b6c", "#1e88e5", "#00acc1", "#28f508", "#c10000"],
                               hideHover: 'auto',
                               pointSize: 4,
                               parseTime: false,
                               resize: true
                           })


                           Swal.close();

                       }
                       else {
                           $(this).mensaje_alerta(1, result.response.msg);
                           Swal.close();
                       }
                   }
               });
           },1000)

            return false;
        })
    }

    $.fn.mostrar_modal = function(tipo = '',view = '',title = '') {
        return this.each(function(){

            var fecha = $("#fecha").val();

            $(this).forms_modal({'page' : 'dashboard_reporte','data1' : tipo ,'data2' : fecha,'data3' : view,'title' : title})

            return false;
        })
    }



    $(this).reporte();
    $(this).fecha_func('#fecha','yyyy-mm');


});