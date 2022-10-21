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
            <div class="col-lg-9 col-md-8 col-xs-12 d-flex justify-content-start justify-content-md-end align-self-center">
                    <form>
                        <div class="input-group">
                            <select class="form-select" id="inputGroupSelect04" onchange="$(this).cambio_anno();">
                                <option selected="selected" hidden  value=""> Seleccionar Año</option>
                                <?php
                                foreach($annos As $an){
                                    echo "<option value='{$an['pa_anno']}'>{$an['pa_anno']}</option>";
                                }
                                ?>
                            </select>
                            <button class="btn btn-dark" type="button" onclick="$(this).forms_modal2({'page' : 'admin_control_pagos','title' : 'Control de Pagos'})">Agregar Pago</button>
                            <button class="btn btn-info" type="button" onclick="$(this).forms_modal2({'page' : 'admin_control_pagos_comprobantes','title' : 'Comprobantes'})">Comprobantes</button>
                        </div>
                    </form>
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
                        CONTROL DE PAGOS
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="table-responsive">
                                <table id="tabla_x" width="100%" class="table table-padded text-center dataTable_report1">
                                    <thead>
                                    <tr>
                                        <th>Meses</th>
                                        <th>Total M3</th>
                                        <th>Monto Total</th>
                                        <th>Valor M3</th>
                                        <th>Agua </th>
                                        <th>Recolección Agua</th>
                                        <th>Condominal</th>
                                        <th>Recolección Condominal</th>
                                        <th>Extraordinaria</th>
                                        <th>Recolección Extraordinaria</th>
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
//                                        print_r($deudas);
                                        $d4 = number_format($deudas[1]['saldo']);
                                        $d5 = number_format($deudas[2]['saldo']);
                                        $d6 = number_format($deudas[3]['saldo']);

                                        $d7 = number_format($deudas[1]['abono']);
                                        $d8 = number_format($deudas[2]['abono']);
                                        $d9 = number_format($deudas[3]['abono']);


                                        echo " <tr>
                                            <td class='nowrap'><b>{$mes_val}</b> </td>
                                            <td class='bolder nowrap'><span class='text-dark'> {$d1}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d2}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d3}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d4}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d7}</span> </td>
                                            
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d5}</span> </td>
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d8}</span> </td>
                                            
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d6}</span> </td>
                                            
                                            <td class='bolder nowrap'><span class='text-success'>₡ {$d9}</span> </td>
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
