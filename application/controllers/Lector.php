<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Lector extends CI_Controller{

    //propiedades
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'lector';
    private $controller   = 'lector/';
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
        $this->load->library(['Mailer','Timer']);
        $timer = new Timer();
    }


    function search_propiedades(){
        $dataR = [];

        if($this->input->post()){
            if($this->class_security->validate_post(array('data'))){
                $data = $this->class_security->data_form('data');
                if(strlen($data) >= 1){

                    $busqueda =  $this->general->query("SELECT DISTINCT * FROM apartamento WHERE t_apartamento LIKE '%".$data."%'");

                    foreach($busqueda as $apt){
                        $id = encriptar($apt['t_id']);
                        $letra = $apt['t_apartamento'];


                        $dataR[] = array(
                            'id'    => $id,
                            'letra' => $letra,
                        );
                    }


                }

            }
        }
        $this->result = array('success' => '1','data' => $dataR);

        api($this->result);
    }

    function valdiar_acceso(){

        if($this->input->post()){
            if($this->class_security->validate_post(array('nombre','documento','filial'))){

                $id          = $this->class_security->data_form('id','decrypt_int');
                $tipo        = $this->class_security->data_form('tipo','int');
                $nombre      = $this->class_security->data_form('nombre');
                $documento   = $this->class_security->data_form('documento');
                $filial      = $this->class_security->data_form('filial');
                $placa       = $this->class_security->data_form('placa');
                $tipo_ing    = $this->class_security->data_form('tipo_ingreso');
                $cono        = $this->class_security->data_form('cono');



                $busqueda =  $this->general->query("(select v.pv_id As id,1 As 'tipo',v.pv_nombre As nombre,v.pv_cedula As documento,a.t_id As apt_id,a.t_apartamento As apt,v.pv_placa As placa,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from propietarios_visitante As v
    LEFT JOIN visitante_ingreso As vi ON v.pv_id=vi.vi_visitante AND vi.vi_tipo=1 and vi.vi_estado=1 
    LEFT JOIN apartamento a on v.pv_apartamento = a.t_id
WHERE v.pv_estado = 1  AND  v.pv_cedula='".$documento."')
UNION ALL
(select v.v_id As id,2 As 'tipo',v.v_nombre As nombre,v.v_docmento As documento,ap.t_id As apt_id,ap.t_apartamento As apt,vp.v_placa As placa,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from visitante As v
 LEFT JOIN visitante_placa vp on v.v_id = vp.v_visitante
 LEFT JOIN visitante_ingreso vi ON v.v_id=vi.vi_ingreso AND vi.vi_estado=1  AND vi.vi_tipo=2
 LEFT JOIN apartamento As ap ON vi.vi_filial=ap.t_id
WHERE v.v_estado = 1  AND   v.v_docmento='".$documento."')",'obj');

                if(isset($busqueda) AND (count($busqueda) >=1 )){
                    $data  = $busqueda[0];
                    $id_data = $data->id;
                    $tipo = $data->tipo;
                }else{
                 //registrar un nuevo usuario
                   $rg = $this->general->create('visitante',[
                       'v_nombre' => $nombre,
                       'v_docmento' => $documento,
                       'v_estado' => 1,
                       'v_atcreate' => fecha(2),
                   ]);
                    $tipo = 2;
                    $id_data = $rg['id'];
                }



                //validar si al usuario 2 trae placa se la guardamos
                if(strlen($placa) >= 3 AND $tipo == 2){
                    $this->general->create_update('visitante_placa',['v_visitante' => $id_data],[
                        'v_visitante' => $id_data,
                        'v_placa' => $placa,
                        'v_atcreate' => fecha(2),
                    ]);
                }


                //validar si el usuario ya ingreso o no para posteriormente hacer el registro

                    $exist = $this->general->query("select * from visitante_ingreso where vi_visitante='".$id_data."' AND vi_tipo='".$tipo."' AND vi_estado = 1", 'obj');

                if(isset($exist) AND (count($exist) >= 1)){

                    $resq = $exist[0];
                    $ingreso = $resq->vi_ingreso;
                    $salida = $resq->vi_salida;
                }else{
                    $ingreso = '';
                    $salida = '';
                }


                    if (($ingreso == '' || $ingreso == null) AND $nombre != 'no' and $placa != 'no' AND $cono != 'no') {
                        //no existe ingreso y se le manda a ingresar la validacion del modal

                        $this->general->create_update('visitante_data',['vd_visitante' => $id_data,'vd_tipo' => $tipo],[
                            'vd_visitante'    => $id_data,
                            'vd_tipo'         => $tipo,
                            'vd_tipo_ingreso' => $tipo_ing,
                        ]);

                        $this->general->create('visitante_ingreso',
                            [
                                'vi_visitante' => $id_data,
                                'vi_tipo' => $tipo,
                                'vi_placa' => $placa,
                                'vi_cono' => $cono,
                                'vi_dia' => fecha(1),
                                'vi_ingreso' => fecha(2),
                                'vi_estado' => 1,
                                'vi_filial' => $filial,
                            ]);

                        $this->result = array('success' => '1');
                    } elseif (($salida == '' || $salida == null) and $placa == 'no') {
                        $this->general->update('visitante_ingreso', ['vi_visitante' => $id_data,'vi_estado' => 1],
                            [
                                'vi_salida' => fecha(2),
                                'vi_estado' => 2
                            ]);
                        $this->result = array('success' => '1');
                    } else {
                        $this->result = array('success' => 2, 'msg' => 'Validar Ingreso');
                    }



            }else {
                $this->result = array('success' => 2, 'msg' => 'Datos Obligatorios');
            }
        }else {
            $this->result = array('success' => 2, 'msg' => 'Que haces');
        }


        api($this->result);
    }

    function data_filial(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){

                $id  = $this->class_security->data_form('id','int');

                $data = [];
                $query = $this->general->query("select u.u_tipo_contacto As tipo,u.u_nombre As nombre,u.u_telefono1 As telefono from apartamento As a JOIN usuarios as u ON a.t_id=u.u_apartamento WHERE a.t_id='".$id."' AND u.u_tipo_contacto IN (1,2) HAVING LENGTH(u.u_telefono1) >= 5 ORDER BY u.u_tipo_contacto ASC;",'obj');
                if(count($query) >= 1){

                    foreach($query As $qd){

                        if($qd->tipo == 1){
                            $qd->tipo;
                            $data[] = $qd;
                        }else{

                            if($data['0']->telefono !=  $qd->telefono AND (!isset($data['1']->telefono))){
                                $data[] = $qd;
                            }
                        }
                    }

                }
                $this->result = array('success' => 1,'data' => $data);

            }else{
                $this->result = array('success' => 2,'msg' => 'Que haces!');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function buscar_usuario(){
        $dataR = [];

        if($this->input->post()){
            if($this->class_security->validate_post(array('usuario'))){
                $data = $this->class_security->data_form('usuario');


                    $busqueda =  $this->general->query("(select  'Invitado R.' As 'tipo_invitado',v.pv_id As id,1 As 'tipo',v.pv_nombre As nombre,v.pv_cedula As documento,a.t_id As apt_id,a.t_apartamento As apt,v.pv_placa As placa,vd.vd_tipo_ingreso  As tipo_ingreso,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from propietarios_visitante As v
    LEFT JOIN visitante_ingreso As vi ON v.pv_id=vi.vi_visitante AND vi.vi_tipo=1 and vi.vi_estado=1 
    LEFT JOIN apartamento a on v.pv_apartamento = a.t_id
    LEFT JOIN visitante_data As vd ON  vd.vd_visitante=v.pv_id AND  vd.vd_tipo=1
WHERE v.pv_estado = 1  AND  v.pv_cedula='".$data."')
UNION ALL
(select  'Llamar..' As 'tipo_invitado',v.v_id As id,2 As 'tipo',v.v_nombre As nombre,v.v_docmento As documento,ap.t_id As apt_id,ap.t_apartamento As apt,vp.v_placa As placa,vd.vd_tipo_ingreso  As tipo_ingreso,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from visitante As v
 LEFT JOIN visitante_placa vp on v.v_id = vp.v_visitante
 LEFT JOIN visitante_ingreso vi ON v.v_id=vi.vi_ingreso AND vi.vi_estado=1  AND vi.vi_tipo=2
 LEFT JOIN apartamento As ap ON vi.vi_filial=ap.t_id
 LEFT JOIN visitante_data As vd ON  vd.vd_visitante= v.v_id AND  vd.vd_tipo=2
WHERE v.v_estado = 1  AND   v.v_docmento='".$data."')");

                    if(isset($busqueda) AND count($busqueda) >= 1){

                        $apt = $busqueda[0];
                        $dataR = array(
                            'id'         => encriptar($apt['id']),
                            'tipo2'      => $apt['tipo'],
                            'apt_id'        => ($apt['apt_id']),
                            'apt'        => $apt['apt'],
                            'nombre'     => $apt['nombre'],
                            'tipo'       => $this->class_security->array_data($apt['tipo'],$this->class_data->tipo_ingreso),
                            'documento'  => $apt['documento'],
                            'placa'      => $apt['placa'],
                            'tipo_ingreso'       => $apt['tipo_ingreso'],
                            'cono'       => $apt['cono'],
                            'ingreso'    => $apt['ingreso'],
                            'salida'     => $apt['salida'],
                            'tipo_invitado'     => $apt['tipo_invitado'],
                        );
                        $this->result = array('success' => '1','data' => $dataR);
                    }else{
                        $this->result = array('success' => 2,'msg' => 'registrar nuevo Visitante');
                    }

            }else{
                $this->result = array('success' => 2,'msg' => 'Datos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces');
        }


        api($this->result);
    }

    function search_visitante(){
        $all_input = $this->input->post("draw");
        if(isset($all_input)){
            $draw       = intval($this->input->post("draw"));
            $start      = intval($this->input->post("start"));
            $length     = intval($this->input->post("length"));
            $busqueda   = $this->input->post("search");
            $order      = $this->input->post("order");
            $dato      = $this->input->post("dato");
            $valor      =  (isset($busqueda)) ? $busqueda['value'] : '';
        }else{
            $draw = 0;
            $start = 0;
            $length = 5;
            $valor = '';
            $dato = '';
            $order = array();
        }
        $data = array();




        $dataget = $this->general->query("(select v.pv_id As id,1 As 'tipo',v.pv_nombre As nombre,v.pv_cedula As documento,a.t_apartamento As apt,v.pv_placa As placa,vd.vd_tipo_ingreso  As tipo_ingreso,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from propietarios_visitante As v
    JOIN visitante_ingreso As vi ON v.pv_id=vi.vi_visitante AND vi.vi_tipo=1 and vi.vi_estado=1
    LEFT JOIN apartamento a on v.pv_apartamento = a.t_id
    LEFT JOIN visitante_data As vd ON  vd.vd_visitante=v.pv_id AND  vd.vd_tipo=1
WHERE v.pv_estado = 1  AND  v.pv_nombre LIKE '%".$valor."%' OR   v.pv_cedula LIKE '%".$valor."%' OR v.pv_placa LIKE '%".$valor."%')
UNION ALL
(select v.v_id As id,2 As 'tipo',v.v_nombre As nombre,v.v_docmento As documento,ap.t_apartamento As apt,vp.v_placa As placa,vd.vd_tipo_ingreso  As tipo_ingreso,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from visitante As v
 LEFT JOIN visitante_placa vp on v.v_id = vp.v_visitante
 JOIN visitante_ingreso vi ON v.v_id=vi.vi_visitante AND vi.vi_estado=1  AND vi.vi_tipo=2
 JOIN apartamento As ap ON vi.vi_filial=ap.t_id
 LEFT JOIN visitante_data As vd ON  vd.vd_visitante= v.v_id AND  vd.vd_tipo=2
WHERE v.v_estado = 1  AND  v.v_nombre LIKE '%".$valor."%' OR   v.v_docmento LIKE '%".$valor."%' OR vp.v_placa LIKE '%".$valor."%') LIMIT $start,$length",'obj');


        $datagetc = $this->general->query("(select v.pv_id As id,1 As 'tipo',v.pv_nombre As nombre,v.pv_cedula As documento,a.t_apartamento As apt,v.pv_placa As placa,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from propietarios_visitante As v
    JOIN visitante_ingreso As vi ON v.pv_id=vi.vi_visitante AND vi.vi_tipo=1 and vi.vi_estado=1
    LEFT JOIN apartamento a on v.pv_apartamento = a.t_id
WHERE v.pv_estado = 1  AND  v.pv_nombre LIKE '%".$valor."%' OR   v.pv_cedula LIKE '%".$valor."%' OR v.pv_placa LIKE '%".$valor."%')
UNION ALL
(select v.v_id As id,2 As 'tipo',v.v_nombre As nombre,v.v_docmento As documento,ap.t_apartamento As apt,vp.v_placa As placa,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from visitante As v
 LEFT JOIN visitante_placa vp on v.v_id = vp.v_visitante
 JOIN visitante_ingreso vi ON v.v_id=vi.vi_visitante AND vi.vi_estado=1  AND vi.vi_tipo=2
 LEFT JOIN apartamento As ap ON vi.vi_filial=ap.t_id
WHERE v.v_estado = 1  AND  v.v_nombre LIKE '%".$valor."%' OR   v.v_docmento LIKE '%".$valor."%' OR vp.v_placa LIKE '%".$valor."%')");

        $total_registros = count($datagetc);

        foreach($dataget as $rows){

            $id = ($rows->documento);
            $fecha1 = $this->fecha($rows->ingreso);
            $fecha2 = $this->fecha($rows->salida);

                $data[]= array(

                    $rows->documento,
                    $rows->nombre,
                    $this->class_security->array_data($rows->tipo,$this->class_data->tipo_ingreso),
                    $rows->placa,
                    $rows->tipo_ingreso,
                    $rows->cono,
                    $rows->apt,
                    $fecha1,
                    $rows->salida,
                    $this->timer->realtime($fecha1),
                    "<button class='btn btn-danger btn-lg' onclick='$(this).validar_acceso(\"{$id}\",\"{$rows->tipo}\")'>Salida</button>"
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

    function fecha($fecha = ""){
        if($fecha != "" || $fecha != "0000-00-00 00:00:00"){
        $date = new DateTime($fecha);
        return $date->format('Y-m-d H:i:s');
        }else{
            return "";
        }
    }

    function search_visitante_reporte(){
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


        $dataget = $this->general->query("(select v.pv_id As id,1 As 'tipo',v.pv_nombre As nombre,v.pv_cedula As documento,a.t_apartamento As apt,v.pv_placa As placa,vd.vd_tipo_ingreso  As tipo_ingreso,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from propietarios_visitante As v
    JOIN visitante_ingreso As vi ON v.pv_id=vi.vi_visitante AND vi.vi_tipo=1 and vi.vi_estado=2
    LEFT JOIN apartamento a on v.pv_apartamento = a.t_id
    LEFT JOIN visitante_data As vd ON  vd.vd_visitante=v.pv_id AND  vd.vd_tipo=1
WHERE v.pv_estado = 1  AND  v.pv_nombre LIKE '%".$valor."%' OR  a.t_apartamento LIKE '%".$valor."%'  OR   v.pv_cedula LIKE '%".$valor."%' OR v.pv_placa LIKE '%".$valor."%' OR vi.vi_ingreso LIKE '%".$valor."%' OR vi_salida LIKE '%".$valor."%')
UNION ALL
(select v.v_id As id,2 As 'tipo',v.v_nombre As nombre,v.v_docmento As documento,ap.t_apartamento As apt,vp.v_placa As placa,vd.vd_tipo_ingreso  As tipo_ingreso,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from visitante As v
 LEFT JOIN visitante_placa vp on v.v_id = vp.v_visitante
 JOIN visitante_ingreso vi ON v.v_id=vi.vi_visitante AND vi.vi_estado=2  AND vi.vi_tipo=2
 JOIN apartamento As ap ON vi.vi_filial=ap.t_id
 LEFT JOIN visitante_data As vd ON  vd.vd_visitante= v.v_id AND  vd.vd_tipo=2
WHERE v.v_estado = 1  AND  v.v_nombre LIKE '%".$valor."%' OR ap.t_apartamento LIKE '%".$valor."%' OR   v.v_docmento LIKE '%".$valor."%' OR vp.v_placa LIKE '%".$valor."%' OR vi.vi_ingreso LIKE '%".$valor."%' OR vi_salida LIKE '%".$valor."%') LIMIT $start,$length",'obj');


        $datagetc = $this->general->query("(select v.pv_id As id,1 As 'tipo',v.pv_nombre As nombre,v.pv_cedula As documento,a.t_apartamento As apt,v.pv_placa As placa,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from propietarios_visitante As v
    JOIN visitante_ingreso As vi ON v.pv_id=vi.vi_visitante AND vi.vi_tipo=1 and vi.vi_estado=2
    LEFT JOIN apartamento a on v.pv_apartamento = a.t_id
WHERE v.pv_estado = 2  AND  v.pv_nombre LIKE '%".$valor."%'  OR  a.t_apartamento LIKE '%".$valor."%' OR   v.pv_cedula LIKE '%".$valor."%' OR v.pv_placa LIKE '%".$valor."%' OR vi.vi_ingreso LIKE '%".$valor."%' OR vi_salida LIKE '%".$valor."%')
UNION ALL
(select v.v_id As id,2 As 'tipo',v.v_nombre As nombre,v.v_docmento As documento,ap.t_apartamento As apt,vp.v_placa As placa,vi.vi_cono As cono,vi.vi_ingreso As ingreso,vi_salida As salida from visitante As v
 LEFT JOIN visitante_placa vp on v.v_id = vp.v_visitante
 JOIN visitante_ingreso vi ON v.v_id=vi.vi_visitante AND vi.vi_estado=2  AND vi.vi_tipo=2
 LEFT JOIN apartamento As ap ON vi.vi_filial=ap.t_id
WHERE v.v_estado = 2  AND  v.v_nombre LIKE '%".$valor."%'  OR ap.t_apartamento LIKE '%".$valor."%' OR   v.v_docmento LIKE '%".$valor."%' OR vp.v_placa LIKE '%".$valor."%' OR vi.vi_ingreso LIKE '%".$valor."%' OR vi_salida LIKE '%".$valor."%')");

        $total_registros = count($datagetc);

        foreach($dataget as $rows){

            $fecha1 = $this->fecha($rows->ingreso);
            $fecha2 = $this->fecha($rows->salida);
            $data[]= array(
                $rows->documento,
                $rows->nombre,
                $this->class_security->array_data($rows->tipo,$this->class_data->tipo_ingreso),
                $rows->placa,
                $rows->tipo_ingreso,
                $rows->cono,
                $rows->apt,
                $rows->ingreso,
                $rows->salida,
                $this->timer->realtime($fecha1,$fecha2),
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


    function get_propiedades(){
        $data1 = [];
        $data = [];

        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    if($this->general->exist('apartamento',array('t_id' => $id))){

                        $propietarios   =  $this->general->query("SELECT * FROM usuarios As u WHERE u.u_apartamento = '".$id."' AND u.u_estado = 1 AND u.u_tipo=2 ORDER BY u.u_tipo_contacto ASC");
                        $vehiculos_q    =  $this->general->all_get('propietarios_vehiculos',array('pv_propietario' => $id),[],'array');
                        $invitados      =  $this->general->all_get('propietarios_visitante',array('pv_apartamento' => $id,'pv_estado' => 1));
                        $mascotas_q     =  $this->general->all_get('propietarios_mascostas',array('pm_apartamento' => $id));

                        $personas = array();
                        $personas2 = array();
                        $vehiculos = array();
                        $mascotas = array();

                        foreach($propietarios As $prop){

                            $personas[$prop['u_id']] = array(
                                'nombre'         => $this->class_security->decodificar($prop['u_nombre']),
                                'telefono1'       => $this->class_security->decodificar($prop['u_telefono1']),
                                'telefono2'       => $this->class_security->decodificar($prop['u_telefono2']),
                                'propietario'       => $this->class_security->array_data($prop['u_tipo_contacto'],$this->class_data->tipo_contacto,''),
                            );

                        }

                        foreach($vehiculos_q As $prop2){
                            $vehiculos[] = array(
                                'tipo'        => $this->class_security->array_data($prop2['pv_tipo'],$this->class_data->autos,''),
                                'placa'       => $this->class_security->decodificar($prop2['pv_placa']),
                                'color'       => $this->class_security->decodificar($prop2['pv_color']),
                            );
                        }

                        foreach($personas As $pp){
                            $personas2[] = $pp;
                        }

                        foreach($mascotas_q as $dd){
                            $mascotas[] = array(
                                'nombre'         => $this->class_security->decodificar($dd->pm_nombre),
                                'tipo'           => $this->class_security->array_data($dd->pm_tipo,$this->class_data->mascota,''),
                                'color'          => $this->class_security->decodificar($dd->pm_color),
                                'raza'           => $this->class_security->decodificar($dd->pm_raza),
                                'imagen'         => $this->class_security->img_avatar($dd->pm_imagen,2),
                                'observacion'  => $this->class_security->decodificar($dd->pm_observacion),
                            );
                        }

                        foreach($invitados as $dd){
                            $btn            = $this->class_data->estado[$dd->pv_estado];
                            $data[] = array(
                                'nombre'         => $this->class_security->decodificar($dd->pv_nombre),
                                'cedula'         => $this->class_security->decodificar($dd->pv_cedula),
                                'telefono'       => $this->class_security->decodificar($dd->pv_telefono),
                                'tipo'           => $this->class_security->array_data($dd->pv_tipo_vehiculo,$this->class_data->autos),
                                'placa'          => $this->class_security->decodificar($dd->pv_placa),
                                'imagen'         => $this->class_security->img_avatar($dd->pv_foto,2),
                                'estado'         => "<button type='button' class='{$btn['class']}'>{$btn['title']}</button>",
                            );
                        }

                        $this->result = array('success' => '1','propietario' => $personas2,'vehiculos' => $vehiculos,'visitante' => $data,'mascotas' => $mascotas);
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

    function save_lectura(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('data_id','attr','lectura'))){

                //campos post
                $id                 = $this->class_security->data_form('data_id','decrypt_int');
                $anterior            = $this->class_security->data_form('attr','saldo',0);
                $lectura             = $this->class_security->data_form('lectura','saldo');


                if($id != '' AND $lectura != ''){

                    if($this->general->exist('apartamento',array('t_id' => $id))){

                        $preimg  = $this->class_security->upload_document('imagen','_files/','jpeg|jpg|png');
                        $imagen_old = $this->general->nique_value('lectura_agua','lt_imagen',array('lt_apartamento' => $id,'lt_mes' => date("n"),'lt_anno'  => date("Y")));

                        $foto = (strlen($preimg) >= 11) ? $preimg : $imagen_old;

                        $fecha = fecha(2);
                        $mes = date('n',strtotime($fecha));
                        $anno = date('Y',strtotime($fecha));
                        $consumo = $this->class_security->calculo_validar_total('0',$anterior,$lectura);
                        //procesar
                        $data = array(
                            'lt_apartamento'    => $id,
                            'lt_usuario'        => $this->user_data->u_id,
                            'lt_lectura'        => $lectura,
                            'lt_anterior'       => $anterior,
                            'lt_consumo'        => $consumo['res'],
                            'lt_imagen'         => $foto,
                            'lt_mes'            => $mes,
                            'lt_anno'           => $anno,
                            'lt_atcreate'       => $fecha,
                        );

                        $this->general->query("CALL proc_movimiento('$id','".fecha(2)."');",'',false);
                        $this->general->create_update('apartamento_movimiento',['am_apartamento'  => $id],['am_apartamento'  => $id,'am_atcreate'  => fecha(2)]);
                        $this->result =   $this->general->create_update('lectura_agua',array('lt_apartamento'  => $id,'lt_mes' => $mes,'lt_anno'  =>$anno   ),$data);


                    }else{
                        $this->result = array('success' => 2,'msg' => 'Validar Inmueble');
                    }


                }else{
                    $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');

                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    function get_lectura(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id'))){
                $id = $this->class_security->data_form('id','decrypt_int');
                if(strlen($id) >= 1){
                    $actual = '';
                    $anterior = 0;
                    $imagen = base_url('_files/default.jpg');


                    $fechax  = fecha(2);
                    $fecha_nueva = date("Y-m", strtotime($fechax));

                    //data mes anterior
                    $mes = date("n", strtotime($fechax));
                    $anno =date("Y", strtotime($fechax));
//                    echo $mes_a = $this->fecha_sum_res($fechax,1);
//                    exit;

                    //obtener la lectura anterior
                    $ant = $this->general->query("SELECT a1.lt_lectura,
COALESCE((SELECT a2.lt_lectura FROM lectura_agua AS a2 WHERE a2.lt_id < a1.lt_id  AND a2.lt_mes !=  '".$mes."' AND a1.lt_anno='".$anno."' AND a2.lt_apartamento=a1.lt_apartamento ORDER BY a2.lt_id DESC LIMIT 1),0) AS anterior
 FROM lectura_agua AS a1 WHERE a1.lt_apartamento='".$id."' AND DATE_FORMAT(a1.lt_atcreate, '%Y-%m') != '".$fecha_nueva."'
 ORDER BY DATE_FORMAT(a1.lt_atcreate, '%Y-%m') DESC LIMIT 1");

                    $atl = $this->general->query("SELECT lt_lectura,lt_imagen FROM lectura_agua  WHERE lt_apartamento='".$id."' AND DATE_FORMAT(lt_atcreate, '%Y-%m') = '".$fecha_nueva."'");

                    if(count($ant) >= 1){
                        $anterior = $ant[0]['lt_lectura'];
                    }

                    if(count($atl) >= 1){
                        $actual = $atl[0]['lt_lectura'];
                        $imagen = base_url('_files/').$atl[0]['lt_imagen'];
                    }


                    $this->result = array('success' => '1','anterior' => $anterior,'actual' => $actual,'imagen' => $imagen);


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


}