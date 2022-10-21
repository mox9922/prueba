<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="content-i">
    <div class="content-box">
        <div class="row pt-4">
            <div class="col-sm-12">
                <!--START - Grid of tablo statistics-->
                <div class="element-wrapper">
                    <div class="element-actions"><form class="form-inline justify-content-sm-end"><input autocomplete="off" readonly onchange="$(this).reporte()" value="<?=date('Y-m')?>" type="text" id="fecha" class="form-control font-weight-bold text-center form-control-sm rounded"></form></div>
                    <h6 class="element-header">
                        Reporte de datos
                    </h6>
                    <div class="element-content">
                        <div class="tablo-with-chart">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="tablos">
                                        <div class="row mb-xl-2 mb-xxl-3">

                                            <div class="col-sm-6">
                                                <a href="javascript:void(0)" onclick="$(this).mostrar_modal(1 , 'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                    <div class="value">
                                                         <b id="rdeuda">0</b>
                                                    </div>
                                                    <div class="label">
                                                       Total de Cuotas Condominales mes de 
                                                    </div>

                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="javascript:void(0)" onclick="$(this).mostrar_modal(1 ,'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                    <div class="value">
                                                         <b id="rpagado">0</b>
                                                    </div>
                                                    <div class="label">
                                                         Recolectado
                                                    </div>

                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="javascript:void(0)" onclick="$(this).mostrar_modal(2 ,'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                    <div class="value">
                                                         <b id="drdeuda">0</b>
                                                    </div>
                                                    <div class="label">
                                                        Deuda
                                                    </div>

                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="javascript:void(0)" onclick="$(this).mostrar_modal(2 ,'Reporte de deudas')" class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                    <div class="value">
                                                        <b id="drpagado">0</b>
                                                    </div>
                                                    <div class="label">
                                                        Recolectado
                                                    </div>
                                                </a>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <a href="javascript:void(0)" onclick="$(this).mostrar_modal(3 ,'Reporte de deudas')"  class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                    <div class="value">
                                                        <b id="morosos">0</b>
                                                    </div>
                                                    <div class="label">
                                                        Cantidad de morosos
                                                    </div>

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <!--START - Chart Box-->
                                    <div class="element-box pl-xxl-5 pr-xxl-5">
                                        <div class="el-tablo bigger highlight bold-label text-center">
                                            <div class="value">
                                               Estadistica de Caja
                                            </div>

                                        </div>
                                        <div class="el-chart-w">
                                            <ul class="list-inline text-end">
                                                <li class="list-inline-item">
                                                    <h5>
                                                        <i class="fa fa-circle me-1 text-inverse" style="color:#fc4b6c !important"></i>Agua
                                                    </h5>
                                                </li>
                                                <li class="list-inline-item">
                                                    <h5><i class="fa fa-circle me-1 text-info" style="color:#1e88e5 !important"></i>Condominal</h5>
                                                </li>
                                                <li class="list-inline-item">
                                                    <h5>
                                                        <i class="fa fa-circle me-1 text-success" style="color:#00acc1 !important"></i>ExtraOrdinaria
                                                    </h5>
                                                </li>
                                                <li class="list-inline-item">
                                                    <h5>
                                                        <i class="fa fa-circle me-1 text-success" style="color:#28f508 !important"></i>Deuda
                                                    </h5>
                                                </li>
                                                <li class="list-inline-item">
                                                    <h5>
                                                        <i class="fa fa-circle me-1 text-success" style="color:#c10000 !important"></i>Moroso
                                                    </h5>
                                                </li>
                                            </ul>
                                            <div id="morris-area-chart"></div>
<!--                                            <canvas height="390px" id="morris-area-chart" width="600px"></canvas>-->
                                        </div>
                                    </div>
                                    <!--END - Chart Box-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--END - Grid of tablo statistics-->
            </div>
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
<div id="root-picker-outlet"></div>


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

