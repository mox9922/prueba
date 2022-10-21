<?php
echo 22;
$col_md = 6;
if($Profile->u_perfil == 2){
    $col_md = 4;

}
?>
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
                        MI PERFIL
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

                                <div class="row mb-3">
                                    <div class="form-group col-md-<?=$col_md?>">
                                        <label>Nombre</label>
                                        <input type="text" id="nombre" name="nombre" value="<?=$Profile->u_nombre; ?>" placeholder="Nombre Completo" autofocus required class="form-control " autocomplete="off">
                                    </div>

                                    <div class="form-group col-md-<?=$col_md?>">
                                        <label>Usuario</label>
                                        <input type="text" disabled value="<?=$Profile->u_username; ?>" placeholder="Usuario" autofocus required class="form-control " autocomplete="off">
                                    </div>

                                    <?php

                                    if($Profile->u_perfil == 2){
                                        echo "<div class='form-group col-md-4'>
                                                <label>Correo Filial</label>
                                                <input type='email' required name='correo_filial' value='{$Profile->t_correo}' placeholder='Correo Filial' autofocus required class='form-control ' autocomplete='off'>
                                             </div>";
                                    }

                                    ?>

                                </div>
                                <div class="row mb-3">

                                    <div class="form-group col-md-4">
                                        <label>Telefono 1</label>
                                        <input type="text" id="telefono1" name="telefono1" value="<?=$Profile->u_telefono1; ?>" placeholder="telefono1" autofocus  class="form-control " autocomplete="off">
                                    </div>


                                    <div class="form-group col-md-4">
                                        <label>telefono 2</label>
                                        <input type="text" id="telefono2" name="telefono2" value="<?=$Profile->u_telefono2; ?>" placeholder="telefono2" autofocus  class="form-control " autocomplete="off">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label>Contraseña</label>
                                        <input type="text" id="password" name="password" placeholder="Contraseña" autofocus  class="form-control " autocomplete="off">
                                    </div>

                                </div>

                                <div class="row mb-3">

                                    <div class="form-group col-md-12">
                                        <label>Imagen</label>
                                        <input type="file" id="imagen" name="imagen" accept="image/png, image/gif, image/jpeg"  autofocus  class="form-control " autocomplete="off">
                                    </div>

                                </div>
                                    <?php

                                    if($Profile->u_perfil == 2){
                                        ?>

                                <div class="row mb-3">
                                        <div class="form-group col-md-6">
                                            <label>Correo 1</label>
                                            <input type="text" id="correo1" name="correo1" required value="<?=$Profile->u_correo1; ?>" placeholder="Correo 1" autofocus  class="form-control " autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Correo 2</label>
                                            <input type="text" id="correo2" name="correo2" value="<?=$Profile->u_correo2; ?>" placeholder="Correo 2" autofocus  class="form-control " autocomplete="off">
                                        </div>
                                </div>
                                        <?php
                                    }
                                    ?>

                                </div>


                                <div class="form-buttons-w">
                                    <button type="submit" class="btn btn-success btn-lg waves-effect waves-light">Guardar <?php echo $modulo; ?></button>
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
