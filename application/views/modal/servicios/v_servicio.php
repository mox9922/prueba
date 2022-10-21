<?php
defined('BASEPATH') or exit('No direct script access allowed');

$nombre     = '';
$proveedor  = '';
$categoria  = '';
$estado     = '';
$fecha      = '';
$precio     = '';

if(isset($servicio) AND count($servicio) >= 1){
    $nombre     = $servicio['sv_titulo'];
    $proveedor  = $servicio['sv_proveedor'];
    $categoria  = $servicio['sv_categoria'];
    $estado     = $servicio['sv_estado'];
    $fecha      = $servicio['sv_fecha'];
    $precio     = $servicio['sv_valor'];
}
?>
<style>
    .custom-file-label::after {
        left: 0;
        right: auto;
        border-left-width: 0;
        border-right: inherit;
    }
</style>
<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="row">



            <div class="form-group col-md-12">
                <label>Nombre Servicio</label>
                <input type="text" name="nombre"  id="nombre" value="<?=$nombre?>" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-6">
                <label>Proveedor</label>
                <select type="text" name="proveedor"  id="proveedor" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
                    <option value="" disabled>[ SELECCIONAR ]</option>
                    <?php
                    foreach($proveedores As $p) {
                        if($p->sp_id == $proveedor){
                            echo "<option value='$p->sp_id' selected>$p->sp_nombre</option>";
                        }else{
                            echo "<option value='$p->sp_id'>$p->sp_nombre</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label>Categoria</label>
                <select type="text" name="categoria"  id="categoria" required autofocus  class="form-control  imput_reset" autocomplete="off">
                    <option value="" disabled>[ SELECCIONAR ]</option>
                    <?php
                    foreach($categorias As $d)
                    {
                        if($d->sc_id == $categoria){
                            echo "<option value='$d->sc_id' selected>$d->sc_nombre</option>";
                        }else{
                            echo "<option value='$d->sc_id'>$d->sc_nombre</option>";
                        }

                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->estado_pago_simplificado As $e_id => $e_vl)
                    {
                        if($e_id == $estado){
                            echo "<option value='$e_id' selected>$e_vl[title]</option>";
                        }else{
                            echo "<option value='$e_id'>$e_vl[title]</option>";
                        }

                    }
                    ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label>Fecha de pago</label>
                <input type="text" name="fecha"  id="fecha" value="<?=$fecha?>"  required autofocus style="width:100%" class="form-control text-center  imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Precio Servicio</label>
                <input type="text" name="monto"  id="monto" value="<?=$precio?>"  required autofocus style="width:100%" class="form-control  text-center imput_reset" autocomplete="off">
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
        $(this).clear_modal_view('modal-600');

        $(this).fecha_func('#fecha');
        $(this).dinero_func('#monto');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {

                $(this).simple_call('frm_data','url_save');
                return false;
            }
        })

    })
</script>