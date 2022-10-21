<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="modal-body">

    <div class="input_hidden hide">

    </div>

    <div class="row mb-3">

        <div class="form-group col-md-4">
            <label>Tipo de deuda</label>
            <input type="text" value="deuda"  readonly autofocus  class="form-control text-center " autocomplete="off">
        </div>

        <div class="form-group col-md-4">
            <label>Saldo a Pagar</label>
            <input type="text" id="saldo_modal_deuda" value="<?=$saldo?>" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
        </div>

        <div class="form-group col-md-4">
            <label>Saldo Cancelado</label>
            <input type="text" id="saldo_modal_cancelado" value="<?=$saldado?>" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
        </div>



    </div>

    <div class="row">
        <div class="table-responsive">
            <table id="modal_tabla" class="table table-padded text-center">
                <thead>
                <tr>
                    <th>
                        Titulo
                    </th>
                    <th>
                        Saldo
                    </th>
                    <th>
                        Cancelado
                    </th>
                    <th>
                        Fecha Inicio
                    </th>
                    <th>
                        Estado
                    </th>
                    <th>
                        Fehca Creacion
                    </th>
                    <th>
                        Monto Pago
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(isset($datas)){
                    foreach ($datas As $dt){
                        $id_data = encriptar($dt->du_id);
                        $estado = $this->class_data->estado_pago[$dt->du_estado];
                        echo "<tr>
                                          <td>$dt->du_nombre</td>
                                          <td class='bolder nowrap'><b class='text-success'>₡ ".number_format($dt->du_saldo)."</b></td>
                                          <td class='bolder nowrap'><b class='text-success'>₡ ".number_format($dt->saldo_pagado)."</b></td>
                                          <td>$dt->du_fecha_inicio</td>
                                          <td><button class='{$estado['class']}'>{$estado['title']}</button></td>
                                          <td>$dt->du_atcreate</td>
                                          <td><button type='button'  onclick='$(this).forms_modal2({\"page\" : \"f_deuda_pago\",\"data1\" : \"{$id_data}\",\"title\" : \"Deuda Abono\"})'  class='btn btn-primary'  data-toggle='tooltip' data-placement='top' title='Editar'><i class=' fas fa-eye text-white'></i></button></td>
                                              
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
</div>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1200','.in_modal_primario');
        $(this).datatable_func('#modal_tabla');
    })
</script>