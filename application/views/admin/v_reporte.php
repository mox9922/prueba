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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        IMPORTAR INFORMACION
                    </div>
                    <div class="card-body">

                        <form role="form" data-toggle="validator" method="POST" class="frm_data text-center font-weight-bold" id="frm_data" autocomplete="off" >
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Tipo de Reporte</label>
                                    <select type="text" class="form-control text-center" id="tipo_reporte" onchange="$(this).filtrar_informacion_tipo()">
                                        <option value="1">Reporte Simplificado</option>
                                        <option value="2">Reporte por meses</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label> Fecha Reporte</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input class="form-control text-center fecha_reporte" id="fecha_reporte" value="<?=date('Y-m')?>" placeholder="Fecha inicial" type="text">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label> Fecha Final</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                        <input class="form-control  text-center fecha_reporte" id="fecha_final" value="<?=fecha(1)?>" placeholder="Fecha final" type="text">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label class="text-white d-block">.</label>
                                    <button class="btn btn-dark btn-block w-100" type="button" onclick="$(this).reporte_data()"> Filtrar</button>
                                </div>

                                <div class="col-md-1">
                                    <label class="text-white d-block">.</label>
                                    <button class="btn btn-dark btn-block w-100" type="button" onclick="$(this).reporte_exportar()"> <i class="fas fa-download"></i></button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        INFORME
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="table-responsive">
                                <table id="tabla_usuario" width="100%" class="table table-striped table-bordered lightfont">
                                    <thead id="tabla_head">
                                    <tr><th>Filial</th></tr>
                                    </thead>

                                    <tbody id="tabla_body">
                                    <tr>
                                        <td>Filial</td>
                                    </tr>
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
