<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- -------------------------------------------------------------- -->
<!-- Container fluid  -->
<!-- -------------------------------------------------------------- -->
<div class="container-fluid page-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                   COMPROBANTES DE PAGO
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="table-responsive">
                            <table id="table_data" class="table table-padded text-center  w-100">
                                <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th>Monto</th>
                                    <th>Fecha Registro</th>
                                    <th>Factura</th>
                                    <th>Comprobante</th>
                                    <th>Estado</th>
                                </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
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
