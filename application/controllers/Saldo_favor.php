<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Saldo_favor extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Saldo a Favor';
    private $controller   = 'saldo_favor/';
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

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/modals.js','cr/generic.js','plugins/numero/AutoNumeric.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_sald_favor',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    function save(){
//
        if($this->input->post()){
            if($this->class_security->validate_post(array('apartamento','saldo'))){
//
//                //campos post
                $id             = $this->class_security->data_form('apartamento','int');
                $saldo          = $this->class_security->data_form('saldo','saldo');

              $this->result = $this->general->create_update('saldo_favor',
                  ['sf_apartamento' => $id],
                  [
                      'sf_apartamento' => $id,
                      'sf_saldo' => $saldo
                  ]);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
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
WHERE ap.t_apartamento LIKE '%$valor%' HAVING sf.sf_saldo > 0";



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
                "<div class='btn-group'><button type='button'onclick='$(this).forms_modal({\"page\" : \"admin_saldo_favor\",\"data1\" : \"{$id}\",\"title\" : \"Saldo a favor\"})' class='btn btn-dark waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>"
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