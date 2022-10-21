<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb border-bottom">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 justify-content-start d-flex align-items-center">
                <h5 class="font-weight-medium text-uppercase mb-0"><?=$modulo?></h5>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        IMPORTAR INFORMACION
                    </div>
                    <div class="card-body">
                        <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
                            <input type="hidden" name="send" value="send">

                            <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <label>Archivo Excel con la información</label>
                                    <input type="file" name="documento" required accept=".xls,.xlsx" class="form-control imput_reset" id="customFile" >
                            </div>
                            </div>

                            <div class="row mb-3">
                            <div class="form-group col-md-12">
                                <div class="progress" style="height: 26px;">
                                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                                </div>
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
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
        <?=$titulo; ?>
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
