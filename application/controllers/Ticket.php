<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Ticket extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Ticket';
    private $controller   = 'ticket/';
    private $controller_filial   = 'afiliales/';
    private $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth('filial');


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
            'nuevos'    => $this->general->count('ticket',array('t_apartamento' => $this->user_data->u_apartamento,'t_estado' => 1)),
            'atendidos' => $this->general->count('ticket',array('t_apartamento' => $this->user_data->u_apartamento,'t_estado' => 2)),
            'total'     => $this->general->count('ticket',array('t_apartamento' => $this->user_data->u_apartamento)),

            'vigilantes' => $this->general->all_get('usuarios',array('u_perfil' => 3,'u_estado' => 1)),
            'afiliales'  => $this->general->all_get('usuarios',array('u_apartamento' => $this->user_data->u_apartamento,'u_id != ' => $this->user_data->u_id)),


            'crud' => array(
                'url_modals'  => base_url("modal/"),
                'tipo_usuario'  => 1,
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_datatable'  => base_url("{$this->controller}datatable"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'url_respuesta'      => base_url("{$this->controller}respuesta"),

                'url_save_comprobante'     => base_url("{$this->controller_filial}save_comprobante"),
                'url_save_deuda_total'     => base_url("{$this->controller_filial}save_deuda_total"),

                'url_save_deuda'           => base_url("{$this->controller_filial}save_deuda"),

                'url_aflial_save'          => base_url("{$this->controller_filial}condomino_save"),
                'url_aflial_delete'        => base_url("{$this->controller_filial}condomino_delete"),

                //vehiculo
                'url_save_vehiculo'        => base_url("{$this->controller_filial}save_vehiculo"),
                'url_dell_vehiculo'        => base_url("{$this->controller_filial}dell_vehiculo"),

                //mascotas
                'url_save_mascotas'        => base_url("{$this->controller_filial}save_mascotas"),
                'url_get_mascotas_all'     => base_url("{$this->controller_filial}get_all_mascotas"),
                'url_get_mascotas'         => base_url("{$this->controller_filial}get_mascotas"),
                'url_dell_mascotas'        => base_url("{$this->controller_filial}dell_mascotas"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/ticket.js','plugins/numero/AutoNumeric.js','cr/afiliales.js'));

        $this->load->view('template/alt/v_header',$data_head);
        $this->load->view('ticket/v_filial',$data_body);
        $this->load->view('template/alt/v_footer',$data_foot);
    }

    function ticket_admin(){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data

        );

        $data_menu = array();

        $data_body = array(
            'nuevos'    => $this->general->count('ticket',array('t_estado' => 1)),
            'tomado'    => $this->general->count('ticket',array('t_estado' => 2)),
            'atendidos' => $this->general->count('ticket',array('t_estado' => 3)),
            'total'     => $this->general->count('ticket',array()),
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_get'       => base_url("{$this->controller}get"),
                'url_respuesta'      => base_url("{$this->controller}respuesta"),
                'url_datatable'  => base_url("{$this->controller}datatable"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/ticket_admin.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_ticket',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('titulo','prioridad','descripcion'))){

                //campos post
                $titulo         = $this->class_security->data_form('titulo');
                $prioridad         = $this->class_security->data_form('prioridad');
                $observacion    = $this->class_security->data_form('descripcion');
                $apartamento    = $this->user_data->u_apartamento;

                //procesar
                //agregar cuota extraordinarioa
                if($titulo != '' and $prioridad != '' AND $observacion != '' AND $this->general->exist('apartamento',array('t_id' => $apartamento))){

                    $documento_1 = $this->class_security->upload_document('documento_1','_files/','jpeg|jpg|png|pdf|doc|docx|xls|xlxs');
                    $documento_2 = $this->class_security->upload_document('documento_2','_files/','jpeg|jpg|png|pdf|doc|docx|xls|xlxs');
                    $documento_3 = $this->class_security->upload_document('documento_3','_files/','jpeg|jpg|png|pdf|doc|docx|xls|xlxs');
                    $documento_4 = $this->class_security->upload_document('documento_4','_files/','jpeg|jpg|png|pdf|doc|docx|xls|xlxs');

                    $codigo = $this->class_security->generar_codigo();

                    $data = array(
                        't_apartamento'       => $apartamento,
                        't_usuario'           => $this->user_data->u_id,
                        't_codigo'            =>  $codigo,
                        't_titulo'            => $titulo,
                        't_descripcion'       => $observacion,
                        't_estado'            => 1,
                        't_prioridad'         => $prioridad,
                        't_documento1'        => $documento_1,
                        't_documento2'        => $documento_2,
                        't_documento3'        => $documento_3,
                        't_documento4'        => $documento_4,
                        't_atcreate'         => fecha(2)
                    );
                    $create =   $this->general->create('ticket',$data);
                    $id = $create['id'];
                    $this->generico->create_mail($id,'ticket','create');
                    /*
                    $mailer = new Mailer();
                    $config = array(
                        'to' => $this->user_data->u_correo1,
                        'subject' => "Se Registro el ticket {$codigo}",
                        'msg' => 'Se registro el ticket ye esta en espera de validacion',
                        'email' => $this->config->config['project']['email']['ticket']
                    );
                    $mailer->initialize($config);
                    $mailer->send_mail();
                    */
                    $this->result = $create;


                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
                }


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
                    if($this->general->exist('ticket',array('t_id' => $id))){
                        $resultado =   $this->general->get('ticket',array('t_id' => $id));
                        $resultado_data = array(
                            'id'                => encriptar($resultado->t_id),
                            'codigo'       => $this->class_security->decodificar($resultado->t_codigo),
                            'titulo'             => $this->class_security->decodificar($resultado->t_titulo),
                            'descripcion'       => $this->class_security->decodificar($resultado->t_descripcion),
                            'estado'              => $this->class_security->decodificar($resultado->t_estado),
                            'prioridad'    => $this->class_security->decodificar($resultado->t_prioridad),
                            'documento1'       => $this->url_imagen($resultado->t_documento1),
                            'documento2'       => $this->url_imagen($resultado->t_documento2),
                            'documento3'       => $this->url_imagen($resultado->t_documento3),
                            'documento4'       => $this->url_imagen($resultado->t_documento4),
                            'mensajes'        => $this->mensajes($id)
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

    function respuesta(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','respuesta'))){

                //campos post
                $id         = $this->class_security->data_form('data_id','decrypt_int');
                $estado         = $this->class_security->data_form('estado','int','1');
                $observacion    = $this->class_security->data_form('respuesta');

                //validar que el estado solo lo pueden manipular los admin

                //procesar
                //agregar cuota extraordinarioa
                if($observacion != '' AND $this->general->exist('ticket',array('t_id' => $id))){

                    $documento_1 = $this->class_security->upload_document('documento_1','_files/','jpeg|jpg|png|pdf|doc|docx|xls|xlxs');
                    $documento_2 = $this->class_security->upload_document('documento_2','_files/','jpeg|jpg|png|pdf|doc|docx|xls|xlxs');


                    $data = array(
                        'tr_ticket'             => $id,
                        'tr_usuario'            => $this->user_data->u_id,
                        'tr_descripcion'        => $observacion,
                        'tr_documento1'         => $documento_1,
                        'tr_document2'          => $documento_2,
                        'tr_atcreate'           => fecha(2 )
                    );

                    $this->generico->create_mail($id,'ticket','respuesta');
                    /*
                     $titulo = ($estado == 2) ? 'Se envio respuesta del ticket' : 'Se cerro el ticket';
                     $mailer = new Mailer();

                     $config = array(
                        'to'        => $this->user_data->u_correo1,
                        'subject'   => $titulo,
                        'msg'       => 'Se registro el ticket ye esta en espera de validacion',
                        'email' => $this->config->config['project']['email']['ticket']
                    );
                    $mailer->initialize($config);
                    $mailer->send_mail();
                    */

                    $ticket = $this->general->get('ticket',['t_id' => $id]);


                    //se actualiza el estado enviado
                    if( $this->user_data->u_perfil == 1 AND $ticket->t_estado != 3){
                        $this->general->update('ticket',['t_id' => $id],['t_estado' => $estado]);
                    }

                    $this->result =   $this->general->create('ticket_response',$data);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
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
                    if($this->general->exist('ticket',array('t_id' => $id,'t_estado' => 1))){
                        $this->result =  $this->general->delete('ticket',array('t_id' => $id));
                    }else{
                        $this->result = array('success' => 2,'msg' => 'El ticket ya entro en proceso');
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
            1 => 'a.t_apartamento',
            2 => 't.t_codigo',
            3 => 't.t_titulo',
        );

        //tabla
        $tabla =  'ticket As t';

        //join
        $join   = array(
           1 =>  [ 'tabla' => 'apartamento As a', 'join' => 't.t_apartamento=a.t_id']
        );

        //validar si es un usuario o administrador
        $where_data = [];
        $where_in = [];

        if($this->user_data->u_perfil != 1){
            $where_data = [
                't.t_apartamento' => $this->user_data->u_apartamento
            ];


        $where_in =
            [
                'in',
                't.t_apartamento',
                $this->user_data->u_apartamento
            ];
        }
        $dataget         = $this->datatable->getdata($filter,$columnas,$where_data,$where_in,$tabla,$join,'t.*,a.t_apartamento As nombre_apt');
        $total_registros = $this->datatable->totalData($valor,$columnas,$where_data,$where_in,$tabla,$join);

        foreach($dataget as $rows){
            $id              = encriptar($rows->t_id);
            $estado            = $this->class_security->array_data($rows->t_estado,$this->class_data->ticket_estado,$this->class_data->estado_default);
            $tipo            = $this->class_security->array_data($rows->t_prioridad,$this->class_data->ticket_prioridad,$this->class_data->estado_default);

            if($this->user_data->u_perfil == 1){
                $data[]= array(
                    $rows->t_codigo,
                    $rows->nombre_apt,
                    $rows->t_titulo,
                    "<button class='{$estado['class']}'>{$estado['title']}</button>",
                    "<button class='{$tipo['class']}'>{$tipo['title']}</button>",
                    $rows->t_atcreate,
                    " <div class='btn-group'><button type='button' onclick='$(this).forms_modal({\"page\" : \"ticket_admin\",\"data1\" : \"{$id}\",\"title\" : \"Tickets\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>"
                );
            }else{
                $data[]= array(
                    $rows->t_codigo,
                    $rows->t_titulo,
                    "<button class='{$estado['class']}'>{$estado['title']}</button>",
                    "<button class='{$tipo['class']}'>{$tipo['title']}</button>",
                    $rows->t_atcreate,
                    "<div class='btn-group'><button type='button'  onclick='$(this).forms_modal({\"page\" : \"ticket\",\"data1\" : \"{$id}\",\"title\" : \"Tickets\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></div>"
                );
            }

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