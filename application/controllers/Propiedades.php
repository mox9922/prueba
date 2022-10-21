<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Propiedades extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Propiedades';
    private $controller   = 'propiedades/';
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

    function index($id = ''){

        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array(
                'plugins/@claviska/jquery-minicolors/jquery.minicolors.css'
            )

        );

        $data_menu = array();

        $data_body = array(
            'dataresult' => $this->general->all_get('usuarios',array('u_perfil' => $id)),
            'perfil'     => $id,
            'crud' => array(
                'url_modals'    => base_url("modal/"),
                'url_get'       => base_url("{$this->controller}get"),
                'url_save'      => base_url("{$this->controller}save"),
                'url_delete'    => base_url("{$this->controller}delete"),
                'url_datatable' => base_url("{$this->controller}datatable"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','cr/propiedades.js','plugins/numero/AutoNumeric.js',
            'plugins/jquery-asColor/dist/jquery-asColor.min.js',
//            'plugins/jquery-asGradient/dist/jquery-asGradient.js',
            'plugins/@claviska/jquery-minicolors/jquery.minicolors.min.js'
        ));


        $this->load->view('template/v_header',$data_head);
        $this->load->view('template/v_menu',$data_menu);
        $this->load->view('admin/v_propiedades',$data_body);
        $this->load->view('template/v_footer',$data_foot);
    }

    //method crud
    function save(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','precio','cantidad','descripcion','estado','color'))){

                //campos post
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $precio         = $this->class_security->solo_numerico($this->class_security->data_form('precio'));
                $descripcion    = $this->class_security->data_form('descripcion');
                $estado         = $this->class_security->data_form('estado','int');
                $cantidad       = $this->class_security->data_form('cantidad','int');
                $color         = $this->class_security->data_form('color','str');


                $preimg  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png');
                $imagen_old = $this->general->nique_value('propiedades','p_imagen',array('p_id' => $id));
                $imagen = (strlen($preimg) >= 11) ? $preimg : ((strlen($id) >= 1 )  ? $imagen_old : 'default.jpg');

                $imagen1  = $this->class_security->upload_document('imagen1','_files/','jpeg|jpg|png');
                $imagen2  = $this->class_security->upload_document('imagen2','_files/','jpeg|jpg|png');
                $imagen3  = $this->class_security->upload_document('imagen3','_files/','jpeg|jpg|png');
                $imagen4  = $this->class_security->upload_document('imagen4','_files/','jpeg|jpg|png');


                //validar si existe propiedad creada
                if($this->general->exist('propiedades',['p_id' => $id])){
                    //validar informacion de imagenes
                    $pre = $this->general->get('propiedades',['p_id' => $id]);
                    $imagen1 = (strlen($imagen1) >= 10)  ? $imagen1 : $pre->p_imagen1;
                    $imagen2 = (strlen($imagen2) >= 10)  ? $imagen2 : $pre->p_imagen2;
                    $imagen3 = (strlen($imagen3) >= 10)  ? $imagen3 : $pre->p_imagen3;
                    $imagen4 = (strlen($imagen4) >= 10)  ? $imagen4 : $pre->p_imagen4;
                }

                //resize image
                if($imagen != 'default.jpg'){
                    $imagen_min = "_files/min/{$imagen}";
                        if(!file_exists($imagen_min)){
                            $this->class_security->imageresize(str_replace('min/','',$imagen_min),'_files/min/',460,300);
                        }
                }

                //eliminar si el archivo que se cambia es diferente al que se va a subir
                if($imagen_old != 'default.jpg'  and $imagen_old != $imagen){
                    $this->class_security->eliminar_archivo('_files/',$imagen_old);
                    $this->class_security->eliminar_archivo('_files/min/',$imagen_old);
                }


                //procesar
                $data = array(
                    'p_nombre'          => $nombre,
                    'p_descripcion'     => $descripcion,
                    'p_valor'           => $precio,
                    'p_cantidad'           => $cantidad,
                    'p_imagen'          => $imagen,
                    'p_imagen1'          => $imagen1,
                    'p_imagen2'          => $imagen2,
                    'p_imagen3'          => $imagen3,
                    'p_imagen4'          => $imagen4,
                    'p_color'          => $color,
                    'p_estado'          => $estado,
                );

                $this->result =   $this->general->create_update('propiedades',array('p_id' => $id),$data);
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
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
                    if($this->general->exist('propiedades',array('p_id' => $id))){

                        $imagen = $this->general->nique_value('propiedades','p_imagen',array('p_id' => $id));

                        //eliminar si el archivo que se cambia es diferente al que se va a subir

                        if($imagen != 'default.jpg' and $imagen != ''){
                            $this->class_security->eliminar_archivo('_files/',$imagen);
                        }

                        $this->result =  $this->general->delete('propiedades',array('p_id' => $id));
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
            0 => 'p_nombre'
        );

        //tabla
        $tabla =  'propiedades';

        //join
        $join   = array();

        $where_in        = array();
        $dataget         = $this->datatable->getdata($filter,$columnas,[],$where_in,$tabla,$join);
        $total_registros = $this->datatable->totalData($valor,$columnas,[],$where_in,$tabla,$join);

        foreach($dataget as $rows){
            $id             = encriptar($rows->p_id);
            $nombre         = $this->class_security->decodificar($rows->p_nombre);
            $valor          = $this->class_security->dinero($rows->p_valor);
            $imagen         = base_url("_files/{$rows->p_imagen}");
            $btn            = $this->class_security->array_data($rows->p_estado,$this->class_data->estado_simple,$this->class_data->estado_default);

            $data[]= array(
                "<a class='single_image' href='{$imagen}'><img class='rounded-circle' src='{$imagen}' style='height: 40px;width: 40px;'></a>
                <script>
                    $('.single_image').fancybox({
                        'zoomSpeedIn': 300,
                        'zoomSpeedOut': 300,
                        'overlayShow': false
                    });
                </script>
                ",
                $nombre,
                '₡ '.$valor,
                "<button type='button' class='{$btn['class']}'>{$btn['title']}</button>",
                "<div class='btn-group'><button type='button'onclick='$(this).forms_modal({\"page\" : \"propiedades\",\"data1\" : \"{$id}\",\"title\" : \"Propiedades\"})' class='btn btn-primary waves-effect waves-light'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil-alt text-white'></i></button>
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