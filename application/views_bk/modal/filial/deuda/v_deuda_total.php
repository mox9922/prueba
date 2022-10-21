<?php
defined('BASEPATH') or exit('No direct script access allowed');
$existencia = count($datas);
?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="tipo_deuda" value="<?=$dataid?>">

    <div class="modal-body">

        <div class="input_hidden hide">

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Tipo de deuda</label>
                <input type="text" id="md_tipo" readonly value="<?=$tipo_nombre?>"  class="form-control text-center imput_reset" autocomplete="off">
            </div>


            <div class="form-group col-md-4">
                <label>Total a pagar</label>
                <input type="text"  readonly value="<?=$deuda_total?>"  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Total a pagar</label>
                <input type="text" id="m_raza" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

        </div>

        <?php
            if($existencia >= 1):
        ?>

         <div class="row mb-3">
            <div class="form-group col-md-4">
                <label>Saldo a favor</label>
                <input type="text" id="saldo_favor" readonly value="<?=$favor?>"  class="form-control favor dinero text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Pagar Saldo a favor</label>
                <input type="text" id="favor_uso" name="saldo_favor" onkeyup="$(this).sumar_saldo_favor(false);$(this).file_upload_file()" autofocus  class="form-control favor dinero text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Saldo a Cancelar</label>
                <input type="text" name="m_saldo_cancelar" onkeyup="$(this).file_upload_file()"  autofocus  class="form-control dinero saldo text-center imput_reset" autocomplete="off">
            </div>

        </div>

      <div class="row mb-3">
            <div class="form-group col-md-12">
                <label>Comprobante</label>
                <input type="file" name="imagen" required accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="formFile" >
            </div>
      </div>

     <div class="row mb-3">
            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar_deuda_total" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso</div>
                </div>
            </div>
        </div>
        <?php
        endif;
        ?>

        <div class="row">
            <div class="table-responsive">
                <table id="modal_tabla" class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Nombre Deuda
                        </th>
                        <th>
                            Fecha
                        </th>
                        <th>
                            Total
                        </th>
                        <th>
                            Abono
                        </th>
                        <th>
                           Monto Pago
                        </th>

                    </tr>
                    </thead>
                    <tbody id="">
                    <?php
                    if(isset($datas)) {
                        foreach ($datas as $dt) {
                            $nombre   = $dt['nombre'];
                            $mes   = $dt['fecha'];
                            $id    = $dt['id'];
                            $deuda = $dt['deuda'];
                            $abono = $dt['abono'];

                            echo "<tr>
                                                    <td class='bolder'>$nombre</td>
                                                    <td class='bolder'>$mes</td>
                                                    <td class='bolder nowrap'><span class='text-success' id='deuda_$id'>₡ $deuda</span></td>
                                                    <td class='bolder nowrap'><span class='text-success' id='deuda_$id'>₡ $abono</span></td>
                                                    <td class='bolder nowrap'><input type='checkbox' id='$id' value='campo[$id]' class='form-check-input checkbox_all' onclick='$(this).pago_totlal_check(this.checked)' style='width: 30px; height: 30px;' name='deuda_check'></td>                                                    
                                              </tr>";
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <?php
        if($existencia >= 1): echo '<button type="submit" class="btn btn-success waves-effect waves-light">Pagar deuda</button>'; endif;
        ?>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-800');

        $(this).upload_file();
         $(this).dinero_func('.dinero');
         $(this).datatable_func('#modal_tabla');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_deuda_total',false,() => {
                    $("#actualizar_deudas").load(location.href+" #actualizar_deudas>*","");
                    $("#actualizar_favor").load(location.href+" #actualizar_favor>*","");
                    $(this).forms_modal({"page" : "f_dueda_total","data1" : "<?=desencriptar($dataid)?>","title" : "Total Deudas "})
                });
                return false;
            }
        })




    })

</script>
