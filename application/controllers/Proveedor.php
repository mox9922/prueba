<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Proveedor extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controller   = 'proveedor/';
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

    function save_comprobante(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id'))){
                //campos post
                $id           = $this->class_security->data_form('data_id','decrypt_int');
                $comprobante  = $this->class_security->upload_document('comprobante','_files/','jpeg|jpg|png|pdf|docx');

                if(strlen($comprobante) >= 10) {
                    //procesar
                    $this->result = $this->general->create('servicios_comprobante',
                        [
                            'st_usuario'     => $this->user_data->u_id,
                            'st_servicio'    => $id,
                            'st_comprobante' => $comprobante,
                            'st_tipo'        => 2,
                            'st_atcreate' => fecha(2),
                        ]);
                }
                else{
                    $this->result = array('success' => 2,'msg' => 'Validar el comprobante');
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
            1 => 's.sv_titulo',
            2 => 's.sv_valor',
        );

        //tabla
        $tabla =  'servicios As s';

        //join
        $join   = array(
            1 =>  [ 'tabla' => 'servicios_proveedores As sp', 'join' => 's.sv_proveedor=sp.sp_id'],
            2 =>  [ 'tabla' => 'servicios_categorias As sc', 'join' => 's.sv_categoria=sc.sc_id'],
        );

        //validar si es un usuario o administrador
        $where_data = [
            'sp.sp_usuario' => $this->user_data->u_id
        ];

        $where_in = [];


        $dataget         = $this->datatable->getdata($filter,$columnas,$where_data,$where_in,$tabla,$join);
        $total_registros = $this->datatable->totalData($valor,$columnas,$where_data,$where_in,$tabla,$join);

        foreach($dataget as $rows){
            $id       = encriptar($rows->sv_id);
            $estado   = $this->class_security->array_data($rows->sv_estado,$this->class_data->estado_pago_simplificado,$this->class_data->estado_default);

            $data[]= array(
                $rows->sc_nombre,
                '₡ '.$this->class_security->dinero($rows->sv_valor),
                $rows->sv_fecha,
                " <div class='btn-group'><button type='button' onclick='$(this).forms_modal({\"page\" : \"servicios_factura\",\"data1\" : \"{$id}\",\"title\" : \"Factura del servicio\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>",
                " <div class='btn-group'><button type='button' onclick='$(this).forms_modal({\"page\" : \"servicios_comprobante\",\"data1\" : \"{$id}\",\"title\" : \"Comprobante\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>",
                "<button class='{$estado['class']}'>{$estado['title']}</button>",
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