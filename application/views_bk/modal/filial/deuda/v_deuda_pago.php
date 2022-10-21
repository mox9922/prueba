<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="deudda_input_hidden hide2">

        </div>

        <div class="row mb-3">

            <div class="form-group text-center col-md-3">
                <label>Nonbre deuda</label>
                <input type="text" value="<?=$deuda->du_nombre?>" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group text-center col-md-3">
                <label>Deuda</label>
                <input type="text" value="₡ <?=number_format($deuda->du_saldo)?>"" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Saldo a favor</label>
                <input type="text" id="saldo_favor" readonly value="<?=$favor?>"  class="form-control dinero text-center imput_reset" autocomplete="off">
            </div>


            <div class="form-group text-center col-md-3">
                <label>Saldo</label>
                <input type="text" value="₡ <?=number_format($saldo)?>" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-12">
                <label>Observacion deuda</label>
                <textarea id="deuda_observacion" rows="3"  readonly autofocus  class="form-control imput_reset" autocomplete="off"></textarea>
            </div>

        </div>

        <?php

            if($deuda->du_estado == 1 || $saldo < $deuda->du_saldo){
                echo "
        <div class='row mb-3 deuda_estado'>

            <div class='form-group col-md-3'>
                <label>Saldo a Cancelar</label>
                <input type='text' name='saldo' id='saldo' placeholder='₡ 0' autofocus  class='form-control dinero text-center imput_reset' autocomplete='off'>
            </div>
            
             <div class='form-group col-md-3'>
                <label>Pagar Saldo a favor</label>
                <input type='text' id='favor_uso' name='saldo_favor' placeholder='₡ 0' onkeyup='$(this).sumar_saldo_favor(false)' autofocus  class='form-control dinero text-center imput_reset' autocomplete='off'>
            </div>
            
            <div class='form-group col-md-6'>
                <label>Imagen</label>
                <input type='file' id='imagen' name='imagen' required accept='image/png, image/gif, image/jpeg'  autofocus  class='form-control text-center imput_reset' autocomplete='off'>
            </div>

            <div class='form-group mb-4 col-md-12'>
                <label>Observacion Pago</label>
                <textarea name='observacion' rows='3'  autofocus  class='form-control imput_reset' autocomplete='off'></textarea>
            </div>
            

            <div class='form-group  mb-4 col-md-12'>
                <div class='progress' style='height: 26px;'>
                    <div class='progress-bar  progress-bar-striped  progress-bar-animated' role='progressbar' id='progressbar_deuda_total' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'>Barra de progreso</div>
                </div>
            </div>
        </div>";
            }
        ?>


        <div class="row">
            <div class="table-responsive">
                <table class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Fecha Pago
                        </th>
                        <th>
                            Valor Ingresado
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            Comp.
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id_data = encriptar($dt->dp_id);
                            $monto  = number_format($dt->pd_monto);
                            $fecha = substr($dt->pd_atcreate,0,10);
                            $estado = $this->class_security->array_data($dt->pd_estado,$this->class_data->estado_pago);
                            $comprobane = $this->class_security->url_documento($dt->pd_comprobante,2);
                            echo "<tr>
                                          <td>$fecha</td>
                                          <td class='bolder nowrap'><b class='text-success'>₡ {$monto}</b></td>
                                          <td><button class='{$estado['class']}'>{$estado['title']}</button></td>
                                          <td>$comprobane</td>
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
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cerrar</button>
        <?php if($deuda->du_estado == 1 ||  $saldo < $deuda->du_saldo) echo '<button type="submit" class="btn btn-success waves-effect waves-light deuda_estado">Pagar deuda</button>'; ?>
    </div>


</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-800','.in_modal_secundario');

        $(this).dinero_func('.dinero');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_deuda',false,() => {
                    $(this).forms_modal2({"page" : "f_deuda_pago","data1" : "<?=$id?>","title" : "Deuda Abono"})
                });
                return false;
            }
        })

    })
</script>


