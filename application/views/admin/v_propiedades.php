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
                    <a class="btn btn-dark btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'propiedades','title' : 'Propeidades'})"> <i class="os-icon os-icon-ui-22"></i> Agregar <?php echo $modulo; ?></a>
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
                        PROPIEDADES
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="table-responsive">
                                <table id="tabla_usuario" width="100%" class="table table-striped table-lightfont">
                                    <thead>
                                    <tr>
                                        <th>Imagen</th>
                                        <th>Nombre Propiedad</th>
                                        <th>Precio</th>
                                        <th>Esado</th>
                                        <th>acci??n</th>
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
