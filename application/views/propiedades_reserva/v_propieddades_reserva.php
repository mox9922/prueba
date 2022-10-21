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
                                <?php
                                foreach($dataresult As $data):
                                    $id = encriptar($data->p_id);
                                    $nombre = $this->class_security->decodificar($data->p_nombre);
                                    $valor = $this->class_security->dinero($data->p_valor);
                                    $color = (strlen($data->p_color) >= 3) ? $data->p_color : '#000000';
                                    $imagen = base_url("_files/min/{$data->p_imagen}");
                                    $url_sitio = base_url("{$controller}/sitio/{$id}")
                                    ?>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="property-item">
                                            <div class="property-image bg-overlay-gradient-04">
                                                <img class="img-fluid" src="<?=$imagen;?>">
                                                <div class='property-lable'>
                                                    <span class='badge badge-md bg-primary' style='background: <?=$color?> !important; color: <?=$color?> !important;'>Salon Comunal BBQ</span>
                                                </div>
                                            </div>
                                            <div class="property-details">
                                                <div class="property-details-inner text-center">
                                                    <h4 class="property-title"><a href="<?=$url_sitio;?>"><?=$nombre;?> </a></h4>
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
                                        Fechas reservadas
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

                    <div class="element-wrapper">
                        <div class="element-box-tp">
                            <div class="el-buttons-list full-width">
                                <a class="btn btn-white btn-sm" href="<?=base_url()?>" ><i class="os-icon os-icon-home"></i><span>Inicio</span></a>
                                <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_invitados','title' : 'Invitados'});" ><i class="os-icon os-icon-delivery-box-2"></i><span>Crear Invitados</span></a>
                                <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_condomino','title' : 'Condomino'});"><i class="os-icon os-icon-window-content"></i><span>Crear Condomino</span></a>
                                <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_mascotas','title' : 'mascotas'});"><i class="fa fa-github-alt"></i> <span>Mis Mascotas</span></a>
                                <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_vehiculos','title' : 'Vehiculos'});"><i class="fa fa-car"></i> <span>Mis Vehiculos</span></a>
                                <a class="btn btn-white btn-sm" href="<?=base_url('propiedades_reserva');?>"  ><i class="fa fa-home"></i> <span>Reservas</span></a>
                                <a class="btn btn-dark btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_reserva_lista','title' : 'Reservas Lista'});"><i class=" fas fa-braille"></i> <span>Reservas Comprobantes</span></a>
                                <a class="btn btn-white btn-sm" href="<?=base_url('ticket');?>" ><i class="os-icon os-icon-window-content"></i><span>Ticket</span></a>
                                <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_dueda','title' : 'Deudas'});"><i class="os-icon os-icon-window-content"></i><span>Deuda</span></a>
                                <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_saldo','title' : 'Saldos Filial'});"><i class="os-icon os-icon-window-content"></i><span>Saldo</span></a>

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
                        <h4 class="element-header">
                            Guardas de seguridad
                        </h4>
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

<div class="onboarding-modal modal fade animated" id="evento_data" role="dialog" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-centered modal-700" role="document">
        <div class="modal-content text-center">
            <button type="button" class="close btn-close"  data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="onboarding-media">
                <img alt="" src="<?=base_url('assets/')?>img/bigicon6.png" width="200px">
            </div>

            <form role="form" data-toggle="validator" method="POST" class="frm_update" id="frm_update">
                <input type="hidden" name="data_id" id="m_f_id" class="imput_reset">
                <div class="onboarding-content with-gradient">
                    <h4 class="onboarding-title">
                        Información de Reserva
                    </h4>
                    <div class="onboarding-text">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="">Nombre Propiedad</label><input class="form-control" id="m_titulo" readonly type="text" value="">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Fecha</label>
                                    <input class="form-control text-center fecha_modal" name="fecha" id="m_f_fecha" readonly  type="text">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Hora Entrada</label>
                                    <input class="form-control text-center hora_modal1" name="inicial" id="m_f_inicio" readonly  type="text">
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="">Hora salida</label>
                                    <input class="form-control text-center hora_modal2" name="salida" id="m_f_final" readonly  type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="update_footer">
                    <button class="btn btn-secondary"  data-bs-dismiss="modal" type="button"> Salir</button>
                    <button class="btn btn-danger" onclick="$(this).evento_eliminar()"type="button"> Eliminar</button>
                    <button class="btn btn-primary" type="submit"> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
