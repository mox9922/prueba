<?php
$lectura_imagen = '';
$lectura_fecha = '';
$lectura_valor = 0;
$lectura_disable = '';
if(isset($lectura->lt_lectura) AND (strlen($lectura->lt_lectura) >= 1)){
    $lectura_disable = 'single_image';
    $lectura_valor = $lectura->lt_lectura-(($lectura->lt_anterior >= 1) ? $lectura->lt_anterior : 0);
    $lectura_fecha = substr($lectura->lt_atcreate,0,10);
    $lectura_imagen = base_url("_files/{$lectura->lt_imagen}");

}?>
<!-- -------------------------------------------------------------- -->
<!-- Container fluid  -->
<!-- -------------------------------------------------------------- -->
<div class="container-fluid page-content">
    <!-- -------------------------------------------------------------- -->
    <!-- Start Page Content -->
    <!-- -------------------------------------------------------------- -->
    <!-- basic table -->
    <div class="row">
        <div class="col-lg-9">
            <div class="padded-lg">

                <div class="row pt-4">
                    <div class="col-sm-12">
                        <div class="element-wrapper">

                            <div class="element-actions">
                                <a class="btn btn-dark" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'ticket','title' : 'Ticket'});"><i class="os-icon os-icon-plus-circle"></i><span>Crear Ticket</span></a>
                            </div>

                            <h6 class="element-header text-uppercase ">Reporte de ticket</h6>
                            <div class="element-content">
                                <div class="tablo-with-chart">
                                    <div class="row">
                                        <div class="col-sm-12 col-xxl-12">
                                            <div class="tablos">
                                                <div class="row mb-xl-2 mb-xxl-3">
                                                    <div class="col-sm-4">
                                                        <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                            <div class="value">
                                                                <?=$nuevos?>
                                                            </div>
                                                            <div class="label">
                                                                Tickets Nuevos
                                                            </div>
                                                        </a>
                                                    </div>


                                                    <div class="col-sm-4">
                                                        <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                            <div class="value">
                                                                <?=$atendidos?>
                                                            </div>
                                                            <div class="label">
                                                                Tickets Atendidos
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-4">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                TICKETS
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="tabla_usuario" class="table   table-padded">
                                        <thead>
                                        <tr>
                                            <th>
                                                Codigo
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
        </div>

        <div class="col-lg-3 b-l-lg">
            <div class="padded-lg">

                <div class="element-wrapper">


                    <div class="element-box-tp">
                        <div class="el-buttons-list full-width">
                            <a class="btn btn-white btn-sm" href="<?=base_url();?>" ><i class="os-icon os-icon-home"></i><span> Inicio</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_invitados','title' : 'Invitados'});" ><i class="os-icon os-icon-delivery-box-2"></i><span> Crear Invitados</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_condomino','title' : 'Condomino'});"><i class="os-icon os-icon-window-content"></i><span> Crear Condomino</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_mascotas','title' : 'mascotas'});"><i class="fa fa-github"></i> <span> Mis Mascotas</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_vehiculos','title' : 'Vehiculos'});"><i class="fa fa-car"></i> <span> Mis Vehiculos</span></a>
                            <a class="btn btn-white btn-sm" href="<?=base_url('propiedades_reserva');?>"  ><i class="fa fa-home"></i> <span> Reservas</span></a>
                            <a class="btn btn-white btn-sm" href="<?=base_url('ticket');?>" ><i class="os-icon os-icon-window-content"></i><span> Ticket</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_dueda','title' : 'Deudas'});"><i class="os-icon os-icon-window-content"></i><span> Deuda</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_saldo','title' : 'Saldos Filial'});"><i class="os-icon os-icon-window-content"></i><span> Saldo</span></a>

                        </div>
                    </div>
                </div>



                <div class="element-wrapper">
                    <h6 class="element-header">
                        Vigilantes
                    </h6>
                    <div class="element-box-tp">
                        <div class="inline-profile-tiles">
                            <div class="row">

                                <?php
                                if(isset($vigilantes)){
                                    foreach($vigilantes as $data){
                                        $imagen = base_url("_files/{$data->u_imagen}");
                                        echo "
                                                <div class='col-6 col-sm-6 col-xxl-6'>
                                                    <div class='profile-tile profile-tile-inlined'>
                                                        <div class='profile-tile-box' >
                                                            <div class='pt-avatar-w'>
                                                               <a class='single_image' href='{$imagen}'> <img style='width:60px;height:60px;' src='{$imagen}'></a>
                                                            </div>
                                                            <div class='pt-user-name'>
                                                                {$data->u_nombre}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                    ";
                                    }
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row"  id="lista_casas">

    </div>
</div>
<!-- -------------------------------------------------------------- -->
<!-- End Container fluid  -->
<!-- -------------------------------------------------------------- -->
<!-- -------------------------------------------------------------- -->

