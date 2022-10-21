<?php
defined('BASEPATH') or exit('No direct script access allowed');


$titulo         = $this->class_security->validate_var($deuda,'du_nombre');
$apartamento_id = $this->class_security->validate_var($deuda,'du_apartamento');
$apartamento    = $this->class_security->validate_var($deuda,'t_apartamento');
$estado         = $this->class_security->validate_var($deuda,'du_estado');
$monto          = $this->class_security->validate_var($deuda,'du_saldo');
$n_pagos        = $this->class_security->validate_var($deuda,'du_n_pagos');
$fecha          = $this->class_security->validate_var($deuda,'du_fecha_inicio');
$descripcion    = $this->class_security->validate_var($deuda,'du_observacion');

if($estado == ''){
    $required = 'required';
}
elseif($estado == 3){
    $required = 'readonly';
}else{
    $required = '';
}

$readonly_input = ($id != '') ? 'readonly' : '';

?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="table-responsive">
            <table class="table table-padded text-center">
                <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Permiso
                    </th>
                    <th>
                        C. Usuarios
                    </th>
                    <th>
                        Asignacion
                    </th>
                </tr>
                </thead>
                <tbody id="tabla_modal">
                <?php
                if(isset($pagos)){
                    $i = 1;
                    foreach($pagos as $pg){

                        echo "<tr> 
                                        <td>{$i}</td>
                                        <td class='bolder nowrap'><span class='text-success'>₡".$this->class_security->dinero($pg['pd_monto'])."</span></td>
                                        <td>{$pg['pd_atcreate']}</td>
                                        <td><button class='{$estado_l['class']}'>{$estado_l['title']}</button></td>
                                        <td>{$comprobante}</td>
                                        <td>{$estado_act}</td>
                                  </tr>";

                        $i++;
                    }
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900','.in_modal_secundario');
        $(this).select2_func('#apartamento');
    })
</script>