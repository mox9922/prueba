<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Propiedades_reserva extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'propiedades reserva';
    private $controller   = 'propiedades_reserva/';
    private $controller_filial   = 'afiliales/';
    private $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth('filial');


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
            'controller'    => $this->controller,
            'user'          => $this->user_data,
            'style_level'   => array('libs/fullcalendar/dist/fullcalendar.min.css','extra-libs/calendar/calendar.css')

        );

        $data_menu = array();

        $data_body = array(
            'dataresult' => $this->general->all_get('propiedades',array('p_estado' => 1),array('RAND()','')),
            'vigilantes' => $this->general->all_get('usuarios',array('u_perfil' => 3,'u_estado' => 1)),
            'afiliales'  => $this->general->all_get('usuarios',array('u_apartamento' => $this->user_data->u_apartamento,'u_id != ' => $this->user_data->u_id)),


            'crud' => array(
                'url_modals'  => base_url("modal/"),
                'url_propiedad_get'      => base_url("{$this->controller}propiead_data"),
                'url_reserva_data'      => base_url("{$this->controller}reserva_data"),
                'url_get_all'      => base_url("{$this->controller}get_all_reserva"),
                'url_save'          => base_url("{$this->controller}save_reserva"),
                'url_update_save'      => base_url("{$this->controller}update_reserva"),
                'url_search_input'      => base_url("{$this->controller}search_input"),
                'url_delete_reserva'      => base_url("{$this->controller}delete"),
                'url_delete'      => base_url("{$this->controller}delete_reserva"),

                'url_save_comprobante'     => base_url("{$this->controller_filial}save_comprobante"),


                'url_save_deuda'        => base_url("{$this->controller_filial}save_deuda"),

                'url_aflial_save'       => base_url("{$this->controller_filial}condomino_save"),
                'url_aflial_delete'     => base_url("{$this->controller_filial}condomino_delete"),

                //vehiculo
                'url_save_vehiculo'     => base_url("{$this->controller_filial}save_vehiculo"),
                'url_dell_vehiculo'     => base_url("{$this->controller_filial}dell_vehiculo"),

                //mascotas
                'url_save_mascotas'     => base_url("{$this->controller_filial}save_mascotas"),
                'url_get_mascotas_all'  => base_url("{$this->controller_filial}get_all_mascotas"),
                'url_get_mascotas'      => base_url("{$this->controller_filial}get_mascotas"),
                'url_dell_mascotas'     => base_url("{$this->controller_filial}dell_mascotas"),

            )
        );

        $data_foot = array('script_level' => array('cr/crud_data.js','plugins/numero/AutoNumeric.js','libs/fullcalendar/dist/fullcalendar.min.js','libs/fullcalendar/dist/locale/es.js','cr/propiedades_reserva.js','cr/afiliales.js'));


        $this->load->view('template/alt/v_header',$data_head);
        $this->load->view('propiedades_reserva/v_propieddades_reserva',$data_body);
        $this->load->view('template/alt/v_footer',$data_foot);
    }

    function get_all_reserva(){

        if($this->input->post()){

                //campos post
                $data=  [];
                $fecha1 = fecha(1);
                $fecha2 = $this->class_security->sumar_meses(20);

                    //procesar
                    $reservas = $this->general->query("select * from propiedades As p JOIN reserva_dias As rd ON p.p_id=rd.rd_propiedad WHERE DATE_FORMAT(rd.rd_fecha1,'%Y-%m-%d') BETWEEN '{$fecha1}' AND  '{$fecha2}'",'json');



                    foreach($reservas As $re){
                        $f_1 = $this->class_security->fecha_formato($re->rd_fecha1,"g:i:s a");
                        $f_2 = $this->class_security->fecha_formato($re->rd_fecha2,"g:i:s a");

                        $data[] = array(
                            'id'    => encriptar($re->rd_id),
                            'title' => "$re->p_nombre [R] {$f_1}-{$f_2}" ,
                            'start' => $re->rd_fecha1,
                            'end'   => $re->rd_fecha2,
                            'color'=> ($re->rd_estado == 1) ? '#ff8040' : $re->p_color
                        );
                    }

//                    $data_join = array_merge($data,$this->get_all_reserva_espera());
                    $this->result = array('success' => 1,'data' =>   $data);


        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function propiead_data(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                //campos post
                $id  = $this->class_security->data_form('id','decrypt_int');

                if($this->general->exist('propiedades',array('p_id' => $id))) {
                    //procesar
                    $propiedad = $this->general->get('propiedades',['p_id' => $id]);


                    $this->result = array('success' => 1,'data' =>   $propiedad);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Algo salio mal con la propiedad');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function reserva_data(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                //campos post
                $id  = $this->class_security->data_form('id','decrypt_int');

                if($this->general->exist('reserva_dias',array('rd_id' => $id))) {
                    //procesar
                    $reservas = $this->general->all_get_join('reserva_dias As r',[['propiedades As p','r.rd_propiedad=p.p_id']],array('r.rd_id' => $id),[],'unique','json');
                    foreach($reservas As $re){
                        $start = strtotime($re->rd_fecha1);
                        $end = strtotime($re->rd_fecha2);


                        $data= array(
                            'id'    => encriptar($re->rd_id),
                            'titulo' => $re->p_nombre,
                            'estado' => $re->rd_estado,
                            'fecha'  => substr($re->rd_fecha1,0,10),
                            'start'  => date('g:i A',$start),
                            'end'    =>  date('g:i A',$end),
                            'mod'    => ($re->rd_apartamento == $this->user_data->u_apartamento) ? 1 : 2,

                        );
                    }

                    $this->result = array('success' => 1,'data' =>   $data);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Algo salio mal con la propiedad');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function save_reserva(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('propiedad','inicial','salida'))){

                //campos post
                $propietario    = $this->user_data->u_id;
                $apartamento    = $this->user_data->u_apartamento;
                $id             = $this->class_security->data_form('propiedad','decrypt_int');
                $nombre         = $this->class_security->data_form('nombre');
                $telefono       = $this->class_security->data_form('telefono');
                $correo         = $this->class_security->data_form('correo');
                $fecha          = $this->class_security->data_form('fecha','alone');
                $asistencia     = $this->class_security->data_form('asistencia');
                $inicial        = $this->class_security->data_form('inicial');
                $salida         = $this->class_security->data_form('salida');


                $documento  = $this->class_security->upload_document('comprobante','_files/','jpeg|jpg|png|pdf|docx|doc');

                if($this->general->exist('propiedades',array('p_id' => $id)) and $documento != '') {

                    //validar si existen dias diferentes

                    $exist_fecha = $this->validar_dias($fecha,$id);
                    if(count($exist_fecha) >= 1){
                        $this->result = array('success' => 3,'msg' => 'Estimado condómino las horas que está tratando ingresar ya fueron reservadas, por favor tratar con otro horario','data' => $exist_fecha);
                    }else{

                        $pop = $this->general->get('propiedades',array('p_id' => $id));

                        $calculo = $pop->p_valor*count($fecha);

                            //procesar
                            $data = array(
                                'r_usuario'         => $propietario,
                                'r_apartamento'     => $apartamento,
                                'r_propiedad'       => $id,
                                'r_nombre'          => $nombre,
                                'r_telefono'        => $telefono,
                                'r_correo'          => $correo,
                                'r_asistencia'      => $asistencia,
                                'r_fecha_inicio'    => $inicial,
                                'r_fecha_salida'    => $salida,
                                'r_estado'          => 1,
                                'r_valor'           => $calculo,
                                'r_comprobante'     => $documento,
                                'r_atcreate'        => fecha(2),
                            );

                            $result = $this->general->create('reserva',  $data);
                            $idin = $result['id'];


                            foreach($fecha as $f){
                                $ff_fecha = $f['fecha'];
                                $ff_hora1 =  date("H:i", strtotime($f['inicial']));
                                $ff_hora2 =  date("H:i", strtotime($f['final']));


                               $cr = $this->general->create('reserva_dias',
                                    [
                                        'rd_usuario'        => $propietario,
                                        'rd_apartamento'    => $apartamento,
                                        'rd_reserva'        => $idin,
                                        'rd_propiedad'      => $id,
                                        'rd_fecha1'         => "{$ff_fecha} $ff_hora1",
                                        'rd_fecha2'         => "{$ff_fecha} $ff_hora2",

                                    ]);
                            }

                            $id = $cr['id'];

                            $this->generico->create_mail($id,'reserva','create');

//                            $mailer = new Mailer();
//                            $config = array(
//                                'to' => $this->user_data->u_correo1,
//                                'subject' => 'Se Creo la Reserva Reserva',
//                                'msg' => 'Reserva Creada el estado en proceso de aprovacion',
//                                'email' => $this->config->config['project']['email']['ticket']
//                            );
//                            $mailer->initialize($config);
//                            $mailer->send_mail();

                        $this->result = array('success' => 1);

                    }


                }else{
                    $this->result = array('success' => 2,'msg' => 'Algo salio mal con la propiedad');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function validar_dias($fechas,$propiedad){

        $data = [];
        foreach($fechas as $f){
            $ff_fecha = $f['fecha'];
            $f1 =  $ff_fecha.' '.$f['inicial'];
            $f2 =  $ff_fecha.' '.$f['final'];
            $fecha1 =  date("Y-m-d H:i", strtotime($f1));
            $fecha2 =  date("Y-m-d H:i", strtotime($f2));

            $result = $this->general->query("SELECT * FROM reserva_dias AS r WHERE r.rd_fecha1 <= '$fecha1' AND r.rd_fecha2  >= '$fecha2'
                                   AND r.rd_propiedad='$propiedad' OR r.rd_fecha1 BETWEEN '$fecha1' AND '$fecha2' 
                                   AND r.rd_propiedad='$propiedad' OR  r.rd_fecha2 BETWEEN '$fecha1' AND '$fecha2'
                                   AND r.rd_propiedad='$propiedad'",'arr');

            if(count($result) >= 1){
                $data[] = $ff_fecha;
            }
        }


        return $data;
    }

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                $this->user_data->u_apartamento;
                if(strlen($id) >= 1){
                    $where = [
                        'r_id' => $id,
                        'r_apartamento' => $this->user_data->u_apartamento,
                    ];
                    if($this->general->exist('reserva',$where)){
                        $this->result =  $this->general->delete('reserva',$where);
                        $this->general->delete('reserva_dias',array('rd_reserva' => $id,'rd_apartamento' => $this->user_data->u_apartamento,));
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


    function delete_reserva(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                //campos post
                $apartamento    = $this->user_data->u_apartamento;
                $id             = $this->class_security->data_form('id','decrypt_int');


                if($this->general->exist('reserva_dias',array('rd_id' => $id,'rd_estado' => 1,'rd_apartamento' => $apartamento))) {


                    $this->general->delete('reserva_dias',array('rd_id' => $id,'rd_estado' => 1,'rd_apartamento' => $apartamento));
                    $this->result = array('success' => 1);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Algo salio mal con la propiedad');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function update_reserva(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','fecha','inicial','salida'))){

                //campos post
                $apartamento    = $this->user_data->u_apartamento;
                $id             = $this->class_security->data_form('data_id','decrypt_int');
                $fecha          = $this->class_security->data_form('fecha','alone');
                $inicial        = $this->class_security->data_form('inicial');
                $salida         = $this->class_security->data_form('salida');

                $ff_hora1 =  date("H:i", strtotime($inicial));
                $ff_hora2 =  date("H:i", strtotime($salida));


                if($this->general->exist('reserva_dias',array('rd_id' => $id))) {

                    $this->general->update('reserva_dias',[
                        'rd_id' => $id,
                        'rd_apartamento' => $apartamento,
                        'rd_estado' => 1
                    ],
                        [
                            'rd_fecha1'          => "{$fecha} $ff_hora1",
                            'rd_fecha2'          => "{$fecha} $ff_hora2",

                        ]);
                    $this->result = array('success' => 1);

                }else{
                    $this->result = array('success' => 2,'msg' => 'Algo salio mal con la propiedad');
                }
            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }



}