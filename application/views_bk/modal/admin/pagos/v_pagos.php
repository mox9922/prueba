<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

    <input type="hidden" name="send" value="data">

    <div class="modal-body">

        <div class="row">

            <input type="hidden" id="mes" value="<?=$mes?>">
            <input type="hidden" id="anno" value="<?=$anno?>">

            <div class="table-responsive">
                <table class="table table-bordered text-center " id="tabla_modal" style="width:100%">
                    <thead>
                    <tr>
                        <th rowspan="2" >
                            Filial
                        </th>
                        <th  colspan="3">
                            Acomulado Mes <?=$this->class_security->array_data($mes,$this->class_data->meses)?>
                        </th>
                        <th colspan="6">
                          Cuota Agua
                        </th>
                        <th colspan="4">
                            Cuota Condominal
                        </th>
                        <th  colspan="4">
                            Cuota Extraorninaria
                        </th>
                        <th  colspan="2">
                            Deuda
                        </th>
                        <th rowspan="2" >
                            Comprobantes
                        </th>

                    </tr>
                    <tr>

                        <!-- Acomulado -->
                        <th>
                            Saldo a Pagar
                        </th>
                        <th>
                            Abonado
                        </th>
                        <th>
                            Total
                        </th>


                        <th>
                            S. Favor
                        </th>
                        <th>
                            Consumo
                        </th>

                        <!-- Agua -->

                        <th>
                            Pago.
                        </th>
                        <th>
                            Saldo.
                        </th>
                        <th>
                            Pago SF.
                        </th>
                        <th>
                            Estado
                        </th>

                        <!-- Condominal -->

                        <th>
                            Pago.
                        </th>
                        <th>
                            Saldo.
                        </th>
                        <th>
                            Pago SF.
                        </th>
                        <th>
                            Estado
                        </th>

                        <!-- Extraordinaria -->

                        <th>
                            Pago.
                        </th>
                        <th>
                            Saldo.
                        </th>
                        <th>
                            Pago SF.
                        </th>
                        <th>
                            Estado
                        </th>

                        <!-- deuda -->
                        <th>
                            Saldo.
                        </th>
                        <th>
                            Pago.
                        </th>


                        <!-- Comprobante -->






                    </tr>
                    </thead>
                    <tbody>

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
        $(this).clear_modal_view('modal-fullscreen','.in_modal_primario');


    })
</script>
