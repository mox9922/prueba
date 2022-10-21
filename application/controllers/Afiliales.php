<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Afiliales extends CI_Controller
{
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

    //Crud Invitados
    function save_condiciones(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('token'))){
                $this->result =   $this->general->update('usuarios',array('u_id' => $this->user_data->u_id),['u_terminos' => 2]);
                $this->result = array('success' => 1);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function visitante_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','tipo_invitado'))){


                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $cedula         = $this->class_security->data_form('cedula');
                $telefono       = '';
//                $telefono       = $this->class_security->data_form('telefono');
                $tipo           = $this->class_security->data_form('tipo');
                $placa          = $this->class_security->data_form('placa');
                $tipo_usaurio          = $this->class_security->data_form('tipo_invitado');
                $fecha_ingreso          = $this->class_security->data_form('fecha_ingreso');
                $estado         = $this->class_security->data_form('estado');
                $aparamento     = $this->user_data->u_apartamento;


                $preimg  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png');
                $foto = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $this->general->nique_value('propietarios_visitante','pv_foto',array('pv_id'    => $id)) : 'default.jpg');


                //procesar
                $data = array(
                    'pv_apartamento'    => $aparamento,
                    'pv_nombre'         => $nombre,
                    'pv_cedula'         => $cedula,
                    'pv_telefono'       => $telefono,
                    'pv_tipo_vehiculo'  => $tipo,
                    'pv_placa'          => $placa,
                    'pv_foto'           => $foto,
                    'pv_tipo_invitado'   => $tipo_usaurio,
                    'pv_fecha_ingreso'   => $fecha_ingreso,
                    'pv_estado'         => $estado
                );

                $this->result =   $this->general->create_update('propietarios_visitante',array('pv_id' => $id),$data);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function visitante_delete(){
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

    //Condominos
    function condomino_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','tipo','estado'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $telefono1      = $this->class_security->data_form('telefono1');
                $telefono2      = $this->class_security->data_form('telefono2');
                $tipo           = $this->class_security->data_form('tipo');
                $estado         = $this->class_security->data_form('estado');
                $tipo_contacto  = $this->class_security->data_form('tipo_contacto');
                $apartamento    = $this->user_data->u_apartamento;

                $usuario        = $this->class_security->random_strings('10');
                $password       = encriptar_password('xls');

                $dd = $this->general->get('usuarios',['u_apartamento' => $apartamento,'u_tipo_contacto' => 1]);
                if((!isset($dd->u_tipo_contacto) || isset($dd->u_tipo_contacto)) and $tipo_contacto == 1){

                    if(isset($dd->u_id)){
                        if($dd->u_id == $id){
                            $data = array(
                                'u_nombre'          => $nombre,
                                'u_username'        => $usuario,
                                'u_password'        => $password,
                                'u_estado'          => $estado,
                                'u_perfil'          => 2,
                                'u_apartamento'     => $apartamento,
                                'u_telefono1'       => $telefono1,
                                'u_telefono2'       => $telefono2,
                                'u_propietario'     => $tipo,
                                'u_tipo_contacto'   => $tipo_contacto,
                                'u_tipo'            => 2,
                                'u_atcreate'        => fecha(2),
                            );
                            $result = $this->general->create_update('usuarios',array('u_id' => $id),$data);

                        }else{
                            $result = array('success' => 2,'msg' => 'Solo puede existir un condomino primario');
                        }

                    }else{
                        //validar si existe un elemento de contacto no dejarlo continuar
                        if(isset($dd->u_tipo_contacto)){
                            $result = array('success' => 2,'msg' => 'Solo puede existir un condomino primario');
                        }else{
                            $data = array(
                                'u_nombre'          => $nombre,
                                'u_username'        => $usuario,
                                'u_password'        => $password,
                                'u_estado'          => $estado,
                                'u_perfil'          => 2,
                                'u_apartamento'     => $apartamento,
                                'u_telefono1'       => $telefono1,
                                'u_telefono2'       => $telefono2,
                                'u_propietario'     => $tipo,
                                'u_tipo_contacto'   => $tipo_contacto,
                                'u_tipo'            => 2,
                                'u_atcreate'        => fecha(2),
                            );
                            $result = $this->general->create_update('usuarios',array('u_id' => $id),$data);
                        }
                    }



                }else{

                    if($tipo_contacto == 2){
                        $data = array(
                            'u_nombre'          => $nombre,
                            'u_username'        => $usuario,
                            'u_password'        => $password,
                            'u_estado'          => $estado,
                            'u_perfil'          => 2,
                            'u_apartamento'     => $apartamento,
                            'u_telefono1'       => $telefono1,
                            'u_telefono2'       => $telefono2,
                            'u_propietario'     => $tipo,
                            'u_tipo_contacto'   => $tipo_contacto,
                            'u_tipo'            => 2,
                            'u_atcreate'        => fecha(2),
                        );
                        $result = $this->general->create_update('usuarios',array('u_id' => $id),$data);
                    }else{
                        //ya se encuentra una condominal primaria

                        $result = array('success' => 2,'msg' => 'Solo puede existir un condomino primario');
                    }

                }

                $this->result =   $result;
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function condomino_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('usuarios',array('u_id' => $id,'u_apartamento' => $this->user_data->u_apartamento  ))){
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

    
    //mascotas
    function save_mascotas(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('tipo','raza'))){

                //campos post
                $id                 = $this->class_security->data_form('data_id','decrypt_int');
                $apartamento        = $this->user_data->u_apartamento;
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


    //vehiuclos
    function save_vehiculo(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('tipo_vehiculo','placa','marca','color'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $propietario    = $this->user_data->u_apartamento;
                $tipo_vehiculo  = $this->class_security->data_form('tipo_vehiculo');
                $placa          = $this->class_security->data_form('placa');
                $color          = $this->class_security->data_form('color');
                $marca          = $this->class_security->data_form('marca');

                $preimg  = $this->class_security->upload_document('imagen','_files/','jpg|png');
                $foto = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $this->general->nique_value('propietarios_vehiculos','pv_imagen',array('pv_id'    => $id)) : 'default.jpg');

                //procesar
                $data = array(
                    'pv_propietario'    => $propietario,
                    'pv_tipo'           => $tipo_vehiculo,
                    'pv_placa'          => $placa,
                    'pv_color'          => $color,
                    'pv_marca'          => $marca,
                    'pv_imagen'         => $foto,
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

    //deuda
    function save_deuda(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id'))){
                $apt = $this->user_data->u_apartamento;
                $deuda        = $this->class_security->data_form('data_id','str');
                $deudas       = [$deuda];
                $observacion  = $this->class_security->data_form('observacion','str');
                $favor        = 0;
                $saldo        = ($this->class_security->data_form('saldo','saldo',0));

                $comprobante  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png|doc|docx|pdf');

                //validar si tiene saldo a favor
                $this->load->model('m_pagos','pagos');
                $this->result =  $this->pagos->pagos($this->user_data,$apt,$deudas,$saldo,$favor,4,$comprobante,$observacion);

            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save_comprobante_reserva(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id'))){


                $id           = $this->class_security->data_form('data_id','decrypt_str');
                $apt          = $this->user_data->u_apartamento;
                $comprobante  = $this->class_security->upload_document('comprobante','_files/','jpeg|jpg|png|doc|docx|xls|xlsx|pdf');

                $where = ['r_id' => $id,'r_apartamento' => $apt,'r_estado' => 3];
                if($this->general->exist('reserva',$where)){

                    $this->result =  $this->general->update('reserva',$where,[
                        'r_comprobante' => $comprobante
                    ]);


                }else{
                    $this->result = array('success' => 2,'msg' => 'Validar que se pueda subir el comprobante');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    //pagos
    function save_comprobante(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','saldo'))){

                $id         = $this->class_security->data_form('data_id');
                $deudas     = [$id];
                $favor      = 0;
                $saldo      = ($this->class_security->data_form('saldo','saldo',0));
//                $favor      = ($this->class_security->data_form('uso','saldo',0));
                $apt = $this->user_data->u_apartamento;
                $comprobante = $this->class_security->upload_document('imagen', '_files/', 'jpg|png|jpeg|pdf|doc|docx');

                //validar si tiene saldo a favor
                $this->load->model('m_pagos','pagos');
                $this->result = $this->pagos->pagos($this->user_data,$apt,$deudas,$saldo,$favor,1,$comprobante);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    //pagos totalidad lista
    function save_deuda_total(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('send','m_saldo_cancelar','tipo_deuda'))){
                $apt    = $this->user_data->u_apartamento;
                $deudas    = $this->input->post('deuda');
//                $favor     = ($this->class_security->data_form('saldo_favor','saldo',0));
                $favor     =  0;
                $tipo_deuda     = ($this->class_security->data_form('tipo_deuda','decrypt_int'));
                $saldo          = ($this->class_security->data_form('m_saldo_cancelar','saldo',0));
                $comprobante    = $this->class_security->upload_document('imagen', '_files/', 'jpg|png|jpeg|pdf|doc|docx');

                //validar si tiene saldo a favor
                $this->load->model('m_pagos','pagos');
                $this->result = $this->pagos->pagos($this->user_data,$apt,$deudas,$saldo,$favor,$tipo_deuda,$comprobante);

            }else{
                $this->result = array('success' => 2,'msg' => 'Falta el dato');
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


}