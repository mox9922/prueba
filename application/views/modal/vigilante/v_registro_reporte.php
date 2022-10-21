<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>




    <div class="modal-body">


        <div class="table-responsive">
            <table  id="tabla_modal" class="table table-padded text-center">
                <thead>
                <tr>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Placa</th>
                    <th>Tipo Ingreso</th>
                    <th>Cono</th>
                    <th>Filial</th>
                    <th >Hora Ingreso</th>
                    <th >Hora Salida</th>
                    <th >Tiempo Transcurrido</th>
                </tr>
                </thead>
                <tbody> </tbody>
            </table>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
    </div>


<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1300','.in_modal_primario');
        $(this).datatable_func('#tabla_modal','ajax','#url_search_reporte');


    })
</script>