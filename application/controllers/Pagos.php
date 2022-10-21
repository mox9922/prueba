<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Pagos  extends Ci_controller{
    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Control de Pagos';
    private $controller   = 'pagos/';
    private $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();
        permissions(1);


       //validar y hacer el llamado de todo
        $this->user_data = $this->general->get('usuarios',array('u_id' => $this->session_id));

        $this->load->model('generico');
        $this->load->helper(array('website','encriptar'));
        $this->load->model('M_calculos','calculos');


    }

    function index(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data

        );

        $data_menu = array();
        $id = $this->user_data->u_id;

        $data_body = array(
            'annos' => $this->general->query('SELECT DISTINCT pa_anno FROM pagos_agua'),
            'crud' => array(

                'url_modals'                        => base_url("modal/"),
                'url_save'                          => base_url("{$this->controller}save"),
                'dell_pago'                         => base_url("{$this->controller}delete"),
                'url_costo'                         => base_url("{$this->controller}costo"),
                'url_report'                        => base_url("{$this->controller}report"),
                'url_estadistica'                   => base_url("{$this->controller}estadistica"),
                'url_cambiar_estado'                => base_url("{$this->controller}cambiar_estado"),
                'url_cambiar_estado_deuda'          => base_url("{$this->controller}cambiar_estado_deuda"),
                'url_cambiar_estado_comprobante'    => base_url("{$this->controller}cambiar_estado_comprobante"),
                'url_asinar_saldo_favor'            => base_url("{$this->controller}asinar_saldo_favor"),
                'url_pagar_saldo_favor'             => base_url("{$this->controller}pagar_saldo_favor"),
                'url_save_deuda_total'             => base_url("{$this->controller}save_total_deuda"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/pagos.js','plugins/numero/AutoNumeric.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_pagos', $data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    //method crud
    function cambiar_estado(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','modulo','dato'))) {

                //campos post
                $id       = $this->class_security->data_form('id','str');
                $deudas   = [$id];
                $modulo   = $this->class_security->data_form('modulo','int');
                $dato     = $this->class_security->data_form('dato','int');

                //validar si tiene saldo a favor
                $this->load->model('m_pagos','pagos');
                $this->result =  $this->pagos->pagos_estados_cambio($deudas,$dato,$modulo);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('total','monto','m3','mes','anno'))) {

                //campos post
                $total  = $this->class_security->solo_numerico($this->class_security->data_form('total'));
                $monto  = $this->class_security->solo_numerico($this->class_security->data_form('monto'));
                $m3     = $this->class_security->solo_numerico($this->class_security->data_form('m3'));
                $mes    = $this->class_security->data_form('mes');
                $anno   = $this->class_security->data_form('anno');
                $fecha  = "{$anno}-{$mes}-01";


                if(!$this->general->exist('pagos_agua',array('pa_mes' => $mes,'pa_anno' => $anno))){

                    $dd = $this->general->create("pagos_agua",array(
                        'pa_monto'  => $monto,
                        'pa_total'  => $total,
                        'pa_m3'     => $m3,
                        'pa_mes'    => $mes,
                        'pa_anno'   => $anno,
                        'pa_fecha'  => $fecha,
                        'pa_atcreate' => fecha(2),
                    ));

                    $id = $dd['id'];

                    //listar todas las propiedades

                    $datas = $this->general->all_get('lectura_agua' , array('lt_mes' => $mes ,'lt_anno' => $anno ));
                    if(count($datas) >= 1){

                        foreach($datas As $data){

                            $id_apartamento = $data->lt_apartamento;
                            $consumo_anterior = $data->lt_anterior;
                            $consumo_actual = $data->lt_lectura;

                            $op = $this->class_security->calculo_validar_total($m3,$consumo_anterior,$consumo_actual);

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
                                'pg_fecha'        => fecha(1),
                                'pg_atcreate'     => fecha(2)
                            ));

                        }
                    }


                    $this->result =   $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2,'msg' => 'Ya se Creo La orden de pago para este mes');
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function distribucion_saldos($datas = [],$saldof = 0,$tipo = ''){
        $valor = 0;
        if(isset($datas) AND (count($datas) >= 1)){
            $limpiar = array_map(function($d){ return  desencriptar($d);  },$datas);
            $apt_id    = $this->user_data->u_apartamento;
            $data_limpia = implode(', ', $limpiar);

            if($tipo == 4){
                $query_p = $this->general->query("SELECT SUM(d.du_saldo) As valor FROM deuda AS d WHERE d.du_id IN($data_limpia) AND d.du_apartamento='".$apt_id."'");

            }else{
                $query_p = $this->general->query("SELECT SUM(ap.pg_deuda) As valor FROM apartamento_pagos AS ap WHERE ap.pg_id IN($data_limpia) AND ap.pg_apartamento='".$apt_id."'");
            }
            $valor = (count($query_p) >= 1) ? $query_p[0]['valor'] : 0;
        }
        return ($saldof >= $valor) ? 1 : 2;

    }


    function save_total_deuda(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('send','apartamento','tipo_deuda'))){
                $apt             = $this->input->post('apartamento');
                $deudas          = $this->input->post('deuda');
                $tipo_deuda      = $this->class_security->data_form('tipo_deuda','int');
                $favor           = ($this->class_security->data_form('saldo_favor','saldo',0));
                $saldo           = ($this->class_security->data_form('m_saldo_cancelar','saldo',0));

                $comprobante  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png|doc|docx|pdf');

                //validar si tiene saldo a favor
                $this->load->model('m_pagos','pagos');
                $this->result =  $this->pagos->pagos($this->user_data,$apt,$deudas,$saldo,$favor,$tipo_deuda,$comprobante);

            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function pagar_saldo_favor(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))) {

                //campos post
                  $id  = $this->class_security->data_form('id','decrypt_int');
                if($this->general->exist('apartamento_pagos',array('pg_id' => $id,'pg_estado' => 1))){

                    //obtener la deuda
                    $query_deuda = $this->general->query("SELECT a.pg_apartamento,a.pg_deuda-COALESCE((ap.aa_monto),0) AS restante FROM apartamento_pagos AS a 
LEFT JOIN apartamento_pago_agregado AS ap ON a.pg_id=ap.as_deuda AND ap.aa_estado=3 WHERE a.pg_id='".$id."' AND a.pg_estado IN(1,2)",'obj');
                    //data result
                    $deuda = $query_deuda[0];
                     $data_id =$deuda->pg_apartamento;
                     $data_deuda =$deuda->restante;

                    $a_saldo        = $this->class_security->saldo_favor_clc($data_id,$data_deuda,$data_deuda);

                    //valiar que si contiene dinero en saldo a favor
                    if($a_saldo >= 1){

                        $this->general->update('apartamento_pagos',['pg_id' => $id],['pg_estado' => 3]);

                        $agua_p = $this->general->create('apartamento_pagos_abono',[
                            'as_apartamento' => $data_id,
                            'aa_deuda'       => $id,
                            'aa_usuario'     => $this->user_data->u_id,
                            'aa_saldo'       => $a_saldo,
                            'aa_comprobante' => 'default.jpg',
                            'aa_estado'      => '3',
                            'aa_atcreate'   => fecha(2)
                        ]);
                        $id_abono = $agua_p['id'];

                        $this->general->create('apartamento_pago_agregado',[
                            'as_apartamento' => $data_id,
                            'as_deuda'      => $id,
                            'as_agregado'   => $id_abono,
                            'aa_monto'      => $a_saldo,
                            'aa_tipo'       => 3,
                            'aa_estado'     => '3',
                            'aa_fecha'      => fecha(2)
                        ]);

                     }




                    $this->result =   $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2,'msg' => 'No se encontro la deuda');
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Dato Obligatorio');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    $this->result = array('success' => 1);

                    if($this->general->exist('pagos_agua',array('pa_id' => $id))){
                        $this->result =  $this->general->delete('pagos_agua',array('pa_id' => $id));
                    }else{
                        $this->result = array('success' => 2,'msg' => 'Usuario no existe');
                    }
                }else{
                    $this->result = array('success' => 2,'msg' => 'Usuario no existe');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
            }

        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function report(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('anno'))) {

                //campos post
                $anno = $this->class_security->solo_numerico($this->class_security->data_form('anno'));

                $data = array();
                foreach($this->class_data->meses as  $mes_id =>  $mes_val){

                    $fecha = date("Y-m",strtotime("{$anno}-{$mes_id}"));
                    $dmp = $this->general->query("SELECT pa_id,FORMAT(pa_monto,2) AS pa_monto,FORMAT(pa_total,2) AS pa_total,FORMAT(pa_m3,2) AS pa_m3  FROM pagos_agua WHERE pa_anno='".$anno."' AND pa_mes='".$mes_id."' LIMIT 1");
                    $id = '';
                    $d1 = 0;
                    $d2 = 0;
                    $d3 = 0;
                    $d4 = 0;
                    $d5 = 0;
                    $d6 = 0;
                    $disabled = "disabled";
                    if(count($dmp) >= 1){
                        $id = encriptar($dmp[0]['pa_id']);
                        $d1 = $dmp[0]['pa_total'];
                        $d2 = $dmp[0]['pa_monto'];
                        $d3 = $dmp[0]['pa_m3'];
                        $disabled = "";
                    }

                    //load model
                    $deudas = $this->calculos->calculo_pagos_sustraer($fecha);
                    $d4 = $this->class_security->dinero($deudas[1]['saldo']);
                    $d5 = $this->class_security->dinero($deudas[2]['saldo']);
                    $d6 = $this->class_security->dinero($deudas[3]['saldo']);

                    $d7 = $this->class_security->dinero($deudas[1]['abono']);
                    $d8 = $this->class_security->dinero($deudas[2]['abono']);
                    $d9 = $this->class_security->dinero($deudas[3]['abono']);

                    $data[] = array(
                        'nombre' => $mes_val,
                        'd1'    => $d1,
                        'd2'    => $d2,
                        'd3'    => $d3,
                        'd4'    => $d4,
                        'd7'    => $d7,
                        'd5'    => $d5,
                        'd8'    => $d8,
                        'd6'    => $d6,
                        'd9'    => $d9,

                        'btn'    => "<div class='btn-group'>
                                            <button type='button'  onclick='$(this).view_report_general(\"{$mes_id}\",\"{$anno}\",\"{$anno}\")' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Reporte {$mes_val}'><i class='fas fa-tasks text-white'></i></button>
                                            <button type='button' {$disabled} onclick='$(this).dell_data(\"{$id}\",\"dell_pago\",\"si\",function(){
                                                window.location.reload();
                                            })' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar {$mes_val}'><i class='fas fa-times text-white'></i></button>
                                            </div>",

                    );
                }



                $this->result =   $this->result = array('success' => 1,'data' => $data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function estadistica(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('anno','mes'))) {
                //campos post
                $anno = $this->class_security->solo_numerico($this->class_security->data_form('anno'));
                $mes = $this->class_security->solo_numerico($this->class_security->data_form('mes'));
                //  $anno = 2021;
                //       $mes = 2;
                $min_fecha = date("Y-m",strtotime("$anno-$mes"));

                $data = array();

                    $dataR = $this->general->query("SELECT  a.t_id,t_torre,a.t_apartamento,lt_imagen,l.lt_consumo AS pa_consumo,COALESCE((sv.sf_saldo),0) AS saldo,du_deuda,du_pagado FROM apartamento AS a 
                          JOIN apartamento_movimiento AS am ON a.t_id=am.am_apartamento
                          LEFT  JOIN lectura_agua As l ON a.t_id=l.lt_apartamento AND l.lt_mes='$mes' AND l.lt_anno='$anno'
                          LEFT  JOIN pagos_agua_montos AS pm ON a.t_id=pm.pm_apartamento AND pm.pm_mes='$mes' AND pm.pm_anno='$anno'
                          LEFT JOIN saldo_favor AS sv ON a.t_id=sv.sf_apartamento
                            LEFT JOIN (
                                 select dx.du_apartamento,SUM(dx.du_saldo)-COALESCE(SUM(pd_monto),0) As du_deuda,COALESCE(SUM(pd_monto),0) As du_pagado
                             from deuda As dx
                             LEFT JOIN (
                                 select dp.pd_deuda,SUM(dp.pd_monto) As pd_monto from deuda_pagos As dp WHERE dp.pd_estado=3 GROUP BY  dp.pd_deuda
                                 ) As dp ON dx.du_id=dp.pd_deuda
                            WHERE  dx.du_estado IN(1,2)
                            GROUP BY dx.du_apartamento
                            ) As d ON a.t_id=d.du_apartamento
                          WHERE  DATE_FORMAT(am.am_atcreate, '%Y-%m') = '".$min_fecha."'
                          GROUP BY a.t_id,t_torre,a.t_apartamento,lt_imagen,l.lt_consumo,l.lt_consumo,saldo
                      ");


                foreach($dataR As $dd){

                     $id_aptartamento = $dd['t_id'];
                     $id_apt = $dd['t_apartamento'];

                    $estado_select = '';
                    foreach ($this->class_data->estado_pago As $est_id => $est_vl){
                        if($est_id == $estado_select){
                            $estado_select .= "<option value='{$est_id}' selected>{$est_vl['title']}</option>";
                        }else{
                            $estado_select .= "<option value='{$est_id}'>{$est_vl['title']}</option>";
                        }
                    }

                   //todos los comprobantes por mes
                   $compb = $this->validar_deudas($id_aptartamento,$mes,$anno);

                        $deu1 = $compb['deuda1'];
                        $deu2 = $compb['deuda2'];
                        $deu3 = $compb['deuda3'];


                        $imgrs = $dd['lt_imagen'];
                        $imagen_url   = base_url("_files/{$imgrs}");

                        $imagen_agua = (strlen($imgrs) >= 10) ?  "<a class='single_image btn btn-success' href='{$imagen_url}'>$dd[pa_consumo]</a><script>$('.single_image').fancybox({'zoomSpeedIn': 300,'zoomSpeedOut': 300,'overlayShow': false});</script>" :
                           (($dd['pa_consumo'] != null) ? "<button class='btn btn-dark'>$dd[pa_consumo]</button>" : '-') ;

                        $favor1 = (isset($deu1['pasal']['favorn'])) ? ($deu1['pasal']['favorn']) : 0;
                        $favor2 = (isset($deu2['pasal']['favorn'])) ? ($deu2['pasal']['favorn']) : 0;
                        $favor3 = (isset($deu3['pasal']['favorn'])) ? ($deu3['pasal']['favorn']) : 0;


                       $data[] = array(
                        'filial' => $dd['t_torre'],
                        'id_apartamento'   => $id_aptartamento,
                        'apartamento'    => $id_apt,
                        'consumo'        => ($dd['pa_consumo']),
                        "agua_comprobante" => $imagen_agua,


                        'total1'         => '₡ '.$this->class_security->dinero($deu1['valor']),
                        'pago1'          => '₡ '.$this->class_security->dinero($deu1['pasal']['pago']),
                        'saldo1'         => '₡ '.$this->class_security->dinero($deu1['pasal']['saldo']),
                        'favor1'         => $deu1['pasal']['favor'],
                        'padof1'         => $deu1['padofavor'],
                        'estado1'        => $deu1['estado'],

                        'total2'         => '₡ '.$this->class_security->dinero($deu2['valor']),
                        'pago2'          => '₡ '.$this->class_security->dinero($deu2['pasal']['pago']),
                        'saldo2'         => '₡ '.$this->class_security->dinero($deu2['pasal']['saldo']),
                        'favor2'         => $deu2['pasal']['favor'],
                        'padof2'         => $deu2['padofavor'],
                        'estado2'        => $deu2['estado'],


                        'du_deuda'        => '₡ '.$this->class_security->dinero($dd['du_deuda']),
                        'du_pagado'       => '₡ '.$this->class_security->dinero($dd['du_pagado']),

                        'total3'         => '₡ '.$this->class_security->dinero($deu3['valor']),
                        'pago3'          => '₡ '.$this->class_security->dinero($deu3['pasal']['pago']),
                        'saldo3'         => '₡ '.$this->class_security->dinero($deu3['pasal']['saldo']),
                        'favor3'         => $deu3['pasal']['favor'],
                        'padof3'         => $deu3['padofavor'],
                        'estado3'        => $deu3['estado'],

                        'comprobante' =>  "<button type='button'  onclick='$(this).forms_modal2({\"page\" : \"admin_pagos_comprobantes\",\"data1\" : \"{$id_aptartamento}\",\"data2\" : \"{$min_fecha}\",\"title\" : \"Pagos Comprobantes\"})' class='btn btn-success' ><i class='text-white fas fa-barcode'></i></button>",



                        'd_deuda'           => '₡ '.$this->class_security->dinero($deu1['valor']+$deu2['valor']+$deu3['valor']),
                        'd_acomulado'       => '₡ '.$this->class_security->dinero($deu1['pasal']['pago']+$deu2['pasal']['pago']+$deu3['pasal']['pago']),
                        'd_resultado'       => '₡ '.$this->class_security->dinero($deu1['pasal']['saldo']+$deu2['pasal']['saldo']+$deu3['pasal']['saldo']),
                        'd_favor'           => '₡ '.$this->class_security->dinero($dd['saldo']),

                    );
                }

                $this->result =   $this->result = array('success' => 1,'data' => $data);


            }else{
                $this->result = array('success' => 2,'msg' => 'Datos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function select_pago_valor($cp_id = '',$estaddo = ''){
        $estado_select = '';
        foreach ($this->class_data->estado_pago As $est_id => $est_vl){


            if($est_id == $estaddo){
                $estado_select .= "<option value='{$est_id}' selected>{$est_vl['title']}</option>";
            }else{
                $estado_select .= "<option value='{$est_id}'>{$est_vl['title']}</option>";
            }
        }

        return ($estaddo == 4) ? '<b>Deuda</b>' :  "<select class='form-control' style='width: 116px;' onchange='$(this).actualizar_estado_deuda(\"{$cp_id}\")'>{$estado_select}</select>";
    }

    private function validar_deudas($apt,$mes,$anno){
        $deuda = 0;
        $btn = "<button type='button' disabled class='btn btn-dark'>Sin Registro</button>";

        //obtener todos los registros
        $datas = $this->general->query("
        SELECT ap.*,COALESCE((sv.sf_saldo),0) favor from apartamento_pagos AS ap 
LEFT JOIN saldo_favor AS sv ON ap.pg_apartamento=sv.sf_apartamento
WHERE ap.pg_apartamento='".$apt."' AND ap.pg_mes='".$mes."' AND ap.pg_anno='".$anno."'",'obj');

        $default_data = [
            'pago' => 0,
            'saldo' => 0,
            'favor' => '-',
            'favorn' => 0,
        ];

        $d1_pasal       = $default_data;
        $d2_pasal       = $default_data;
        $d3_pasal       = $default_data;

        $d1_monto       = 0;
        $d2_monto       = 0;
        $d3_monto       = 0;

        $d1_favor       = 0;
        $d2_favor       = 0;
        $d3_favor       = 0;

        $d1_estado = '-';
        $d2_estado = '-';
        $d3_estado = '-';

        $d1_btn =  "<button type='button' class='btn btn-dark' disabled>-</button>";
        $d2_btn =  "<button type='button' class='btn btn-dark' disabled>-</button>";
        $d3_btn =  "<button type='button' class='btn btn-dark' disabled>-</button>";

        $pf1_btn =  "<button type='button' class='btn btn-dark' disabled>-</button>";
        $pf2_btn =  "<button type='button' class='btn btn-dark' disabled>-</button>";
        $pf3_btn =  "<button type='button' class='btn btn-dark' disabled>-</button>";

        //valiadr si existe saldo a favor para habilitar o dejarlo morir


        if(count($datas) >= 1){
            foreach($datas As $dd){

                $id = encriptar($dd->pg_id);
                $favor = $dd->favor;

                if($dd->pg_tipo_deuda == 1){
                     $d1_monto = $dd->pg_deuda;

                    $d1_estado = $this->class_security->array_data($dd->pg_estado,$this->class_data->estado_pago,$this->class_data->estado_default)['title'];
                    $d1_pasal = $this->class_security->abonos_saldado($dd->pg_id,$d1_monto,1);

                    if($favor >= 1){
                        $pf1_btn =  "<button type='button'  onclick='$(this).pagar_deuda_saldo_favor(\"{$id}\")' class='btn btn-success' ><i class='text-white  fas fa-dollar-sign'></i></button>";
                    }
                    $d1_btn =  "<button type='button'  onclick='$(this).forms_modal2({\"page\" : \"admin_pagos_comprobantes\",\"data1\" : \"{$id}\",\"title\" : \"Pagos Comprobantes\"})' class='btn btn-success' ><i class='text-white fas fa-barcode'></i></button>";
                }

                if($dd->pg_tipo_deuda == 2){
                    $d2_monto = $dd->pg_deuda;

                    $d2_estado = $this->class_security->array_data($dd->pg_estado,$this->class_data->estado_pago,$this->class_data->estado_default)['title'];
                    $d2_pasal = $this->class_security->abonos_saldado($dd->pg_id,$d2_monto,2);

                    if($favor >= 1){
                     $pf2_btn =  "<button type='button'  onclick='$(this).pagar_deuda_saldo_favor(\"{$id}\")' class='btn btn-success' ><i class='text-white  fas fa-dollar-sign'></i></button>";
                    }
                     $d2_btn =  "<button type='button'  onclick='$(this).forms_modal2({\"page\" : \"admin_pagos_comprobantes\",\"data1\" : \"{$id}\",\"title\" : \"Pagos Comprobantes\"})' class='btn btn-success' ><i class='text-white fas fa-barcode'></i></button>";
                }

                if($dd->pg_tipo_deuda == 3){
                    $d3_monto  = $dd->pg_deuda;

                    $d3_estado = $this->class_security->array_data($dd->pg_estado,$this->class_data->estado_pago,$this->class_data->estado_default)['title'];
                    $d3_pasal  = $this->class_security->abonos_saldado($dd->pg_id,$d3_monto,3);
                    if($favor  >= 1){
                        $pf3_btn =  "<button type='button'  onclick='$(this).pagar_deuda_saldo_favor(\"{$id}\")' class='btn btn-success' ><i class='text-white  fas fa-dollar-sign'></i></button>";
                    }
                        $d3_btn =  "<button type='button'  onclick='$(this).forms_modal2({\"page\" : \"admin_pagos_comprobantes\",\"data1\" : \"{$id}\",\"title\" : \"Pagos Comprobantes\"})' class='btn btn-success' ><i class='text-white fas fa-barcode'></i></button>";
                }

            }
        }

        return array(
            'deuda1' => array('valor' => $d1_monto,'pasal' => $d1_pasal,'padofavor' => $pf1_btn,'comprobante' => $d1_btn,'estado' => $d1_estado),
            'deuda2' => array('valor' => $d2_monto,'pasal' => $d2_pasal,'padofavor' => $pf2_btn,'comprobante' => $d2_btn,'estado' => $d2_estado),
            'deuda3' => array('valor' => $d3_monto,'pasal' => $d3_pasal,'padofavor' => $pf3_btn,'comprobante' => $d3_btn,'estado' => $d3_estado),
        );
    }

    function test(){

        $this->load->model('m_pagos','pagos');

        $deudas = [
//         262,
//        415,
//        475,
            encriptar(541)
        ];
        $this->pagos->pagos($this->user_data,341,$deudas,32413,0,2,'default.jpg');
    }


}