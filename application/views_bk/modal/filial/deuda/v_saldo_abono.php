<?php
defined('BASEPATH') or exit('No direct script access allowed');
$restante = $deuda->pg_deuda-$cancelado;
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" value="<?=$id?>">
    <input type="hidden" name="mes"   id="saldo_mes">
    <input type="hidden" name="anno"  id="saldo_anno">
    <input type="hidden" name="deuda" id="saldo_deuda">
    <input type="hidden" name="tipo"  id="saldo_tipo">

    <div class="modal-body">
        <div class="row mb-3 pt-2">
            <div class="col-6 col-md-4">
                <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
                    <div class="label">
                        Saldo a cancelar
                    </div>
                    <div class="value" id="saldo_cancelar">
                        ₡ <?=number_format($deuda->pg_deuda)?>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4">
                <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
                    <div class="label">
                        Saldo a Favor
                    </div>
                    <div class="value" id="head_saldo_favor">
                        ₡ <?=$favor?>
                    </div>
                </a>
            </div>

            <div class="col-6 col-md-4">
                <a class="element-box el-tablo centered trend-in-corner smaller" href="#">
                    <div class="label">
                        Saldo cancelado
                    </div>
                    <div class="value" id="saldo_cancelado">
                        ₡ <?=number_format($cancelado)?>
                    </div>
                </a>
            </div>

        </div>

        <?php
        if(in_array($deuda->pg_estado,[1,2]) ):
            ?>
            <div id="div_form">
                <div  class="row mb-3 " >
                    <div class="form-group col-md-4">
                        <label>Saldo a favor</label>
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input class="form-control  text-center imput_reset" readonly id="saldo_favor" value="₡ <?=$favor?>" placeholder="Ingresar Monto..." autocomplete="off" type="text">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    CRC
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Saldo a favor Uso</label>
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input class="form-control favor dinero text-center imput_reset"  name="uso" id="favor_uso" onkeyup="$(this).sumar_saldo_favor();$(this).file_upload_file()" placeholder="Ingresar Monto..." autocomplete="off" type="text">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    CRC
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label>Saldo a pagar</label>
                        <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                            <input class="form-control dinero saldo text-center imput_reset" name="saldo"  id="saldo" onkeyup="$(this).operar_sados();$(this).file_upload_file()"  placeholder="Ingresar Monto..." autocomplete="off" type="text">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    CRC
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div  class="row mb-3 " >
                <div class="form-group col-md-3">
                    <label>Saldo Restante</label>
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input class="form-control  text-center imput_reset" readonly id="saldo_restante" value="₡ <?=number_format($restante)?>" placeholder="Restante..." autocomplete="off" type="text">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                CRC
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label>Total a Cancelar</label>
                    <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                        <input class="form-control  text-center imput_reset" readonly id="saldo_total_cancelar"  placeholder="Total..." autocomplete="off" type="text">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                CRC
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group col-md-6">
                    <label>Comprobante</label>
                        <input type="file" name="imagen" required accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="customFile" >
                </div>

                </div>

                <div  class="row mb-3 " >
                <div class="form-group col-md-12">
                    <div class="progress" style="height: 26px;">
                        <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                    </div>
                </div>
                </div>

            </div>
        <?php
        endif;
        ?>

        <div class="row mb-3  mt-4">
            <div class="col-md-12">
                <div class="element-wrapper">
                    <h6 class="element-header">
                        Abonos Registrados
                    </h6>

                    <div class="element-box">
                        <div class="table-responsive">
                            <table class="table table-padded text-center">
                                <thead>
                                <tr>
                                    <th>
                                        Saldo
                                    </th>
                                    <th>
                                        Estado
                                    </th>
                                    <th>
                                        Comp
                                    </th>
                                    <th>
                                        Fecha Abono
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($abonos)){
                                    foreach ($abonos As $dt){
                                        $saldo  = number_format($dt['aa_monto']);
                                        $fecha = substr($dt['aa_fecha'],0,10);
                                        $estado = $this->class_security->array_data($dt['aa_estado'],$this->class_data->estado_pago_formal);
//                                        $comprobane = $this->class_security->url_documento($dt['aa_comprobante'],2);
                                        $comprobane = '';
                                        echo "<tr>
                                          <td class='bolder nowrap'><b class='text-success'>₡ {$saldo}</b></td>
                                          <td><button class='{$estado['class']}'>{$estado['title']}</button></td>
                                          <td>$comprobane</td>
                                          <td>$fecha</td>
                                       </tr>";
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cerrar</button>
        <?php echo (in_array($deuda->pg_estado,[1,2]) ) ? '<button type="submit" class="btn btn-success waves-effect waves-light">Guardar Comprobante</button>' : ''; ?>
    </div>

</form>

</div>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000','.in_modal_secundario');

        $(this).dinero_func('.dinero');
        $(this).upload_file();


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_comprobante',false,() => {
                    $(this).forms_modal2({"page" : "f_saldo_abono","data1" : "<?=$id?>","title" : "Saldo abono"})
                });
                return false;
            }
        })

    })
</script>
