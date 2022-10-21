<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Apartamentos extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Filial';
    private $controller   = 'apartamentos/';
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
            'user'          => $this->user_data,


        );

        $data_menu = array();

        $data_body = array(
            'dataresult' => $this->general->all_get('usuarios',array('u_perfil' => $id)),
            'perfil'     => $id,
            'crud' => array(
                'tipo_usuario'  => 1,
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_database'  => base_url("{$this->controller}database"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'url_dell_saldo_extrordinario'    => base_url("{$this->controller}dell_saldo_extrordinario"),
                'url_datatable' => base_url("{$this->controller}datatable")
            )
        );

        $data_foot = array('script_level' => array(
            'cr/crud_data.js',
            'cr/apartamentos.js',
            'plugins/numero/AutoNumeric.js'
        ));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('v_apartamentos',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    //method crud
    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('torre','apartamento','tipo','cuota','fecha_cobro','tipo_estado','estado'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $torre          = $this->class_security->data_form('torre');
                $apartamento    = $this->class_security->data_form('apartamento');

                $cuota          = $this->class_security->solo_numerico($this->class_security->data_form('cuota'));
                $extra          = $this->class_security->solo_numerico($this->class_security->data_form('cuota_ex'));
                $pagos          = $this->class_security->solo_numerico($this->class_security->data_form('n_pagos',1));
                $fecha_cobro          = $this->class_security->letras_numeros_espace($this->class_security->data_form('fecha_cobro'));
                $total          = $this->class_security->solo_numerico($this->class_security->data_form('p_total'));



                $observacion           = $this->class_security->data_form('observacion');
                $tipo           = $this->class_security->data_form('tipo');
                $tipo_estado    = $this->class_security->data_form('tipo_estado');
                $estado         = $this->class_security->data_form('estado');



                //procesar
                $data = array(
                    't_torre'          => $torre,
                    't_apartamento'    => $apartamento,
                    't_cuota'          => $cuota,
                    't_tipo'           => $tipo,
                    't_estado_afilial' => $tipo_estado,
                    't_estado'         => $estado,
                );
                $resultado =   $this->general->create_update('apartamento',array('t_id' => $id),$data);

                //agregar cuota extraordinarioa
                if($extra >= 1 and $pagos >= 1){
                    $obt = $this->obtener_fechas_asignacion($pagos,$fecha_cobro);


                    $calcular_extra = $extra/$pagos;

                    $id2 = $resultado['data'];
                    if($id2 != ''){

                        $id_extr = '';
                        if($extra != '' AND $pagos != '' AND $fecha_cobro != ''){
                            $data_cuota = array(
                                'ce_apartamento'    => $id2,
                                'ce_valor'          => $extra,
                                'ce_cuota'          => $pagos,
                                'ce_observacion'    => $observacion,
                                'ce_fecha'          => $fecha_cobro,
                                'ce_atcreate'       => fecha(2),
                            );
                            $res_e = $this->general->create('cuota_extraordinaria',$data_cuota);
                            $id_extr = $res_e['id'];
                        }

                        foreach($obt As $ob){
                            $mes = $ob['mes'];
                            $anno = $ob['anno'];
                            $this->general->create("apartamento_pagos",array(
                                'pg_apartamento'  => $id2,
                                'pg_id_cuota_extraordinaria'  => $id_extr,
                                'pg_tipo_deuda'   => 3,
                                'pg_mes'          => $mes,
                                'pg_anno'         => $anno,
                                'pg_deuda'        => $calcular_extra,
                                'pg_estado'       => 1,
                                'pg_fecha'        => "{$anno}-{$mes}",
                                'pg_atcreate'     => fecha(2)
                            ));
                        }
                    }

                }

                $this->result = array('success' => 1);

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

    function get(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('apartamento',array('t_id' => $id))){
                        $resultado =   $this->general->get('apartamento',array('t_id' => $id));
                        $resultado_data = array(
                            'id'            => encriptar($resultado->t_id),
                            'torre'         => $this->class_security->decodificar($resultado->t_torre),
                            'apartamento'   => $this->class_security->decodificar($resultado->t_apartamento),
                            'cuota'   => $this->class_security->decodificar($resultado->t_cuota),
                            'tipo'   => $this->class_security->decodificar($resultado->t_tipo),
                            'estado_afilial'   => $this->class_security->decodificar($resultado->t_estado_afilial),
                            'estado'   => $this->class_security->decodificar($resultado->t_estado),
                            'cuotas'      => $this->general->all_get('cuota_extraordinaria',array('ce_apartamento' => $resultado->t_id))
                        );

                        $this->result = array('success' => '1','data' => $resultado_data);
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

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('apartamento',array('t_id' => $id))){
                        $this->result =  $this->general->delete('apartamento',array('t_id' => $id));
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

    function dell_saldo_extrordinario(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','int');
                if(strlen($id) >= 1){
                    if($this->general->exist('cuota_extraordinaria',array('ce_id' => $id))){
                        $this->result =  $this->general->delete('cuota_extraordinaria',array('ce_id' => $id));
                        $this->result =  $this->general->delete('apartamento_pagos',array('pg_id_cuota_extraordinaria' => $id));

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
            $order      = $this->input->post("order");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
            $order = array();
        }
        $data = array();


        $filter = array('start'  => $start,'length' => $length, 'order' => $order,'busqueda' => $valor);

        //columnas
        $columnas = array(
            1 => 't_torre',
            2 => 't_apartamento',
        );

        //tabla
        $tabla =  'apartamento';

        //join
        $join   = array();

        $where_in        = array();
        $dataget         = $this->datatable->getdata($filter,$columnas,[],$where_in,$tabla,$join);
        $total_registros = $this->datatable->totalData($valor,$columnas,[],$where_in,$tabla,$join);

        foreach($dataget as $rows){
            $id              = encriptar($rows->t_id);
            $apartamento     = $this->class_security->decodificar($rows->t_apartamento);
            $torre           = $this->class_security->decodificar($rows->t_torre);
            $tipo            = (strlen($rows->t_tipo) >= 1) ? $this->class_data->tipo_filial[$rows->t_tipo]  : '';
            $estado2            = (strlen($rows->t_estado_afilial) >= 1) ? $this->class_data->estado_filial[$rows->t_estado_afilial]  : '';
            $estado          = $this->class_data->estado[$rows->t_estado];

            $data[]= array(
                $torre,
                $apartamento,
                $tipo,
                $estado2,
                '₡ '. number_format($rows->t_cuota),
//                '',
//                '',
//                '',
//                '',
                "<button class='{$estado['class']}'>{$estado['title']}</button>",
                "<div class='btn-group'><button type='button' onclick='$(this).edit_data(\"{$id}\");' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></div>"
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