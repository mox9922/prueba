<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Reporte extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Informe de pagos';
    private $controller   = 'reporte/';
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
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_reporte_data' => base_url("{$this->controller}reporte_data"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/reporte.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_reporte',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }


    function reporte_data(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('fecha'))) {
//                campos post
                $fecha = $this->class_security->data_form('fecha','str2');
                $final = $this->class_security->data_form('final','str2',$fecha);

//                $fecha = '2021-09';
//                $final = '2021-11';

                //obtener rango de meses
                $rango_fechas = $this->class_security->rango_fechas($fecha,$final);
                $data = array();
                $i = 0;
                foreach($this->general->all_get('apartamento',[]) As $apt){
                    $x = 0;

                    $data[$i][] = "<b class='btn btn-dark'  onclick='$(this).forms_modal({\"page\" : \"reporte_informes_pagos\",\"data1\" : \"{$apt->t_id}\",\"data2\" : \"{$fecha}\",\"data3\" : \"{$final}\",\"title\" : \"Reporte informe de pagos\"})'>{$apt->t_apartamento}</b>";
                    foreach($rango_fechas As $fc){

                        $consulta = "
SELECT 
DISTINCT a.t_id,a.t_apartamento,COALESCE((la.lt_consumo),0) AS agua 


#deuda
,COALESCE((SELECT SUM(d.du_saldo) FROM deuda AS d WHERE d.du_apartamento=a.t_id  AND DATE_FORMAT(d.du_atcreate, '%Y-%m') = '".$fc."'),0) AS deuda_valor,
COALESCE((SELECT SUM(p2.pd_monto) AS cantidad FROM deuda_pagos AS p2 WHERE p2.pd_apartamento=a.t_id AND DATE_FORMAT(p2.pd_atcreate, '%Y-%m') = '".$fc."' AND p2.pd_estado=3),0) AS deuda_pago

FROM apartamento AS a
LEFT JOIN lectura_agua AS la ON a.t_id=la.lt_apartamento AND DATE_FORMAT(la.lt_atcreate, '%Y-%m') = '".$fc."'
WHERE a.t_id='".$apt->t_id."' ";

                        $resultado = $this->general->query($consulta);

                        foreach($resultado As $r){
                            $data[$i][] = $this->class_security->asertarMoneda($r['agua']);

                            //informacion de las deudas
                            $agua = $this->class_security->simplificar_deudas($apt->t_id,1,$fc);
                            $ce = $this->class_security->simplificar_deudas($apt->t_id,2,$fc);
                            $cc = $this->class_security->simplificar_deudas($apt->t_id,3,$fc);

                            $favor1 = $this->class_security->favor_reporte($apt->t_id,1,$fc);
                            $favor2 = $this->class_security->favor_reporte($apt->t_id,2,$fc);
                            $favor3 = $this->class_security->favor_reporte($apt->t_id,3,$fc);


                            $data[$i][] = ''. $this->class_security->dinero($agua['deuda']);
                            $data[$i][] = ''. $this->class_security->dinero($agua['agregado']);
                            $data[$i][] = ''. $this->class_security->dinero($agua['reciduo']);
                            $data[$i][] = ''. $this->class_security->dinero($favor1);

                            $data[$i][] = ''. $this->class_security->dinero($ce['deuda']);
                            $data[$i][] = ''. $this->class_security->dinero($ce['agregado']);
                            $data[$i][] = ''. $this->class_security->dinero($ce['reciduo']);
                            $data[$i][] = ''. $this->class_security->dinero($favor2);


                            $data[$i][] = ''. $this->class_security->dinero($cc['deuda']);
                            $data[$i][] = ''. $this->class_security->dinero($cc['agregado']);
                            $data[$i][] = ''. $this->class_security->dinero($cc['reciduo']);
                            $data[$i][] = ''. $this->class_security->dinero($favor3);

                            $data[$i][] = $this->class_security->asertarMoneda($r['deuda_valor']);
                            $data[$i][] = $this->class_security->asertarMoneda($r['deuda_pago']);
                            $data[$i][] = $this->class_security->saldo_validar($r['deuda_valor'],$r['deuda_pago']);


                            $data[$i][] = ''. $this->class_security->dinero($agua['deuda']+$ce['deuda']+$cc['deuda']);
                            $data[$i][] = ''. $this->class_security->dinero($agua['agregado']+$ce['agregado']+$cc['agregado']);
                            $data[$i][] = ''. $this->class_security->dinero($agua['reciduo']+$ce['reciduo']+$cc['reciduo']);
                            $data[$i][] = ''. $this->class_security->dinero($favor1+$favor2+$favor3);



                        }

                    }
                    $i++;

                }


                $this->result =   $this->result = array('success' => 1,'data' => $data,'fechas' => $rango_fechas);


            }else{
                $this->result = array('success' => 2,'msg' => 'Datos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }









}