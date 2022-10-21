<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="modal-body">

    <div class="row">


        <div class="table-responsive">
            <table class="table table-padded text-center " id="tabla_modal2" style="width:100%">
                <thead>
                <tr>
                    <th>
                        Filial
                    </th>

                    <th>
                        Tipo Deuda
                    </th>
                    <th>
                        Fecha Deuda
                    </th>
                    <th>
                        Adeudado
                    </th>
                    <th>
                        Saldo
                    </th>
                    <th>
                        Fecha Registro
                    </th>
                    <th>
                        Comprobante
                    </th>
                    <th>
                        Estado
                    </th>

                </tr>

                </thead>
                <tbody>
                <?php
                    foreach($deudas As $dt){
                        $tipo = $this->class_security->array_data($dt->tipo_de_deuda,$this->class_data->comprobante,$this->class_data->estado_default);
                        $comprobante = $this->class_security->url_documento($dt->comprobante,1,'w-100');
                        $estado = $this->class_security->select_pago_valor(encriptar($dt->id),$this->class_data->estado_pago_formal,$dt->estado,'comprobantes_estado_simple',4,$dt->tipo_de_deuda);
                        echo "<tr>
                                 <td>$dt->apt</td>   
                                 <td>$tipo</td>   
                                  <td>$dt->fecha_deuda</td>  
                                 <td>₡ ".$this->class_security->dinero($dt->deuda)."</td>   
                                 <td>₡ ".$this->class_security->dinero($dt->saldo)."</td>   
                                 <td>$dt->fecha</td>   
                                 <td>$comprobante</td>   
                                 <td>$estado</td>   
                             </tr>";
                    }

                ?>

                </tbody>
            </table>
        </div>

    </div>

</div>


<div class="modal-footer">
    <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cerrar ventana</button>
</div>


<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1400');
        $(this).datatable_func('#tabla_modal2');


    })
</script>
