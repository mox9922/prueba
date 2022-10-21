<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Reservas extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Reservas';
    private $controller   = 'reservas/';
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
        $this->load->library('Mailer');


    }

    function index(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data

        );

        $data_menu = array();

        $data_body = array(
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_reserva_estado'      => base_url("{$this->controller}reserva_estado"),
                'url_datatable'  => base_url("{$this->controller}datatable"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/reservas.js','plugins/numero/AutoNumeric.js'));


        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_reservas',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    //method crud
    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','estado'))){

                //campos post
                $id            = $this->class_security->data_form('data_id','decrypt_int');
                $estado        = $this->class_security->data_form('estado','int');
                $observacion   = $this->class_security->data_form('observacion');

                //procesarticket
                $data = array('r_estado' => $estado,'r_observacion' => $observacion);
                $this->result =   $this->general->create_update('reserva',array('r_id' => $id),$data);
                $res =   $this->general->get('reserva',array('r_id' => $id));

                $reserva_estado = $this->class_data->restado_reserva[$estado]['title'];

                if($estado != 1 and $res->r_correo != ''){
                    $this->generico->create_mail($id,'reserva','estado');

                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    function reserva_estado(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','estado'))){

                //campos post
                $id            = $this->class_security->data_form('id','decrypt_int');
                $estado        = $this->class_security->data_form('estado','int');

                //procesarticket
                if($this->general->exist('reserva_dias',array('rd_id' => $id))){
                    $this->result =   $this->general->update('reserva_dias',array('rd_id' => $id),['rd_estado' => $estado]);
                }else{
                    $this->result = array('success' => 2,'msg' => 'no se encontro el dia reservado');
                }
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
            0 => 'u.u_nombre',
            1 => 'r.r_nombre',
            2 => 'p.p_nombre',
            3 => 'r.r_nombre',
            4 => 'r.r_telefono',
            5 => 'r.r_correo',
        );

        //tabla
        $tabla =  'reserva As r';

        //join
        $join   = array(
            array('tabla' => 'usuarios u','join' => 'r.r_usuario=u.u_id'),
            array('tabla' => 'propiedades p','join' => 'r.r_propiedad=p.p_id'),
        );

        $where_in        = array();
        $dataget         = $this->datatable->getdata($filter,$columnas,[],$where_in,$tabla,$join);
        $total_registros = $this->datatable->totalData($valor,$columnas,[],$where_in,$tabla,$join);

        foreach($dataget as $rows){
            $id              = encriptar($rows->r_id);
            $nombre1         = $this->class_security->decodificar($rows->u_nombre);
            $nombre          = $this->class_security->decodificar($rows->r_nombre);
            $telefono        = $this->class_security->decodificar($rows->r_telefono);
            $correo          = $this->class_security->decodificar($rows->r_correo);
            $asistencia      = $this->class_security->decodificar($rows->r_asistencia);
            $f_entrada       = $this->class_security->decodificar($rows->r_fecha_inicio);
            $f_salida        = $this->class_security->decodificar($rows->r_fecha_salida);
            $btn             = $this->class_security->array_data($rows->r_estado,$this->class_data->restado_reserva,$this->class_data->estado_default);

            $imgrs = $rows->r_comprobante;
            $imagen_url   = base_url("_files/{$imgrs}");

            $comrpobante = (strlen($imgrs) >= 10) ?  "<a class='btn btn-success' href='{$imagen_url}'><i class='text-white fa fa-download'></i></a>" : ' - ';

            $data[]= array(
                $comrpobante,
                $nombre1,
                $nombre,
                $telefono,
                $correo,
                $asistencia,
                $f_entrada .'-'. $f_salida,
                "<button type='button' class='{$btn['class']}'>{$btn['title']}</button>",
                "<div class='btn-group'><button type='button'  onclick='$(this).forms_modal({\"page\" : \"admin_reserva\",\"data1\" : \"{$id}\",\"title\" : \"Reservas\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Validar'><i class='fa fa-pencil-alt text-white'></i></button>
                 "
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