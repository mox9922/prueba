<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb border-bottom">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-xs-12 justify-content-start d-flex align-items-center">
                <h5 class="font-weight-medium text-uppercase mb-0"><?=$modulo?></h5>
            </div>
            <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                <div class="btn-group2">
                    <a class="btn btn-primary btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'admin_deuda','title' : 'Deudas'})"> <i class="os-icon os-icon-ui-22"></i> Agregar <?php echo $modulo; ?><i class="ti-plus"></i></a>
                </div>
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
                        DEUDAS
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="table-responsive">
                                <table id="tabla_usuario" width="100%" class="table table-striped table-lightfont">
                                    <thead>
                                    <tr>
                                        <th>Filial</th>
                                        <th>Monto</th>
                                        <th>Pagado</th>
                                        <th>Saldo</th>
                                        <th>acción</th>
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
