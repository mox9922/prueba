<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" class="imput_reset">

    <div class="modal-body">

        <div class="row">



            <div class="form-group col-md-7">
                <label>Nombre</label>
                <input type="text" name="nombre"  id="nombre" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-5">
                <label>Correo</label>
                <input type="text" name="correo"  id="correo" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
            </div>


            <div class="form-group col-md-4">
                <label>Telefono</label>
                <input type="text" name="telefono"  id="telefono" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Categoria</label>
                <select type="text" name="tipo"  id="tipo" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
                    <option value="">[ SELECCIONAR ]</option>
                    <?php
                    foreach($this->class_data->tipo_documentos As $t_id => $t_val) echo "<option value='$t_id'>$t_val</option>";
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Numero Documento</label>
                <input type="text" name="documento"  id="documento" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
            </div>

        </div>

        <div class="row mt-4">
            <div class="table-responsive">
                <table id="modal_table" class="table table-striped table-padded text-center">
                    <thead>
                    <tr>
                        <th> Cegoria</th>
                        <th> Correo</th>
                        <th> Telefono</th>
                        <th> Documento</th>
                        <th>Acci??n</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id = encriptar($dt->sp_id);
                            echo "<tr>
                                          <td>$dt->sp_nombre</td>
                                          <td>$dt->sp_correo</td>
                                          <td>$dt->sp_telefono</td>
                                          <td>$dt->sp_documento</td>
                                          <td>
                                            <div class='btn-group'>
                                          <button type='button' onclick='$(this).edit_proveedor(\"{$id}\");' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                                          <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id}\",\"url_proveedores_delete\",false,function(){
                                                 $(this).forms_modal({\"page\" : \"servicios_proveedores\",\"title\" : \"Categorias\"})
                                          })'><i class='fas fa-times'></i></button>
                                          </div>
                                          </td>
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
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar </button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-800');
        $(this).datatable_func("#modal_table");

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {

                $(this).simple_call('frm_data','url_proveedor_save',false,() => {
                    $(this).forms_modal({'page' : 'servicios_proveedores','title' : 'proveedor'})
                });
                return false;
            }
        })

    })
</script>