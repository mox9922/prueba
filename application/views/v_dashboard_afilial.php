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
            <div class="content-i">
                <div class="content-box">


                    <div class="row">


                        <div class="col-lg-9">
                            <div class="padded-lg">
                              <div class="element-wrapper">
                                            <h6 class="element-header">
                                                Visitantes Autorizados Filial
                                                <b class="text-success"><?php echo $this->general->nique_value('apartamento','t_apartamento',array('t_id' => $user->u_apartamento)); ?></b>
                                            </h6>


                                  <div class="element-box" id="actualizar_deudas">
                                      <div class="element-info">
                                          <div class="row align-items-center">
                                              <div class="col-sm-8">
                                                  <div class="element-info-with-icon">
                                                      <div class="element-info-icon">
                                                          <div class="os-icon os-icon-wallet-loaded"></div>
                                                      </div>
                                                      <div class="element-info-text">
                                                          <h5 class="element-inner-header">
                                                              Deudas Filial
                                                          </h5>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="row">
                                          <div class="col-md-6 col-xl-8">
                                              <div class="row">
                                                  <div class="col-sm-6 b-r b-b">
                                                      <a href="javascript:void(0)" class="el-tablo centered padded" onclick="$(this).forms_modal({'page' : 'f_dueda_total','data1' : 1,'title' : 'Total Deudas Agua'})">
                                                          <div class="value">
                                                              ₡ <?=$deudas['deuda1']['monto']?>
                                                          </div>
                                                          <div class="label">
                                                              Deuda Agua
                                                          </div>
                                                      </a>
                                                  </div>
                                                  <div class="col-sm-6 b-b">
                                                      <a href="javascript:void(0)" class="el-tablo centered padded" onclick="$(this).forms_modal({'page' : 'f_dueda_total','data1' : 2,'title' : 'Total Deudas Cuota Condominal'})">
                                                          <div class="value">
                                                              ₡  <?=$deudas['deuda2']['monto']?>
                                                          </div>
                                                          <div class="label">
                                                              Cuota Condominal
                                                          </div>
                                                      </a>
                                                  </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-sm-6 b-r">
                                                      <a href="javascript:void(0)" class="el-tablo centered padded" onclick="$(this).forms_modal({'page' : 'f_dueda_total','data1' : 3,'title' : 'Total Deudas Cuota Extraordinaria'})">
                                                          <div class="value">
                                                              ₡  <?=$deudas['deuda3']['monto']?>
                                                          </div>
                                                          <div class="label">
                                                              Cuota Extraordinaria
                                                          </div>
                                                      </a>
                                                  </div>
                                                  <div class="col-sm-6">
                                                      <a href="javascript:void(0)" class="el-tablo centered padded"  class="el-tablo centered padded" onclick="$(this).forms_modal({'page' : 'f_dueda_total','data1' : 4,'title' : 'Total Deudas'})">
                                                          <div class="value">
                                                              ₡  <?=$deudas['deuda4']['monto']?>
                                                          </div>
                                                          <div class="label">
                                                              Deuda
                                                          </div>
                                                      </a>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-md-6 col-xl-4">
                                              <div class="padded b-l b-r-xl">
                                                  <div class="element-info-with-icon smaller">
                                                      <div class="element-info-icon">
                                                          <div class="os-icon os-icon-bar-chart-stats-up"></div>
                                                      </div>
                                                      <div class="element-info-text text-center">
                                                          <h3 class="element-inner-header">
                                                              ₡  <?=$deudas['total']?>
                                                          </h3>

                                                      </div>
                                                  </div>
                                                  <div class="os-progress-bar primary">
                                                      <div class="bar-labels">
                                                          <div class="bar-label-left">
                                                              <span>Deuda Agua</span><span class="negative"><?=$deudas['deuda1']['valor']?>%</span>
                                                          </div>

                                                      </div>
                                                      <div class="bar-level-1" style="width: 100%">
                                                          <div class="bar-level-2" style="width: <?=$deudas['deuda1']['valor']?>%">
                                                              <div class="bar-level-3" style="width: 100%"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="os-progress-bar primary">
                                                      <div class="bar-labels">
                                                          <div class="bar-label-left">
                                                              <span>Cuota Condominal</span><span class="negative"><?=$deudas['deuda2']['valor']?>%</span>
                                                          </div>

                                                      </div>
                                                      <div class="bar-level-1" style="width: 100%">
                                                          <div class="bar-level-2" style="width: <?=$deudas['deuda2']['valor']?>%">
                                                              <div class="bar-level-3" style="width: 100%"></div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="os-progress-bar primary">
                                                      <div class="bar-labels">
                                                          <div class="bar-label-left">
                                                              <span> Cuota Extraordinaria</span><span class="negative"><?=$deudas['deuda3']['valor']?>%</span>
                                                          </div>
                                                      </div>
                                                      <div class="bar-level-1" style="width: 100%">
                                                          <div class="bar-level-2" style="width: <?=$deudas['deuda3']['valor']?>%">
                                                              <div class="bar-level-3" style="width: 100%"></div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  <div class="os-progress-bar primary">
                                                      <div class="bar-labels">
                                                          <div class="bar-label-left">
                                                              <span>Deuda</span><span class="negative"><?=$deudas['deuda4']['valor']?>%</span>
                                                          </div>
                                                      </div>
                                                      <div class="bar-level-1" style="width: 100%">
                                                          <div class="bar-level-2" style="width: <?=$deudas['deuda4']['valor']?>%">
                                                              <div class="bar-level-3" style="width: 100%"></div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                              <div class="element-box-tp">


                                                <div class="row">
                                                <div class="table-responsive">
                                                    <table class="table table-padded text-center">
                                                        <thead>
                                                        <tr>
                                                            <th>
                                                                #
                                                            </th>  <th>
                                                                Nombre Visitante
                                                            </th>
                                                            <th>
                                                                Cedula
                                                            </th>

                                                            <th>
                                                                Tipo Vehiculo
                                                            </th>
                                                            <th>
                                                                Placas
                                                            </th>
                                                            <th>
                                                                Fecha
                                                            </th>
                                                            <th>
                                                                Estado
                                                            </th>
                                                            <th>
                                                                Accion
                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(isset($visitantes)){
                                                            foreach($visitantes as $data){
                                                                $id_visita = encriptar($data->pv_id);
                                                                $btn            = $this->class_data->estado[$data->pv_estado];
                                                                $imagen        = base_url("_files/{$data->pv_foto}");
                                                                echo " <tr>
                                                                    <td class='cell-with-media'><a class='single_image' href='{$imagen}'><img class='rounded-circle' src='{$imagen}' style='height: 40px;width: 40px;'></a> </td>
                                                                    <td class='cell-with-media'>{$data->pv_nombre}</td>
                                                                    <td class='nowrap'>{$data->pv_cedula}</td>
                                                                    <td class=' nowrap'>{$this->class_security->array_data($data->pv_tipo_vehiculo,$this->class_data->autos)}</td>
                                                                    <td class='bolder nowrap'><span class='text-success'>{$data->pv_placa}</span></td>
                                                                    <td class='bolder nowrap'><span class='text-success'>{$data->pv_fecha_ingreso}</span></td>
                                                                    <td> <button type='button' class='{$btn['class']}'>{$btn['title']}</button></td>
                                                                    <td  class='bolder nowrap'>
                                                                        <button type='button'  onclick='$(this).forms_modal({\"page\" : \"f_invitados\",\"data1\" : \"{$id_visita}\",\"title\" : \"Visitante\"})'   class='btn btn-info'><i class='text-white fa fa-pencil-square-o'></i></button>
                                                                        <button type='button' onclick='$(this).dell_data(\"{$id_visita}\",\"url_delete\")' class='btn btn-danger'><i class='fa fa-times'></i></button>
                                                                    </td>
                                                                </tr>";
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


                        <div class="col-lg-3 b-l-lg">
                            <div class="padded-lg">

                                <div class="element-wrapper">

                                    <a class="element-box el-tablo centered trend-in-corner padded bold-label" href="javascript:void(0)" id="actualizar_favor">
                                        <div class="value"  style="font-size: 2.00rem;">
                                            ₡  <?=$deudas['favor']?>
                                        </div>
                                        <div class="label">
                                            Saldo a Favor
                                        </div>
                                    </a>

                                    <a class="element-box el-tablo centered trend-in-corner padded bold-label <?php echo $lectura_disable; ?>" href="<?php echo $lectura_imagen; ?>">
                                        <div class="value" style="font-size: 2.00rem;">
                                            <?php echo $lectura_valor; ?>
                                        </div>
                                        <div class="label">
                                            Consumo De Agua M3
                                            <br>
                                            Fecha De lectura<br>
                                            <?php echo $lectura_fecha; ?>
                                        </div>
                                    </a>

                                    <?php
                                        if($this->class_security->solo_numerico($deudas['total']) >= 1):
                                    ?>
                                    <div class="mb-3">
                                        <div class="cta-w orange text-center">
                                            <div class="cta-content extra-padded">
                                                <div class="highlight-header">Saldo Pendiente</div>
                                                <h5 class="cta-header">Por favor cancelar el saldo que tienes pendiente</h5>
                                                <h3 class="text-white">₡  <?=$deudas['total']?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    endif;
                                    ?>


                                    <div class="element-box-tp">
                                        <div class="el-buttons-list full-width">
                                            <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_invitados','title' : 'Invitados'});" ><i class="os-icon os-icon-delivery-box-2"></i><span>Crear Invitados</span></a>
                                            <a class="btn btn-white btn-sm" href="javascript:void(0)" onclick="$(this).forms_modal({'page' : 'f_condomino','title' : 'Condomino'});"><i class="os-icon os-icon-window-content"></i><span>Crear Condomino</span></a>
                                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_mascotas','title' : 'mascotas'});"><i class="fa fa-github-alt"></i> <span>Mis Mascotas</span></a>
                                            <a class="btn btn-white btn-sm" href="javascript:void(0)"  onclick="$(this).forms_modal({'page' : 'f_vehiculos','title' : 'Vehiculos'});"><i class="fa fa-car"></i> <span>Mis Vehiculos</span></a>
                                            <a class="btn btn-white btn-sm" href="<?=base_url('propiedades_reserva');?>"  ><i class="fa fa-home"></i> <span>Reservas</span></a>
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
            </div>




    <div id="root-picker-outlet"></div>
    <!-- Modal Principal -->
    <div id="modal_principal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog in_modal_primario modal-800">
            <div class="modal-content">
                <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                    <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?>  - <b id="label_modal"></b></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                            style="float: right;font-size: 30px;font-weight: 700;line-height: 1;color: #FFF;text-shadow: 0 1px 0 #fff;opacity:1;">
                        ×
                    </button>
                </div>

                <div class="modal-body-view"></div>

            </div>
        </div>
    </div>

    <div id="modal_principal2" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog in_modal_secundario  modal-800">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?>  - <b id="label_modal2"></b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="float: right;font-size: 30px;font-weight: 700;line-height: 1;color: #FFF;text-shadow: 0 1px 0 #fff;opacity:1;">
                    ×
                </button>
            </div>

            <div class="modal-body-view2"></div>

        </div>
    </div>
</div>
