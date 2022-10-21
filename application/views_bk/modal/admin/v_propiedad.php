<?php
defined('BASEPATH') or exit('No direct script access allowed');

    $nombre     = $this->class_security->validate_var($propiedad,'p_nombre');
    $precio     = $this->class_security->validate_var($propiedad,'p_valor');
    $estado     = $this->class_security->validate_var($propiedad,'p_estado');
    $cantidad   = $this->class_security->validate_var($propiedad,'p_cantidad');
    $color      = $this->class_security->validate_var($propiedad,'p_color');
    $descipcion = $this->class_security->validate_var($propiedad,'p_descripcion');

?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
    <input type="hidden" id="url_acción">

    <div class="modal-body">

        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label>Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?=$nombre?>" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Precio</label>
                <input type="text" id="precio" name="precio" value="<?=$precio?>"  placeholder="precio" autofocus required class="form-control dinero text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Estado</label>
                <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                    <option value=""> [ SELECCIONAR ] </option>
                    <?php
                    foreach($this->class_data->estado_simple As $e_id => $e_vl)
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

        </div>

        <div class="row mb-3">
               <div class="form-group col-md-7">
                   <label>Imagen Principal</label>
                   <input type="file" id="imagen" accept="image/png, image/gif, image/jpeg" name="imagen" placeholder="Usuario" autofocus  class="form-control imput_reset" autocomplete="off">
               </div>

               <div class="form-group col-md-2">
                   <label>Cantidad</label>
                   <input type="text"  name="cantidad" value="<?=$cantidad?>"  id="cantidad" required autofocus  class="form-control text-center  imput_reset" autocomplete="off">
               </div>

               <div class="form-group col-md-3">
                   <label>Color Propiedad</label>
                   <input type="text"  name="color" id="color" value="<?=$color?>"  required data-control="hue" value="#ff6161" autofocus  class="form-control text-center  imput_reset" autocomplete="off">
               </div>
           </div>

        <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label>Imagen 1</label>
                        <input type="file" name="imagen1"  accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="customFile" >
                </div>

                <div class="form-group col-md-6">
                    <label>Imagen 2</label>
                        <input type="file" name="imagen2"  accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="customFile" >
                </div>

        </div>

        <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label>Imagen 3</label>
                        <input type="file" name="imagen3"  accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="customFile" >
                </div>

                <div class="form-group col-md-6">
                    <label>Imagen 4</label>
                        <input type="file" name="imagen4"  accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="customFile" >
                </div>

        </div>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>

        </div>

        <div class="row">
               <div class="form-group col-md-12">
                   <label>Descripción</label>
                   <textarea id="descripcion" name="descripcion" placeholder="descripcion" rows="7" autofocus required class="form-control imput_reset" autocomplete="off"><?=$descipcion?></textarea>
               </div>
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
        $(this).clear_modal_view('modal-900');

        $(this).numeros_func('#cantidad');
        $(this).dinero_func('#precio');
        $(this).upload_file();
        $('#color').minicolors({theme: 'bootstrap'});

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

