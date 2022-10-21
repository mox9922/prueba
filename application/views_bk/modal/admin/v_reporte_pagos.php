<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" class="imput_reset">

    <div class="modal-body">

        <div class="row  mt-4">
            <div class="table-responsive">
                <table id="" class="table table-striped table-padded text-center dataTable_report1">
                    <thead>
                    <?php

                    $template  = '<tr><th rowspan="3">Filial</th>';
                    $tmp1 = '<tr>';
                    $tmp2 = '<tr>';
                    $fase1 = '<th colspan="5">Cuota Agua</th><th colspan="4">Cuota Extraordinaria</th><th colspan="4">Cuota Condominal</th><th colspan="4">Acomulado</th><th colspan="3">Deuda</th>';
                    $fase2 = '
                            <th>Lectura</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th><th>Favor</th>
                                <th>Deuda</th><th>Pago</th><th>saldo</th>
                                ';
                        foreach($fechas As $f){
                            $template .= "<th colspan='16'>{$f}</th>";
                            $tmp1  .= $fase1;
                            $tmp2  .= $fase2;
                        }

                    $tmp1 .= '</tr>';
                    $tmp2 .= '</tr>';
                    $template .= "</tr>{$tmp1}{$tmp2}";
                    echo $template;

                    ?>

                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){

                        for($i = 0 ;$i < count($datas);$i++){
                            $dd = $datas[$i];
                            echo "<tr>";

                            for($i2 = 0 ;$i2 < count($datas[0]);$i2++){
                                $dd2 = $dd[$i2];
                                echo "<td>$dd2</td>";
                            }
                            echo "</tr>";

                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cerrar Ventana</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1200');
        $(this).datatable_func("#modal_table");


        $('.dataTable_report1').DataTable(
            {
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'print'
                ],
                "language": {"url": url_sitio +"assets/data_spanish.json"},
                scrollX: true,
            }
        );

      setTimeout(() => {
          $($.fn.dataTable.tables( true ) ).css('width', '100%');
          $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
      },600)

    })
</script>