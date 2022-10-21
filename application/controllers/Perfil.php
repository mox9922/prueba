<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Perfil  extends Ci_controller{
    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Perfil';
    private $controller   = 'perfil/';
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
            'Profile' => $this->general->query("select u.*,a.t_correo from usuarios As u
LEFT JOIN apartamento AS a ON u.u_apartamento=a.t_id
WHERE u.u_id='".$id."'",'obj')['0'],
            'crud' => array(
                'url_save'      => base_url("{$this->controller}save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/profile.js'));


        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('v_profile',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    //method crud
    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre'))){

                //campos post
                $id             = $this->user_data->u_id;
                $nombre         = $this->class_security->data_form('nombre');
                $password       = encriptar_password($this->class_security->data_form('password'));
                $password_no    = $this->class_security->data_form('password');
                $telefono1      = $this->class_security->data_form('telefono1');
                $telefono2      = $this->class_security->data_form('telefono2');
                $correo1        = $this->class_security->data_form('correo1');
                $correo2        = $this->class_security->data_form('correo2');
                $correo        = $this->class_security->data_form('correo_filial');

                $preimg  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png');
                $foto = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $this->general->nique_value('usuarios','u_imagen',array('u_id' => $id)) : 'default.jpg');

                //validar que venga una imagen bajar el tamaño
                if(strlen($preimg) >= 7){

                    $this->class_security->imageresize("_files/{$preimg}",'_files/min',300,300);
                    $this->class_security->eliminar_archivo("_files/",$preimg);
                    $this->class_security->mover_archivo("_files/min/",'_files/',$preimg);
                 }

                //validar si es estado 2

                if($this->user_data->u_perfil == 2){
                    $this->general->update('apartamento',['t_id' => $this->user_data->u_apartamento],['t_correo' => $correo]);
                }

                //procesar
                if(strlen($password_no) > 1){
                    $data = array(
                        'u_nombre'           => $nombre,
                        'u_password'         => $password,
                        'u_telefono1'        => $telefono1,
                        'u_telefono2'        => $telefono2,
                        'u_imagen'           => $foto,
                        'u_correo1'          => $correo1,
                        'u_correo2'          => $correo2,
                    );
                } else {
                    $data = array(
                        'u_nombre'           => $nombre,
                        'u_telefono1'        => $telefono1,
                        'u_telefono2'        => $telefono2,
                        'u_imagen'           => $foto,
                        'u_correo1'          => $correo1,
                        'u_correo2'          => $correo2,
                    );
                }

                $this->result =   $this->general->create_update('usuarios',array('u_id' => $id),$data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

}