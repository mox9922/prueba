<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="content-i">
    <div class="content-box">
        <div class="element-wrapper">
            <h6 class="element-header">
                <?=$modulo?>
            </h6>
            <div class="element-box">

                <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
                    <input type="hidden" name="send" value="send">

                    <div class="form-group col-md-12">
                        <label>Archivo Excel con la información</label>
                        <div class="custom-file d-flex flex-row-reverse">
                            <input type="file" name="documento" required accept=".xls,.xlsx" class="custom-file-input imput_reset" id="customFile" >
                            <label class="custom-file-label text-right" for="customFile">Seleccionar archivos</label>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="progress" style="height: 26px;">
                            <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success waves-effect waves-light">Importar Información</button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>


<?php
if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>
