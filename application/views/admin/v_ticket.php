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
            <div class="col-sm-12 col-xxl-12">
                <div class="tablos">
                    <div class="row mb-xl-2 mb-xxl-3">
                        <div class="col-sm-3">
                            <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <?=$nuevos?>
                                </div>
                                <div class="label">
                                    Tickets Nuevos
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-3">
                            <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <?=$tomado?>
                                </div>
                                <div class="label">
                                    Tickets Tomado
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-3">
                            <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <?=$atendidos?>
                                </div>
                                <div class="label">
                                    Tickets Atendidos
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-3">
                            <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <?=$total?>
                                </div>
                                <div class="label">
                                    Total Tickets
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        Tickets
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="table-responsive">
                                <table id="tabla_usuario" class="table   table-padded" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>
                                            Codigo
                                        </th>
                                        <th>
                                            Filial
                                        </th>
                                        <th>
                                            Titulo
                                        </th>
                                        <th>
                                            Estado Ticket
                                        </th>
                                        <th>
                                            Prioridad
                                        </th>
                                        <th>
                                            Fecha Creación
                                        </th>
                                        <th>
                                            acción
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
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
