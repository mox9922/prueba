<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Servicios extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Proveedores';
    private $controller   = 'servicios/';
    private $controller_generic   = 'Generic/';
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
            'dataresult' => $this->general->all_get('apartamento',array('t_estado' =>1)),
            'perfil'     => $id,
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'url_datatable' => base_url("{$this->controller}datatable"),

                'url_usuarios_save'      => base_url("usuarios/save"),
                'url_usuarios_delete'    => base_url("usuarios/delete"),

                'url_categoria_get'       => base_url("{$this->controller}categoria_get"),
                'url_categoria_get'       => base_url("{$this->controller}categoria_get"),
                'url_categoria_save'      => base_url("{$this->controller}categoria_save"),
                'url_categoria_delete'    => base_url("{$this->controller}categoria_delete"),

                'url_proveedor_get'       => base_url("{$this->controller}proveedor_get"),
                'url_proveedor_save'      => base_url("{$this->controller}proveedor_save"),
                'url_proveedor_delete'    => base_url("{$this->controller}proveedor_delete"),

                'url_comprobante_save'    => base_url("{$this->controller}comprobador_save"),
                'url_delete_comprobante'    => base_url("{$this->controller}delete_comprobante"),
            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/servicios.js','plugins/numero/AutoNumeric.js'));

        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_servicios',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('categoria','proveedor','nombre','monto','fecha','estado'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $categoria    = $this->class_security->data_form('categoria','int');
                $proveedor    = $this->class_security->data_form('proveedor','int');
                $nombre         = $this->class_security->data_form('nombre');
                $saldo          = $this->class_security->data_form('monto','saldo');
                $estado         = $this->class_security->data_form('estado');
                $fecha         = $this->class_security->data_form('fecha');
                $concurrencia         = $this->class_security->data_form('concurrencia');

                //procesar
                    $data = array(
                        'sv_categoria'       => $categoria,
                        'sv_proveedor'       => $proveedor,
                        'sv_titulo'          => $nombre,
                        'sv_valor'           => $saldo,
                        'sv_estado'          => $estado,
                        'sv_fecha'           => $fecha,
                        'sv_recurrente'      => $concurrencia,
                        'sv_atcreate'        => fecha(2),
                    );
                    $this->result =   $this->general->create_update('servicios',array('sv_id' => $id),$data);


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
                    if($this->general->exist('servicios',array('sv_id' => $id))){
                        $resultado =   $this->general->get('servicios',array('sv_id' => $id));
                        $resultado_data = array(
                            'id'           => encriptar($resultado->sv_id),
                            'categoria'    => $this->class_security->decodificar($resultado->sv_categoria),
                            'proveedor'    => $this->class_security->decodificar($resultado->sv_proveedor),
                            'titulo'       => $this->class_security->decodificar($resultado->sv_titulo),
                            'monto'        => $resultado->sv_valor,
                            'estado'       => $resultado->sv_estado,
                            'fecha'        => $resultado->sv_fecha
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

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('servicios',array('sv_id' => $id))){
                        $this->result =  $this->general->delete('servicios',array('sv_id' => $id));
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

    //categoria
    function categoria_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre'))){
                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                //procesar
                $this->result =   $this->general->create_update('servicios_categorias',array('sc_id' => $id),['sc_nombre' => $nombre]);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function categoria_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('servicios_categorias',array('sc_id' => $id))){
                        $this->result =  $this->general->delete('servicios_categorias',array('sc_id' => $id));
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


    //categoria
    function proveedor_save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre'))){
                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $correo         = $this->class_security->data_form('correo');
                $telefono       = $this->class_security->data_form('telefono');
                $tipo           = $this->class_security->data_form('tipo');
                $documento      = $this->class_security->data_form('documento');
                //procesar
                $this->result =   $this->general->create_update('servicios_proveedores',array('sp_id' => $id),
                    [
                        'sp_nombre' => $nombre,
                        'sp_correo' => $correo,
                        'sp_telefono' => $telefono,
                        'sp_tipo_documento' => $tipo,
                        'sp_documento' => $documento,
                        'sp_atcreate' => fecha(2),
                    ]);

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function proveedor_delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('servicios_proveedores',array('sp_id' => $id))){
                        $this->result =  $this->general->delete('servicios_proveedores',array('sp_id' => $id));
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

    function delete_comprobante(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('servicios_comprobante',array('st_id' => $id))){
                        $this->result =  $this->general->delete('servicios_comprobante',array('st_id' => $id));
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

    //comprobante
    function comprobador_save(){

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
                            'st_tipo'        => 1,
                            'st_atcreate'    => fecha(2),
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
            3 => 'c.sc_nombre',
        );

        //tabla
        $tabla =  'servicios As s';

        //join
        $join   = array(
            array('tabla' =>'servicios_categorias As c','join' => 's.sv_categoria=c.sc_id'),
            array('tabla' =>'servicios_proveedores As p','join' => 's.sv_proveedor=p.sp_id'),
            array('tabla' =>'usuarios As u','join' => 'p.sp_usuario=u.u_id'),
        );

        $where_in        = array();
        $dataget         = $this->datatable->getdata($filter,$columnas,[],$where_in,$tabla,$join);
        $total_registros = $this->datatable->totalData($valor,$columnas,[],$where_in,$tabla,$join);

        foreach($dataget as $rows){
            $id              = encriptar($rows->sv_id);
            $estado          = $this->class_data->estado_pago_simplificado[$rows->sv_estado];
            $concurrencia          = $this->class_data->estado[$rows->sv_recurrente];

            $data[]= array(
                $rows->sc_nombre,
                $rows->u_nombre,
                $rows->sv_titulo,
                "<b>₡ ". $this->class_security->dinero($rows->sv_valor)."</b>",
                $rows->sv_fecha,
                " <div class='btn-group'><button type='button' onclick='$(this).forms_modal({\"page\" : \"servicios_factura\",\"data1\" : \"{$id}\",\"title\" : \"Factura del servicio\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>",
                "<button type='button' onclick='$(this).forms_modal({\"page\" : \"servicios_comprobante\",\"data1\" : \"{$id}\",\"title\" : \"Servicios Comprobantes\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class=' fas fa-file-pdf text-white'></i></button>",
                "<button class='{$concurrencia['class']}'>{$concurrencia['title']}</button>",
                "<button class='{$estado['class']}'>{$estado['title']}</button>",
                "<div class='btn-group'><button type='button' onclick='$(this).forms_modal({\"page\" : \"servicios\",\"data1\" : \"{$id}\",\"title\" : \"Servicios\"})'  class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-eye text-white'></i></button>
                 <button type='button' onclick='$(this).dell_data(\"{$id}\",\"url_delete\")' class='btn btn-danger waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Eliminar'><i class='fa fa-times text-white'></i></button></div>"
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