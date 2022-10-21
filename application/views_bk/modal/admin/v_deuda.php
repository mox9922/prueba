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
        $required = 'disabled';
    }else{
        $required = '';
    }

?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-12">
                <label>Nombre Deuda</label>
                <input type="text" name="nombre"  id="nombre" value="<?=$titulo?>" <?=$required?>  autofocus style="width:100%" class="form-control  " autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-8">
                <label>Apartamento</label>
                <?php
                    if($apartamento == ''){
                        echo ' <select  name="apartamento"  id="apartamento" required autofocus style="width:100%" class="form-control custom-select " autocomplete="off"><option value=""> [ SELECCIONAR ] </option>';
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

            <div class="form-group col-md-4">
                <label>Estado</label>
                <?php
                if($estado  != 3):
                    if(strlen($estado)>= 1) {

                        echo '<select  name="estado" id="estado" required class="form-control " > <option value="" selected disabled hidden> [SELECCIONAR] </option>';
                        foreach ($this->class_data->estado_pago_formal as $pri_id => $pri_val) {
                            if ($estado == $pri_id) {
                                echo "<option value=\"{$pri_id}\"  selected='selected'>{$pri_val['title']}</option>";
                            }elseif($pri_id == 4){} else {
                                echo "<option value=\"{$pri_id}\" >{$pri_val['title']}</option>";
                            }
                        }
                        echo ' </select>';
                    }else{
                        echo " <input type='hidden' name='estado'  value='1'><input type='text' value='Crear Nueva Deuda' disabled  class='form-control text-center'>";
                    }
                else:
                    echo " <input type='text' value='{$this->class_security->array_data($estado,$this->class_data->estado_pago_formal)['title']}' disabled  class='form-control text-center'>";
                endif;
                ?>

            </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col-md-4">
                <label>Deuda</label>
                <input type="text"  name="saldo" id="saldo" value="<?=$monto?>" <?=$required?>  placeholder="" autofocus  class="form-control text-center dinero " autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label># Pagos</label>
                <input type="text" name="n_pagos"  id="n_pagos" value="<?=$n_pagos?>" <?=$required?>  placeholder="" autofocus  class="form-control text-center " autocomplete="off">

            </div>

            <div class="form-group col-md-4">
                <label>Fecha Cobro</label>
                <input type="text"  name="fecha_cobro"  id="fecha" value="<?=$fecha?>" <?=$required?>  autofocus value="<?php echo date('Y-m'); ?>" class="form-control  text-center " autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-12">
                <label>Observacion deuda</label>
                <textarea class="form-control "  rows="7" name="observacion" id="observacion" autocomplete="off"><?=$descripcion?></textarea>
            </div>

        </div>

        <div class="table-responsive">
            <table class="table table-padded text-center">
                <thead>
                <tr>
                    <th>
                        #
                    </th>
                    <th>
                        Valor
                    </th>
                    <th>
                        Fecha Pago
                    </th>
                    <th>
                        Estado
                    </th>
                    <th>
                        Comp
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

                            $comprobante =  $this->class_security->url_documento($pg['pd_comprobante'],2);
                            $estado_l  = $this->class_security->array_data($pg['pd_estado'],$this->class_data->estado_pago_formal);
                            $estado_act = $this->class_security->select_pago_valor(encriptar($pg['dp_id']),$this->class_data->estado_pago_formal,$pg['pd_estado'],'actualizar_estado_comprobantes');

                            echo "<tr> 
                                        <td>{$i}</td>
                                        <td class='bolder nowrap'><span class='text-success'>₡".number_format($pg['pd_monto'])."</span></td>
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
        <?=($estado != 3) ? '<button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>' : ''?>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900','.in_modal_secundario');
        $(this).clear_form();
        $(this).selected_func('#estado','<?=$estado?>');
        $(this).select2_func('#apartamento');
        $(this).fecha_func('#fecha','yyyy-mm-dd');
        $(this).dinero_func('#saldo');
        $(this).numeros_func('#n_pagos');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
               $(this).simple_call('frm_data','url_save',false,function(){

                   let id = $("#data_id_principal").val();
                   $("#modal_principal2").modal('hide');
                   $(this).forms_modal({"page" : "admin_deuda_lista","data1" : id,"title" : "Dueda"})

               });
               return false;
            }
        })

    })
</script>