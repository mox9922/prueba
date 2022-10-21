<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Depositos extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Depositos Sin Identificcados';
    private $controller   = 'depositos/';
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

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/modals.js','cr/depositos.js','plugins/numero/AutoNumeric.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_depositos',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    function save(){
//
        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','saldo','fecha','estado'))){
//
//                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $apt            = $this->class_security->data_form('apartamento','int');
                $nombre         = $this->class_security->data_form('nombre');
                $saldo          = $this->class_security->data_form('saldo','saldo');
                $fecha          = $this->class_security->data_form('fecha');
                $observacion    = $this->class_security->data_form('observacion');
                $estado         = $this->class_security->data_form('estado');
                $preimg         = $this->class_security->upload_document('comprobante','_files/','gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|ppt|pptx');
                $comprobante    = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $this->general->nique_value('depositos','dp_comprobante',array('dp_id' => $id)) : '');
                if ($comprobante  == ''){
                    $this->result = array('success' => 2,'msg' => 'Comprobante Obligatorio');
                }else{

                    if(strlen($id) == 0){
                        $data = array(
                            'dp_usuario'         => $this->user_data->u_id,
                            'dp_apartamento'     => $apt,
                            'dp_titulo'          => $nombre,
                            'dp_saldo'           => $saldo,
                            'dp_comprobante'     => $comprobante,
                            'dp_observacion'     => $observacion,
                            'dp_estado'          => $this->estado($apt,$estado),
                            'dp_fecha'           => $fecha,
                            'dp_atcreate'        => fecha(2)
                        );
                    }else{
                        $data = array(
                            'dp_titulo'          => $nombre,
                            'dp_apartamento'     => $apt,
                            'dp_saldo'           => $saldo,
                            'dp_comprobante'     => $comprobante,
                            'dp_observacion'     => $observacion,
                            'dp_estado'          => $this->estado($apt,$estado),
                            'dp_fecha'           => $fecha,
                        );
                    }


                    if($this->estado($apt,$estado) == 2){
                        $this->aplicar_saldo_favor($apt,$saldo);
                    }

                    $this->result = $this->general->create_update('depositos',['dp_id' => $id],$data);
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function estado($filial = '',$estado = ''){
        $result = 1;
        if($this->general->exist('apartamento',['t_id' => $filial]) and $estado == 2){
            $result = $estado;
        }
        return $result;
    }

    private function aplicar_saldo_favor($apt,$monto){
        $this->consultas->agregar_saldo_a_favor($apt,$monto);
    }

    private function regresar_saldo_favor($apt,$monto,$saldo_favor){
        //validar si el saldo a favor actual es igual al monto a regresar
        if($saldo_favor >= $monto) {
            $this->consultas->tomar_saldo_a_favor($apt, $monto);//se toma el saldo a favor que se requiere para pagar la deuda
        }
    }

    private function validar_regreso_saldo($apt,$monto,$saldo_favor,$estador,$estado){
        if($estador == 2){
            $this->regresar_saldo_favor($apt,$monto,$saldo_favor);
        }

//        if($estado == 1){
//            $this->regresar_saldo_favor($apt,$monto,$saldo_favor);
//        }else{
//
//        }
    }

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id','estado'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                $estado = $this->class_security->data_form('estado','int');
                if(strlen($id) >= 1 AND in_array($estado,array(1,2))){
                    if($this->general->exist('depositos',array('dp_id' => $id))){


                        //se obtiene el deposito actual
                        $deposito = $this->general->query("select dp.dp_id,dp.dp_saldo,dp.dp_estado,dp.dp_apartamento,COALESCE(sf.sf_saldo,0) As favor from depositos As dp
    LEFT JOIN saldo_favor As sf ON dp.dp_apartamento=sf.sf_apartamento WHERE dp.dp_id='".$id."'",'obj');

                        $data = $deposito['0'];

                        $apt = $data->dp_apartamento;
                        $saldo_favor = $data->favor;
                        $saldo = $data->dp_saldo;
                        $estador = $data->dp_estado;

                        //se valida la operacion a realizar
                        if($estado == 1){
                            //Se desea retroceder el saldo del deposito
                            $this->validar_regreso_saldo($apt,$saldo,$saldo_favor,$estador,1);
                            $this->general->update('depositos',array('dp_id' => $id),['dp_estado' => 1]);
                        }else{
                            $this->validar_regreso_saldo($apt,$saldo,$saldo_favor,$estador,2);//regresar saldo a favor y eliminar
                            //se elimina el deposito
                            $this->general->delete('depositos',array('dp_id' => $id));
                        }

                        $this->result = array('success' => 1);

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

    //datatable
    function datatable(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
            $dato      = $this->input->post("dato");
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
            $dato = '';
        }
        $data = array();


        $consulta_primary = "select dp.*,a.t_apartamento from depositos As dp  LEFT JOIN apartamento As a ON dp_apartamento = a.t_id
WHERE   dp.dp_estado = '".$dato."' AND (dp.dp_titulo LIKE  '%".$valor."%' OR dp.dp_saldo LIKE '%".$valor."%' OR  a.t_apartamento LIKE '%".$valor."%')";



        $dataget         = $this->general->query("{$consulta_primary} LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);

        foreach($dataget as $rows){
            $id              = encriptar($rows->dp_id);
            $apartamento     = $this->class_security->decodificar($rows->t_apartamento);
            $titulo           = $rows->dp_titulo;
            $saldo            = $rows->dp_saldo;
            $comprobante      = $this->class_security->url_documento($rows->dp_comprobante);
            $estado          = $this->class_security->array_data($rows->dp_estado,$this->class_data->estado_deposito);
            $fecha            = $rows->dp_atcreate;

            $data[]= array(
                $apartamento,
                $titulo,
                "<b>₡ ". $this->class_security->dinero($saldo)."</b>",
                $comprobante,
                "<button class='{$estado['class']}'>{$estado['title']}</button>",
                substr($fecha,0,10),
                "<div class='btn-group'>
                 <button type='button' onclick='$(this).forms_modal({\"page\" : \"admin_deposito\",\"data1\" : \"{$id}\",\"title\" : \"Depositos Sin Identificcados\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data_preguntas(\"{$id}\",\"url_delete\")' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></div>"
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