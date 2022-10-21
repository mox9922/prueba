<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Propietarios extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Propietario';
    private $controller   = 'propietarios/';
    private $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();


//        //validar y hacer el llamado de todo
        $this->user_data = $this->general->get('usuarios',array('u_id' => $this->session_id));
//            //load
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
                'tipo_usuario'  => 1,
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_database'  => base_url("{$this->controller}database"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'url_datatable' => base_url("{$this->controller}datatable"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/propietarios.js'));


        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('propietarios/v_propietarios',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }


    //method crud
    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','torre','apartamento','telefono','estado'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $torre          = $this->class_security->data_form('torre');
                $apartamento    = $this->class_security->data_form('apartamento');
                $telefono       = $this->class_security->data_form('telefono');
                $estado         = $this->class_security->data_form('estado','int');


                //procesar
                $data = array(
                    'p_nombre'        => $nombre,
                    'p_torre'         => $torre,
                    'p_apartamento'   => $apartamento,
                    'p_telefono'      => $telefono,
                    'p_estado'        => $estado,
                );

                    $this->result =   $this->general->create_update('propietarios',array('p_id' => $id),$data);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function get(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios',array('p_id' => $id))){
                        $resultado =   $this->general->get('propietarios',array('p_id' => $id));

                        $resultado_data = array(
                            'id'            => encriptar($resultado->p_id),
                            'nombre'        => $this->class_security->decodificar($resultado->p_nombre),
                            'torre'         => $this->class_security->decodificar($resultado->p_torre),
                            'apartamento'   => $this->class_security->decodificar($resultado->p_apartamento),
                            'telefono'      => $this->class_security->decodificar($resultado->p_telefono),
                            'estado'        => $this->class_security->decodificar($resultado->p_estado),
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
                    if($this->general->exist('propietarios',array('p_id' => $id))){
                        $this->result =  $this->general->delete('propietarios',array('p_id' => $id));
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
            0 => 'p_nombre',
            1 => 'p_torre',
            2 => 'p_apartamento',
            3 => 'p_telefono',
        );

        //tabla
        $tabla =  'propietarios';

        //join
        $join   = array();

        $where_in        = array();
        $dataget         = $this->datatable->getdata($filter,$columnas,[],$where_in,$tabla,$join);
        $total_registros = $this->datatable->totalData($valor,$columnas,[],$where_in,$tabla,$join);

        foreach($dataget as $rows){
            $id             = encriptar($rows->p_id);
            $nombre         = $this->class_security->decodificar($rows->p_nombre);
            $torre          = $this->class_security->decodificar($rows->p_torre);
            $apartamento    = $this->class_security->decodificar($rows->p_apartamento);
            $telefono       = $this->class_security->decodificar($rows->p_telefono);
            $btn            = $this->class_data->estado[$rows->p_estado];

            $data[]= array(
                $nombre,
                $torre,
                $apartamento,
                $telefono,
                "<button type='button' onclick='$(this).calendario_trabajo(\"{$id}\");' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-car text-white'></i></button>",
                "<button type='button' onclick='$(this).timer_cliente(\"{$id}\");' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-user text-white'></i></button>",
                "<button type='button' class='{$btn['class']}'>{$btn['title']}</button>",
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