<?php
defined('BASEPATH') or exit('No direct script access allowed');


$data_q = array_chunk($data, 2);


?>

<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="data_id" id="comp_data_id">
    <input type="hidden" id="modal_fiial" value="<?=$filial?>">
    <input type="hidden" id="modal_fecha" value="<?=$fecha?>">

    <div class="modal-body">


        <?php
        foreach($data_q As $dd){

            echo "<div class='row pt-2'>";

            foreach($dd As $kk){
                $saldo = $kk['monto'];
                $cancelado = $kk['pagado'];
                $restado = $saldo;
                echo "<div class='col-6 col-md-6'>
        <div class='card'>
            <div class='card-header text-uppercase bg-dark text-white'>
                Comprobantes {$kk['nombre']}
            </div>
            <div class='card-body'>

                <div class='row pt-2'>
                    <div class='col-6 col-md-6'>
                        <a  onclick='$(this).forms_modal2({\"page\" : \"admin_pago_deudas\",\"data1\" : \"{$filial}\",\"data2\" : \"{$kk['id']}\",\"data3\" : \"{$fecha}\",\"title\" : \"Pagos de saldos\"})' class='element-box el-tablo centered trend-in-corner smaller' href='javascript:void(0)'>
                            <div class='label'>
                                Saldo a cancelar
                            </div>
                            <div class='value' id='saldo_cancelar'>
                                ₡ ".$this->class_security->dinero($restado)."
                            </div>
                        </a>
                    </div>



                    <div class='col-6 col-md-6'>
                        <a class='element-box el-tablo centered trend-in-corner smaller' href='javascript:void(0)'>
                            <div class='label'>
                                Saldo cancelado
                            </div>
                            <div class='value' id='saldo_cancelado'>
                                ₡ ".$this->class_security->dinero($cancelado)."
                            </div>
                        </a>
                    </div>

                </div>

                <div class='table-responsive'>
                    <table class='table table-padded text-center modal_data'>
                        <thead>
                        <tr>
                            <th>
                               Nombre
                            </th>
                             <th>
                                Deuda
                            </th>
                            <th>
                                Abono
                            </th>
                            <th>
                                Fecha Pago
                            </th>
                            <th>
                                Estado
                            </th>
                            <th>
                                Comp
                            </th>
                        </tr>
                        </thead>
                        <tbody id='tabla_modal'>
                     ";


                if(isset($kk['comprobantes'])){

                    foreach($kk['comprobantes'] As $comp){

                         $id_encriptar =  encriptar(trim($comp['as_id']));
                        $estado_pago = (in_array($comp['aa_estado'],[3,5])) ? $this->class_data->estado_pago[$comp['aa_estado']]['title'] :
                        $this->class_security->select_pago_valor($id_encriptar,$this->class_data->estado_pago,$comp['aa_estado'],'comprobantes_estado',4,$comp['pg_tipo_deuda']);

                        echo "<tr>
                                    <td>".($comp['titulo_deuda'])."</td>
                                    <td>".$this->class_security->dinero($comp['saldo_pagar'])."</td>
                                    <td>".$this->class_security->dinero($comp['aa_monto'])."</td>
                                    <td>".($comp['aa_atcreate'])."</td>
                                    <td>".$estado_pago."</td>
                                    <td>".$this->class_security->url_documento($comp['aa_comprobante'],1)."</td>
                            </tr>";
                    }
                }


                echo "

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>";
            }
            echo "</div>";

        }

        ?>






    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Ventana</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1600','.in_modal_secundario');

        // $(this).clear_form();
        $(this).datatable_func('.modal_data');
    })
</script>