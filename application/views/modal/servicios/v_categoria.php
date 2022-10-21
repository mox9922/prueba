<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="data_id" class="imput_reset">

    <div class="modal-body">

        <div class="row">

            <div class="form-group col-md-12">
                <label>Nombre Categoria</label>
                <input type="text" name="nombre"  id="nombre" required autofocus style="width:100%" class="form-control  imput_reset" autocomplete="off">
            </div>

        </div>

        <div class="row  mt-4">
            <div class="table-responsive">
                <table id="modal_table" class="table table-striped table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Cegoria
                        </th>
                        <th>
                            Acción
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($datas)){
                        foreach ($datas As $dt){
                            $id = encriptar($dt->sc_id);
                            echo "<tr>
                                          <td>$dt->sc_nombre</td>
                                          <td>
                                          <button type='button' onclick='$(this).edit_categoria(\"{$id}\");' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                                          <button type='button' class='btn btn-danger' onclick='$(this).dell_data(\"{$id}\",\"url_categoria_delete\",false,function(){
                                                 $(this).forms_modal({\"page\" : \"servicios_categoria\",\"title\" : \"Categorias\"})
                                          })'><i class='fas fa-times'></i></button></td>
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
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar Proceso</button>
        <button type="submit" class="btn btn-success waves-effect waves-light">Guardar </button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-600');
        $(this).datatable_func("#modal_table");


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                $(this).simple_call('frm_data','url_categoria_save',false,function(){
                        $(this).forms_modal({'page' : 'servicios_categoria','title' : 'Categorias'})
                    }
                );
                return false;
            }
        })

    })
</script>