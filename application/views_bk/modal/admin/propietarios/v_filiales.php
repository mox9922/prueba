<?php
defined('BASEPATH') or exit('No direct script access allowed');

    $bloque     = $this->class_security->validate_var($datas,'t_torre');
    $filial     = $this->class_security->validate_var($datas,'t_apartamento');
    $cuota      = $this->class_security->validate_var($datas,'t_cuota');
    $tipo       = $this->class_security->validate_var($datas,'t_tipo');
    $estadof    = $this->class_security->validate_var($datas,'t_estado_afilial');
    $estado     = $this->class_security->validate_var($datas,'t_estado');
    $correo     = $this->class_security->validate_var($datas,'t_correo');
    $fecha      = date("Y-m");


?>
    <div class="modal-body">

        <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

            <input type="hidden" name="send" value="data">
            <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

            <div class="modal-body">

                <div class="row">
                    <div class="form-group col-md-3">
                        <label>Bloque</label>
                        <input type="text" id="torre" name="torre" value="<?=$bloque?>" placeholder="Bloque" autofocus required class="form-control text-center imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Filial</label>
                        <input type="text" id="apartamento" value="<?=$filial?>" name="apartamento" placeholder="Filial" autofocus required class="form-control text-center imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Cuota Condominal</label>
                        <input type="text" id="cuota" name="cuota" value="<?=$cuota?>" placeholder="Cuota Condominal" onkeyup="$(this).calcular_total();" autofocus required class="form-control text-center dinero imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Pago Total</label>
                        <input type="text"  name="p_total" id="p_total" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Cuota Extraordinaria</label>
                        <input type="text"  name="cuota_ex" id="cuota_ex" placeholder="" autofocus onkeyup="$(this).calcular_extra();$(this).calcular_total();"  class="form-control text-center dinero imput_reset" autocomplete="off">
                    </div>


                    <div class="form-group col-md-3">
                        <label># Pagos</label>
                        <input type="text"  name="n_pagos"  id="n_pagos" placeholder=""onkeyup="$(this).calcular_extra()"  autofocus  class="form-control  imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Fecha Cobro</label>
                        <input type="text"  name="fecha_cobro" value="<?=$fecha?>"  autofocus  class="form-control fecha text-center " autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Monto a pagar</label>
                        <input type="text"  name="m_pagar" id="m_pagar" readonly autofocus  class="form-control text-center dinero imput_reset" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Correo FIlial</label>
                        <input type="email"  name="correo" id="correo"  autofocus  class="form-control imput_reset" value="<?=$correo?>" autocomplete="off">
                    </div>

                    <div class="form-group col-md-3">
                        <label>Tipo</label>
                        <select class="form-control text-center  imput_reset" style="width:100%;" required name="tipo" id="tipo" autocomplete="off">
                            <option value=""> [ SELECCIONAR ] </option>
                            <?php
                            foreach($this->class_data->tipo_filial As $tf_id => $tf_vl){
                                if($tipo == $tf_id){
                                    echo "<option value='{$tf_id}' selected>{$tf_vl}</option>";
                                }else{
                                    echo "<option value='{$tf_id}'>{$tf_vl}</option>";
                                }

                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Estado Afilial</label>
                        <select class="form-control text-center  imput_reset" style="width:100%;" required name="tipo_estado" id="tipo_estado" autocomplete="off">
                            <option value=""> [ SELECCIONAR ] </option>
                            <?php
                            foreach($this->class_data->estado_filial As $tf_id => $tf_vl){
                                if($estadof == $tf_id){
                                    echo "<option value='{$tf_id}' selected>{$tf_vl}</option>";
                                }else{
                                    echo "<option value='{$tf_id}'>{$tf_vl}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label>Estado</label>
                        <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                            <option value=""> [ SELECCIONAR ] </option>
                            <?php
                            foreach($this->class_data->estado_simple As $e_id => $e_vl){
                                if($estado == $e_id){
                                    echo "<option value='{$e_id}' selected>{$e_vl[title]}</option>";
                                }else{
                                    echo "<option value='{$e_id}'>{$e_vl[title]}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Observacion Cuota Exraordinaria</label>
                        <textarea class="form-control imput_reset" rows="4" name="observacion" id="observacion" autocomplete="off"></textarea>
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
                                # Pagos
                            </th>
                            <th>
                                Fecha aplicación
                            </th>
                            <th>
                                Fecha Registro
                            </th>
                            <th>
                                acción
                            </th>
                        </tr>
                        </thead>
                        <tbody id="tabla_modal">
                        <?php
//                        if(isset($extraordinaria)){
//                            $i = 1;
//                            foreach($extraordinaria as $pg){
//
//                                $id_extr = encriptar($pg['ce_id']);
//                                echo "<tr>
//                                        <td>{$i}</td>
//                                        <td class='bolder nowrap'><span class='text-success'>₡".number_format($pg['ce_valor'])."</span></td>
//                                        <td>{$pg['ce_cuota']}</td>
//                                        <td>{$pg['ce_fecha']}</td>
//                                        <td>{$pg['ce_atcreate']}</td>
//                                        <td><button type='button' onclick='$(this).dell_data(\"{$id_extr}\",\"url_dell_filial_extra\",false,function(){
//                                                $(this).forms_modal({\"page\" : \"propietario_filiales\",\"data1\" : \"${id}\",\"title\" : \"Filiales\"})
//                                        })' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></td>
//                                     </tr>";
//
//                                $i++;
//                            }
//                        }
                        ?>
                        </tbody>
                    </table>
                </div>


            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
                <button type="submit" class="btn btn-success waves-effect waves-light">Guardar </button>
            </div>


        </form>

        <div class="row mt-4">
            <div class="table-responsive">
                <table id="modal_table" class="table table-striped table-padded text-center">
                    <thead>
                    <tr>
                        <th>Bloque</th>
                        <th>Filial</th>
                        <th>Tipo</th>
                        <th>Estado Vivienda</th>
                        <th>Cuota Don</th>
                        <th>Estado</th>
                        <th>acción</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>

    </div>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1000');
        $(this).dinero_func('.dinero');
        $(this).fecha_min_func('.fecha','yyyy-mm-dd');
        $(this).numeros_func('#n_pagos');
        $(this).datatable_func("#modal_table",'ajax','#modal_tabla_filiales');

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                    $(this).simple_call('frm_data','url_save_filial',false,() => {
                    $(this).forms_modal({'page' : 'propietario_filiales','title' : 'Filiales'})
                });
                return false;
            }
        })

    })
</script>