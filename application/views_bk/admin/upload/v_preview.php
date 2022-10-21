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
                 <a href='javascript:void(0)' class='btn btn-dark  btn-sm' onclick='$(this).importar_documento("<?=$documento?>")'><i class='ti-upload'></i> Importar Datos</a>
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

                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-padded text-center dataTable_es" >
                                    <thead>
                                    <tr>
                                        <th rowspan="2">
                                            Filial
                                        </th>
                                        <th rowspan="2">
                                            Mes
                                        </th>
                                        <th colspan="4">
                                            Agua
                                        </th>
                                        <th colspan="2">
                                            Cuota Condominal
                                        </th>
                                        <th colspan="2">
                                            Cuota Extraordinaria
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>M3ANT</th>
                                        <th>M3ACT</th>
                                        <th>Agua</th>
                                        <th>Pago</th>
                                        <th>CC</th>
                                        <th>Pago</th>
                                        <th>CE</th>
                                        <th>Pago</th>
                                    </tr>
                                    </thead>
                                    <tbody id="tabla_mascotas">
                                    <?php

                                    if(isset($datas) AND count($datas) >= 1){
                                        for ($i = 0;$i < count($datas);$i++){
                                            echo "<tr>";
                                            for($e = 0;$e <= 9;$e++){

                                                $dd = $datas[$i][$e];
                                                $val = (strlen($dd) >= 1) ? $dd : 0;
                                                echo "<td>$val</td>";

                                            }
                                            echo "</tr>";
                                        }
                                    }
                                    ?>
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
