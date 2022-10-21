<?php
defined('BASEPATH') or exit('No direct script access allowed');


$titulo            = $this->class_security->validate_var($data,'dp_titulo');
$apartamento_id    = $this->class_security->validate_var($data,'dp_apartamento');
$apartamento       = $this->class_security->validate_var($data,'t_apartamento');
$saldo             = $this->class_security->validate_var($data,'dp_saldo');
$comprobante       = $this->class_security->validate_var($data,'dp_comprobante');
$estado            = $this->class_security->validate_var($data,'dp_estado');
$descripcion       = $this->class_security->validate_var($data,'dp_observacion');
$fecha             = $this->class_security->validate_var($data,'dp_atcreate');

if($estado == 1){
    $required = 'required';
}
elseif($estado == 2){
    $required = 'readonly';
}else{
    $required = '';
}



?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-9">
                <label>Nombre Del deposito</label>
                <input type="text" name="nombre"  id="nombre" value="<?=$titulo?>" <?=$required?>  autofocus style="width:100%" class="form-control  " autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Usuario Aportante</label>
                <input type="text" disabled value="<?=$this->user_data->u_nombre?>"  class="form-control text-center  " autocomplete="off">
            </div>


        </div>

        <div class="row mb-3">

            <div class="form-group col-md-3">
                <label>Filial</label>
                <?php
                if($estado  != 2 ){
                    echo ' <select  name="apartamento"  id="apartamento" <?=$required?> autofocus style="width:100%" class="form-control custom-select " autocomplete="off"><option value=""> [ SELECCIONAR ] </option>';
                    foreach($apartamentos As $apt){
                        echo "<option value='{$apt->t_id}'>{$apt->t_apartamento}</option>";
                    }
                    echo '</select>';
                }else{
                    echo "<input type='hidden' name='apartamento' value='$apartamento_id'> <input type='text' value='{$apartamento}' disabled  class='form-control text-center'>";
                }
                ?>
                <div id="myModal"></div>
            </div>

            <div class="form-group col-md-3">
                <label>Saldo</label>
                <input type="text"  name="saldo" id="saldo" value="<?=$saldo?>" <?=$required?>  placeholder="" autofocus  class="form-control saldo text-center dinero " autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Fecha Pago</label>
                <input type="text" name="fecha"  value="<?=fecha(1)?>"  <?=$required?> placeholder="" autofocus  class="form-control fecha text-center " autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Estado</label>
                <?php
                if($estado  != 2 || $estado == ''):
//                    if(strlen($estado)>= 1) {

                        echo '<select  name="estado" id="estado" required class="form-control " > <option value="" selected disabled hidden> [SELECCIONAR] </option>';
                        foreach ($this->class_data->estado_deposito as $pri_id => $pri_val) {
                            if ($estado == $pri_id) {
                                echo "<option value=\"{$pri_id}\"  selected='selected'>{$pri_val['title']}</option>";
                            }elseif($pri_id == 4){} else {
                                echo "<option value=\"{$pri_id}\" >{$pri_val['title']}</option>";
                            }
                        }
                        echo ' </select>';
//                    }else{
//                        echo " <input type='hidden' name='estado'  value='1'><input type='text' value='Crear Nueva Deuda' disabled  class='form-control text-center'>";
//                    }
                else:
                    echo " <input type='text' value='{$this->class_security->array_data($estado,$this->class_data->estado_pago_formal)['title']}' disabled  class='form-control text-center'>";
                endif;
                ?>

            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-12">
                <label>Observacion</label>
                <textarea class="form-control " <?=$required?>  rows="7" name="observacion" id="observacion" autocomplete="off"><?=$descripcion?></textarea>
            </div>

        </div>

        <?php
        if($estado != 2):
        ?>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <label>Comprobante</label>
                <input type="file" name="comprobante"  placeholder="" autofocus  class="form-control" autocomplete="off">

            </div>
        </div>

        <div class="row  mb-3 text-center">
            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>

        </div>

        <?php
            endif;
        ?>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <?php
        if($estado != 2):
          echo '<button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>';
        endif;
        ?>

    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000','.in_modal_secundario');
        $(this).clear_form();
        $(this).selected_func('#estado','<?=$estado?>');
        $(this).select2_func('#apartamento');
        <?php
        if($estado != 2):
            echo "$(this).fecha_func('.fecha','yyyy-mm-dd');";
        endif;
        ?>
        $(this).dinero_func('.saldo');



        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save');
                return false;
            }
        })

    })
</script>