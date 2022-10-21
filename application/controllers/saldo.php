<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Saldo extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Saldo a Favor';
    private $controller   = 'saldo/';
    private $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();

        //validar y hacer el llamado de todo
        $this->user_data = $this->general->get('usuarios',array('u_id' => $this->session_id));
        //load
        $this->load->model('m_datatable','datatable');
        $this->load->model('generico');
        $this->load->helper(array('website','encriptar'));

    }

    function index($id = ''){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data

        );

        $data_menu = array();

        $data_body = array(

            'perfil'     => $id,
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_database'  => base_url("{$this->controller}database"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'url_ajax_apartamento'    => base_url("{$this->controller}search_apartamentos"),
                'url_datatable' => base_url("{$this->controller}datatable"),
                'url_cambiar_estado' => base_url("{$this->controller}cambio_estado"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/modals.js','cr/deuda.js','plugins/numero/AutoNumeric.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_sald_favor',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }


    function cambio_estado(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','estado'))){

                //campos post
                $id             = $this->class_security->data_form('id','decrypt_int');
                $estado         = $this->class_security->data_form('estado');

                //procesar
                //agregar cuota extraordinarioa
                if($this->general->exist('deuda_pagos',array('dp_id' => $id))){
                    $resultado =   $this->general->update('deuda_pagos',array('dp_id' => $id),array('pd_estado' => $estado));
                    $this->result = $resultado;
                }else{
                    $this->result = array('success' => 2,'msg' => 'No se encontro la deuda');
                }


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
            if($this->class_security->validate_post(array('apartamento','nombre','saldo','n_pagos','fecha_cobro','estado'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $apartamento    = $this->class_security->data_form('apartamento','int');
                $nombre         = $this->class_security->data_form('nombre');
                $saldo          = $this->class_security->data_form('saldo','saldo');
                $n_pagos        = $this->class_security->data_form('n_pagos','saldo');
                $fecha_cobro    = $this->class_security->letras_numeros_espace($this->class_security->data_form('fecha_cobro'));
                $observacion    = $this->class_security->data_form('observacion');
                $estado         = $this->class_security->data_form('estado');

                //procesar
                //agregar cuota extraordinarioa
                if($saldo >= 1 and $n_pagos >= 1 AND $this->general->exist('apartamento',array('t_id' => $apartamento))){
                    //  $obt = $this->obtener_fechas_asignacion($n_pagos,$fecha_cobro);

                    //   $calcular_extra = $saldo/$n_pagos;

                    if(strlen($id) == 0){
                        $data = array(
                            'du_apartamento'     =>  $apartamento,
                            'du_nombre'          => $nombre,
                            'du_saldo'           => $saldo,
                            'du_n_pagos'         => $n_pagos,
                            'du_fecha_inicio'    => $fecha_cobro,
                            'du_observacion'     => $observacion,
                            'du_estado'          => $estado,
                            'du_atcreate'        => fecha(2)
                        );
                    }else{
                        $data = array(
                            'du_nombre'          => $nombre,
//                            'du_saldo'           => $saldo,
                            'du_n_pagos'         => $n_pagos,
                            'du_fecha_inicio'    => $fecha_cobro,
                            'du_observacion'     => $observacion,
                            'du_estado'          => $estado,
                        );
                    }

                    $this->general->query("CALL proc_movimiento('$apartamento','".fecha(2)."');",'',false);
                    $resultado =   $this->general->create_update('deuda',array('du_id' => $id),$data);


                    //procesar un pago
                    $add_numero      = $this->class_security->data_form('add_numero');
                    $add_saldo       = $this->class_security->data_form('add_saldo','saldo');
                    $add_fecha       = $this->class_security->data_form('add_fecha');
                    $add_observacion       = $this->class_security->data_form('add_observacion');
                    $hora = hora();


                    if(strlen($add_saldo) >= 2 ){

                        if(strlen($id) >= 1  AND  $add_numero != '' AND $add_saldo != '' AND $add_fecha != '') {

                            $comprobante = $this->class_security->upload_document('comprobante','_files/','gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|ppt|pptx');

                            $this->general->create("deuda_pagos",array(
                                'pd_usuario'            => $this->user_data->u_id,
                                'pd_deuda'              => $id,
                                'pd_apartamento'        => $apartamento,
                                'pd_n_comprobante'      => $add_numero,
                                'pd_comprobante'        => $comprobante,
                                'pd_estado'             => 3,
                                'pd_observacion'        => $add_observacion ,
                                'pd_monto'              => $add_saldo,
                                'pd_atcreate'           => "{$add_fecha} {$hora}"
                            ));

                            //saldo a favor
                            $saldo_favor_clc = $this->general->query("select d.du_apartamento,d.du_id,d.du_saldo,COALESCE(monto,0) As monto,
       CASE
           WHEN d.du_saldo > COALESCE(monto,0) THEN d.du_saldo-COALESCE(monto,0)
           ELSE COALESCE(monto,0)-d.du_saldo
        END
         As resta from deuda As d
                        LEFT JOIN (
                            SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
                            ) As dp ON d.du_id=dp.pd_deuda
                        WHERE d.du_id=".$id."",'obj');

                            if(isset($saldo_favor_clc) AND count($saldo_favor_clc) >= 1){
                                $this->load->model('Consultas_model','consultas');
                                $f_deuda = $saldo_favor_clc[0]->du_saldo;
                                $f_pagado = $saldo_favor_clc[0]->monto;
                                $f_favor = $saldo_favor_clc[0]->resta;

                                if($f_pagado > $f_deuda){
                                    $this->consultas->agregar_saldo_a_favor($apartamento,$f_favor);
                                }
                            }

                        }else{
                            $this->result = array('success' => 2,'msg' => 'Se a realizado un abono debes llenar todos los campos');
                            api($this->result);
                            exit;
                        }

                    }

//                    $id2 = $resultado['data'];
//                    if($id2 != ''){
//
//                        foreach($obt As $ob){
//                            $mes = $ob['mes'];
//                            $anno = $ob['anno'];

//                        }
//                    }
                    $this->result = $resultado;
                }else{
                    $this->result = array('success' => 2,'msg' => 'El inmueble no existe');

                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function obtener_fechas_asignacion($cuotas,$fecha){
        $fecha_actual = date($fecha);

        $mm = array();
        for($i = 0;$i < $cuotas;$i++){
            $mm[] = array(

                'mes' => date("n",strtotime($fecha_actual."+ $i month")),
                'anno' => date("Y",strtotime($fecha_actual."+ $i month")),
            )  ;
        }
        return $mm;
    }


    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('deuda',array('du_id' => $id))){
                        $this->result =  $this->general->delete('deuda',array('du_id' => $id));
                        $this->result =  $this->general->delete('apartamento_pagos',array('pg_id_cuota_extraordinaria' => $id,'pg_tipo_deuda' => 5));
                    }else{
                        $this->result = array('success' => 2,'msg' => 'Dato no existe');
                    }
                }else{
                    $this->result = array('success' => 2,'msg' => 'Dato no existe');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
            }

        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    //datatable
    function datatable(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
        }
        $data = array();


        $consulta_primary = "select ap.t_id,ap.t_apartamento,sf.sf_saldo from apartamento As ap
INNER JOIN saldo_favor sf on ap.t_id = sf.sf_apartamento
WHERE ap.t_apartamento LIKE '%$valor%'";



        $dataget    = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);

        foreach($dataget as $rows){
            $id              = encriptar($rows->t_id);
            $apartamento     = $this->class_security->decodificar($rows->t_apartamento);
            $saldo     = ($rows->sf_saldo);

            $data[]= array(
                $apartamento,
                "<b>₡ ". $this->class_security->dinero($saldo)."</b>",
                "<div class='btn-group'><button type='button'onclick='$(this).forms_modal({\"page\" : \"admin_deuda_lista\",\"data1\" : \"{$id}\",\"title\" : \"Dueda\"})' class='btn btn-dark waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>"
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $total_registros,
            "recordsFiltered" => $total_registros,
            "data" => $data
        );

        apirest($output);
        exit();
    }

}