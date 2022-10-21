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
                <?php

                if($perfil == 2){
                    echo "
                                         <a href='javascript:void(0)' class='btn btn-dark btn-sm mx-2' onclick='$(this).forms_modal({\"page\" : \"propietario_filiales\",\"title\" : \"Filiales\"})'>Filiales<i class='ti-plus'></i></a>
                                         <a href='javascript:void(0)' class='btn btn-dark btn-sm' onclick='$(this).forms_modal({\"page\" : \"propietario_usuarios\",\"title\" : \"Usuarios\"})'>Agregar Propietarios<i class='ti-plus'></i></a>
                                        ";
                }else{
                    echo " <a href='javascript:void(0)' class='btn btn-dark  btn-sm' onclick='$(this).forms_modal({\"page\" : \"usuarios\",\"title\" : \"Usuarios\"})'>Agregar Usuarios<i class='ti-plus'></i></a>";
                }

                ?>

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
                    <div class="card-header text-uppercase bg-dark text-white">
                        <?=$modulo?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_usuario" class="table table-padded" style="width: 100%">
                                <thead>
                                <tr>
                                    <?php
                                    if($perfil == 2){
                                        echo "
                                                   <th>Filial</th>
                                                   <th>quickpass</th>
                                                   <th>Vehículos</th>
                                                   <th>Mascota</th>
                                                   <th>Invitado</th>
                                                   <th>Filial</th>
                                                   <th>Propietario</th>
                                                   <th>Cuota</th>
                                                   <th>Estado</th>
                                                   <th>acción</th>
                                                   
                                                 ";
                                    }else{
                                        echo " <th>Imagen</th>
                                               <th>Nombre</th>
                                                <th>Usuario</th>
                                                <th>Tipo</th>
                                                <th>Esado</th>
                                                <th>acción</th>";
                                    }
                                    ?>

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
