<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" class="imput_reset">

    <div class="modal-body">

        <div class="row  mt-4">
            <div class="table-responsive">
                <table id="modal_table" class="table table-striped table-padded text-center ">
                    <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Tipo Deuda</th>
                        <th>Monto</th>
                        <th>Fecha Pago</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){

                        foreach($datas as $dt){
                            ?>
                            <tr>
                                <td><?php echo $dt->u_nombre; ?></td>
                                <td><?php echo $this->class_security->array_data($dt->tipo_deuda,$this->class_data->comprobante,''); ?></td>
                                <td><?php echo $dt->aa_monto; ?></td>
                                <td><?php echo $dt->aa_fecha; ?></td>
                            </tr>
                            <?php
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
        $(this).clear_modal_view('modal-900');
        $(this).datatable_export_file_func("#modal_table");

    })
</script>