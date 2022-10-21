<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


    <input type="hidden"  id="data_id_principal" value="<?=$id?>">

    <div class="modal-body">

        <div class="table-responsive">
            <table id="modal_table" class="table table-padded text-center">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Deuda</th>
                    <th>Monto</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Creación</th>
                    <th>Estado</th>
                    <th>acción</th>
                </tr>
                </thead>
                <tbody id="tabla_modal">
                <?php
                if(isset($deuda)){
                    $i = 1;
                    foreach($deuda as $rows){
                        $id              = encriptar($rows->du_id);
                        $apartamento     = $this->class_security->decodificar($rows->t_apartamento);
                        $estado          = $this->class_data->estado_pago[$rows->du_estado];


                        echo "<tr> 
                                        <td>{$rows->du_nombre}</td>
                                        <td class='bolder nowrap'><span class='text-success'>₡".$this->class_security->dinero($rows->du_saldo)."</span></td>
                                        <td class='bolder nowrap'><span class='text-success'>₡".$this->class_security->dinero($rows->saldo)."</span></td>
                                        <td>{$rows->du_fecha_inicio}</td>
                                        <td>{$rows->du_atcreate}</td>
                                        <td><button class='{$estado['class']}'>{$estado['title']}</button></td>
                                        <td>
                                            <div class='btn-group'>
                                                <button type='button'onclick='$(this).forms_modal2({\"page\" : \"admin_deuda\",\"data1\" : \"{$id}\",\"title\" : \"Deuda Filial\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>
                                                <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button>
                                            </div>
                                        </td>
                                  </tr>";
                    }
                }
                ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
    </div>


<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1100');

        $(this).datatable_func("#modal_table");


    })
</script>