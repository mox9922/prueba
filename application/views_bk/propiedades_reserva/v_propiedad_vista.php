<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- -------------------------------------------------------------- -->
<!-- Container fluid  -->
<!-- -------------------------------------------------------------- -->
<div class="container-fluid page-content">
    <div class="row">


        <div class="col-lg-9">

            <div class="padded-lg">
                <div class="element-wrapper">
                    <h6 class="element-header">
                        Visitantes Autorizados Filial
                        <b class="text-success"><?php echo $this->general->nique_value('apartamento','t_apartamento',array('t_id' => $user->u_apartamento)); ?></b>
                    </h6>
                    <div class="element-box-tp">
                        <div class="row mb-xl-2 mb-xxl-3">

                            <h1>
                                <?=$propiedad->p_nombre; ?>
                            </h1>

                        </div>

                        <div class="row mb-xl-2 mt-5 mb-3 mb-xxl-3">
                            <div class="property-description">
                                <p><?=$propiedad->p_descripcion; ?></p>
                            </div>
                        </div>

                        <div class="row  mb-xl-2 mb-xxl-3">

                                <?php
                                foreach($dataresult As $data):
                                    $id = encriptar($data->p_id);
                                    $nombre = $this->class_security->decodificar($data->p_nombre);
                                    $valor = number_format($data->p_valor);
                                    $imagen = base_url("_files/min/{$data->p_imagen}");
                                    $color = (strlen($data->p_color) >= 3) ? $data->p_color : '#000000';
                                    ?>
                                    <div class="col-md-4">
                                        <div class="property-item">
                                            <div class="property-image bg-overlay-gradient-04">
                                                <a class="property-link" href="#"><img class="img-fluid w-100" src="<?=$imagen;?>"></a>
                                                <div class='property-lable'>
                                                    <span class='badge badge-md bg-primary' style='background: <?=$color?> !important; color: <?=$color?> !important;'>Salon Comunal BBQ</span>
                                                </div>
                                            </div>
                                            <div class="property-details">
                                                <div class="property-details-inner text-center">
                                                    <h5 class="property-title"><?=$nombre;?></h5>
                                                    <div class="property-price">₡  <?=$valor;?></span> </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php
                                endforeach;
                                ?>

                        </div>


                        <div class="row mb-xl-2 mb-xxl-3">
                            <div class="related-listings-w">
                                <h2 class="property-section-big-header">
                                    Fechas reservadas para eventos
                                </h2>
                                <div id="fullCalendar"></div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 b-l-lg">
            <div class="padded-lg">

                <div class="element-wrapper ">

                    <adiv class="element-box el-tablo centered trend-in-corner padded bold-label ">
                        <div class="value" style="font-size: 2.00rem;">
                            <div class="property-price">
                                <strong>₡  <?=number_format($propiedad->p_valor); ?></strong>
                            </div>
                        </div>
                        <div class="label">
                            <span>Precio de lista</span>
                        </div>
                    </adiv>

                </div>

                <div class="element-wrapper">
                    <div class="element-box-tp">
                        <div class="el-buttons-list full-width">
                            <a class="btn btn-white btn-sm" href="<?=base_url()?>" ><i class="os-icon os-icon-home"></i> <span>Inicio</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_condomino','title' : 'Condomino'});"><i class="os-icon os-icon-window-content"></i> <span>Crear Condomino</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_mascotas','title' : 'mascotas'});"><i class="fa fa-github-alt"></i> <span>Mis Mascotas</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_vehiculos','title' : 'Vehiculos'});"><i class="fa fa-car"></i> <span>Mis Vehiculos</span></a>
                            <a class="btn btn-white btn-sm" href="<?=base_url('propiedades_reserva');?>"  ><i class="fa fa-home"></i> <span>Reservas</span></a>
                            <a class="btn btn-white btn-sm" href="<?=base_url('ticket');?>" ><i class="os-icon os-icon-window-content"></i> <span>Ticket</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_dueda','title' : 'Deudas'});"><i class="os-icon os-icon-window-content"></i> <span>Deuda</span></a>
                            <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_saldo','title' : 'Saldos Filial'});"><i class="os-icon os-icon-window-content"></i> <span>Saldo</span></a>
                            <a class="btn btn-dark btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_reserva_lista','title' : 'Reservas Lista'});"><i class=" fas fa-braille"></i> <span>Reservas Comprobantes</span></a>
                        </div>
                    </div>
                </div>

                <div class="element-wrapper">
                    <h6 class="element-header">
                        Condominos
                    </h6>
                    <div class="element-box-tp">
                        <div class="users-list-w">
                            <?php
                            if(isset($afiliales)){
                                foreach($afiliales as $data){
                                    $afilial_id = encriptar($data->u_id);
                                    $estado = ($data->u_estado == 1) ? ' status-green' : ' status-red';
                                    $con_propietario = $this->class_security->array_data($data->u_propietario,$this->class_data->tipo_propietario,[]);
                                    echo "<div class='user-w with-status {$estado}'>
                                                <div class='user-avatar-w'>
                                                    <div class='user-avatar'>
                                                        <img alt='' src='".base_url('assets/')."img/avatar1.jpg' />
                                                    </div>
                                                </div>
                                                <div class='user-name'>
                                                    <h6 class='user-title'>
                                                        <a href='javascript:void(0)' class='link'   onclick='$(this).forms_modal({\"page\" : \"f_condomino\",\"data1\" : \"{$afilial_id}\",\"title\" : \"Condominos\"})'>{$data->u_nombre}</a>
                                                    </h6>
                                                    <div class='user-role'>
                                                        {$con_propietario}
                                                    </div>
                                                </div>
                                                <a class='user-action' href='javascript:void(0)'  onclick='$(this).dell_data(\"{$afilial_id}\",\"url_aflial_delete\")'><div class='icon-close'></div></a>
                                            </div>";
                                }
                            }
                            ?>
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
</div>
<!-- -------------------------------------------------------------- -->
<!-- End Container fluid  -->
<!-- -------------------------------------------------------------- -->
<!-- -------------------------------------------------------------- -->



