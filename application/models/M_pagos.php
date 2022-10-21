<?php

class M_pagos extends CI_model{

    private $result = array();

    private function implode_crypt($deudas = []){
        $result = '';
        if(count($deudas) >= 1){
            foreach($deudas as $de){
//                    $result .= ($de).',';
                    $decrypt =  desencriptar($de);
                    $pre     = (isset($decrypt) and (strlen($de) >= 1) AND $decrypt != '') ? $decrypt : '0';
                    $result .= $pre.',';
            }
             $result .= '0';
        }
        return $result;
    }

    function pagos($user,$apt,$deudas,$saldo,$favor,$tipo,$comprobante,$deu_numero = '',$deu_fecha = '',$deu_observacion = ''){

        if($favor == 0 and $saldo == 0){
            //no se acepta que se pase como referencia de pago un valor igual a cero
            $this->result = array('success' => 2,'msg' => 'Debes Ingresar para realizar el proceso mayor a ₡ 1');
        }else{
            //validar que se tengan deudas para poder realizar a donde se aplicaran los pagos
            if((isset($deudas)) AND count($deudas) >= 1 and in_array($tipo,[1,2,3,4])){
                $pagos_in = $this->implode_crypt($deudas);
//                foreach($deudas As $deu){
//                    $deu_id = desencriptar($deu);

                if(in_array($tipo,[1,2,3])){
                        //pago agua, extra, condo
                        //la validacion de deuda solo aplica para filiales para la administracion no
//                        if($user->u_perfil == 2){ $this->validar_deudas($apt,$tipo);}
                        $this->realizar_pagos($user,$pagos_in,$apt,$saldo,$favor,$comprobante);
                    }else{
                        //saldazo de deudas
                        $this->realizar_deudas($user,$pagos_in,$apt,$saldo,$favor,$comprobante,$deu_numero,$deu_fecha,$deu_observacion);
                    }
//                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Debes Agregar una deuda a pagar');
            }
        }
        return  $this->result;
    }

    function realizar_pagos($usu,$ids,$apt,$saldo,$favor,$comprobante){
        $validar_deudas = $this->general->query("select GROUP_CONCAT(ap.pg_id) As ids from  apartamento_pagos As ap WHERE ap.pg_apartamento='".$apt."' AND ap.pg_id IN($ids)  order by ap.pg_fecha ASC",'obj');
        $ids_resolved = isset($validar_deudas[0]->ids) ? $validar_deudas[0]->ids : '';
        if(strlen($ids_resolved) >= 1){

            //operacion favor
            if($favor > 0) {
                //validar cual es el saldo a favor para y tomar lo que realmente tiene en su data
                $saldo_f = $this->saldo_favor($apt);
                $saldo_v = ($saldo_f <= $favor) ? $saldo_f : $favor;
                if($saldo_v > 0){
                    $this->lanzar_pagos_ace($ids_resolved, $saldo_v,2,$usu,$apt,$comprobante);
                }
            }

            //operacion saldo
            if($saldo > 0){
                $this->lanzar_pagos_ace($ids_resolved,$saldo,1,$usu,$apt,$comprobante);
            }


        }else{
            $this->result = array('success' => 2,'msg' => 'Debes Seleccionar una deuda valida para poder realizar los pagos');
        }
        return $this->result;
    }

    function realizar_deudas($usu,$ids,$apt,$saldo,$favor,$comprobante,$deu_numero = '',$deu_fecha = '',$deu_observacion = ''){
        $validar_deudas = $this->general->query("select GROUP_CONCAT(d.du_id) As ids from deuda As d where du_id IN ($ids) AND d.du_estado IN(1,2) and d.du_apartamento='".$apt."' order by  d.du_fecha_inicio ASC",'obj');
         $ids_resolved = isset($validar_deudas[0]->ids) ? $validar_deudas[0]->ids : '';

        if(strlen($ids_resolved) >= 1){

            //operacion favor
            if($favor > 0) {
                //validar cual es el saldo a favor para y tomar lo que realmente tiene en su data
                $saldo_f = $this->saldo_favor($apt);
                 $saldo_v = ($saldo_f <= $favor) ? $saldo_f : $favor;
                 if($saldo_v > 0){
                        $this->lanzar_pagos_deuda($ids_resolved, $saldo_v,2,$usu,$apt,$comprobante,$deu_numero = '',$deu_fecha = '',$deu_observacion = '');
                 }
             }

             //operacion saldo
            if($saldo > 0){
                $this->lanzar_pagos_deuda($ids_resolved,$saldo,1,$usu,$apt,$comprobante,$deu_numero = '',$deu_fecha = '',$deu_observacion = '');
            }


        }else{
            $this->result = array('success' => 2,'msg' => 'Debes Seleccionar una deuda valida para poder realizar los pagos');
        }
        return $this->result;
    }

    private function lanzar_pagos_ace($ids,$saldo,$tipo_pago,$usu,$apt,$comprobante){
    $validar_deudas = $this->general->query("select ap.pg_id As id,ap.pg_deuda-COALESCE(pagado,0) As dinero from apartamento_pagos As ap
LEFT JOIN (
    SELECT ag.as_deuda,SUM(ag.aa_monto) as pagado from apartamento_pago_agregado As ag WHERE ag.aa_estado=3 GROUP BY ag.as_deuda
    ) As t ON ap.pg_id=t.as_deuda
WHERE ap.pg_id IN($ids) AND ap.pg_estado IN (1,2) AND ap.pg_apartamento='".$apt."' ORDER BY ap.pg_fecha ASC");

    $pagosArray = $this->acomodar_pagos($validar_deudas,$saldo,$tipo_pago);
    $estado     = ($usu->u_perfil == 2) ? 1 : 3;

    if(isset($pagosArray['data']) and count($pagosArray['data']) > 0) {
        //variables obtenidas acomulacion de pagos

        $re_salvo_favor = $pagosArray['saldo_favor'];
        $acomulado      = $pagosArray['acomulado'];

        foreach ($pagosArray['data'] as $pagor) {

            $re_id    = $pagor['id'];
            $re_tomo = $pagor['tomo'];
            $re_pagar = $pagor['pagar'];
//            $this->deuda_comprobante($usu->u_id, $re_id, $apt, $re_pagar, $comprobante, $estado);
            $this->pago_comprobante($usu->u_id,$re_id,$apt,$re_tomo,$comprobante,$estado);

            if ($tipo_pago ==2) {
                //pago con saldo favor
                $this->consultas->log_saldo_favor('', 'pago', $apt, 2, 4, $re_id, $re_pagar);
            }
        }

        //Realizar apreciacion y tomar el saldo a favor
        if ($tipo_pago == 2) {
//            $re_tomo = $pagor['tomo'];
            $this->consultas->tomar_saldo_a_favor($apt, $re_tomo);//se toma el saldo a favor que se requiere para pagar la deuda
        }

        //agregar el saldo de favor
        if($re_salvo_favor > 0 and $usu->u_perfil != 2){
            $this->consultas->agregar_saldo_a_favor($apt,$re_salvo_favor);
        }else{
            //todo pago realizado por filiales se le inyecta al primer pago
            //variables locales de pago
            $re_date_re = reset($pagosArray['data']);
            $re_pv_id = $re_date_re['id'];
//            $this->deuda_comprobante($usu->u_id, $re_pv_id, $apt, $re_salvo_favor, $comprobante, $estado);
            $this->pago_comprobante($usu->u_id,$re_pv_id,$apt,$re_salvo_favor,$comprobante,$estado);
        }

        $this->result = array('success' => 1);
    }else{
        $this->result = array('success' => 2,'msg' => 'Validar los Comprobantes');
    }
}

    private function lanzar_pagos_deuda($ids,$saldo,$tipo_pago,$usu,$apt,$comprobante,$deu_numero = '',$deu_fecha = '',$deu_observacion = ''){
        $validar_deudas = $this->general->query("select d.du_id As id,d.du_saldo-COALESCE(pagado,0) As dinero from deuda As d
LEFT JOIN (
        select dp.pd_deuda,SUM(dp.pd_monto) As pagado from deuda_pagos As dp WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
    ) As t  ON d.du_id=t.pd_deuda
where du_id IN ($ids) AND d.du_apartamento='".$apt."' order by  d.du_fecha_inicio ASC");



        $pagosArray = $this->acomodar_pagos($validar_deudas,$saldo,$tipo_pago);
        $estado     = ($usu->u_perfil == 2) ? 1 : 3;

        if(isset($pagosArray['data']) and count($pagosArray['data']) > 0) {
            //variables obtenidas acomulacion de pagos

            $re_salvo_favor = $pagosArray['saldo_favor'];
            $acomulado      = $pagosArray['acomulado'];

            foreach ($pagosArray['data'] as $pagor) {

                $re_id    = $pagor['id'];
                $re_tomo = $pagor['tomo'];
                $re_pagar = $pagor['pagar'];
                $this->deuda_comprobante($usu->u_id, $re_id, $apt, $re_tomo, $comprobante, $estado);

                if ($tipo_pago ==2) {
                    //pago con saldo favor
                    $this->consultas->log_saldo_favor('', 'pago', $apt, 2, 4, $re_id, $re_pagar);
                }
            }

            //Realizar apreciacion y tomar el saldo a favor
            if ($tipo_pago == 2) {
                  $this->consultas->tomar_saldo_a_favor($apt, $re_tomo);//se toma el saldo a favor que se requiere para pagar la deuda
            }

            //agregar el saldo de favor
            if($re_salvo_favor > 0 and $usu->u_perfil != 2){
                $this->consultas->agregar_saldo_a_favor($apt,$re_salvo_favor);
            }else{
                //todo pago realizado por filiales se le inyecta al primer pago
                //variables locales de pago
                $re_date_re = reset($pagosArray['data']);
                 $re_pv_id = $re_date_re['id'];
                $this->deuda_comprobante($usu->u_id, $re_pv_id, $apt, $re_salvo_favor, $comprobante, $estado);
            }

            $this->result = array('success' => 1);
        }else{
            $this->result = array('success' => 2,'msg' => 'Validar los Comprobantes');
        }
    }



    //validacion si un usuario tiene deudas presentes NO BORRAR
//    private function validar_deudas($apt_id,$tipo){
//
//        //no permitir hacer pagos sin saldar anmteriormente sus deudas
//        $deuda_query = $this->general->query("select d.du_apartamento  from deuda As d WHERE d.du_apartamento='".$apt_id."'  AND d.du_estado IN(1,2)",'obj');
//        if(count($deuda_query) >= 1 and in_array($tipo,[1,2,3])){
//            //tiene deudas
////            $dd = $deuda_query[0];
////            if($dd->monto >= 1){
//            $this->result = array('success' => 2,'msg' => 'Por favor realizar la cancelacion de tus deudas');
//            api($this->result);
//            exit;
////            }
//        }
//    }


    //validacion si un usuario tiene deudas presentes NO BORRAR
    private function saldo_favor($apt){
        $favor_data =  $this->general->get('saldo_favor',['sf_apartamento' => $apt]);
        return (isset($favor_data->sf_saldo))?$favor_data->sf_saldo:0;
    }

    //cambiar los estados de los comprobantes
    function pagos_estados_cambio($deudas,$estado,$tipo){
        $deu_id = isset($deudas['0']) ? desencriptar($deudas['0']) : '';
        $this->realizar_cambio_estados($deu_id,$estado,$tipo);
        return $this->result;
    }

    function realizar_cambio_estados($id,$estado,$tipo){
        $this->load->model('Generico','generico');
        if(in_array($tipo,[1,2,3])){
            //cambiar estado de pagos normales
            $data_q = $this->general->query(
                "select apa.as_agregado,apa.as_apartamento,ap.pg_tipo_deuda,ap.pg_tipo_deuda,apa.aa_monto,ap.pg_deuda As deuda,COALESCE(pagado,0) As pagado,(ap.pg_deuda-COALESCE(pagado,0)) As restado from apartamento_pago_agregado As apa
    LEFT JOIN apartamento_pagos As ap ON apa.as_deuda=ap.pg_id
                     LEFT JOIN (
                                SELECT ag.as_deuda,SUM(ag.aa_monto) As pagado from apartamento_pago_agregado As ag WHERE ag.aa_estado=3 GROUP BY ag.as_deuda
                        ) As t ON ap.pg_id=t.as_deuda
                    WHERE apa.as_id='".$id."' AND apa.aa_estado IN(1,2)",'obj');

            if(count($data_q) >= 1){

                $data = $data_q[0];

                $id_padre    = $data->as_agregado;
                $tipo_deuda  = $data->pg_tipo_deuda;
                $apt         = $data->as_apartamento;
                $saldo       = $data->aa_monto;
                $deuda       = $this->class_security->solo_numerico($data->deuda);
                $pagado      = $this->class_security->solo_numerico($data->pagado);
                $res_add     = ($pagado+$saldo);
                if($res_add > $deuda){
                    //se debe validar un saldo a favor
                    if($pagado >= $deuda){
                        //agregar el saldo a favor
                        if($estado == 3) {
                            $this->consultas->agregar_saldo_a_favor($apt, $saldo);
                            $this->consultas->log_saldo_favor('', 'pago', $apt, 1, $tipo_deuda, $id, $saldo);
                        }

                    }else{
                        //agregar el saldo a favor
                        if($estado == 3){
                            $saldo_favor = $res_add-$deuda;
                            $this->consultas->agregar_saldo_a_favor($apt,$saldo_favor);
                            $this->consultas->log_saldo_favor('','pago',$apt,1,$tipo_deuda,1,$id,$saldo_favor);
                        }
                    }



                    $this->general->update('apartamento_pagos_abono',['aa_id'=>$id_padre],['aa_estado'=>$estado]);
                    $this->general->update('apartamento_pago_agregado',['as_id'=>$id],['aa_estado'=>$estado]);
                }else{
                    //solo se actualiza el comprobante ya que no tiene saldo a favor el cual poder manejar
                    $this->general->update('apartamento_pagos_abono',['aa_id'=>$id_padre],['aa_estado'=>$estado]);
                    $this->general->update('apartamento_pago_agregado',['as_id'=>$id],['aa_estado'=>$estado]);
                }

                if(in_array($estado,[3,5])){
                    $this->generico->create_mail($id,'pagos','pago');
                }
                $this->validacion_estado_pagos($id);
                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Validar el pago ya no se encuentra soportado');
            }
        }else{

            //cambiar estado de deudas
            $data_q = $this->general->query(
                "select d.du_apartamento,dp2.pd_monto,d.du_saldo As deuda,COALESCE(pagado,0) As pagado,(d.du_saldo-COALESCE(pagado,0)) As restado from deuda As d
                    INNER JOIN deuda_pagos As dp2  ON d.du_id = dp2.pd_deuda
                LEFT JOIN (
                    SELECT dp.pd_deuda,SUM(dp.pd_monto) As pagado from deuda_pagos As dp  WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
                ) As t ON t.pd_deuda=d.du_id
                WHERE dp2.dp_id='".$id."' AND dp2.pd_estado IN(1,2)",'obj');

            if(count($data_q) >= 1){

                $data = $data_q[0];

                $tipo_deuda  = 4;
                $apt         = $data->du_apartamento;
                $saldo       = $data->pd_monto;
                $deuda       = $this->class_security->solo_numerico($data->deuda);
                $pagado      = $this->class_security->solo_numerico($data->pagado);
                $res_add     = ($pagado+$saldo);


                if($res_add > $deuda){
                    //se debe validar un saldo a favor
                    if($pagado >= $deuda){
                        //agregar el saldo a favor
                        if($estado == 3) {
                            $this->consultas->agregar_saldo_a_favor($apt, $saldo);
                            $this->consultas->log_saldo_favor('', 'pago', $apt, 1, $tipo_deuda, $id, $saldo);
                        }

                    }else{
                        //agregar el saldo a favor
                        if($estado == 3){
                            $saldo_favor = $res_add-$deuda;
                            $this->consultas->agregar_saldo_a_favor($apt,$saldo_favor);
                            $this->consultas->log_saldo_favor('','pago',$apt,1,$tipo_deuda,1,$id,$saldo_favor);
                        }
                    }
                    $this->general->update('deuda_pagos',['dp_id'=>$id],['pd_estado'=>$estado]);
                }else{
                    //solo se actualiza el comprobante ya que no tiene saldo a favor el cual poder manejar
                    $this->general->update('deuda_pagos',['dp_id'=>$id],['pd_estado'=>$estado]);
                }

                if(in_array($estado,[3,5])){
                    $this->generico->create_mail($id,'pagos','deuda');
                }

                $this->validacion_estado_deuda($id);
                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Validar el pago ya no se encuentra soportado');
            }
        }
    }


    //Validar los estados de las deudas y cambiar su estado cuando se cumple el pago completo
    function validacion_estado_pagos($id){
        $data = $this->general->query("select  ag1.as_deuda,CASE
           WHEN ap.pg_deuda-COALESCE(pagado,0) <= 0 THEN 0
           ELSE ap.pg_deuda-COALESCE(pagado,0)
       END
       As validar from apartamento_pagos As ap
           INNER JOIN apartamento_pago_agregado As ag1 ON ag1.as_deuda=ap.pg_id
 LEFT JOIN (
     SELECT ag.as_deuda,SUM(ag.aa_monto) As pagado from apartamento_pago_agregado As ag WHERE ag.aa_estado=3 GROUP BY ag.as_deuda
    ) As t ON ap.pg_id=t.as_deuda
WHERE ag1.as_id='".$id."'",'obj');


        if(isset($data[0])){
            $row = $data[0];

            if($row->validar == 0){
                //cambiar el estado de la Deuda
                $this->general->update('apartamento_pagos',['pg_id' => $row->as_deuda],['pg_estado' => 3]);
            }
        }
    }

    function validacion_estado_deuda($id){
        $data = $this->general->query("select
               ag1.pd_deuda,CASE
                   WHEN d.du_saldo-COALESCE(pagado,0) <= 0 THEN 0
                   ELSE d.du_saldo-COALESCE(pagado,0)
               END
               As validar
               from deuda As d
                    INNER JOIN deuda_pagos As ag1 ON ag1.pd_deuda=d.du_id
        LEFT JOIN (
            SELECT dp.pd_deuda,SUM(dp.pd_monto) As pagado from deuda_pagos As dp  WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
        ) As t ON t.pd_deuda=d.du_id
        WHERE ag1.dp_id='".$id."'",'obj');

        $row = $data[0];

        if($row->validar == 0){
            //cambiar el estado de la Deuda
            $this->general->update('deuda',['du_id' => $row->pd_deuda],['du_estado' => 3]);
        }

    }

    //registro de comprobantes de pagos
    private function pago_comprobante($usu,$id,$apt,$saldo,$comprobante,$estado,$tipo = 1){
            if($saldo > 0){
              $data =   $this->general->create('apartamento_pagos_abono',[
                    'as_apartamento'    => $apt,
                    'aa_deuda'          => $id,
                    'aa_usuario'        => $usu,
                    'aa_saldo'          => $saldo,
                    'aa_comprobante'    => $comprobante,
                    'aa_estado'         => $estado,
                    'aa_atcreate'       => fecha(2)
                ]);

               $id_r = $data['id'];

              $id_p =  $this->general->create('apartamento_pago_agregado',[
                    'as_apartamento'    => $apt,
                    'as_deuda'          => $id,
                    'as_agregado'       => $id_r,
                    'aa_monto'          => $saldo,
                    'aa_tipo'           => 2,
                    'aa_estado'         => $estado,
                    'aa_favor'          => $tipo,
                    'aa_fecha'          => fecha(2)
                ]);
                $id_pago = $id_p['id'];
                $this->validacion_estado_pagos($id_pago);
                $this->general->query("CALL proc_movimiento('$apt','".fecha(2)."');",'',false);
            }

    }

    private function deuda_comprobante($usu,$id,$apt,$saldo,$comprobante,$estado,$deu_numero = '',$deu_fecha = '',$deu_observacion = ''){

        if($saldo > 0){
            $id_p =   $this->general->create('deuda_pagos',[
                    'pd_usuario'     => $usu,
                    'pd_deuda'       => $id,
                    'pd_apartamento' => $apt,
                    'pd_monto'       => $saldo,
                    'pd_n_comprobante' => $deu_numero,
                    'pd_comprobante' => $comprobante,
                    'pd_estado'      => $estado,
                    'pd_observacion' => $deu_observacion,
                    'pd_atcreate'    => ($deu_fecha != '') ? $deu_fecha : fecha(2)
                ]);
            $id_pago = $id_p['id'];
            $this->validacion_estado_deuda($id_pago);
            $this->general->query("CALL proc_movimiento('$apt','".fecha(2)."');",'',false);
        }
    }

    //funciones genericas

    private function acomodar_pagos($deudas,$dinero,$tipo){
        $pagar = [];
        $acomulado_pago = 0;

        //valdiacion de dinero y saldos de usuarios
        foreach($deudas as $deuda){

            $resta = $this->dinero($deuda['dinero'], $dinero,$tipo);

            if($resta['pagar'] > 0) {
                $acomulado_pago += $resta['pagar'];
                $pagar['data'][$deuda['id']]  = $deuda;
                $pagar['data'][$deuda['id']] += $resta;
                $dinero = $resta['resta'];
            }else{
                $pagar['data'][$deuda['id']]  = $deuda;
                $pagar['data'][$deuda['id']] += $resta;
            }
        }
        $pagar['saldo_favor']  = $dinero;
        $pagar['acomulado']  = $acomulado_pago;
        return $pagar;
    }

    private function dinero($monto,$valor,$tipo){
        if($valor >= $monto){
            $res = [
                'pagar' => $monto,
                'tomo' => $valor,
                'resta' => round(floatval($valor)-floatval($monto),2),
                'tipo' => $tipo
            ];
        }
        elseif($monto > $valor){
            $res = [
                'pagar' => $valor,
                'tomo'  => $valor,
                'resta' => 0,
                'tipo'  => $tipo
            ];
        }
        else{
            $res = [
                'pagar' => 0,
                'tomo'  => 0,
                'resta' => 0,
                'tipo'  => $tipo
            ];
        }
        return $res;
    }
}