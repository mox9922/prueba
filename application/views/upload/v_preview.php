<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="content-i">
    <div class="content-box">
        <div class="element-wrapper">
            <div class="element-actions">
                    <a href='javascript:void(0)' class='btn btn-primary btn-sm' onclick='$(this).importar_documento("<?=$documento?>")'>Importar Datos<i class='ti-plus'></i></a>
            </div>

            <h6 class="element-header">
                <?=$modulo?>
            </h6>
            <div class="element-box">

                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-padded text-center dataTable_es" >
                            <thead>
                            <tr>
                                <th rowspan="2">
                                    Filial
                                </th>
                                <th rowspan="2">
                                    Mes
                                </th>
                                <th colspan="4">
                                    Agua
                                </th>
                                <th colspan="2">
                                    Cuota Condominal
                                </th>
                                <th colspan="2">
                                    Cuota Extraordinaria
                                </th>
                            </tr>
                            <tr>
                                <th>M3ANT</th>
                                <th>M3ACT</th>
                                <th>Agua</th>
                                <th>Pago</th>
                                <th>CC</th>
                                <th>Pago</th>
                                <th>CE</th>
                                <th>Pago</th>
                            </tr>
                            </thead>
                            <tbody id="tabla_mascotas">
                            <?php

                            if(isset($datas) AND count($datas) >= 1){
                                for ($i = 0;$i < count($datas);$i++){
                                     echo "<tr>";
                                    for($e = 0;$e <= 9;$e++){

                                        $dd = $datas[$i][$e];
                                        $val = (strlen($dd) >= 1) ? $dd : 0;
                                        echo "<td>$val</td>";

                                    }
                                    echo "</tr>";
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


<?php
if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>

