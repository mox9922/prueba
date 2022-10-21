<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
    <div class="modal-body">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-padded text-center modal_data">
                    <thead>
                    <tr>
                        <th>
                            Imagen
                        </th>
                        <th>
                            Nombre propiedad
                        </th>
                        <th>
                            Dias
                        </th>
                        <th>
                            Fecha Inicio
                        </th>
                        <th>
                            Fecha Fin
                        </th>
                        <th>
                            Fecha Solicitud
                        </th>
                        <th>
                            Estado
                        </th>
                        <th>
                            acción
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id_data = encriptar($dt['r_id']);
                            $imagen = $this->class_security->img_avatar($dt['p_imagen'],2);
                            $estado          = $this->class_data->restado_reserva[$dt['r_estado']];
                            echo "<tr>
                                          <td>{$imagen}</td>
                                          <td>$dt[p_nombre]</td>
                                          <td>$dt[dias]</td>
                                          <td>$dt[r_fecha_inicio]</td>
                                          <td>$dt[r_fecha_salida]</td>
                                          <td>$dt[r_atcreate]</td>
                                          <td><button class='{$estado['class']}'>{$estado['title']}</button></td>
                                          <td>
                                            <div class='btn-group'>
                                              <button type='button'  onclick='$(this).forms_modal2({\"page\" : \"f_reserva_comprobante\",\"data1\" : \"{$id_data}\",\"title\" : \"Reserva Informacion\"})' class='btn btn-dark '  data-toggle='tooltip' data-placement='top' title='Reserva Dias'><i class='fas fa-eye text-white'></i></button>
                                              <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id_data}\",\"url_delete_reserva\",false,function(){
                                                     $(this).forms_modal({\"page\" : \"f_reserva_lista\",\"title\" : \"Reservas Lista\"})
                                              })'><i class='fa fa-times'></i></button>
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
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar Mascota</button>
    </div>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1200');
        $(this).datatable_func('.modal_data');
        $(this).fancybox_func('.single_image');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_mascotas',false,() => {
                    $(this).forms_modal({"page" : "f_mascotas","title" : "Mascotas"})
                });
                return false;
            }
        })

    })
</script>
