<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data"  autocomplete="off">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">

    <div class="modal-body">

        <div class="row mb-3">

            <div class="form-group col-md-8">
                <label>Nombre Servicio</label>
                <input type="text" value="<?=$data->sv_titulo?>" readonly autofocus style="width:100%" class="form-control  imput_reset">
            </div>

            <div class="form-group col-md-4">
                <label>Monto</label>
                <input type="text" value="₡ <?=$this->class_security->dinero($data->sv_valor)?>" readonly autofocus style="width:100%" class="form-control text-center imput_reset" autocomplete="off">
            </div>
        </div>

        <?php
        if($this->user_data->u_perfil == 6 and $data->sv_estado == 1):
        ?>
        <div class="row mb-3">
            <div class="form-group col-md-12">
                <label>Comprobante</label>
                <input type="file" name="comprobante" required accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="customFile" >
            </div>
        </div>

        <div class="row mb-3">

            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>

        </div>
        <?php
         endif;
        ?>

        <div class="table-responsive mt-5">
            <table  id="modal_tabla" class="table table-padded text-center">
                <thead>
                <tr>
                    <th>
                        Documento
                    </th>
                    <th>
                        Fecha Registro
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(isset($comprobantes)){
                    foreach($comprobantes as $pg){

                        $fecha =  $pg->st_atcreate;
                        $comprobante = $this->class_security->url_documento($pg->st_comprobante,1,'w-100');

                        echo "<tr> 
                                        <td>{$comprobante}</td>
                                        <td>{$fecha}</td>
                                  </tr>";

                    }
                }
                ?>
                </tbody>
            </table>
        </div>


    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <?=($this->user_data->u_perfil == 6) ? '<button type="submit" class="btn btn-success waves-effect waves-light">Guardar </button>' : '';?>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-600');

        $(this).datatable_func('#modal_tabla');
        $(this).upload_file();

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {

                $(this).simple_call('frm_data','url_comprobante_save');
                return false;
            }
        })

    })
</script>