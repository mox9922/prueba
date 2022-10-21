<?php
defined('BASEPATH') or exit('No direct script access allowed');
$existencia = count($datas);
?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <input type="hidden" name="tipo_deuda" value="<?=$tipo?>">
    <input type="hidden" name="apartamento" value="<?=$apartamento?>">

    <div class="modal-body">

        <div class="input_hidden hide2">

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-4">
                <label>Tipo de deuda</label>
                <input type="text" id="md_tipo" readonly value="<?=$tipo_nombre?>"  class="form-control text-center imput_reset" autocomplete="off">
            </div>


            <div class="form-group col-md-4">
                <label>Total de deuda</label>
                <input type="text"  readonly value="<?=$deuda_total?>"  class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-4">
                <label>Total a pagar</label>
                <input type="text" id="m_raza" readonly autofocus  class="form-control text-center imput_reset" autocomplete="off">
            </div>

        </div>

        <?php
        if($existencia >= 1):
            ?>

            <div class="row mb-3">
                <div class="form-group col-md-3">
                    <label>Saldo a favor</label>
                    <input type="text" id="saldo_favor" readonly value="₡ <?=$this->class_security->dinero($favor)?>"  class="form-control  text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Pagar Saldo a favor</label>
                    <input type="text" id="favor_uso" name="saldo_favor" onkeyup="$(this).sumar_saldo_favor(true);" autofocus  class="form-control favor dinero text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Saldo a Cancelar</label>
                    <input type="text" id="m_saldo_cancelar" onkeyup="$(this).operar_sados()"  name="m_saldo_cancelar"   autofocus  class="form-control dinero saldo text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <label>Acumulado a Cancelar</label>
                    <input type="text" id="acomulado_cancelar"  readonly autofocus  class="form-control dinero saldo text-center imput_reset" autocomplete="off">
                </div>

            </div>

            <div class="row mb-3">

                <div class="form-group col-md-12">
                    <label>Comprobante</label>
                    <input type="file" name="imagen" required accept=".gif,.jpg,.jpeg,.png,.doc,.docx, .pdf" class="form-control imput_reset" id="formFile" >
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-12">
                    <div class="progress" style="height: 26px;">
                        <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar_deuda_total" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso</div>
                    </div>
                </div>
            </div>

        <?php
        endif;
        ?>

        <div class="row">
            <div class="table-responsive">
                <table id="modal_tabla" class="table table-padded text-center">
                    <thead>
                    <tr>
                        <th>
                            Nombre Deuda
                        </th>
                        <th>
                            Fecha
                        </th>
                        <th>
                            Deuda
                        </th>

                        <th>
                            Abono
                        </th>
                        <th>
                            Total
                        </th>
                        <th>
                            Monto Pago
                        </th>

                    </tr>
                    </thead>
                    <tbody id="">
                    <?php
                    if(isset($datas)) {
                        foreach ($datas as $dt) {
                            $nombre   = $dt['nombre'];
                            $mes   = $dt['fecha'];
                            $id    = $dt['id'];
                            $estado    = $dt['estado'];
                            $deuda = $this->class_security->dinero($dt['deuda']);
                            $resta = $this->class_security->dinero($dt['resta']);
                            $abono = $this->class_security->dinero($dt['abono']);

                            echo "<tr>" .
                                "<td class='bolder'>$nombre</td>" .
                                "<td class='bolder'>$mes</td>" .
                                "<td class='bolder nowrap'><span class='text-success'>$deuda</span></td>" .
                                "<td class='bolder nowrap'><span class='text-success'>$abono</span></td>" .
                                "<td class='bolder nowrap'><span class='text-success' id='deuda_$id'>$resta</span></td>" .
                                (($estado == 3) ? "<td><b>-</b></td>" :  "<td class='bolder nowrap'><input type='checkbox' id='$id' value='campo[$id]' class='form-check-input checkbox_all' onclick='$(this).pago_totlal_check(this.checked);$(this).operar_sados()' style='width: 30px; height: 30px;' name='deuda_check'></td> ") .
                                "</tr>";

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
        <?php
        if($existencia >= 1): echo '<button type="submit" class="btn btn-success waves-effect waves-light">Pagar deuda</button>'; endif;
        ?>
    </div>
</form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-1200','.in_modal_secundario');

        $(this).upload_file();
        $(this).dinero_func('.dinero');
        $(this).datatable_func('#modal_tabla');

        $.fn.sumar_saldo_favor = function(calcular  = true) {
            return this.each(function() {

                let salor_favor = $("#saldo_favor").val().replaceAll('₡ ','').replaceAll(',','');
                let favor = $("#favor_uso").val().replaceAll('₡ ','').replaceAll(',','');


                //limpiar
                let favor_input = (salor_favor >= 1) ? parseFloat(salor_favor) : 0;
                let favor_clear = (favor >= 1) ?  parseFloat(favor) : 0;

                let operacion = 0;
                let operacion_resta = 0;
                //validar que no ingre mas de lo indicado
                if(favor_clear > favor_input){
                    $("#favor_uso").val('').change();
                    $(this).mensaje_alerta(1,  "El Valor Ingresado No puede superar el saldo a favor");
                    favor_clear = 0;
                }

                if(calcular == true){
                    $(this).operar_sados();
                }

                return false;
            })
        }

        $.fn.operar_sados = function() {
            return this.each(function() {

                let pagar       = $("#m_raza").val().replaceAll('₡ ','').replaceAll(',','');//valor a pagar
                let favor       = $("#favor_uso").val().replaceAll('₡ ','').replaceAll(',',''); //saldo favor
                let cancelado   = $("#m_saldo_cancelar").val().replaceAll('₡ ','').replaceAll(',','');//saldo cancelar

                //limpiar
                let pagar_clear      = ($.isNumeric(pagar) && pagar >= 1) ?  parseFloat(pagar) : 0;
                let favor_clear      = ($.isNumeric(favor)  && favor >= 1) ?  parseFloat(favor) : 0;
                let cancelar_clear   = ($.isNumeric(cancelado) && cancelado >= 1) ?  parseFloat(cancelado) : 0;





                operacion = (pagar_clear) >= 1 ? pagar_clear-(favor_clear+cancelar_clear) : 0;
                // let suma_datos = parseFloat(cancelar)-parseFloat(cancelado);
                // operacion_resta = (parseFloat(suma_datos)-parseFloat(operacion));
                // $("#saldo_restante").val('');
                //
                $(this).asignacion_numeric('#acomulado_cancelar',operacion)
                // $(this).asignacion_numeric('#saldo_restante',operacion_resta)

                return false;
            })
        }


        $.fn.pago_totlal_check = function() {
            return this.each(function(){
                console.log('check')
                let div_inpus = $(".input_hidden").empty();
                let inputs = '';
                let valor = 0;
                $('.checkbox_all:checked').each(function () {
                    let id = $(this).attr("id");

                    inputs += `<input type='hidden' name='deuda[]' value="${id}">`;

                    let valor2 = $(`#deuda_`+id).text().replaceAll('₡ ','').replaceAll(',','');
                    valor += parseFloat(valor2);
                });
                div_inpus.append(inputs)
                $(this).asignacion_numeric("#m_raza",valor);
                return false;
            })
        }

        // $.fn.pago_deuda_check = function() {
        //     return this.each(function(){
        //
        //         let div_inpus = $(".deudda_input_hidden").empty();
        //         let inputs = '';
        //         let valor = 0;
        //         $('.checkbox_all:checked').each(function () {
        //             let id = $(this).attr("id");
        //
        //             inputs += `<input type='hidden' name='afilial[]' value="${id}">`;
        //
        //             let valor2 = $(`#deuda_`+id).text().replaceAll('₡ ','').replaceAll(',','');
        //             valor += parseFloat(valor2);
        //         });
        //
        //         div_inpus.append(inputs)
        //         $(this).asignacion_numeric("#m_suma_deuda_valor",valor);
        //         return false;
        //     })
        // }


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_save_deuda_total',false,() => {
                    // $("#actualizar_deudas").load(location.href+" #actualizar_deudas>*","");
                    // $("#actualizar_favor").load(location.href+" #actualizar_favor>*","");
                    $(this).forms_modal2({"page" : "admin_pagos_comprobantes","data1" : "<?=($apartamento)?>",'data2' : "<?=$fecha;?>","title" : "Pagos Comprobantes"})
                });
                return false;
            }
        })




    })

</script>
