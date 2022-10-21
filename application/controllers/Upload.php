<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Upload extends Ci_controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Importador de Información';
    private $controller   = 'upload/';
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
                'url_comprobante'   => base_url("{$this->controller}comprobante"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/upload.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/upload/v_upload',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }


    function preview($indocumento = ''){

        $documento = desencriptar($indocumento);
        $file_direct =  "_files/import/$documento";
        if(strlen($documento) > 10 AND file_exists($file_direct)){


        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data

        );

        $data_menu = array();
        $data_body = array(
            'datas'  => $this->leer_documento($file_direct),
            'documento' => $indocumento,
            'crud' => array(
                'url_import' => base_url("{$this->controller}save"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/upload.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/upload/v_preview',$data_body);
        $this->load->view('template/v_footer',$data_foot);

        }else{
            redirect(site_url($this->controller));
        }
    }

    private function leer_documento($documento){
        try {
            $this->load->library('excel');
            $excel = new Excel();
            $excel->Phpexcelsystem();

            $excelReader = PHPExcel_IOFactory::createReaderForFile($documento);
            $excelObj = $excelReader->load($documento);
            $worksheet = $excelObj->getSheet(0);
                        $lastRow = $worksheet->getHighestRow();

              $highestRow = $worksheet->getHighestRow();


            //validar titulos
            if($worksheet->getCell('A1')->getValue() == 'filial' AND $worksheet->getCell('B2')->getValue() ==  'mes'){
                return $worksheet->rangeToArray("A3:J{$highestRow}", null, true, false, false);
            }else{
                redirect(site_url($this->controller));
            }

        }catch (Exception $e){
            redirect(site_url($this->controller));
        }

    }

    function comprobante(){

        if ($this->input->post()) {
            if ($this->class_security->validate_post(array('send','clave'))) {

                $documento = $this->class_security->upload_document('documento', '_files/import/', 'xls|xlsx');
                if(strlen($documento) >= 10){
                    $document_crypt = encriptar($documento);
                    $this->result = array('success' => 1,'file' => site_url("{$this->controller}preview/{$document_crypt}"));
                }else{
                    $this->result = array('success' => 2, 'msg' => 'Campos Obligatiorios');
                }

            } else {
                $this->result = array('success' => 2, 'msg' => 'Campos Obligatiorios');
            }

        }else {
            $this->result = array('success' => 2, 'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save(){

        if ($this->input->post()) {
            if ($this->class_security->validate_post(array('documento'))) {

                $usuario = $this->user_data->u_id;
                $documento = desencriptar($this->class_security->data_form('documento','str'));
                $file_direct =  "_files/import/$documento";
                if(strlen($documento) > 10 AND file_exists($file_direct)){

                    $datas = $this->leer_documento($file_direct);
                    foreach($datas As $dd){

                        $fililal = $this->class_security->letras_numeros_mas($dd[0]);
                        $fecha = $dd[1];
                        $anno = date('Y', strtotime($fecha));
                        $mes = date('n', strtotime($fecha));
                        $mesl = date('m', strtotime($fecha));
                        $fecha_corta = "$anno-$mesl";

                        //lectura
                        $lect1   = $this->validar($dd[2]);
                        $lect2   = $this->validar($dd[3]);
                        $consumo = abs($lect2-$lect1);

                        //agua
                        $a_monto = $this->validar($dd[4]);
                        $a_saldo = $this->validar($dd[5]);

                        //cc
                        $cc_monto = $this->validar($dd[6]);
                        $cc_saldo = $this->validar($dd[7]);

                        //ce
                        $ce_monto = $this->validar($dd[8]);
                        $ce_saldo = $this->validar($dd[9]);

                        //validar si existe la filial
                        if($this->general->exist('apartamento',['t_apartamento' => $fililal])){

                            $fl = $this->general->get('apartamento',['t_apartamento' => $fililal]);

                            $apt = $fl->t_id;


                            $this->general->query("CALL proc_movimiento('$apt','$fecha');",'',false);


                            //import Lectura agua
                            $this->general->create('lectura_agua',[
                                'lt_apartamento' => $apt,
                                'lt_usuario'    => $usuario,
                                'lt_anterior'   => $lect1,
                                'lt_lectura'    => $lect2,
                                'lt_consumo'    => $consumo,
                                'lt_imagen'     => 'default.jpg',
                                'lt_mes'        => $mes,
                                'lt_anno'       => $anno,
                                'lt_atcreate'   => $fecha
                            ]);

                            //agua
                            $this->insertar_saldos($apt,$usuario,1,$mes,$anno,$fecha_corta,$fecha,$a_monto,$a_saldo);

                            //CC
                            $this->insertar_saldos($apt,$usuario,2,$mes,$anno,$fecha_corta,$fecha,$cc_monto,$cc_saldo);

                            //CE
                            $this->insertar_saldos($apt,$usuario,3,$mes,$anno,$fecha_corta,$fecha,$ce_monto,$ce_saldo);
                        }





                    }

                    $this->class_security->eliminar_archivo("_files/import/",$documento);
                    $this->result = array('success' => 1);
                }else{
                    $this->result = array('success' => 2, 'msg' => 'Documento No valido');
                }

            } else {
                $this->result = array('success' => 2, 'msg' => 'Documento Obligatorio');
            }

        }else {
            $this->result = array('success' => 2, 'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function insertar_saldos($apt,$usuario,$deuda,$mes,$anno,$fecha_corta,$fecha,$a_monto,$a_saldo){

        if($a_monto >= 1){
        $agua_d = $this->general->create('apartamento_pagos',[
            'pg_apartamento' => $apt,
            'pg_tipo_deuda'  => $deuda,
            'pg_id_cuota_extraordinaria'    => 0,
            'pg_mes'        => $mes,
            'pg_anno'       => $anno,
            'pg_deuda'      => $a_monto,
            'pg_fecha'      => $fecha_corta,
            'pg_fecha'      => $fecha
        ]);
        $id_agua = $agua_d['id'];

        if($a_saldo >= 1){
            $agua_p = $this->general->create('apartamento_pagos_abono',[
                'as_apartamento' => $apt,
                'aa_deuda'       => $id_agua,
                'aa_usuario'     => $usuario,
                'aa_saldo'       => $a_saldo,
                'aa_comprobante' => 'default.jpg',
                'aa_estado'      => '3',
                'aa_atcreate'   => $fecha
            ]);
            $id_agua_abono = $agua_p['id'];

            $this->general->create('apartamento_pago_agregado',[
                'as_apartamento' => $apt,
                'as_deuda'      => $id_agua,
                'as_agregado'   => $id_agua_abono,
                'aa_monto'      => $a_saldo,
                'aa_tipo'       => 1,
                'aa_estado'     => '3',
                'aa_fecha'      => $fecha
            ]);
        }

        }
    }

    private function validar($valor = 0){
        $clean = $this->class_security->letras_numeros_mas($valor);
        $r = 0;
        if(strlen($clean) >= 1){
            $r = $clean;
        }
        return $r;
    }



}