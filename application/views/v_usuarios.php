<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="content-i">
    <div class="content-box">
                    <div class="element-wrapper">
                        <div class="element-actions">
                            <form class="form-inline justify-content-sm-end">
                                <?php

                                    if($perfil == 2){
                                        echo "
                                         <a href='javascript:void(0)' class='btn btn-dark btn-sm' onclick='$(this).forms_modal({\"page\" : \"propietario_filiales\",\"title\" : \"Filiales\"})'>Filiales<i class='ti-plus'></i></a>
                                         <a href='javascript:void(0)' class='btn btn-primary btn-sm' onclick='$(this).forms_modal({\"page\" : \"propietario_usuarios\",\"title\" : \"Usuarios\"})'>Agregar Propietarios<i class='ti-plus'></i></a>
                                        ";
                                    }else{
                                        echo " <a href='javascript:void(0)' class='btn btn-primary btn-sm' onclick='$(this).forms_modal({\"page\" : \"usuarios\",\"title\" : \"Usuarios\"})'>Agregar Usuarios<i class='ti-plus'></i></a>";
                                    }

                                ?>

                            </form>
                        </div>
                        <h6 class="element-header">
                            <?=$modulo?>
                        </h6>
                        <div class="element-box" style="padding: 1.5rem 0rem !important; ">

                            <style>
                                /*th, td { white-space: nowrap; }*/
                                /*div.dataTables_wrapper { width: 100%;margin: 0;  }*/

                            </style>
                            <div class="table-responsive" >
                                <table id="tabla_usuario" style="width: 100%" class="table table-striped  table-lightfont2">
                                    <thead>
                                    <tr>
                                        <?php
                                        if($perfil == 2){
                                            echo "
                                                   <th>F</th>
                                                   <th>quic</th>
                                                   <th>Veh</th>
                                                   <th>Mas</th>
                                                   <th>Inv</th>
                                                   <th>FL</th>
                                                 ";
                                        }else{
                                            echo " <th>Imagen</th>";
                                        }
                                        ?>
                                        <th>Nombre Completo</th>
                                        <th>Usuario</th>
                                        <th>Tipo</th>
                                        <th>Esado</th>
                                        <th>Accion</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
</div>


<div id="root-picker-outlet"></div>

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
