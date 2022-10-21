<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- -------------------------------------------------------------- -->
<!-- Container fluid  -->
<!-- -------------------------------------------------------------- -->
<div class="container-fluid page-content">
    <!-- -------------------------------------------------------------- -->
    <!-- Start Page Content -->
    <!-- -------------------------------------------------------------- -->
    <!-- basic table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card sin_fondo">
                <div class="card-body text-center">
                    <h1 class="card-title" style="font-size: 2.5rem">Lista de Filiales</h1>
                    <div class="element-search autosuggest-search-activator ">
                        <input class="text-center display-3 text-uppercase" placeholder="Buscar..." onkeyup="$(this).buscar_apartamentos()">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row"  id="lista_casas">
    </div>
</div>
<!-- -------------------------------------------------------------- -->
<!-- End Container fluid  -->
<!-- -------------------------------------------------------------- -->
<!-- -------------------------------------------------------------- -->
<!--</div>-->
<div id="modal_principal" class="modal fade  modal fade animated" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-800">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?>  - <b id="label_modal"></b></h4>
                <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body-view"></div>

        </div>
    </div>
</div>
