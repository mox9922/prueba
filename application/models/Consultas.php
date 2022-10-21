<?php

class Consultas extends CI_Model
{

    function tomar_saldo_a_favor($apt,$valor){

        $query_f = $this->general->get('saldo_favor',['sf_apartamento' => $apt],'array');
        $favor_r = (isset($query_f['sf_saldo']) AND (count($query_f) >= 1)) ? $query_f['sf_saldo'] : 0;
        $suma = $favor_r-$valor;
        $this->general->create_update('saldo_favor',['sf_apartamento' => $apt],['sf_apartamento' => $apt,'sf_saldo' => $suma]);
    }

    function agregar_saldo_a_favor($apt,$valor){

        $query_f = $this->general->get('saldo_favor',['sf_apartamento' => $apt],'array');
        $favor_r = (isset($query_f['sf_saldo']) AND (count($query_f) >= 1)) ? $query_f['sf_saldo'] : 0;
        $suma = $favor_r+$valor;
        $this->general->create_update('saldo_favor',['sf_apartamento' => $apt],['sf_apartamento' => $apt,'sf_saldo' => $suma]);
    }

    function log_saldo_favor($id = '',$procedimiento,$apt = '',$tipo_recaudo = '',$tipo_deuda = '',$id_deuda = '',$monto  = ''){

        if($procedimiento == 'pago') {

            if($monto > 0){
                //registra dinero de un saldo a favor

                $this->general->create('apartamento_pagos_logs', [
                    'al_apartamento'    => $apt,
                    'al_tipo_recaudo'   => $tipo_recaudo, // 1 = ingreso 2 = egreso
                    'al_tipo_deuda'     => $tipo_deuda, // tipo de deuda 1,2,3,4
                    'al_id_deuda'       => $id_deuda, //id de la deuda
                    'al_monto'          => $monto, // monto a pagar
                    'al_fecha'          => fecha(2),
                ]);
            }

        }else{
            //se regresa el dinero a favor total tomado o ingresado
            $data = $this->general->get('apartamento_pagos_logs',['al_id' => $id],'obj');


            //validar de donde proviene el dinero y realizar el descuento de lo ingresado
            if($data->al_tipo_recaudo == '4'){
                //deuda
                $this->general->update('deuda_pagos',['de_id' => $id_deuda],['pd_estado' => 4]);
            }else {
                //pago condominal , agua, extra ordinaria
                $this->general->update('apartamento_pago_agregado',['de_id' => $id_deuda],['aa_estado'=> 4]);
            }
            //regresar el saldo a favor
            $saldo_tomado = $data->al_monto;
            $this->agregar_saldo_a_favor($apt,$saldo_tomado);

            //eliminar registro de log de los saldos agregados
            $this->general->delete('apartamento_pagos_logs', ['al_id' => $id]);
        }

    }

}