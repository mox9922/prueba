<?php
defined('BASEPATH') or exit('No direct script access allowed');
//print_r($datas);
?>

<form role="form" data-toggle="validator" method="POST" class="frm_afilial" id="frm_afilial">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="af_data_id" class="imput_reset">

    <div class="modal-body my-0 py-0" >

        <div class="table-responsive">
            <table id="table_modal" class="table table-striped  text-center">
                <thead>
                <tr>
                    <th>
                        Meses
                    </th>
                    <th>
                        Valor M3
                    </th>
                    <th>
                        Total
                    </th>
                    <th>
                        Saldo
                    </th>
                    <th>
                        Estado
                    </th>

                    <th>
                        Cuota Condominal
                    </th>
                    <th>
                        Saldo
                    </th>
                    <th>
                        Estado
                    </th>

                    <th>
                        Cuota Extraordinaria
                    </th>
                    <th>
                        Saldo
                    </th>
                    <th>
                        Estado
                    </th>
<!--                    <th>-->
<!--                        Monto Pago-->
<!--                    </th>-->

                </tr>
                </thead>
                <tbody>
                <?php

                if (isset($datas)) {
                    foreach ($datas as $dt) {

                        $comp_agua = $this->class_security->url_documento($dt['comprobante'],2);

                        $nombre =  $dt['nombre'];
                        $comprobante =  $dt['comprobante'];
                        $mes_nombre = (isset($comprobante) and (strlen($comprobante) >= 10)) ? "<a href='".base_url("_files/{$comprobante}")."'><b>$nombre</b></a>" : $nombre;

                        $deuda1 = $dt['deuda1'];
                        $deuda2 = $dt['deuda2'];
                        $deuda3 = $dt['deuda3'];

                        echo "<tr>
                                          <td>$mes_nombre</td>
                                          <td>$dt[m3]</td>
                                          <td class='bolder nowrap'><b class='text-success'>₡ $deuda1[deuda]</b></td>
                                          <td class='bolder nowrap'><b class='text-success'>₡ $deuda1[abono]</b></td>
                                          <td>$deuda1[estado]</td>
                                           <td class='bolder nowrap'><b class='text-success'>₡ $deuda2[deuda]</b></td>
                                          <td class='bolder nowrap'><b class='text-success'>₡ $deuda2[abono]</b></td>
                                          <td>$deuda2[estado]</td>
                                             <td class='bolder nowrap'><b class='text-success'>₡ $deuda3[deuda]</b></td>
                                          <td class='bolder nowrap'><b class='text-success'>₡ $deuda3[abono]</b></td>
                                          <td>$deuda3[estado]</td>
                                          
                                       </tr>";
                    }
                }

                ?>

                </tbody>
            </table>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cerrar</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1300','.in_modal_primario');

        setTimeout(function() {
            var table = $('#table_modal').DataTable().destroy();
            var t = $('#table_modal').DataTable(
                {
                    "searching" : false,
                    "paging" : false,
                    "info" : false,
                    "ordering" : false,
                    "scrollX": true,
                    "language": {"url": url_sitio +"assets/data_spanish.json"},
                }
            );


            setTimeout(function () {
                $($.fn.dataTable.tables( true ) ).css('width', '100%');
                $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
            },200);

        },1000)



    })
</script>