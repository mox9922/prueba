<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
    <div class="content-i">
        <div class="content-box">
            <div class="row pt-4">
                <div class="col-sm-12">
                    <div class="element-wrapper">

                        <h6 class="element-header">Reporte de ticket</h6>

                        <div class="element-content">
                            <div class="tablo-with-chart">
                                <div class="row">
                                    <div class="col-sm-12 col-xxl-12">
                                        <div class="tablos">
                                            <div class="row mb-xl-2 mb-xxl-3">
                                                <div class="col-sm-3">
                                                    <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                        <div class="value">
                                                            <?=$nuevos?>
                                                        </div>
                                                        <div class="label">
                                                            Tickets Nuevos
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-3">
                                                    <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                        <div class="value">
                                                            <?=$tomado?>
                                                        </div>
                                                        <div class="label">
                                                            Tickets Tomado
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-3">
                                                    <a class="element-box el-tablo centered trend-in-corner padded bold-label">
                                                        <div class="value">
                                                            <?=$atendidos?>
                                                        </div>
                                                        <div class="label">
                                                            Tickets Atendidos
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-3">
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

            <div class="row pt-1">
                <div class="col-sm-12">
                    <div class="element-wrapper">
                        <div class="element-box-tp">
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
                                            Accion
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


    <!-- Modal data -->
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


<?php
if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>