<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="content-i">
    <div class="content-box">
        <div class="element-wrapper">
            <div class="element-actions">
                <form class="form-inline justify-content-sm-end">
                    <select class="form-control form-control-sm rounded bright" onchange="$(this).cambio_anno();">
                        <option selected="selected"   value=""> Seleccionar Año</option>
                        <?php
                        foreach($annos As $an){
                            echo "<option value='{$an['pa_anno']}'>{$an['pa_anno']}</option>";
                        }
                        ?>
                    </select>

                    <a href="javascript:void(0)" class="btn btn-sm btn-secondary" id="open_data" data-toggle="modal" data-target="#modal_data" onclick=" $(this).clear_form()">Agregar Pago<i class="ti-plus"></i></a>

                </form>
            </div>

            <h6 class="element-header">
                <?=$modulo?>
            </h6>
            <div class="element-box-tp">

                <div class="table-responsive">
                    <table id="tabla_x" class="table table-padded text-center dataTable_report1">
                        <thead>
                            <tr>
                                <th>Meses</th>
                                <th>Total M3</th>
                                <th>Monto Total</th>
                                <th>Valor M3</th>
                                <th>Agua </th>
                                <th>Condominal</th>
                                <th>Extraordinaria</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $anno = date("Y");
                            foreach($this->class_data->meses as $mes_id =>  $mes_val):

                                 $fecha = date("Y-m",strtotime("{$anno}-{$mes_id}"));
                                $dmp = $this->general->query("SELECT pa_id,FORMAT(pa_monto,2) AS pa_monto,FORMAT(pa_total,2) AS pa_total,FORMAT(pa_m3,2) AS pa_m3  FROM pagos_agua WHERE pa_anno='".$anno."' AND pa_mes='".$mes_id."' LIMIT 1");
                                $id = '';
                                $d1 = 0;
                                $d2 = 0;
                                $d3 = 0;
                                $d4 = 0;
                                $d5 = 0;
                                $d6 = 0;
                                $disabled = "disabled";
                                if(count($dmp) >= 1){
                                    $id = encriptar($dmp[0]['pa_id']);
                                    $d1 = $dmp[0]['pa_total'];
                                    $d2 = $dmp[0]['pa_monto'];
                                    $d3 = $dmp[0]['pa_m3'];
                                    $disabled = "";
                                }

                                //load model
                                $deudas = $this->calculos->calculo_pagos_sustraer($fecha);
                                $d4 = $this->class_security->dinero($deudas[1]);
                                $d5 = $this->class_security->dinero($deudas[2]);
                                $d6 = $this->class_security->dinero($deudas[3]);


                                echo " <tr>
                                            <td class='nowrap'><b>{$mes_val}</b> </td>
                                            <td class='bolder nowrap'><span class='text-dark'> {$d1}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d2}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d3}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d4}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d5}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d6}</span> </td>
                                            <td>
                                            <div class='btn-group'>
                                            <button type='button'  onclick='$(this).view_report_general(\"{$mes_id}\",\"{$anno}\",\"{$anno}\")' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Reporte {$mes_val}'><i class='fas fa-tasks text-white'></i></button>
                                            <button type='button' {$disabled} onclick='$(this).dell_data(\"{$id}\",\"dell_pago\",\"si\",function(){
                                                window.location.reload();
                                            })' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar {$mes_val}'><i class='fas fa-times text-white'></i></button>
                                            </div>
                                            </td>


                                        </tr>
                                    ";
                            endforeach;
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>



<!-- Modal data -->
<div id="modal_data" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-700">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?> | <?php echo $modulo; ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="float: right;font-size: 30px;font-weight: 700;line-height: 1;color: #FFF;text-shadow: 0 1px 0 #fff;opacity:1;">×</button>
            </div>

            <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

                <input type="hidden" name="send" value="data">

                <div class="modal-body">

                    <div class="row">



                        <div class="form-group col-md-4">
                            <label class="text-center">Total M3</label>
                            <input type="text" class="form-control text-center numeros imput_reset" placeholder="₡ 0" required  name="total" id="total" autoCompete="off" onchange="$(this).calcular()">
                        </div>

                        <div class="form-group col-md-4">
                            <label class="text-center">Monto total</label>
                            <input type="text" class="form-control text-center dinero imput_reset" placeholder="₡ 0" required name="monto" id="monto" autoCompete="off" onchange="$(this).calcular()">
                        </div>

                        <div class="form-group col-md-4">
                            <label class="text-center">total M3</label>
                            <input type="text" class="form-control dinero text-center " value="0" name="m3" id="m3" readonly autoCompete="off">
                        </div>


                        <div class="form-group col-md-6">
                            <label class="text-center">Mes</label>
                            <select class="form-control text-center select imput_reset"  required name="mes" autoCompete="off">
                                <option value="">[ SELECCCIONAR ]</option>
                            <?php
                                foreach($this->class_data->meses As $me_id => $me_val){
                                    echo "<option value='{$me_id}'>{$me_val}</option>";
                                }
                            ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="text-center">Año</label>
                            <input type="text" value="<?=date("Y")?>" readonly class="form-control text-center select " required name="anno" autoCompete="off">
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

<div id="modal_pagos" class="modal fade"  role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-1300">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;">Control de Pagos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="float: right;font-size: 30px;font-weight: 700;line-height: 1;color: #FFF;text-shadow: 0 1px 0 #fff;opacity:1;">×</button>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>

<!-- Modal Principal -->
<div id="modal_principal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="padding-left: 0 !important;">
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

<div id="modal_principal2" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="padding-left: 0 !important;">
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




<?php
if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>
