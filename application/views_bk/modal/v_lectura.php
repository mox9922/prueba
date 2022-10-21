<?php
defined('BASEPATH') or exit('No direct script access allowed');

$comprobante = ((isset($lectura->comprobante)) AND strlen($lectura->comprobante) >= 10) ? base_url("_files/{$lectura->comprobante}")  : base_url("_files/default.jpg");
$comp_estado = ((isset($lectura->comprobante)) AND strlen($lectura->comprobante) >= 10) ? 'btn-success' : 'btn-dark';


?>

<div class="onboarding-media text-center"><img alt="" src="<?=base_url('assets/');?>img/contador.jpg" width="200px"></div>
<div class="onboarding-content with-gradient">
    <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
        <input type="hidden" name="data_id" value="<?=$id?>">
        <div class="modal-body">

            <div class="row mb-3 text-center">

                <div class="form-group col-md-6">
                    <h4 class="d-block">Lectura Anterior</h4>
                    <input type="text" name="attr" id="lectura_anterior" value="<?=isset($lectura->anterior) ? $lectura->anterior : 0?>" readonly placeholder="Lectura Anterior"  class="form-control  text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-6">
                    <h4 class="d-block">Lectura Actual</h4>
                    <input type="text" id="lectura" name="lectura" value="<?=isset($lectura->actual) ? $lectura->actual: ''?>" placeholder="Lectura Actual" autofocus required class="form-control numeros text-center imput_reset" autocomplete="off">
                </div>

            </div>

            <div class="row  mb-3 text-center">
                <div class="form-group col-md-2">
                    <a href="<?=$comprobante?>"  class="btn <?=$comp_estado?> single_image"><i class="fa fa-eye-slash"></i></a>
                </div>


                <div class="form-group col-md-10">
                    <input class="form-control imput_reset" name="imagen"  accept="image/png, image/gif, image/jpeg"  type="file" id="customFile">
                </div>

            </div>


            <div class="row  mb-3 text-center">
                <div class="form-group col-md-12">
                    <div class="progress" style="height: 26px;">
                        <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                    </div>
                </div>

            </div>

            <div class="row  mb-3 text-center">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success btn-lg waves-effect">Guardar Lectura</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-600');
        $(this).numeros_func('#lectura');
        $(this).upload_file();


        $(".single_image").fancybox({
            'zoomSpeedIn': 300,
            'zoomSpeedOut': 300,
            'overlayShow': false
        });


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save',false,() => {
                    $("#modal_principal").modal('hide')
                });
                return false;
            }
        })

    })
</script>