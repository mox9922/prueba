<?php

class Jobs extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('generico');
        $this->load->library('mailer');
        $this->load->helper(array('website','encriptar'));
    }

    function index(){
        $anno_actual = date('Y-m');
        $fecha = fecha(2);

        $consulta = "select p.pg_id,p.pg_apartamento,p.pg_tipo_deuda,p.pg_anno,p.pg_fecha,DATE_FORMAT(p.pg_fecha,'%Y-%m') As fecha_pago,p.pg_deuda,COALESCE(pagado,0) As pagado,p.pg_deuda-COALESCE(pagado,0) As resta  from apartamento_pagos As p
LEFT JOIN (select ap.as_deuda,SUM(ap.aa_monto)As pagado from apartamento_pago_agregado As ap WHERE  ap.aa_estado=3 GROUP BY ap.as_deuda) As t ON p.pg_id=t.as_deuda
WHERE p.pg_estado=1 AND p.pg_tipo_deuda IN(1,2)  AND DATE_FORMAT(p.pg_fecha,'%Y-%m') < '$anno_actual'
HAVING(resta) > 0";

        $consultar = $this->general->query($consulta,'obj');
        foreach($consultar As $deu){

            $anno_actual = $deu->pg_anno;
            $fecha = $deu->pg_fecha . ' 10:02:00';
            //actualiar estado a deuda
            $this->general->query("update apartamento_pagos set pg_estado='4' WHERE pg_id='$deu->pg_id'",'',false);

            $tipo = $this->class_data->comprobante[$deu->pg_tipo_deuda];
            $descripcion_deuda = "Deuda {$deu->pg_id} - {$tipo}";
            $fecha_hoy = fecha(1);

            //con
            $this->general->query("insert into deuda(du_apartamento,du_nombre,du_saldo,du_n_pagos,du_fecha_inicio,du_observacion,du_estado,du_atcreate)  VALUES('$deu->pg_apartamento','$descripcion_deuda','$deu->resta','1','$fecha_hoy','$descripcion_deuda','1','$fecha')",'',false);
        }

    }

    function movimientos(){
      $datas =   $this->general->query(" select ap.pg_apartamento,ap.pg_atcreate,m.am_apartamento from  apartamento_pagos As ap
LEFT JOIN apartamento_movimiento As m ON ap.pg_apartamento=m.am_apartamento AND DATE_FORMAT(m.am_atcreate,'%Y-%m') = DATE_FORMAT(ap.pg_atcreate,'%Y-%m')
HAVING m.am_apartamento IS NULL",'obj');

      foreach($datas As $dt){
          $this->general->create('apartamento_movimiento',['am_apartamento' => $dt->pg_apartamento,'am_atcreate' => $dt->pg_atcreate]);
      }
    }

    function usuarios(){
        $this->general->query("DELETE FROM propietarios_visitante WHERE pv_id = (select pv_id FROM (SELECT p.pv_id,DATEDIFF(p.pv_fecha_ingreso,NOW()) AS fecha FROM propietarios_visitante AS p WHERE pv_tipo_invitado=2 HAVING (fecha) < 0) pv_id)");
    }

    function condominal(){

        $mess = date("n");
        $mes = date("m");
        $anno = date("Y");
        $fecha =  date('Y-m');
        $fecha_min = fecha(1);
        $fecha_big = fecha(2);


        //obtener todas las filiales
        $filiales = $this->general->query("select ap.t_id,ap.t_cuota,a.pg_id from apartamento As ap 
        LEFT JOIN apartamento_pagos a on ap.t_id = a.pg_apartamento AND a.pg_tipo_deuda=2 AND DATE_FORMAT(a.pg_fecha,'%Y-%m') = '".$fecha."'
        HAVING a.pg_id IS NULL AND ap.t_cuota IS NOT NULL AND ap.t_cuota >= 1",'obj');

        if(count($filiales) >= 1){
            foreach($filiales As $fi){
                $this->general->query("CALL proc_movimiento('$fi->t_id','$fecha_big')",'x',false);

                $this->general->create('apartamento_pagos',
                [
                    'pg_apartamento' => $fi->t_id,
                    'pg_tipo_deuda'  => 2,
                    'pg_mes' => $mess,
                    'pg_anno' => $anno,
                    'pg_deuda' => $fi->t_cuota,
                    'pg_estado' => 1,
                    'pg_fecha' => $fecha_min,
                    'pg_atcreate' => $fecha_big,

                ]);

            }
        }

    }

    function agua(){

//        $fecha  =  date('Y-03');
//        $mes    = date("3");
//        $anno   = date("Y");

//        $fecha  =  date('Y-m');
//        $mes    = date("n");
//        $anno   = date("Y");

        //obtener todas las filiales

        $filiales = $this->general->query("select pa.pa_id,pa.pa_m3,pa.pa_monto,la.lt_apartamento,COALESCE(la.lt_anterior,0) As lt_anterior,la.lt_lectura,la.lt_consumo,ap.pg_id,la.lt_atcreate,DATE_FORMAT(CONCAT(la.lt_anno,'-',la.lt_mes,'-',DAY(NOW())),'%Y-%m-%d') As fecha,la.lt_anno As anno,la.lt_mes As 'mes' from pagos_agua As pa
          LEFT JOIN lectura_agua As la ON la.lt_anno=pa.pa_anno AND la.lt_mes=pa.pa_mes
          LEFT JOIN apartamento_pagos As ap ON la.lt_apartamento=ap.pg_apartamento AND DATE_FORMAT(ap.pg_fecha , '%Y-%m') = DATE_FORMAT(pa.pa_fecha , '%Y-%m') AND ap.pg_tipo_deuda=1
        #WHERE la.lt_anterior < la.lt_lectura
        HAVING la.lt_lectura IS NOT NULL AND ap.pg_id IS NULL AND la.lt_consumo >= 1 AND lt_lectura > 0",'obj');


        $valor = 0;
        if(count($filiales) >= 1){
            foreach($filiales As $data){

                $id               = $data->pa_id;
                $m3               = $data->pa_m3;
                $id_apartamento   = $data->lt_apartamento;
                $consumo_anterior = $data->lt_anterior;
                $consumo_actual   = $data->lt_lectura;
                $consumo          = $data->lt_consumo;

                $anno = $data->anno;
                $mes = $data->mes;


                $op = $this->class_security->calculo_validar_total($m3,$consumo_anterior,$consumo_actual,$consumo);


//                echo $m3. ' - '.$consumo_anterior. ' - '.$consumo_actual;
//                echo "\n";
//                exit;
//                print_r($op);

                 if($op['mul'] >= 1){

                //registro pago de agua
                $this->general->create_update("pagos_agua_montos",array(
                    'pm_apartamento'    => $id_apartamento,
                    'pm_mes'            => $mes,
                    'pm_anno'           => $anno,
                ),array(
                    'pm_pago'           => $id,
                    'pm_apartamento'    => $id_apartamento,
                    'pm_mes'            => $mes,
                    'pm_anno'           => $anno,
                    'pm_anterior'       => $consumo_anterior,
                    'pm_actual'         => $consumo_actual,
                    'pa_consumo'        => $op['res'],
                    'pa_total'          => $op['mul'],
                ));

                //log de pago
                $this->general->create_update("apartamento_pagos",array(
                    'pg_apartamento'  => $id_apartamento,
                    'pg_mes'          => $mes,
                    'pg_anno'         => $anno,
                    'pg_tipo_deuda'   => 1,
                ),array(
                    'pg_apartamento'  => $id_apartamento,
                    'pg_tipo_deuda'   => 1,
                    'pg_mes'          => $mes,
                    'pg_anno'         => $anno,
                    'pg_deuda'        => $op['mul'],
                    'pg_estado'       => 1,
                    'pg_fecha'        => $data->fecha,
                    'pg_atcreate'     => $data->lt_atcreate
                ));


                }
            }
        }

    }

    function proveedor(){

        $fecha  =  date('Y-m-d');
        $dia    = date("d");

        //obtener todas las filiales
        $filiales = $this->general->query("select s.*,
               (select s2.sv_id As res
        from servicios As s2 WHERE s2.sv_fecha='".$fecha."') As subhijo from servicios As s
        WHERE s.sv_recurrente=1
         AND date_format(s.sv_fecha , '%d') ='".$dia."' HAVING subhijo IS NULL",'obj');

        if(count($filiales) >= 1){
            foreach($filiales As $data){

              $categoria    = $data->sv_categoria;
              $proveedor    = $data->sv_proveedor;
              $titulo       = $data->sv_titulo;
              $valor        = $data->sv_valor;

              $this->general->create('servicios',[
                  'sv_categoria' => $categoria,
                  'sv_proveedor' => $proveedor,
                  'sv_titulo' => $titulo,
                  'sv_valor' => $valor,
                  'sv_estado' => 1,
                  'sv_fecha' => fecha(1),
                  'sv_atcreate' => fecha(2),
                  'sv_recurrente' => 2,
              ]);

            }
        }

    }

    function correos(){
        $msg = "";
        $template = "correos/v_defualt";
        $titulo = "";
        $u_correo = "";
        $documentos = [];
        $correos_task = $this->general->all_get('correos_task');
        foreach($correos_task As $cr){

            $t_id = $cr->cr_registro;
            //validar que tipo de correos son
            if($cr->cr_cola == 'ticket'){

                //obtener la informacion de la reserva
                $data = $this->general->query("select t.t_estado,t.t_titulo,t.t_prioridad,t.t_codigo,t.t_descripcion,t.t_documento1,t.t_documento2,t.t_documento3,t.t_documento4,u.t_correo As correo,tr.tr_descripcion,tr.tr_documento1,tr_document2 from ticket As t
                LEFT JOIN ticket_response tr on t.t_id = tr.tr_ticket
                JOIN apartamento As u ON t.t_apartamento=u.t_id
                WHERE t.t_id='".$t_id."'
                ORDER BY tr.tr_id DESC LIMIT 1",'obj');


                foreach($data As $tmp){

                    $t_titulo       = $tmp->t_titulo;
                    $t_estado_n     = $tmp->t_estado;
                    $t_prioridad_n  = $tmp->t_prioridad;
                    $t_codigo       = $tmp->t_codigo;
                    $t_descip       = $tmp->t_descripcion;
                    $u_correo       = $tmp->correo;
                    $r_descip       = $tmp->tr_descripcion;

                    //documentos
                    $t_doc1         = $tmp->t_documento1;
                    $t_doc2         = $tmp->t_documento2;
                    $t_doc3         = $tmp->t_documento3;
                    $t_doc4         = $tmp->t_documento4;
                    $r_doc1         = $tmp->tr_documento1;
                    $r_doc2         = $tmp->tr_document2;

                    $t_estado       = $this->class_security->array_data($t_estado_n,$this->class_data->data_estado['ticket'],'');
                    $t_prioridad    = $this->class_security->array_data($t_prioridad_n,$this->class_data->ticket_prioridad,$this->class_data->estado_default)['title'];


                    //validar el ticket como se toca
                    if($t_estado_n == 1){

                        //se creo el ticket recientemente
                        $titulo = "Se creo un nuevo ticket  #{$t_codigo} con la Prioridad $t_prioridad";
                        $descripcion = $t_descip;

                        if(strlen($t_doc1) >= 10 AND $this->class_security->validar_existe_archivo('_files',$t_doc1)) $documentos[] = ["_files/{$t_doc1}",$t_doc1];
                        if(strlen($t_doc2) >= 10 AND $this->class_security->validar_existe_archivo('_files',$t_doc2)) $documentos[] = ["_files/{$t_doc2}",$t_doc2];
                        if(strlen($t_doc3) >= 10 AND $this->class_security->validar_existe_archivo('_files',$t_doc3)) $documentos[] = ["_files/{$t_doc3}",$t_doc3];
                        if(strlen($t_doc4) >= 10 AND $this->class_security->validar_existe_archivo('_files',$t_doc4)) $documentos[] = ["_files/{$t_doc4}",$t_doc4];


                    }else{

                        //el ticket tiene movimiento entre 2 y 3
                        $titulo = "Se $t_estado El ticket {$t_codigo} con la Prioridad $t_prioridad";
                        $descripcion = $r_descip;

                        if(strlen($r_doc1) >= 10 AND $this->class_security->validar_existe_archivo('_files',$r_doc1)) $documentos[] = ["_files/{$r_doc1}",$r_doc1];
                        if(strlen($r_doc2) >= 10 AND $this->class_security->validar_existe_archivo('_files',$r_doc2)) $documentos[] = ["_files/{$r_doc2}",$r_doc2];
                    }

                    $template = "correos/ticket/v_ticket";
                    $data_msg = [
                        'nombre' => '',
                        'titulo' => $t_titulo,
                        'estado' => $t_estado_n,
                        'cuerpo' => $descripcion,
                    ];
                }
                //END TICKET

            }

            elseif($cr->cr_cola == 'reserva'){

                //obtener la informacion de la reserva
                $reserva_data = $this->general->query("select a.t_correo As correo,a.t_apartamento,r.r_id,r.r_nombre,r.r_telefono,r.r_correo,r.r_valor,r.r_atcreate,r.r_asistencia,r.r_estado,r.r_comprobante,SUBSTRING(rd.rd_fecha1,1,10) As fecha,SUBSTRING(rd.rd_fecha1,11,16) As hora1,SUBSTRING(rd.rd_fecha2,11,16) As hora2,rd.rd_estado from reserva As r
                JOIN reserva_dias As rd on r.r_id = rd.rd_reserva
                JOIN apartamento AS a ON r.r_apartamento=a.t_id
                WHERE r.r_id='".$t_id."'",'obj');

                $tabla_dias = "";

                if(isset($reserva_data) AND count($reserva_data) >= 1){
                $r_data = $reserva_data[0];

                $u_correo        = $r_data->correo;
                $nombre          = $r_data->t_apartamento;
                $r_nombre        = $r_data->r_nombre;
                $r_telefono      = $r_data->r_telefono;
                $r_correo        = $r_data->r_correo;
                $r_asistencia    = $r_data->r_asistencia;
                $r_estado        = $r_data->r_estado;
                $r_valor         = $r_data->r_valor;
                $r_fecha         = $r_data->r_atcreate;
                $estado          = $this->class_security->array_data($r_estado,$this->class_data->data_estado['reserva'],'');

                //titulo reserva
                $titulo          = ($r_estado == 1) ? "Se Registro la reserva {$r_fecha}" : "Se $estado la reserva $r_fecha";


                foreach($reserva_data As $tmpr){

                    $d_fecha         = $tmpr->fecha;
                    $d_fecha1        = $this->formato_fecha($tmpr->hora1);
                    $d_fecha2        = $this->formato_fecha($tmpr->hora2);
                    $d_estado        = $this->class_security->array_data($tmpr->rd_estado,$this->class_data->restado_reserva,$this->class_data->estado_default)['title'];

                    //documentos
                    $comprobante     = $tmpr->r_comprobante;
                    if(strlen($comprobante) >= 10 AND $this->class_security->validar_existe_archivo('_files',$comprobante)) $documentos[] = ["_files/{$comprobante}",$comprobante];

                    $tabla_dias .= "<tr>
                                        <td><b>$d_fecha</b></td>
                                        <td><b>$d_fecha1</b></td>
                                        <td><b>$d_fecha2</b></td>
                                        <td><b>$d_estado</b></td>
                                    </tr>
                                    ";
                }


                $template = "correos/v_reserva";
                $data_msg = [
                    'anombre'     => $nombre,
                    'nombre'     => $r_nombre,
                    'telefono'   => $r_telefono,
                    'correo'     => $r_correo,
                    'asistentes' => $r_asistencia,
                    'estado'     => $estado,
                    'valor'      => $r_valor,
                    'fecha'      => $r_fecha,

                    'dias'       => $tabla_dias,
                ];
                //END RESERVA
             }

            }

            elseif($cr->cr_cola == 'pagos'){

                if($cr->cr_tipo == 'deuda'){

                    $data_q = $this->general->query("select a.t_correo As correo,a.t_apartamento,'4' As 'tipo_deuda',d.du_saldo As deuda,dp.pd_monto As monto,dp.pd_estado As estado,SUBSTRING(d.du_atcreate,1,10) As fecha,dp.pd_comprobante As comprobante,dp.pd_atcreate As fecha_pago from deuda As d
                    JOIN deuda_pagos dp on d.du_id = dp.pd_deuda
                    JOIN apartamento a on a.t_id = d.du_apartamento
                 WHERE dp.dp_id='".$t_id."'",'obj');

                }else{

                    $data_q = $this->general->query("select a.t_correo As correo,a.t_apartamento,d.pg_tipo_deuda As 'tipo_deuda',d.pg_deuda As deuda,ag.aa_monto As monto,ag.aa_estado As estado,SUBSTRING(d.pg_fecha,1,10) As fecha,apa.aa_comprobante As comprobante,ag.aa_fecha As fecha_pago from apartamento_pagos As d
    JOIN apartamento_pagos_abono apa on d.pg_id = apa.aa_deuda
    JOIN apartamento_pago_agregado AS ag ON d.pg_id=ag.as_deuda AND apa.aa_id = ag.as_agregado
    JOIN apartamento a on d.pg_apartamento = a.t_id
 WHERE ag.as_id='".$t_id."'",'obj');
                }


                if(isset($data_q) AND count($data_q) >= 1){

                    $tmpr = $data_q[0];
                    $nombre = $tmpr->t_apartamento;
                    $u_correo = $tmpr->correo;


                    //documentos
                    $comprobante     = $tmpr->comprobante;
                    if(strlen($comprobante) >= 10 AND $this->class_security->validar_existe_archivo('_files',$comprobante)) $documentos[] = ["_files/{$comprobante}",$comprobante];
                    $tipo = $this->class_security->array_data($tmpr->tipo_deuda,$this->class_data->comprobante);
                    $estado = $this->class_security->array_data($tmpr->estado,$this->class_data->estado_pago_formal,$this->class_data->estado_default)['title'];

                    $deuda = $this->class_security->dinero($tmpr->monto);
                    $fecha = ($tmpr->fecha_pago);
                    $titulo = "Se valido estado para el comprobante {$tipo} fecha $fecha";

                        $template = "correos/v_pagos";
                        $data_msg = [
                            'anombre'   => $nombre,
                            'tipo'      => $tipo,
                            'estado'    => $estado,
                            'deuda'     => $deuda,
                            'fecha'     => $fecha,
                        ];
                        //END PAGOS

                    }

            }

            $this->general->delete('correos_task',['cr_id' => $cr->cr_id]);



            if($u_correo != ''){
                $mailer = new Mailer();
                $config = array(
                   'to'            =>$u_correo,
                   'subject'       => $titulo,
                   'email'         => $this->config->config['project']['email']['ticket'],
                   'bcc'           => $this->admin_notify(),
                   'data'          => isset($data_msg) ? $data_msg : '',
                   'template'      => $template,
                   'attachment'    => $documentos,
               );
               $mailer->initialize($config);
               $mailer->send_mail();

            }

        }
    }

    function validar_agua(){
        $data = $this->general->query("select ap.t_apartamento,lt_id, lt_apartamento, lt_usuario, lt_anterior, lt_lectura, lt_consumo, la.lt_imagen,lt_atcreate from apartamento As ap
 INNER JOIN lectura_agua la on ap.t_id = la.lt_apartamento",'obj');

        foreach($data As $d){

            $consumo = $this->class_security->calculo_validar_total(0,$d->lt_anterior,$d->lt_lectura);
            $this->general->update('lectura_agua',['lt_id' => $d->lt_id],['lt_consumo' => $consumo['res']]);
        }
    }

   private function admin_notify(){
        $correos = $this->general->query("select GROUP_CONCAT(u_correo1) As correos from usuarios WHERE u_perfil=1 AND u_estado=1 AND LENGTH(u_correo1) >= 5 limit 1",'obj');
        return (count($correos) >= 1) ? $correos[0]->correos : '';
    }

    private function formato_fecha($fecha){
        return date("g:m:s A", strtotime($fecha));
    }
}