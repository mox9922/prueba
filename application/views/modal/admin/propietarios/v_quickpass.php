<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="data_id" id="q_data_id" class="imput_reset">
    <input type="hidden" name="propietario" id="q_propietario" value="<?=$id?>">

    <div class="modal-body">

        <div class="row">
            <div class="form-group col-md-9">
                <label>Quick Pass</label>
                <input type="text" id="m_quick" name="quick" required placeholder="Quick Pass" autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Placa</label>
                <input type="text" id="m_placa" name="placa" placeholder="Placa" autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>
        </div>

        <div class="row">
            <div class="table-responsive">
                <table class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Codigo
                        </th>
                        <th>
                            Placa
                        </th>
                        <th>
                            acción
                        </th>
                    </tr>
                    </thead>
                    <tbody id="tabla_quick">
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id_data = encriptar($dt['pq_id']);
                            echo "<tr>
                                          <td>$dt[pq_codigo]</td>
                                          <td>$dt[pq_placa]</td>
                                          <td>
                                            <div class='btn-group'>
                                              <button type='button' onclick='$(this).edit_quick(\"{$id_data}\");' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                                              <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id_data}\",\"url_dell_qickpass\",false,function(){
                                                     $(this).forms_modal({\"page\" : \"propietario_quickpass\",\"data1\" : \"{$id}\",\"title\" : \"QuickPass\"})
                                              })'><i class='fas fa-times'></i></button>
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
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>

    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-700');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_qickpass',false,() => {
                    $(this).forms_modal({"page" : "propietario_quickpass","data1" : "<?=$id?>","title" : "QuickPass"})
                });
                return false;
            }
        })

    })
</script>