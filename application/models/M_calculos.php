<?php

class M_calculos extends CI_Model
{

    function calculo_pagos_sustraer($fecha){

        $Qdata = $this->db->query("SELECT ap.pg_id,ap.pg_tipo_deuda,COALESCE(monto,0) As pagado,ap.pg_deuda,ap.pg_deuda-COALESCE(monto,0) As agregado FROM apartamento_pagos AS ap
LEFT JOIN (
    SELECT ag.as_deuda,SUM(ag.aa_monto) AS monto FROM apartamento_pago_agregado AS ag WHERE ag.aa_estado=3 GROUP BY ag.as_deuda
    )
AS t ON ap.pg_id=t.as_deuda
WHERE  DATE_FORMAT(ap.pg_fecha, '%Y-%m') = '$fecha'
#HAVING agregado >= 1
")->result_object();

        $data = [];
        foreach($Qdata As $dd){

            $tipo = $dd->pg_tipo_deuda;
             $saldo = $dd->pg_deuda;
             $pagado = $dd->pagado;
            if(isset($data[$tipo])){
                //update
                $data[$tipo]['saldo'] +=$saldo;
                $data[$tipo]['abono'] +=$pagado;
            }else{
                //create
                $data[$tipo]['saldo'] =$saldo;
                $data[$tipo]['abono'] =$pagado;
            }
        }

        for($i =1;$i <= 3;$i++){
            if(!isset($data[$i])){
//                $data[$i] = 0;
                $data[$i]['saldo'] =0;
                $data[$i]['abono'] =0;
            }
        }
        return $data;
    }

}