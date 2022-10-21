<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="content-i">
    <div class="content-box">
        <div class="element-wrapper">
            <div class="element-actions">
                <form class="form-inline justify-content-sm-end">
                    <a href="javascript:void(0)" id="open_data" data-toggle="modal" data-target="#modal_data">Agregar <?php echo $modulo; ?><i class="ti-plus"></i></a>
                </form>
            </div>
            <h6 class="element-header">
                <?=$modulo?>
            </h6>
            <div class="element-box">

                <div class="table-responsive">
                    <table id="tabla_usuario" width="100%" class="table table-striped table-lightfont">
                        <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Telefono</th>
                            <th>Torre</th>
                            <th>Apartamento</th>
                            <th>Vehiculo</th>
                            <th>visitante</th>
                            <th>Estado</th>
                            <th>Accion</th>
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
<!-- Modal data -->
<div id="modal_data" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-800">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?> | <?php echo $modulo; ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="float: right;font-size: 30px;font-weight: 700;line-height: 1;color: #FFF;text-shadow: 0 1px 0 #fff;opacity:1;">×</button>
            </div>

            <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

                <input type="hidden" name="send" value="data">
                <input type="hidden" name="data_id" id="data_id" class="imput_reset">
                <input type="hidden" name="pp" id="pp" value="<?=$perfil?>">
                <input type="hidden" id="url_accion">

                <div class="modal-body">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Nombre</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Torre</label>
                            <input type="text" id="torre" name="torre" placeholder="torre" autofocus required class="form-control imput_reset" autocomplete="off">
                        </div>

                        <div class="form-group col-md-3">
                            <label>Apartamento</label>
                            <input type="text" id="apartamento" name="apartamento" placeholder="Apartamento" autofocus required class="form-control imput_reset" autocomplete="off">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Telefono</label>
                            <input type="text" id="telefono" name="telefono" placeholder="telefono" autofocus required class="form-control imput_reset" autocomplete="off">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Estado</label>
                            <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                                <option value=""> [ SELECCIONAR ] </option>
                                <option value="1">Activa</option>
                                <option value="2">Inactiva</option>
                            </select>
                        </div>

                    </div>



                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar Proceso</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Guardar <?php echo $modulo; ?></button>
                </div>


            </form>

        </div>
    </div>
</div>

<div id="modal_vehiculos" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-700">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?> | <?php echo $modulo; ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="float: right;font-size: 30px;font-weight: 700;line-height: 1;color: #FFF;text-shadow: 0 1px 0 #fff;opacity:1;">×</button>
            </div>

            <form role="form" data-toggle="validator" method="POST" class="frm_vehiculo" id="frm_vehiculo">


                <div class="modal-body">

                    <div class="row">
                        <div class="form-group col-md-12">
                            <label>Nombre</label>
                            <input type="text" id="nombre" name="nombre" placeholder="Nombre Completo" autofocus required class="form-control imput_reset" autocomplete="off">
                        </div>

                        <div class="form-group col-md-6">
                            <label>telefono</label>
                            <input type="text" id="telefono" name="telefono" placeholder="telefono" autofocus required class="form-control imput_reset" autocomplete="off">
                        </div>

                        <div class="form-group col-md-6">
                            <label>Estado</label>
                            <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                                <option value=""> [ SELECCIONAR ] </option>
                                <option value="1">Activa</option>
                                <option value="2">Inactiva</option>
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <div class="table-responsive">

                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Tipo Acceso</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                </tr>
                                </thead>
                                <tbody id="tabla"></tbody>
                            </table>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar Proceso</button>
                    <button type="submit" class="btn btn-success waves-effect waves-light">Guardar <?php echo $modulo; ?></button>
                </div>

            </form>

        </div>
    </div>
</div>


<?php
if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>






