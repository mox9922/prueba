<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Usuarios extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Usuarios';
    private $controller   = 'usuarios/';
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

    function _remap($method)
    {
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            $this->index($method);
        }
    }

    function index($id = '') {
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => ($id == 2) ? 'Propietarios' : 'Usuarios',
            'controller'    => 'Dashboard',
            'user'          => $this->user_data

        );

        $data_menu = array();

        $data_body = array(
            'dataresult' => $this->general->all_get('usuarios',array('u_perfil' => $id)),
            'apartamentos' => $this->general->all_get('apartamento'),
            'perfil'     => $id,
            'crud' => array(
                'tipo_usuario'  => $id,
                'url_modals'    => base_url("modal/"),
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_database'  => base_url("{$this->controller}database"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'url_datatable' => base_url("{$this->controller}datatable"),


                //vehiculo
                'url_save_vehiculo' => base_url("{$this->controller}save_vehiculo"),
                'url_get_vehiculo' => base_url("{$this->controller}get_vehiculo"),
                'url_dell_vehiculo' => base_url("{$this->controller}dell_vehiculo"),

                //mascotas
                'url_save_mascotas'     => base_url("{$this->controller}save_mascotas"),
                'url_get_mascotas'      => base_url("{$this->controller}get_mascotas"),
                'url_dell_mascotas'     => base_url("{$this->controller}dell_mascotas"),

                //Invitados
                'url_save_invitados'     => base_url("{$this->controller}save_invitados"),
                'url_get_invitados'      => base_url("{$this->controller}get_invitados"),
                'url_dell_invitados'     => base_url("{$this->controller}dell_invitados"),

                //quickpass
                'url_save_qickpass'     => base_url("{$this->controller}save_qickpass"),
                'url_get_qickpass'      => base_url("{$this->controller}get_qickpass"),
                'url_dell_qickpass'     => base_url("{$this->controller}dell_qickpass"),

                //filiales
                'url_save_filial'     => base_url("{$this->controller}save_filial"),
                'url_dell_filial'     => base_url("{$this->controller}dell_filial"),
                'url_dell_filial_extra'     => base_url("{$this->controller}dell_filial_extrordinario"),
                'modal_tabla_filiales'     => base_url("{$this->controller}datatable_filiales"),



                'url_search_apartamentos' => base_url("{$this->controller}search_apartamentos")
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/numero/AutoNumeric.js','cr/usuarios.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_usuarios',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    //method crud
    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','usuario','estado','perfil'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $usuario        = $this->class_security->data_form('usuario');
                $password       = encriptar_password($this->class_security->data_form('password'));
                $perfil         = $this->class_security->data_form('perfil','int');
                $estado         = $this->class_security->data_form('estado','int');
                $apartamento    = $this->class_security->data_form('apartamento','int');
                $password_no    = $this->class_security->data_form('password');
                $telefono1      = $this->class_security->data_form('telefono1');
                $telefono2      = $this->class_security->data_form('telefono2');
                $correo1        = $this->class_security->data_form('correo1');
                $correo2        = $this->class_security->data_form('correo2');


                $preimg  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png');
                $foto = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $this->general->nique_value('usuarios','u_imagen',array('u_id'    => $id)) : 'default.jpg');


                //procesar
                if(strlen($password_no) > 1){
                    $data = array(
                        'u_nombre'          => $nombre,
                        'u_username'        => $usuario,
                        'u_password'        => $password,
                        'u_estado'          => $estado,
                        'u_perfil'          => $perfil,
                        'u_apartamento'     => $apartamento,
                        'u_telefono1'       => $telefono1,
                        'u_telefono2'       => $telefono2,
                        'u_correo1'         => $correo1,
                        'u_correo2'         => $correo2,
                        'u_imagen'          => $foto,
                        'u_atcreate'        => fecha(2),
                    );
                } else {
                    $data = array(
                        'u_nombre'          => $nombre,
                        'u_username'        => $usuario,
                        'u_perfil'          => $perfil,
                        'u_apartamento'     => $apartamento,
                        'u_telefono1'       => $telefono1,
                        'u_telefono2'       => $telefono2,
                        'u_correo1'         => $correo1,
                        'u_correo2'         => $correo2,
                        'u_imagen'          => $foto,
                        'u_estado'          => $estado,
                    );
                }



                //validar la duplicidad del username or emailes
                if(strlen($id) >= 1){
                    //user exist

                    if($this->general->exist('usuarios',array('u_id' => $id))){
                        //exist user update data

                        $data_email = $this->general->all_get('usuarios',array('u_username' => $usuario));
                        $data_id = array_column($data_email,'u_id');
                        $diferencia = array_diff($data_id,array($id));

                        if(count($diferencia) >= 1){
                            $this->result = array('success' => 2,'msg' => 'El Usuario ya Existe');
                        }else{
                            $this->result =   $this->general->create_update('usuarios',array('u_id' => $id),$data);
                        }

                    }else{
                        //user not exist create new user
                        $data_email = $this->general->exist('usuarios',array('u_username' => $usuario));
                        if(!$data_email){
                            if(strlen($password_no) >= 2){
                                $this->result =   $this->general->create_update('usuarios',array(),$data);
                            }else{
                                $this->result = array('success' => 2,'msg' => 'Usuario Nuevo debes Agregar una contraseña');
                            }
                        }else{
                            $this->result = array('success' => 2,'msg' => 'El Usuario ya Existe');
                        }
                    }
                }else{
                    //create user not id
                    //validate email not exist user register
                    $data_email = $this->general->exist('usuarios',array('u_username' => $usuario));
                    if(!$data_email){
                        if(strlen($password_no) >= 2){
                            $this->result =   $this->general->create_update('usuarios',array(),$data);
                        }else{
                            $this->result = array('success' => 2,'msg' => 'Usuario Nuevo debes Agregar una contraseña');
                        }
                    }else{
                        $this->result = array('success' => 2,'msg' => 'El usuario ya Existe');
                    }
                }

                if($perfil == 6 AND isset($this->result['data'])){
                    $this->proveedor($this->result['data']);
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function proveedor($id){
        if($this->class_security->validate_post(array('tipo','documento')) AND $id != ''){
            $tipo           = $this->class_security->data_form('tipo');
            $documento      = $this->class_security->data_form('documento');
            //procesar
            $this->result =   $this->general->create_update('servicios_proveedores',array('sp_usuario' => $id),
                [
                    'sp_usuario'         => $id,
                    'sp_tipo_documento'  => $tipo,
                    'sp_documento'       => $documento,
                    'sp_atcreate'        => fecha(2),
                ]);
        }
    }

    function get(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('usuarios',array('u_id' => $id))){
                        $resultado =   $this->general->get('usuarios',array('u_id' => $id));
                        $resultado_data = array(
                            'id'            => encriptar($resultado->u_id),
                            'nombre'        => $this->class_security->decodificar($resultado->u_nombre),
                            'usuario'       => $this->class_security->decodificar($resultado->u_username),
                            'estado'        => $this->class_security->decodificar($resultado->u_estado),
                            'apartamento'        => $this->class_security->decodificar($resultado->u_apartamento),
                            'telefono1'        => $this->class_security->decodificar($resultado->u_telefono1),
                            'telefono2'        => $this->class_security->decodificar($resultado->u_telefono2),
                            'correo1'        => $this->class_security->decodificar($resultado->u_correo1),
                            'correo2'        => $this->class_security->decodificar($resultado->u_correo2),
                            'perfil'            => $this->class_security->decodificar($resultado->u_perfil),
                            'pp'            => $this->class_security->decodificar($resultado->u_estado),
                        );

                        $this->result = array('success' => '1','data' => $resultado_data);
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

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('usuarios',array('u_id' => $id))){

                        $data = $this->general->get('usuarios',array('u_id' => $id));
                        if($data->u_perfil == 6){
                            $this->result =  $this->general->delete('servicios_proveedores',array('sp_usuario' => $id));
                        }
                        $this->result =  $this->general->delete('usuarios',array('u_id' => $id));
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

    //vehiculos
    function save_vehiculo(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('tipo_vehiculo','propietario','marca','placa','color'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $propietario    = $this->class_security->data_form('propietario','decrypt_int');
                $marca          = $this->class_security->data_form('marca');
                $tipo_vehiculo  = $this->class_security->data_form('tipo_vehiculo');
                $placa          = $this->class_security->data_form('placa');
                $color          = $this->class_security->data_form('color');

                //procesar
                $data = array(
                    'pv_propietario'    => $propietario,
                    'pv_tipo'           => $tipo_vehiculo,
                    'pv_placa'          => $placa,
                    'pv_color'          => $color,
                    'pv_marca'          => $marca
                );

                $this->result =   $this->general->create_update('propietarios_vehiculos',array('pv_id' => $id),$data);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function dell_vehiculo(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_vehiculos',array('pv_id' => $id))){
                        $this->result =  $this->general->delete('propietarios_vehiculos',array('pv_id' => $id));
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

    function get_vehiculo(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_vehiculos',array('pv_id' => $id))){
                        $resultado =   $this->general->get('propietarios_vehiculos',array('pv_id' => $id));

                        $resultado_data = array(
                            'id'               => encriptar($resultado->pv_id),
                            'propietario'      => encriptar($resultado->pv_propietario),
                            'marca'             => $this->class_security->decodificar($resultado->pv_marca),
                            'tipo'             => $this->class_security->decodificar($resultado->pv_tipo),
                            'placa'            => $this->class_security->decodificar($resultado->pv_placa),
                            'color'            => $this->class_security->decodificar($resultado->pv_color),
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


    //mascotas
    function save_mascotas(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('apartamento','tipo','raza'))){

                //campos post
                $id                 = $this->class_security->data_form('data_id','decrypt_int');
                $apartamento        = $this->class_security->data_form('apartamento','decrypt_int');
                $nombre             = $this->class_security->data_form('nombre');
                $tipo               = $this->class_security->data_form('tipo');
                $color              = $this->class_security->data_form('color');
                $raza               = $this->class_security->data_form('raza');
                $observacion        = $this->class_security->data_form('observacion');

                $preimg  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png');
                $imagen_old = $this->general->nique_value('propietarios_mascostas','pm_imagen',array('pm_id' => $id));
                $foto = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $imagen_old : 'default.jpg');

                //eliminar si el archivo que se cambia es diferente al que se va a subir
                if($foto != 'default.jpg' AND $imagen_old != $foto){
                    $this->class_security->eliminar_archivo('_files/',$imagen_old);
                }


                //procesar
                $data = array(
                    'pm_apartamento'    => $apartamento,
                    'pm_nombre'         => $nombre,
                    'pm_tipo'           => $tipo,
                    'pm_color'          => $raza,
                    'pm_raza'           => $color,
                    'pm_observacion'    => $observacion,
                    'pm_imagen'         => $foto
                );

                $this->result =   $this->general->create_update('propietarios_mascostas',array('pm_id' => $id),$data);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function get_mascotas(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_mascostas',array('pm_id' => $id))){
                        $resultado =   $this->general->get('propietarios_mascostas',array('pm_id' => $id));

                        $resultado_data = array(
                            'id'               => encriptar($resultado->pm_id),
                            'apartamento'      => encriptar($resultado->pm_apartamento),
                            'nombre'           => $this->class_security->decodificar($resultado->pm_nombre),
                            'tipo'             => $this->class_security->decodificar($resultado->pm_tipo),
                            'color'            => $this->class_security->decodificar($resultado->pm_color),
                            'raza'             => $this->class_security->decodificar($resultado->pm_raza),
                            'observacion'      => substr($this->class_security->decodificar($resultado->pm_observacion),0,33),
                        );

                        $this->result = array('success' => '1','data' => $resultado_data);
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

    function dell_mascotas(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_mascostas',array('pm_id' => $id))){
                        $this->result =  $this->general->delete('propietarios_mascostas',array('pm_id' => $id));
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

    //Invitados
    function save_invitados(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','apartamento','estado'))){

                $id                 = $this->class_security->data_form('data_id','decrypt_int');
                $apartamento        = $this->class_security->data_form('apartamento','decrypt_int');
                if($this->general->exist('propietarios_visitante',array('pv_id' => $id,'pv_apartamento'  => $apartamento))){


                //campos post


                $nombre             = $this->class_security->data_form('nombre');
                $cedula             = $this->class_security->data_form('cedula');
                $tipo               = $this->class_security->data_form('tipo');
                $placa              = $this->class_security->data_form('placa');
                $estado               = $this->class_security->data_form('estado');

                //procesar
                $data = array(  'pv_estado' => $estado );

                $this->result =   $this->general->update('propietarios_visitante',array('pv_id' => $id),$data);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Invitado no existe');
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function get_invitados(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_visitante',array('pv_id' => $id))){
                        $resultado =   $this->general->get('propietarios_visitante',array('pv_id' => $id));

                        $resultado_data = array(
                            'id'               => encriptar($resultado->pv_id),
                            'apartamento'               => encriptar($resultado->pv_apartamento),
                            'nombre'           => $this->class_security->decodificar($resultado->pv_nombre),
                            'cedula'             => $this->class_security->decodificar($resultado->pv_cedula),
                            'tipo'             => $this->class_security->decodificar($resultado->pv_tipo_vehiculo),
                            'placa'            => $this->class_security->decodificar($resultado->pv_placa),
                            'estado'             => $this->class_security->decodificar($resultado->pv_estado),
                        );

                        $this->result = array('success' => '1','data' => $resultado_data);
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

    function dell_invitados(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_visitante',array('pv_id' => $id))){
                        $this->result =  $this->general->delete('propietarios_visitante',array('pv_id' => $id));
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

    //quick pass
    function save_qickpass(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('propietario','quick','placa'))){

                //campos post
                $id                 = $this->class_security->data_form('data_id','decrypt_int');
                $propietario        = $this->class_security->data_form('propietario','decrypt_int');
                $codigo               = $this->class_security->data_form('quick');
                $placa               = $this->class_security->data_form('placa');

                //procesar
                $data = array(
                    'pq_propietario'    => $propietario,
                    'pq_codigo'           => $codigo,
                    'pq_placa'           => $placa,
                );

                $this->result =   $this->general->create_update('propietarios_quick_pass',array('pq_id' => $id),$data);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function get_qickpass(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_quick_pass',array('pq_id' => $id))){
                        $resultado =   $this->general->get('propietarios_quick_pass',array('pq_id' => $id));

                        $resultado_data = array(
                            'id'               => encriptar($resultado->pq_id),
                            'propietario'      => encriptar($resultado->pq_propietario),
                            'codigo'             => $this->class_security->decodificar($resultado->pq_codigo),
                            'placa'             => $this->class_security->decodificar($resultado->pq_placa),
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

    function dell_qickpass(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('propietarios_quick_pass',array('pq_id' => $id))){
                        $this->result =  $this->general->delete('propietarios_quick_pass',array('pq_id' => $id));
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

    //filiales
    function save_filial(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('torre','apartamento','tipo','cuota','tipo_estado','estado'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $torre          = $this->class_security->data_form('torre');
                $apartamento    = $this->class_security->data_form('apartamento');

                $cuota          = $this->class_security->solo_numerico($this->class_security->data_form('cuota'));
                $extra          = $this->class_security->solo_numerico($this->class_security->data_form('cuota_ex'));
                $pagos          = $this->class_security->solo_numerico($this->class_security->data_form('n_pagos',1));
                $fecha_cobro    = $this->class_security->letras_numeros_espace($this->class_security->data_form('fecha_cobro'));
                $correo         = ($this->class_security->data_form('correo'));



                $observacion    = $this->class_security->data_form('observacion');
                $tipo           = $this->class_security->data_form('tipo');
                $tipo_estado    = $this->class_security->data_form('tipo_estado');
                $estado         = $this->class_security->data_form('estado');



                //procesar
                $data = array(
                    't_torre'          => $torre,
                    't_apartamento'    => $apartamento,
                    't_cuota'          => $cuota,
                    't_tipo'           => $tipo,
                    't_correo'         => $correo,
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
                            $dia = $ob['dia'];
                            $this->general->create("apartamento_pagos",array(
                                'pg_apartamento'  => $id2,
                                'pg_id_cuota_extraordinaria'  => $id_extr,
                                'pg_tipo_deuda'   => 3,
                                'pg_mes'          => $mes,
                                'pg_anno'         => $anno,
                                'pg_deuda'        => $calcular_extra,
                                'pg_estado'       => 1,
                                'pg_fecha'        => "{$anno}-{$mes}-{$dia}",
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

    function dell_filial(){
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

    function dell_filial_extrordinario(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('apartamento_pagos',array('pg_id' => $id))){
//                        $this->result =  $this->general->delete('cuota_extraordinaria',array('ce_id' => $id));
                        $this->result =  $this->general->delete('apartamento_pagos',array('pg_id' => $id));
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

    private function obtener_fechas_asignacion($cuotas,$fecha){
        $fecha_actual = date($fecha);

        $mm = array();
        for($i = 0;$i < $cuotas;$i++){
            $mm[] = array(

                'mes' => date("n",strtotime($fecha_actual."+ $i month")),
                'anno' => date("Y",strtotime($fecha_actual."+ $i month")),
                'dia' => date("d",strtotime($fecha_actual)),
            )  ;
        }
        return $mm;
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
            $dato      = $this->input->post("dato");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
            $dato = '';
            $order = array();
        }
        $data = array();


        $filter = array('start'  => $start,'length' => $length, 'order' => $order,'busqueda' => $valor);

        //columnas

        //tabla
        $tabla =  'usuarios As u';

        //join
        if($dato == 2){

            $columnas = array(
                1 => 'u.u_nombre',
                2 => 'u.u_username',
                3 => 'a.t_torre',
                4 => 'a.t_apartamento',
            );

            $join   = array(
                array('tabla' =>'apartamento As a','join' => 'u.u_apartamento=a.t_id')
            );
        }else{
            $columnas = array(
                1 => 'u.u_nombre',
            );

            $join   = array();
        }


        $where_data = array(
//            'u_propietario !=' => 2,
            'u_tipo' => 1
        );

        $where_in        =  [
           0 =>  ['in','u.u_perfil',($dato == 2 ) ? $dato : [1,3,4,5]],

        ];

        $dataget         = $this->datatable->getdata($filter,$columnas,$where_data,$where_in,$tabla,$join);
        $total_registros = $this->datatable->totalData($valor,$columnas,$where_data,$where_in,$tabla,$join);
        foreach($dataget as $rows){
            $id         = encriptar($rows->u_id);
            $apart      = encriptar($rows->u_apartamento);
            $nombre     = $this->class_security->decodificar($rows->u_nombre);
            $user       = $this->class_security->decodificar($rows->u_username);
            $perfil     = $this->class_security->array_data($rows->u_perfil,$this->class_data->perfiles);
            $estado     = $this->class_data->estado_simple[$rows->u_estado];
            $img_pre    = (strlen($rows->u_imagen) >= 5) ? $rows->u_imagen : 'default.jpg';
            $imagen     = base_url("_files/{$img_pre}");


            if($dato == 2){
                $apt = $this->general->get('apartamento',array('t_id' => $rows->u_apartamento));
                $pago       = '₡  '.$this->class_security->dinero($rows->t_cuota);
                $data[]= array(
                    $apt->t_apartamento,
                    "<button class='btn btn-dark' title='Quick Pass' onclick='$(this).forms_modal({\"page\" : \"propietario_quickpass\",\"data1\" : \"{$id}\",\"title\" : \"Quickpass\"})'><i class='fas fa-barcode'></i></button>",
                    "<button class='btn btn-dark' title='Vehiculo' onclick='$(this).forms_modal({\"page\" : \"propietario_vehiculos\",\"data1\" : \"{$apart}\",\"title\" : \"Vehiculos\"})'><i class='fas fa-car'></i></button>",
                    "<button class='btn btn-dark' title='Mascotas' onclick='$(this).forms_modal({\"page\" : \"propietario_mascotas\",\"data1\" : \"{$apart}\",\"title\" : \"Mascotas\"})'><i class='fas fa-paw'></i></button>",
                    "<button class='btn btn-dark' title='Inviados' onclick='$(this).forms_modal({\"page\" : \"propietario_invitados\",\"data1\" : \"{$apart}\",\"title\" : \"Invitados\"})'><i class='fas fa-user'></i></button>",
                    "<button class='btn btn-dark' title='Filial' onclick='$(this).forms_modal({\"page\" : \"propietario_filial\",\"data1\" : \"{$apart}\",\"title\" : \"Filial\"})'><i class='fas fa-home'></i></button>",
                    $nombre,
                    $pago,
                    "<button class='{$estado['class']}'>{$estado['title']}</button>",
                    "<div class='btn-group'><button type='button' onclick='$(this).forms_modal({\"page\" : \"propietario_usuarios\",\"data1\" : \"{$id}\",\"title\" : \"Propietarios\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></div>"
                );
            }else {
                $data[]= array(

                    "<a class='single_image' href='{$imagen}'><img class='rounded-circle' src='{$imagen}' style='height: 40px;width: 40px;'></a><script> $('.single_image').fancybox({'zoomSpeedIn': 300,'zoomSpeedOut': 300,'overlayShow': false});</script>",
                    $nombre,
                    $user,
                    $perfil,
                    "<button class='{$estado['class']}'>{$estado['title']}</button>",
                    "<div class='btn-group'>
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"usuarios\",\"data1\" : \"{$id}\",\"title\" : \"Usuarios\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
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

    function datatable_filiales(){
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
                '₡ '. $this->class_security->dinero($rows->t_cuota),
//                '',
//                '',
//                '',
//                '',
                "<button class='{$estado['class']}'>{$estado['title']}</button>",
                "<div class='btn-group'><button type='button'  onclick='$(this).forms_modal({\"page\" : \"propietario_filiales\",\"data1\" : \"{$id}\",\"title\" : \"Filiales\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_dell_filial\",false,function(){
                 $(this).forms_modal({\"page\" : \"propietario_filiales\",\"title\" : \"Filiales\"})
                 })' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></div>"
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