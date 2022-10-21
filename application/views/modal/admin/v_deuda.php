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
    $favor          = $this->class_security->validate_var($deuda,'saldo_favor');

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

        <div class="row mb-3">

            <div class="form-group col-md-6">
                <label>Nombre Deuda</label>
                <input type="text" name="nombre"  id="nombre" value="<?=$titulo?>" <?=$required?>  autofocus style="width:100%" class="form-control  " autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Usuario Aportante</label>
                <input type="text" disabled value="<?=$this->user_data->u_nombre?>"  class="form-control text-center  " autocomplete="off">
            </div>

            <div class="form-group col-md-3">
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

            <div class="form-group col-md-3">
                <label>Apartamento</label>
                <?php
                    if($apartamento_id == ''){
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

            <div class="form-group col-md-3">
                <label>Deuda</label>
                <input type="text"  name="saldo" id="saldo" value="<?=$monto?>" <?=$required?> <?=$readonly_input?> placeholder="" autofocus  class="form-control saldo text-center dinero " autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label># Pagos</label>
                <input type="text" name="n_pagos"  id="n_pagos" value="<?=$n_pagos?>" <?=$required?>  placeholder="" autofocus  class="form-control text-center " autocomplete="off">

            </div>

            <div class="form-group col-md-3">
                <label>Fecha Cobro</label>
                <input type="text"  name="fecha_cobro"  id="fecha" value="<?=$fecha?>" <?=$required?>  autofocus value="<?=fecha(1); ?>" class="form-control fecha text-center " autocomplete="off">
            </div>

        </div>

        <div class="row mb-3">


            <div class="form-group col-md-2">
                <label>Saldo a favor</label>
                <input type="text" id="saldo_favor" readonly value="₡ <?=$this->class_security->dinero($favor)?>"  class="form-control  text-center " autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Pagar Saldo a favor</label>
                <input type="text" id="favor_uso" name="saldo_favor"  placeholder="₡ 0"  onkeyup="$(this).sumar_saldo_favor(true);" autofocus  class="form-control  saldo text-center " autocomplete="off">
            </div>


            <div class="form-group col-md-3">
                <label>Saldo Cancelar</label>
                <input type="text"  name="add_saldo"  placeholder="₡ 0" autofocus  class="form-control text-center saldo " autocomplete="off">
            </div>

            <div class="form-group col-md-2">
                <label> Comprobante</label>
                <input type="text"  name="add_numero"  placeholder="" autofocus  class="form-control text-center  " autocomplete="off">
            </div>

            <div class="form-group col-md-2">
                <label>Fecha Pago</label>
                <input type="text" name="add_fecha"  value="<?=fecha(1)?>"  placeholder="" autofocus  class="form-control fecha text-center " autocomplete="off">
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <label>Observacion de pago</label>
                <textarea class="form-control "  rows="2" name="add_observacion" autocomplete="off"></textarea>
            </div>
        </div>

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
       <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000','.in_modal_secundario');
        $(this).clear_form();
        $(this).selected_func('#estado','<?=$estado?>');
        $(this).select2_func('#apartamento');
        $(this).fecha_func('.fecha','yyyy-mm-dd');
        $(this).dinero_func('.saldo');
        $(this).numeros_func('#n_pagos');

        $.fn.sumar_saldo_favor = function(calcular  = true) {
            return this.each(function() {

                let salor_favor = $("#saldo_favor").val().replaceAll('₡ ','').replaceAll(',','');
                let favor = $("#favor_uso").val().replaceAll('₡ ','').replaceAll(',','');


                //limpiar
                let favor_input = (salor_favor >= 1) ? parseFloat(salor_favor) : 0;
                let favor_clear = (favor >= 1) ?  parseFloat(favor) : 0;

                //validar que no ingre mas de lo indicado
                if(favor_clear > favor_input){
                    $("#favor_uso").val('').change();
                    $(this).mensaje_alerta(1,  "El Valor Ingresado No puede superar el saldo a favor");
                    favor_clear = 0;
                }

                if(calcular == true){
                    $(this).operar_sados();
                }

                return false;
            })
        }


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