<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Deuda extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Deuda';
    private $controller   = 'deuda/';
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

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/modals.js','cr/deuda.js','plugins/numero/AutoNumeric.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_deuda',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }


    function cambio_estado(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id','estado'))){

                //campos post
                $id             = $this->class_security->data_form('id','decrypt_int');
                $estado         = $this->class_security->data_form('estado');

                //procesar
                //agregar cuota extraordinarioa
                if($this->general->exist('deuda_pagos',array('dp_id' => $id))){
                    $resultado =   $this->general->update('deuda_pagos',array('dp_id' => $id),array('pd_estado' => $estado));
                    $this->result = $resultado;
                }else{
                    $this->result = array('success' => 2,'msg' => 'No se encontro la deuda');
                }


            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }


    function save(){
//
        if($this->input->post()){
            if($this->class_security->validate_post(array('apartamento','nombre','saldo','n_pagos','fecha_cobro','estado'))){
//
//                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $apt            = $this->class_security->data_form('apartamento','int');
                $nombre         = $this->class_security->data_form('nombre');
                $saldo          = $this->class_security->data_form('saldo','saldo');
                $favor          = $this->class_security->data_form('saldo_favor','saldo');
                $n_pagos        = $this->class_security->data_form('n_pagos','saldo');
                $fecha_cobro    = $this->class_security->letras_numeros_espace($this->class_security->data_form('fecha_cobro'));
                $observacion    = $this->class_security->data_form('observacion');
                $estado         = $this->class_security->data_form('estado');



                //crear o actualizar  deuda
                $add_numero      = $this->class_security->data_form('add_numero');
                $add_saldo       = $this->class_security->data_form('add_saldo','saldo');
                $add_fecha       = $this->class_security->data_form('add_fecha');
                $add_observacion       = $this->class_security->data_form('add_observacion');

                 if(strlen($id) == 0){
                        $data = array(
                            'du_apartamento'     =>  $apt,
                            'du_nombre'          => $nombre,
                            'du_saldo'           => $saldo,
                            'du_n_pagos'         => $n_pagos,
                            'du_fecha_inicio'    => $fecha_cobro,
                            'du_observacion'     => $observacion,
                            'du_estado'          => $estado,
                            'du_atcreate'        => fecha(2)
                        );
                    }else{
                        $data = array(
                            'du_nombre'          => $nombre,
//                            'du_saldo'           => $saldo,
                            'du_n_pagos'         => $n_pagos,
                            'du_fecha_inicio'    => $fecha_cobro,
                            'du_observacion'     => $observacion,
                            'du_estado'          => $estado,
                        );
                    }


                    $resultado =   $this->general->create_update('deuda',array('du_id' => $id),$data);
                    $id_r = $resultado['data'];

                    if(isset($id_r) and $id_r != '' and $add_numero != '' and ($favor > 0 or $add_saldo > 0)){

                        $deudas        =    [encriptar($id_r)];

                        $hora = hora();
                        $fecha_pago = "$add_fecha $hora";
                        $comprobante  = $this->class_security->upload_document('comprobante','_files/','jpeg|jpg|png|doc|docx|pdf');

                        //validar si tiene saldo a favor
                        $this->load->model('m_pagos','pagos');
                        $this->result = $this->pagos->pagos($this->user_data,$apt,$deudas,$add_saldo,$favor,4,$comprobante,$add_numero,$fecha_pago,$add_observacion);


                    }else{
                        $this->result = array('success' => 1);
                    }


                //                    $add_numero      = $this->class_security->data_form('add_numero');
//                    $add_saldo       = $this->class_security->data_form('add_saldo','saldo');
//                    $add_fecha       = $this->class_security->data_form('add_fecha');
//                    $add_observacion       = $this->class_security->data_form('add_observacion');
//                    $hora = hora();





            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
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
            )  ;
        }
        return $mm;
    }


    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('deuda',array('du_id' => $id))){
                        $this->result =  $this->general->delete('deuda',array('du_id' => $id));
                        $this->result =  $this->general->delete('apartamento_pagos',array('pg_id_cuota_extraordinaria' => $id,'pg_tipo_deuda' => 5));
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
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
        }
        $data = array();


        $consulta_primary = "SELECT d.du_apartamento,a.t_apartamento,d.du_estado,SUM(d.du_saldo) As deuda,COALESCE(SUM(dpagos),0) As saldo FROM apartamento AS a
INNER JOIN deuda AS d ON a.t_id=d.du_apartamento
LEFT JOIN  (
	SELECT dp.pd_deuda,SUM(dp.pd_monto) AS dpagos FROM deuda_pagos AS dp WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
	) AS t ON d.du_id=t.pd_deuda
#WHERE d.du_estado IN(1,2)
WHERE   a.t_apartamento LIKE '%".$valor."%'
GROUP BY d.du_apartamento,a.t_apartamento,d.du_estado
HAVING saldo < saldo OR du_estado IN(1,2)";



        $dataget         = $this->general->query("{$consulta_primary}  LIMIT $start,$length",'obj');
        $query_count = $this->general->query($consulta_primary);
        $total_registros = count($query_count);

        foreach($dataget as $rows){
            $id              = encriptar($rows->du_apartamento);
            $apartamento     = $this->class_security->decodificar($rows->t_apartamento);
            $deuda           = $rows->deuda;
            $pago           = $rows->saldo;
            $saldo           = ($deuda-$pago);

            $data[]= array(
                $apartamento,
                "<b>₡ ". $this->class_security->dinero($deuda)."</b>",
                "<b>₡ ". $this->class_security->dinero($pago)."</b>",
                "<b>₡ ". $this->class_security->dinero($saldo)."</b>",
                "<div class='btn-group'><button type='button'onclick='$(this).forms_modal({\"page\" : \"admin_deuda_lista\",\"data1\" : \"{$id}\",\"title\" : \"Dueda\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>"
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