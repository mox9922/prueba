<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="content-i">
    <div class="content-box">
        <div class="element-wrapper">
            <div class="element-wrapper">
                <h6 class="element-header">Informe de Pagos</h6>
                <div class="element-box-tp">



                    <div class="element-wrapper">
                        <div class="element-box">
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

                                    <div class="col-md-3">
                                        <label class="text-white">.</label>
                                        <button class="btn btn-primary btn-block" type="button" onclick="$(this).reporte_data()"> Filtrar</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>




                    <div class="row">
                        <div class="table-responsive col-md-12">
                            <table id="tabla_modal" class="table table-bordered text-center  dataTable_report12" style="width: 100%;">
                                <thead id="reporte_head">
<!--                                <tr>-->
<!--                                    <th rowspan="3" class="sorting_asc" tabindex="0" aria-controls="tabla_modal" colspan="1" style="width: 31px;" aria-label="Filial: Activar para ordenar la columna de manera descendente" aria-sort="ascending">Filial</th>-->
<!--                                    <th colspan="9" rowspan="1">2021-10</th>-->
<!--                                    <th colspan="9" rowspan="1">2021-11</th>-->
<!--                                    <th colspan="9" rowspan="1">2021-12</th>-->
<!--                                </tr>-->
<!--                                <tr>-->
<!--                                    <th colspan="3" rowspan="1">Agua</th>-->
<!--                                    <th colspan="2" rowspan="1">Extraordinaria</th>-->
<!--                                    <th colspan="2" rowspan="1">Condominal</th>-->
<!--                                    <th colspan="2" rowspan="1">Deuda</th>-->
<!--                                    <th colspan="3" rowspan="1">Agua</th>-->
<!--                                    <th colspan="2" rowspan="1">Extraordinaria</th>-->
<!--                                    <th colspan="2" rowspan="1">Condominal</th>-->
<!--                                    <th colspan="2" rowspan="1">Deuda</th>-->
<!--                                    <th colspan="3" rowspan="1">Agua</th>-->
<!--                                    <th colspan="2" rowspan="1">Extraordinaria</th>-->
<!--                                    <th colspan="2" rowspan="1">Condominal</th>-->
<!--                                    <th colspan="2" rowspan="1">Deuda</th>-->
<!--                                </tr>-->
<!---->
<!--                                <tr>-->
<!--                                    <th>xx</th>-->
<!--                                    <th>xx</th>-->
<!--                                    <th>xx</th>-->
<!--                                    <th>xx</th>-->
<!--                                </tr>-->

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

<div id="modal_principal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-800">
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



<div id="root-picker-outlet"></div>


<?php
if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>







