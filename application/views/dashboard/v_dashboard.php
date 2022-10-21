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
                    <input autocomplete="off" readonly onchange="$(this).reporte()" value="<?=date('Y-m')?>" type="text" id="fecha" class="form-control font-weight-bold text-center form-control-sm rounded">                </div>
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

            <div class="col-md-12">
                <div class="tablos">
                    <div class="row mb-xl-2 mb-xxl-3">

                        <div class="col-sm-6">
                            <a href="javascript:void(0)" onclick="$(this).mostrar_modal(1 ,1, 'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <b id="rdeuda">0</b>
                                </div>
                                <div class="label">
                                    Filiales Deuda
                                </div>

                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="javascript:void(0)" onclick="$(this).mostrar_modal(1 ,2,'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <b id="rpagado">0</b>
                                </div>
                                <div class="label">
                                    Recolectado
                                </div>

                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <a href="javascript:void(0)" onclick="$(this).mostrar_modal(2 ,1,'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <b id="drdeuda">0</b>
                                </div>
                                <div class="label">
                                    Deuda
                                </div>

                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="javascript:void(0)" onclick="$(this).mostrar_modal(2 ,2,'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <b id="drpagado">0</b>
                                </div>
                                <div class="label">
                                    Recolectado
                                </div>
                            </a>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="javascript:void(0)" onclick="$(this).mostrar_modal(3 ,1,'Reporte de deudas')"  class="element-box el-tablo centered trend-in-corner padded bold-label">
                                <div class="value">
                                    <b id="morosos">0</b>
                                </div>
                                <div class="label">
                                    Cantidad de morosos
                                </div>

                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!--START - Chart Box-->
                <div class="element-box pl-xxl-5 pr-xxl-5">
                    <div class="el-tablo bigger highlight bold-label text-center">
                        <div class="value">
                            Estadistica de Caja
                        </div>

                    </div>
                    <div class="el-chart-w">
                        <ul class="list-inline text-end">
                            <li class="list-inline-item">
                                <h5>
                                    <i class="fa fa-circle me-1 text-inverse" style="color:#fc4b6c !important"></i>Agua
                                </h5>
                            </li>
                            <li class="list-inline-item">
                                <h5><i class="fa fa-circle me-1 text-info" style="color:#1e88e5 !important"></i>Condominal</h5>
                            </li>
                            <li class="list-inline-item">
                                <h5>
                                    <i class="fa fa-circle me-1 text-success" style="color:#00acc1 !important"></i>ExtraOrdinaria
                                </h5>
                            </li>
                            <li class="list-inline-item">
                                <h5>
                                    <i class="fa fa-circle me-1 text-success" style="color:#28f508 !important"></i>Deuda
                                </h5>
                            </li>
                            <li class="list-inline-item">
                                <h5>
                                    <i class="fa fa-circle me-1 text-success" style="color:#c10000 !important"></i>Moroso
                                </h5>
                            </li>
                        </ul>
                        <div id="morris-area-chart"></div>
                        <!--                                            <canvas height="390px" id="morris-area-chart" width="600px"></canvas>-->
                    </div>
                </div>
                <!--END - Chart Box-->
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
