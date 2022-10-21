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
                $estado           = $this->class_security->data_form('estado');
                $apartamento    = $this->user_data->u_apartamento;

                $usuario        = $this->class_security->random_strings('10');
                $password       = encriptar_password('xls');
                $password_no    = 'xls';

                //procesar
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
                    'u_atcreate'        => fecha(2),
                );

                $this->result =   $this->general->create_update('usuarios',array('u_id' => $id),$data);

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
                $propietario    = $this->user_data->u_id;
                $tipo_vehiculo  = $this->class_security->data_form('tipo_vehiculo');
                $placa          = $this->class_security->data_form('placa');
                $color          = $this->class_security->data_form('color');
                $marca          = $this->class_security->data_form('marca');

                $preimg  = $this->class_security->upload_document('imagen','_files/','jpg|png');
                $foto = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $this->general->nique_value('propietarios_vehiculos','pv_imagen',array('pv_id'    => $id)) : 'default.jpg');

                //procesar
                $data = array(
                    'pv_propietario'    => $propietario,
                    'pv_tipo'    => $tipo_vehiculo,
                    'pv_placa'   => $placa,
                    'pv_color'   => $color,
                    'pv_marca'   => $marca,
                    'pv_imagen'   => $foto,
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
                $apt_id = $this->user_data->u_apartamento;

                $deuda        = $this->class_security->data_form('data_id','decrypt_int');
                $observacion  = $this->class_security->data_form('observacion','str');
                $favor        = ($this->class_security->data_form('saldo_favor','saldo',0));
                $saldo        = ($this->class_security->data_form('saldo','saldo',0));

                if($favor == 0  and  $saldo == 0){
                    $this->result = array('success' => 2,'msg' => 'Debes Ingresar para realizar el proceso mayor a ₡ 1');
                }else {
                    if ($deuda != '') {


                        if ($this->general->exist('deuda', ['du_id' => $deuda])) {

                            $data_deuda  = $this->general->get('deuda', ['du_id' => $deuda], 'array');
                            $saldo_deuda = $this->general->sum_data('deuda_pagos', ['pd_deuda' => $deuda, 'pd_apartamento' => $apt_id, 'pd_estado' => 3], 'pd_monto');
                            $saldo_valor = (isset($saldo_deuda) and ($saldo_deuda->suma >= 1)) ? $saldo_deuda->suma : 0;

                            $suma_data    = $saldo_valor + $saldo;
                            $suma_totales = $data_deuda['du_saldo']-$saldo_valor;
//                            echo $suma_totales;
//                            exit;

                            if ($suma_data <= $data_deuda['du_saldo']) {

                                $preimg = $this->class_security->upload_document('imagen', '_files/', 'jpeg|jpg|png|doc|docx|pdf');
                                $documento = (strlen($preimg)) ? $preimg : '';

                                $saldo_favor = $this->class_security->saldo_favor_clc($apt_id,$favor,$suma_totales);

                                //insertar data
                                if($saldo_favor >= 1 ){
                                    $this->general->create('deuda_pagos',
                                        [
                                            'pd_usuario' => $this->user_data->u_id,
                                            'pd_deuda' => $deuda,
                                            'pd_apartamento' => $apt_id,
                                            'pd_monto' => $saldo_favor,
                                            'pd_comprobante' => $documento,
                                            'pd_observacion' => $observacion,
                                            'pd_estado' => 3,
                                            'pd_atcreate' => fecha(2),
                                        ]
                                    );
                                }


                                if($saldo >= 1){
                                    $this->general->create('deuda_pagos',
                                        [
                                            'pd_usuario' => $this->user_data->u_id,
                                            'pd_deuda' => $deuda,
                                            'pd_apartamento' => $apt_id,
                                            'pd_monto' => $saldo,
                                            'pd_comprobante' => $documento,
                                            'pd_observacion' => $observacion,
                                            'pd_estado' => 1,
                                            'pd_atcreate' => fecha(2),
                                        ]
                                    );
                                }

                                $this->result = array('success' => 1);
                            } else {
                                $this->result = array('success' => 2, 'msg' => 'El Saldo Ingresado Supera el valor');
                            }

                        } else {
                            $this->result = array('success' => 2, 'msg' => 'Validar la deuda no existe');
                        }
                    } else {
                        $this->result = array('success' => 2, 'msg' => 'No se Seleccionaron Deudas');
                    }
                }

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
            if($this->class_security->validate_post(array('data_id'))){


                $id        = $this->class_security->data_form('data_id','decrypt_str');
                $deuda      = $this->class_security->solo_numerico($this->class_security->data_form('deuda'));
                $favor      = ($this->class_security->data_form('uso','saldo',0));
                $saldo      = ($this->class_security->data_form('saldo','saldo',0));
                $mes        = $this->class_security->data_form('mes','int',0);
                $anno       = $this->class_security->data_form('anno','int');
                $tipo       = $this->class_security->data_form('tipo','int');
                $apartamento_id = $this->user_data->u_apartamento;

                //validar si tiene saldo a favor

                if($favor == 0 AND  $saldo == 0){
                    $this->result = array('success' => 2,'msg' => 'Debes Ingresar para realizar el proceso mayor a ₡ 1');
                    // $this->result = array('success' => 2,'msg' => 'No Tienes Saldo a favor Validar Por favor');
                }else{
                    //validar que el saldo y a favor sean  mayor a 1
                    if($this->general->exist('apartamento_pagos' , array('pg_id' => $id,'pg_apartamento' => $apartamento_id))) {

                        //no permitir hacer pagos sin saldar anmteriormente sus deudas
                        $deuda_query = $this->general->query("select d.du_apartamento,SUM(d.du_saldo)-COALESCE(SUM(dp.pd_monto),0) As monto from deuda As d
 LEFT JOIN deuda_pagos As dp on d.du_id = dp.pd_deuda AND dp.pd_estado=3  WHERE d.du_apartamento='".$apartamento_id."' GROUP BY d.du_apartamento",'obj');

                        if(count($deuda_query) >= 1){
                            //tiene deudas
                            $dd = $deuda_query[0];
                            if($dd->monto >= 1){
                                $this->result = array('success' => 2,'msg' => 'Por favor realizar la cancelaciond tus deudas');
                                api($this->result);
                                exit;
                            }
                        }

                        //data de la deuda
                        $deuda = $this->general->get('apartamento_pagos' , array('pg_id' => $id,'pg_apartamento' => $apartamento_id));
                        //valdiar si existe saldo a favor


                        //validar que si es deuda de agua no pague menor a la cantidad se suma saldo a favor con el valor normal
                        if($deuda->pg_tipo_deuda == 1){
                            //validamos que el saldo sea menor
                            $suma = $favor+$saldo;
                            if($suma < $deuda->pg_deuda){
                            $this->result = array('success' => 2,'msg' => 'El saldo que debes pagar el menor a la deuda');
                            api($this->result);
                            exit;
                            }
                        }


                        //obtener lo sumado que se agrego
                        $clc_agregados = $this->general->sum_data('apartamento_pagos_abono',['aa_deuda' => $id,'aa_estado' => 2],'aa_saldo');
                        $m_agregados = (isset($clc_agregados->suma) AND $clc_agregados->suma >= 1) ? $clc_agregados->suma : 0;


                        $clc_favor = $this->general->get('saldo_favor',['sf_apartamento' => $this->user_data->u_apartamento]);
                        $m_favor = (isset($clc_favor->sf_saldo)) ? ($clc_favor->sf_saldo >= 1) ? $clc_favor->sf_saldo : 0 : 0;


                        $comprobante = $this->class_security->upload_document('imagen', '_files/', 'jpg|png|jpeg|pdf|doc|docx');

                        //validar si tiene saldo a favor para saber si se debe o no agregar tomando en cuenta que el usuario tiene saldo o no
                        $money_favor  = 0;
                        if($favor >= 1 and $m_favor >= 1){
//                            echo 'puede tomar saldo';
                            if($m_favor >= $favor){
//                                echo '<br> toma todo el saldo';
                                $money_favor = $favor;//se puede tomar todo el saldo que se ingreso ya que este es superado por el guardado
                            }else{
//                                echo '<br> solo puede tomar lo que exista en saldo a favor';
                                $money_favor = $m_favor;//se valida que el saldo no supera la cuota y se toma la todalidad del saldo a favor
                            }
                        }

                        $money_real = $saldo+$money_favor;

                        //registrar el saldo a tener en cuenta en la cuestion
                        $data_abono = array(
                            'as_apartamento'  => $apartamento_id,
                            'aa_deuda'        => $id,
                            'aa_usuario'      => $this->user_data->u_id,
                            'aa_saldo'        => $money_real,
                            'aa_comprobante'  => $comprobante,
                            'aa_estado'       => (($saldo == 0 and $money_favor >= 1) ? 3  : 1),
                            'aa_atcreate'     => fecha(2),
                        );

                        $insert_abono =  $this->general->create('apartamento_pagos_abono',$data_abono);
                        $id_abono = $insert_abono['id'];

                        //registrar el valor siempre y cuando sea mayor a cero
                        if($saldo >= 1) {

                            $this->general->create('apartamento_pago_agregado', [
                                'as_apartamento' => $apartamento_id,
                                'as_deuda' => $id,
                                'as_agregado' => $id_abono,
                                'aa_monto' => $saldo,
                                'aa_tipo' => 1,
                                'aa_estado' => 1,
                                'aa_fecha' => fecha(2),
                            ]);
                        }

                        if($money_favor >= 1){
                            $clc_favor = $m_favor-$money_favor;

                            //descontar el valor del saldo a favor directamente
                            $this->general->update('saldo_favor',['sf_apartamento' => $apartamento_id],['sf_saldo' => $clc_favor]);

                            $this->general->create('apartamento_pago_agregado',[
                                'as_apartamento' => $apartamento_id,
                                'as_deuda'       => $id,
                                'as_agregado'    => $id_abono,
                                'aa_monto'       => $money_favor,
                                'aa_tipo'        => 2,
                                'aa_estado'      => 3,
                                'aa_fecha'       => fecha(2),
                            ]);

                        }

                        $this->result = array('success' => 1);

                    }else{
                        $this->result = array('success' => 2,'msg' => 'Lo siento no se pudo relacionar la deuda');
                    }

                }

                //    $this->result =   $this->general->create_update('apartamento_pagos',array('pg_id' => $id),$data);

                //       $this->result = array('success' => 1);

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
            if($this->class_security->validate_post(array('send'))){
                $apt_id    = $this->user_data->u_apartamento;
                $deudas    = $this->input->post('deuda');
                $tipo      = $this->class_security->data_form('tipo_deuda','decrypt_int');
                $favor     = ($this->class_security->data_form('saldo_favor','saldo',0));
                $saldo     = ($this->class_security->data_form('m_saldo_cancelar','saldo',0));
                $usuario   = $this->user_data->u_id;
                $cantidad  = isset($deudas) ? count($deudas) : 0;

                if($favor == 0 AND  $saldo == 0 ){
                    $this->result = array('success' => 2,'msg' => 'Debes Ingresar para realizar el proceso mayor a ₡ 1');
                }else{

                    if((isset($deudas)) AND count($deudas)>= 1 and in_array($tipo,[1,2,3,4]))  {
                        //validar el saldo a favor si ingreso


                        //no permitir hacer pagos sin saldar anmteriormente sus deudas
                        $deuda_query = $this->general->query("select d.du_apartamento,SUM(d.du_saldo)-COALESCE(SUM(dp.pd_monto),0) As monto from deuda As d
 LEFT JOIN deuda_pagos As dp on d.du_id = dp.pd_deuda AND dp.pd_estado=3  WHERE d.du_apartamento='".$apt_id."' GROUP BY d.du_apartamento",'obj');

                        if(count($deuda_query) >= 1 and in_array($tipo,[1,2,3])){
                            //tiene deudas
                            $dd = $deuda_query[0];
                            if($dd->monto >= 1){
                                $this->result = array('success' => 2,'msg' => 'Por favor realizar la cancelaciond tus deudas');
                                api($this->result);
                                exit;
                            }
                        }


                        $preimg  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png|doc|docx|pdf');
                        $foto   =  (strlen($preimg)) ? $preimg : '';


                        //validar que lo ingresado saldo a favor igual a la deuda y considerar el procedimiento
                        $dinero_normal = $this->distribucion_saldos($deudas,$saldo,$tipo);
                        $dinero_tomar = $this->distribucion_saldos($deudas,$favor,$tipo);
                        $op_normal = ($dinero_normal == 1) ? 1 : $cantidad;
                        $op_favor = ($dinero_tomar == 1) ? 1 : $cantidad;


                        //obtener las deudas a pagar
                        foreach($deudas As $de ){
                            $id = desencriptar($de);

                            //validar si existe
                            $datas = $this->general->query("SELECT ap.pg_id,ap.pg_deuda,COALESCE(SUM(ag.aa_monto),0) AS pagado FROM apartamento_pagos AS ap
LEFT JOIN apartamento_pago_agregado AS ag ON ap.pg_id=ag.as_deuda AND ag.aa_estado=3
WHERE ap.pg_id='".$id."' AND ap.pg_apartamento='".$apt_id."'",'array');


                            if(in_array($tipo,[1,2,3])){
                                    if(count($datas) >= 1){

                                        $datasr = $datas[0];

                                        $id_dueda = $datasr['pg_id'];
                                        $monto_dueda = $datasr['pg_deuda'];
                                        $pagado_dueda = $datasr['pagado'];
                                        $suma_valores = $monto_dueda-$pagado_dueda;


                                    //saldo a favor data
                                    $saldo_favor        = $this->class_security->saldo_favor_clc($apt_id,$favor/$op_favor,$suma_valores);
                                    $saldo_favor_sumar  = $this->class_security->solo_numerico(number_format($saldo_favor,2));
                                    $saldo_sumar        = $this->class_security->solo_numerico(number_format($saldo/$op_normal,2));
                                    $sumar_saldos       = $saldo_favor_sumar+$saldo_sumar;


                                    $agua_p = $this->general->create('apartamento_pagos_abono',[
                                        'as_apartamento' => $apt_id,
                                        'aa_deuda'       => $id_dueda,
                                        'aa_usuario'     => $usuario,
                                        'aa_saldo'       => $sumar_saldos,
                                        'aa_comprobante' => $foto,
                                        'aa_estado'      => '2',
                                        'aa_atcreate'    => fecha(2)
                                    ]);
                                    $id_agua_abono = $agua_p['id'];


                                    if($saldo_favor_sumar >= 1 ){
                                        $this->general->create('apartamento_pago_agregado',[
                                            'as_apartamento' => $apt_id,
                                            'as_deuda'       => $id_dueda,
                                            'as_agregado'    => $id_agua_abono,
                                            'aa_monto'       => $saldo_favor_sumar,
                                            'aa_tipo'        => 2,
                                            'aa_estado'      => 3,
                                            'aa_fecha'       => fecha(2),
                                        ]);
                                    }

                                    if($saldo_sumar >= 1){
                                        $this->general->create('apartamento_pago_agregado',[
                                            'as_apartamento' => $apt_id,
                                            'as_deuda'       => $id_dueda,
                                            'as_agregado'    => $id_agua_abono,
                                            'aa_monto'       => $saldo_sumar,
                                            'aa_tipo'        => 1,
                                            'aa_estado'      => '2',
                                            'aa_fecha'       => fecha(2)
                                        ]);
                                    }

                            }
                            }else{
                                //deuda
                                $datas = $this->general->query("SELECT d.du_id,d.du_saldo,COALESCE((SUM(dp.pd_monto)),0) As pagado FROM deuda AS d
LEFT JOIN deuda_pagos AS dp ON d.du_id=dp.pd_deuda AND dp.pd_estado=3
WHERE d.du_id='".$id."' AND d.du_apartamento='".$apt_id."' AND d.du_estado IN(1,2)",'array');


                                if(count($datas) >= 1){

                                    $datasr = $datas[0];
                                    $id_dueda = $datasr['du_id'];
                                     $monto_dueda = $datasr['du_saldo'];
                                    $pagado_dueda = $datasr['pagado'];
                                     $suma_valores       = $monto_dueda-$pagado_dueda;

                                    //saldo a favor data
                                    $saldo_favor        = $this->class_security->saldo_favor_clc($apt_id,$favor/$op_favor,$suma_valores);
                                    $saldo_favor_sumar  = $this->class_security->solo_numerico(number_format($saldo_favor,2));
                                    $saldo_sumar        = $this->class_security->solo_numerico(number_format($saldo/$cantidad,2));


                                    if($saldo_favor_sumar >= 1 ){
                                        $this->general->create('deuda_pagos',[
                                            'pd_usuario'    => $this->user_data->u_id,
                                            'pd_deuda'       => $id_dueda,
                                            'pd_apartamento' => $apt_id,
                                            'pd_monto'       => $saldo_favor_sumar,
                                            'pd_comprobante' => $foto,
                                            'pd_estado'      => '3',
                                            'pd_observacion' => '',
                                            'pd_atcreate'    => fecha(2)
                                        ]);
                                    }

                                    if($saldo_sumar >= 1){
                                        $this->general->create('deuda_pagos',[
                                            'pd_usuario'    => $this->user_data->u_id,
                                            'pd_deuda'       => $id_dueda,
                                            'pd_apartamento' => $apt_id,
                                            'pd_monto'       => $saldo_sumar,
                                            'pd_comprobante' => $foto,
                                            'pd_estado'      => '2',
                                            'pd_observacion' => '',
                                            'pd_atcreate'    => fecha(2)
                                        ]);
                                    }

                                }

                            }

                        }

                        $this->result = array('success' => 1);
                    }else{
                        $this->result = array('success' => 2,'msg' => 'No hay deudas que validar');
                    }

                }

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