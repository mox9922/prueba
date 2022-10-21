<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Modal  extends CI_Controller{

    //propiedades
    private $session_id;
    private $result = array();


    public function __construct(){
        parent::__construct();
        $this->session_id     = $this->session->userdata('user_id');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        $this->user_data = $this->general->get('usuarios',array('u_id' => $this->session_id));

         //load
        $this->load->model('m_datatable','datatable');
        $this->load->helper(array('website','encriptar'));

    }

    function index(){
        $page          = $this->class_security->data_form('page','str');
        $data1         = $this->class_security->data_form('data1');
        $data2         = $this->class_security->data_form('data2');
        $data3         = $this->class_security->data_form('data3');
        $data4         = $this->class_security->data_form('data4');

        if($this->input->post()) {
            if ($this->class_security->validate_post(array('page'))) {
                if($page == 1){
                    //deuda lista
                    $this->load->view($page,array('page' => $page,'turno' => $data1 , 'tipo' => $data2));
                }elseif($page == 2){
                    //deuda Pago
                    $this->load->view('modal/afilial/deuda/v_deuda_pago',array('page' => $page));
                }elseif($page == 3){
                    //Lista de deudas apaga - administracion - extraordinaria
                    $modulos = $this->general->all_get('modulo');
                    $this->load->view('modal/v_declinar',array('page' => $page,'id' => $data1,'modulos' => $modulos));

                }

                //dashboard
                elseif($page == 'dashboard_reporte'){
                    $fecha = (strlen($data2) == 7) ? $data2 : date("Y-m");
                    $datas = [];

                    $having = ($data3 != 1) ? ' HAVING rpagado >= 1' : '';
                    if($data1 == 1){
                        //deudas 1,2,3f
                        $datas = $this->general->query("SELECT  a.t_apartamento,ap.pg_tipo_deuda,(ap.pg_deuda) As rdeuda,COALESCE((pagado),0) AS rpagado
 FROM apartamento AS a
JOIN apartamento_pagos AS ap ON a.t_id=ap.pg_apartamento
LEFT JOIN (
	SELECT ag.as_deuda,SUM(ag.aa_monto) AS pagado FROM apartamento_pago_agregado AS ag WHERE DATE_FORMAT(ag.aa_fecha, '%Y-%m') = '".$fecha."'  AND  ag.aa_estado=3 GROUP BY ag.as_deuda
    ) As t ON ap.pg_id=t.as_deuda
 WHERE DATE_FORMAT(ap.pg_fecha, '%Y-%m') = '".$fecha."'  AND ap.pg_tipo_deuda=2 {$having}","array");
                    }

                    elseif($data1 == 2){
                        //deudas en general

//                        echo "SELECT a.t_apartamento,COALESCE(SUM(d.du_saldo),0) As rdeuda,COALESCE((rpagado),0) rpagado
//FROM apartamento AS a
//JOIN deuda AS d ON a.t_id=d.du_apartamento
//LEFT JOIN (
//SELECT dp.pd_apartamento,SUM(dp.pd_monto) AS rpagado from deuda_pagos dp  WHERE dp.pd_estado=3 AND  DATE_FORMAT(dp.pd_atcreate, '%Y-%m') = '$fecha' GROUP BY dp.pd_apartamento
//    ) As t ON a.t_id=t.pd_apartamento
//WHERE DATE_FORMAT(d.du_atcreate, '%Y-%m') = '$fecha'
//GROUP BY a.t_apartamento,rpagado {$having}";




                        $datas = $this->general->query("
SELECT a.t_apartamento,COALESCE(SUM(d.du_saldo),0) As rdeuda,COALESCE(SUM((rpagado)),0) rpagado
FROM apartamento AS a
JOIN deuda AS d ON a.t_id=d.du_apartamento
LEFT JOIN (
    SELECT dp.pd_deuda,SUM(dp.pd_monto) AS rpagado from deuda_pagos dp  WHERE dp.pd_estado=3  GROUP BY dp.pd_deuda
    ) As t ON d.du_id=t.pd_deuda
WHERE d.du_estado IN(1,2)
GROUP BY a.t_apartamento","array");
                    }
                    else{
                        //deudas 1,2,3,deudas

                        $datas = $this->general->query("SELECT a.t_apartamento,COALESCE(SUM(d.du_saldo),0) As rdeuda,COALESCE(SUM((rpagado)),0) rpagado
FROM apartamento AS a
JOIN deuda AS d ON a.t_id=d.du_apartamento
LEFT JOIN (
    SELECT dp.pd_deuda,SUM(dp.pd_monto) AS rpagado from deuda_pagos dp  WHERE dp.pd_estado=3 AND DATE_FORMAT(dp.pd_atcreate, '%Y-%m') = '".$fecha."' GROUP BY dp.pd_deuda
    ) As t ON d.du_id=t.pd_deuda
WHERE d.du_estado IN(1,2)
GROUP BY a.t_apartamento","array");



                    }



                    $this->load->view('modal/admin/v_dashboard',array('page' => $page,'id' => $data1,'datas' => $datas));
                }

                elseif($page == 'admin_pago_deudas'){


                    $apt_id     = (strlen($data1) >= 1) ? $data1 : '';
                    $tipo       = (strlen($data2) == 1) ? $data2 : '';
                    $fecha      = (strlen($data3) >=  4) ? $data3 : '';


                    if(in_array($tipo,[1,2,3,4])){
                        $data = [];

                        $favor = ($this->general->sum_data('saldo_favor',['sf_apartamento' => $apt_id],'sf_saldo')->suma);
                        $tipo_nombre = $this->class_security->array_data($tipo,$this->class_data->comprobante);

                        if(in_array($tipo,[1,2,3])){

                            $all_data = $this->general->query("SELECT ap.pg_id As id,'' As du_nombre,ap.pg_estado As estado,ap.pg_fecha As fecha,ap.pg_anno,ap.pg_deuda As deuda,COALESCE(abono,0) As abono,ap.pg_deuda-COALESCE(abono,0) As saldado FROM apartamento_pagos AS ap
LEFT JOIN (
    SELECT pa.as_deuda,SUM(pa.aa_monto) As abono from apartamento_pago_agregado AS pa WHERE pa.aa_estado=3 GROUP BY pa.as_deuda
    ) As pa ON ap.pg_id=pa.as_deuda
WHERE ap.pg_apartamento='".$apt_id ."'  AND pg_tipo_deuda='".$tipo ."' AND ap.pg_estado IN(1,2) AND DATE_FORMAT(ap.pg_atcreate,'%Y-%m')='".$fecha."'
GROUP BY ap.pg_id,ap.pg_mes,ap.pg_anno
HAVING saldado > 0",'array');


                        }else{

                            $all_data = $this->general->query("SELECT d.du_id As id,d.du_estado As estado,d.du_nombre,DATE_FORMAT(d.du_atcreate,'%Y-%m-%d') As fecha,d.du_saldo As deuda,COALESCE(monto,0) As abono,
       CASE
           WHEN d.du_saldo > COALESCE(monto,0) THEN d.du_saldo-COALESCE(monto,0)
           ELSE 0
        END As saldado FROM deuda AS d
    LEFT JOIN
    (
        SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3 group by dp.pd_deuda
    ) As dp ON d.du_id=dp.pd_deuda
     WHERE d.du_apartamento='".$apt_id ."'
   GROUP BY d.du_id",'array');
                        }

//                        print_r($all_data);

                        $saldo_total = 0;

                        if(count($all_data) >= 1) {
                            foreach ($all_data as $dl) {

                                $deuda =  (in_array($dl['estado'],[1,2])) ? $dl['saldado'] : 0;
                                $saldo_total += $deuda;

                                $data[] = array(
                                    'id' => encriptar($dl['id']),
                                    'nombre' =>  (in_array($data1,[1,2,3])) ? $this->class_security->array_data($data1,$this->class_data->comprobante,'') : $dl['du_nombre'],
                                    'deuda' =>  $dl['deuda'],
                                    'fecha' =>  $dl['fecha'],
                                    'resta' => $dl['saldado'],
                                    'abono' => ($dl['abono']),
                                    'estado' => ($dl['estado']),
                                );
                            }
                        }
                        $this->load->view('modal/admin/v_pago_deuda',array('page' => $page,'apartamento' => ($data1),'fecha' => $fecha,'datas' => $data,'tipo' => $tipo,'favor' => $favor,'tipo_nombre' => $tipo_nombre,'deuda_total' => '₡ '.$this->class_security->dinero($saldo_total)));

                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar el tipo de deuda']);
                    }



                }


                //Usuarios
                elseif($page == 'usuarios'){
                        $id = desencriptar($data1);
                        $datas = $this->general->get('usuarios',['u_id' => $id],'array');
                        $this->load->view('modal/admin/v_usuarios',array('page' => $page,'id' => $data1,'datas' => $datas));
                }
                elseif($page == 'usuarios_perfiles'){
                        $id = desencriptar($data1);
                        $datas = $this->general->get('usuarios',['u_id' => $id],'array');
                        $this->load->view('modal/admin/usuarios/v_perfiles',array('page' => $page,'id' => $data1,'datas' => []));
                }

                //Propiedades
                elseif($page == 'propietario_usuarios'){
                    $id = desencriptar($data1);
                        $datas = $this->general->get('usuarios',['u_id' => $id],'array');
                        $apartamentos = $this->general->all_get('apartamento');
                        $this->load->view('modal/admin/propietarios/v_usuarios',array('page' => $page,'id' => $data1,'datas' => $datas,'apartamentos' => $apartamentos));
                }
                elseif($page == 'propietario_filiales'){
                        $id = desencriptar($data1);
                        $datas = $this->general->get('apartamento',['t_id' => $id],'array');
                        $extraordinaria = $this->general->all_get('cuota_extraordinaria',['ce_apartamento' => $id],[],'array');
                        $this->load->view('modal/admin/propietarios/v_filiales',array('page' => $page,'id' => $data1,'datas' => $datas,'extraordinaria' => $extraordinaria));
                }
                elseif($page == 'propietario_quickpass'){
                    $id = desencriptar($data1);
                    if($this->general->exist('usuarios',['u_id' => $id])){
                        $datas = $this->general->all_get('propietarios_quick_pass',['pq_propietario' => $id],[],'array');
                        $this->load->view('modal/admin/propietarios/v_quickpass',array('page' => $page,'id' => $data1,'datas' => $datas));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que el propietario exista']);
                    }
                }
                elseif($page == 'propietario_vehiculos'){
                    $id = desencriptar($data1);
                    if($this->general->exist('apartamento',['t_id' => $id])){
                        $datas = $this->general->all_get('propietarios_vehiculos',['pv_propietario' => $id],[],'array');
                        $this->load->view('modal/admin/propietarios/v_vehiculo',array('page' => $page,'id' => $data1,'datas' => $datas));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que el propietario exista']);
                    }
                }
                elseif($page == 'propietario_mascotas'){
                    $apt    = desencriptar($data1);
                    $mascot = desencriptar($data2);
                    if($this->general->exist('apartamento',['t_id' => $apt])){
                        $mascota_alone =$this->general->get('propietarios_mascostas',['pm_id' => $mascot],'array');
                        $datas = $this->general->all_get('propietarios_mascostas',['pm_apartamento' => $apt],[],'array');
                        $this->load->view('modal/admin/propietarios/v_mascota',array('page' => $page,'id' => $data1,'datas' => $datas,'mascota' => $mascota_alone));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que el propietario exista']);
                    }
                }
                elseif($page == 'propietario_invitados'){
                    $id = desencriptar($data1);
                    $invit = desencriptar($data2);
                    if($this->general->exist('apartamento',['t_id' => $id])){
                        $invitado_alone =$this->general->get('propietarios_visitante',['pv_id' => $invit],'array');
                        $datas = $this->general->all_get('propietarios_visitante',['pv_apartamento' => $id],[],'array');
                        $this->load->view('modal/admin/propietarios/v_invitado',array('page' => $page,'id' => $data1,'datas' => $datas,'invitado' => $invitado_alone));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que el propietario exista']);
                    }
                }
                elseif($page == 'propietario_filial'){
                    $id = desencriptar($data1);
                    if($this->general->exist('apartamento',['t_id' => $id])){
                        $datas = $this->general->get('apartamento',['t_id' => $id]);

                        $deudas = $this->general->query("select a.pg_id,a.pg_estado,a.pg_fecha,a.pg_deuda,COALESCE(pagado,0) As agregado from apartamento_pagos As a
                        LEFT JOIN (
                            SELECT ap.as_deuda,SUM(ap.aa_monto) As pagado from apartamento_pago_agregado As ap WHERE ap.aa_estado=3 GROUP BY ap.as_deuda
                            ) As ap ON a.pg_id=ap.as_deuda
                         WHERE a.pg_apartamento='".$id."' AND a.pg_tipo_deuda=3",'array');

                        $this->load->view('modal/admin/propietarios/v_filial',array('page' => $page,'id' => $data1,'datas' => $datas,'deudas' =>$deudas));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Asignar una Filial']);
                    }
                }


                //Propiedades
                elseif($page == 'propiedades'){
                    $id = desencriptar($data1);
                    $propiedad = $this->general->get('propiedades',['p_id' => $id],'array');
                    $this->load->view('modal/admin/v_propiedad',array('page' => $page,'id' => $data1,'propiedad' => $propiedad));
                }

                //Servicios
                elseif($page == 'servicios'){
                    $id = desencriptar($data1);
                    $servicio = $this->general->get('servicios',['sv_id' => $id],'array');
                    $data_categorias = $this->general->all_get('servicios_categorias');
                    $data_proveedor = $this->general->query('select * from usuarios As u INNER JOIN servicios_proveedores As s ON u.u_id= s.sp_usuario',"obj");

                    $this->load->view('modal/admin/servicios/v_servicio',array('page' => $page,'id' => $data1,'servicio' => $servicio,'categorias' => $data_categorias,'proveedores' => $data_proveedor));
                }
                elseif($page == 'servicios_categoria'){
                    $id = desencriptar($data1);
                    $datas = $this->general->get('servicios_categorias',['sc_id' => $id],'array');
                    $data = $this->general->all_get('servicios_categorias');
                    $this->load->view('modal/admin/servicios/v_categoria',array('page' => $page,'datas' => $data,'query' => $datas,'id' => $data1));
                }
                elseif($page == 'servicios_proveedores'){
                    $id = desencriptar($data1);
                    $datas = $this->general->query("select * from usuarios As u INNER JOIN servicios_proveedores As s ON u.u_id= s.sp_usuario WHERE u.u_id='".$id."'",'array');
                    $data = $this->general->query('select * from usuarios As u INNER JOIN servicios_proveedores As s ON u.u_id= s.sp_usuario',"array");
                    $this->load->view('modal/admin/servicios/v_proveedor',array('page' => $page,'datas' => $data,'query' => $datas,'id' => $data1));
                }
                elseif($page == 'servicios_comprobante'){

                    $id = desencriptar($data1);
                        if($this->general->exist('servicios',['sv_id' => $id])){
                            $data         = $this->general->get('servicios',['sv_id' => $id]);
                            $comprobantes = $this->general->all_get('servicios_comprobante',['st_servicio' => $id,'st_tipo' => 1]);
                            $this->load->view('modal/admin/servicios/v_comprobante',array('page' => $page,'id' => $data1,'data' => $data,'comprobantes' => $comprobantes));
                        }else{
                            $this->load->view('modal/v_error',['msg' => 'Validar que El servicio se haya prestado']);
                        }
                } elseif($page == 'servicios_factura'){
                    $id = desencriptar($data1);
                    if($this->general->exist('servicios',['sv_id' => $id])){
                        $data         = $this->general->get('servicios',['sv_id' => $id]);
                        $comprobantes = $this->general->all_get('servicios_comprobante',['st_servicio' => $id,'st_tipo' => 2]);
                        $this->load->view('modal/admin/servicios/v_factura',array('page' => $page,'id' => $data1,'data' => $data,'comprobantes' => $comprobantes));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que El servicio se haya prestado']);
                    }
                }
                elseif($page == 'servicios_usuarios'){

                    $id = desencriptar($data1);
                    $data = $this->general->query("select * from usuarios As u INNER JOIN servicios_proveedores As s ON u.u_id= s.sp_usuario WHERE u.u_id='".$id."'",'array');
                    $datas = $this->general->query('select * from usuarios As u INNER JOIN servicios_proveedores As s ON u.u_id= s.sp_usuario',"obj");
                    $this->load->view('modal/admin/servicios/v_usuarios',array('page' => $page,'id' => $data1,'data' => $data,'datas' => $datas));

                }

                //reporte_informes_pagos
                elseif($page == 'reporte_informes_pagos'){

                    $apt    = $this->class_security->Int($data1);
                    $fecha1 = $this->class_security->letras_numeros_espace($data2);
                    $fecha2 = $this->class_security->letras_numeros_espace($data3);



                    $data_consulta = $this->general->query("(select DISTINCT ag.as_id,ap.pg_tipo_deuda As 'tipo_deuda',u.u_nombre,ag.aa_monto,ag.aa_fecha from apartamento_pagos As ap
    JOIN apartamento_pagos_abono apa on ap.pg_id = apa.aa_deuda
    INNER JOIN apartamento_pago_agregado AS ag ON ap.pg_id=ag.as_deuda AND ag.aa_estado=3
LEFT JOIN usuarios As u ON apa.aa_usuario=u.u_id
WHERE DATE_FORMAT(ag.aa_fecha,'%Y-%m') BETWEEN  '".$fecha1."' and '".$fecha2."'  AND ap.pg_id='".$apt."')
UNION ALL
(SELECT d.du_id,4  As 'tipo_deuda',u.u_nombre,dp.pd_monto,dp.pd_atcreate from deuda As d
INNER JOIN deuda_pagos dp on d.du_id = dp.pd_deuda AND dp.pd_estado=3
LEFT JOIN usuarios As u ON dp.pd_usuario=u.u_id
WHERE DATE_FORMAT(dp.pd_atcreate,'%Y-%m') BETWEEN  '".$fecha1."' and '".$fecha2."' AND d.du_apartamento='".$apt."')",'obj');
//                    if($this->general->exist('ticket',['t_id' => $id])){
//                        $ticket = $this->general->get('ticket',array('t_id' => $id),'array');
//                        $ticket_mensajes = $this->general->all_get_join('usuarios As u',[['ticket_response As tr','u.u_id=tr.tr_usuario']],['tr.tr_ticket'=> $id]);
//
                        $this->load->view('modal/admin/v_reporte_informe_pagos',array('datas' => $data_consulta));
//
//                    }else{
//                        $this->load->view('modal/v_error',['msg' => 'Ticket no existe']);
//                    }
                }
                //tickets
                elseif($page == 'ticket_admin'){

                    $id = desencriptar($data1);
                    if($this->general->exist('ticket',['t_id' => $id])){
                        $ticket = $this->general->get('ticket',array('t_id' => $id),'array');
                        $ticket_mensajes = $this->general->all_get_join('usuarios As u',[['ticket_response As tr','u.u_id=tr.tr_usuario']],['tr.tr_ticket'=> $id]);

                        $this->load->view('modal/filial/ticket/v_ticket_response',array('id'=> $data1,'page' => $page,'datas' => $ticket,'mensajes' => $ticket_mensajes,'id' => $data1));

                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Ticket no existe']);
                    }
                }

                //deuda
                elseif($page == 'admin_deuda_lista'){
                    $id = desencriptar($data1);
                    $deuda = $this->general->query("SELECT d.du_id,d.du_nombre,d.du_estado,d.du_fecha_inicio,d.du_atcreate,t_apartamento,SUM(d.du_saldo) As du_saldo,COALESCE(SUM(dpagos),0) As saldo FROM apartamento AS a
INNER JOIN deuda AS d ON a.t_id=d.du_apartamento
LEFT JOIN  (
	SELECT dp.pd_deuda,SUM(dp.pd_monto) AS dpagos FROM deuda_pagos AS dp WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
	) AS t ON d.du_id=t.pd_deuda
WHERE  a.t_id='".$id."'
GROUP BY d.du_id,dpagos
HAVING saldo < du_saldo OR   du_estado IN(1,2)",'obj');
                    $this->load->view('modal/admin/v_deuda_lista',array('page' => $page,'id' => $data1,'deuda' => $deuda));
                }
                elseif($page == 'admin_deuda'){
                    $id = desencriptar($data1);
                    $deuda = $this->general->query("SELECT d.*,DATE_FORMAT(d.du_atcreate,'%Y-%m-%d') As fecha,d.du_saldo As deuda,COALESCE(monto,0) As abono,a.t_apartamento,COALESCE(s.sf_saldo,0) As saldo_favor,
       CASE
           WHEN d.du_saldo > COALESCE(monto,0) THEN d.du_saldo-COALESCE(monto,0)
           ELSE COALESCE(monto,0)-d.du_saldo
        END As du_saldo FROM deuda AS d
            LEFT JOIN apartamento As a ON d.du_apartamento = a.t_id
            LEFT JOIN saldo_favor As s ON d.du_apartamento=s.sf_apartamento
    LEFT JOIN
    (
        SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3 group by dp.pd_deuda
    ) As dp ON d.du_id=dp.pd_deuda
     WHERE d.du_id='".$id."'",'array');
                    $pagos = $this->general->all_get('deuda_pagos',array('pd_deuda' => $id),[],'array');
                    $deuda_data = (count($deuda) >= 1) ? $deuda[0] : [];


                    $apartementos = $this->general->all_get('apartamento',array('t_estado' =>1));
                    $this->load->view('modal/admin/v_deuda',array('page' => $page,'id' => $data1,'deuda' => $deuda_data,'apartamentos' => $apartementos,'pagos' => $pagos));
                }


                //deuda
                elseif($page == 'admin_reserva'){
                    $id = desencriptar($data1);
                    if($this->general->exist('reserva',['r_id' => $id])){
                        $reserva        = $this->general->get('reserva',['r_id' => $id]);
                        $reserva_dias   = $this->general->all_get('reserva_dias',array('rd_reserva' => $id),[],'array');

                        $apartementos = $this->general->all_get('apartamento',array('t_estado' =>1));
                        $this->load->view('modal/admin/v_reserva',array('page' => $page,'id' => $data1,'reserva' => $reserva,'reserva_dias' => $reserva_dias));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que la reserva actualmente exista']);
                    }
                }

                //admin_deposito
                elseif($page == 'admin_deposito'){
                    $id = desencriptar($data1);
                        $deposito        = $this->general->query("select dp.*,a.t_apartamento from depositos As dp LEFT JOIN apartamento As a ON dp_apartamento = a.t_id WHERE dp.dp_id='".$id."'",'array');
                        $data = (count($deposito) >= 1) ? $deposito[0] : [];
                        $apartementos = $this->general->all_get('apartamento',array('t_estado' =>1));
                        $this->load->view('modal/admin/v_deposito',array('page' => $page,'id' => $data1,'data' => $data,'apartamentos' => $apartementos));

                }


                //admin_deposito
                elseif($page == 'admin_saldo_favor'){
                    $id = desencriptar($data1);


                        $saldo = $this->general->query("select ap.t_apartamento,sf.sf_apartamento,COALESCE(sf.sf_saldo,0) As favor from apartamento As ap LEFT JOIN saldo_favor As sf ON ap.t_id=sf.sf_apartamento WHERE sf_apartamento='".$id."'",'array');
                        $data = (count($saldo) >= 1 AND isset($saldo)) ? $saldo['0'] : [];
                        $apartementos = $this->general->all_get('apartamento',array('t_estado' =>1));


                        $this->load->view('modal/admin/v_saldo_favor',array('page' => $page,'id' => $data1,'data' => $data,'apartamentos' => $apartementos));


                }


                //deuda
                elseif($page == 'admin_reporte_pagos'){


                     $pre1  = $this->class_security->letras_numeros_mas($data1);
                     $pre2  = $this->class_security->letras_numeros_mas($data2);

                    $fecha = $pre1;
                    $final = (strlen($pre2) >= 7) ? $pre2 : $pre1;

                    if((strlen($fecha) >= 7  AND strlen($final) >= 7 ) AND ($fecha <= $final)){

                        $rango_fechas = $this->class_security->rango_fechas($fecha,$final);
                        $data = array();

                        $i = 0;
                        foreach($this->general->all_get('apartamento',[]) As $apt){
                            $x = 0;

                            $data[$i][] = $apt->t_apartamento;
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
//                                    $favor1 = 0;
//                                    $favor2 = 0;
//                                    $favor3 = 0;


                                    $data[$i][] = '₡ '. $this->class_security->dinero($agua['deuda']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($agua['agregado']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($agua['reciduo']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($favor1);

                                    $data[$i][] = '₡ '. $this->class_security->dinero($ce['deuda']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($ce['agregado']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($ce['reciduo']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($favor2);


                                    $data[$i][] = '₡ '. $this->class_security->dinero($cc['deuda']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($cc['agregado']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($cc['reciduo']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($favor3);


                                    $data[$i][] = '₡ '. $this->class_security->dinero($agua['deuda']+$ce['deuda']+$cc['deuda']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($agua['agregado']+$ce['agregado']+$cc['agregado']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($agua['reciduo']+$ce['reciduo']+$cc['reciduo']);
                                    $data[$i][] = '₡ '. $this->class_security->dinero($favor1+$favor2+$favor3);


                                    $data[$i][] = $this->class_security->asertarMoneda($r['deuda_valor']);
                                    $data[$i][] = $this->class_security->asertarMoneda($r['deuda_pago']);
                                    $data[$i][] = $this->class_security->saldo_validar($r['deuda_valor'],$r['deuda_pago']);



                                }

                            }
                            $i++;

                        }


                        $this->load->view('modal/admin/v_reporte_pagos',array('page' => $page,'datas' => $data,'fechas' => $rango_fechas));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que la reserva actualmente exista']);
                    }
                }


                //pagos
                elseif($page == 'admin_pagos'){
                    $this->load->view('modal/admin/pagos/v_pagos',array('page' => $page,'mes' => $data1,'anno' => $data2));
                }
                elseif($page == 'admin_control_pagos'){

                    $this->load->view('modal/admin/pagos/v_control_pagos',array('page' => $page));
                }
                elseif($page == 'admin_control_pagos_comprobantes'){
                    $datas = $this->general->query("(
    select ap.t_apartamento As apt,app.pg_fecha As fecha_deuda,app.pg_tipo_deuda As tipo_de_deuda,apg.as_id As id,app.pg_deuda-COALESCE(dinero,0) As deuda,ag.aa_saldo As saldo,ag.aa_atcreate As fecha,ag.aa_comprobante As comprobante,ag.aa_estado As estado from apartamento As ap
    JOIN apartamento_pagos AS app ON ap.t_id=app.pg_apartamento
    JOIN apartamento_pagos_abono As ag ON ap.t_id=ag.as_apartamento AND app.pg_id=ag.aa_deuda
    JOIN apartamento_pago_agregado As apg ON ag.aa_id=apg.as_agregado AND apg.aa_estado IN(1,2)
    LEFT JOIN (
        select apg.as_deuda,SUM(apg.aa_monto) As dinero from apartamento_pago_agregado As apg WHERE apg.aa_estado=3 GROUP BY apg.as_deuda
        ) As apg2 ON app.pg_id=apg2.as_deuda
   # WHERE app.pg_estado IN(1,2)
    )
UNION ALL
(
    SELECT  ap.t_apartamento As apt,DATE_FORMAT(d.du_atcreate,'%Y-%m-%d') As fecha_deuda,4 As 'tipo_de_deuda',dp.dp_id As id,d.du_saldo-COALESCE(dinero,0) As deuda,dp.pd_monto  As saldo,dp.pd_atcreate As fecha,dp.pd_comprobante As comprobante,dp.pd_estado As estado from apartamento As ap
    JOIN deuda d on ap.t_id = d.du_apartamento
    JOIN deuda_pagos As dp ON ap.t_id=dp.pd_apartamento AND d.du_id=dp.pd_deuda AND dp.pd_estado  IN(1,2)
    LEFT JOIN (
        SELECT dp2.pd_deuda,SUM(dp2.pd_monto) As dinero from deuda_pagos As dp2 WHERE dp2.pd_estado=3 group by  dp2.pd_deuda
        ) As dp2 ON d.du_id=dp2.pd_deuda
  #  WHERE d.du_estado IN(1,2)
)",'obj');
                    $this->load->view('modal/admin/pagos/v_control_pagos_comprobantes',array('page' => $page,'deudas' => $datas));
                }
                elseif($page == 'admin_pagos_comprobantes'){

                    $id   = $this->class_security->Int($data1);
                    $fecha = $this->class_security->letras_numeros_mas($data2);
                    if($this->general->exist('apartamento',['t_id' => $id])){

                        //saldo cancelar
                        $clc_header = $this->general->query("(select p.pg_tipo_deuda,p.pg_id,p.pg_deuda  As pg_deuda,
        CASE
            WHEN p.pg_deuda-COALESCE(aa_saldo,0) > 0 THEN p.pg_deuda-COALESCE(aa_saldo,0)
                ELSE 0
            END
         As restado,COALESCE(aa_saldo,0) As pagado from apartamento_pagos As p
                        LEFT JOIN (
                              SELECT ab.as_deuda,SUM(ab.aa_monto) As aa_saldo from apartamento_pago_agregado As ab WHERE ab.aa_estado=3  group by ab.as_deuda
                            ) As dp ON p.pg_id=dp.as_deuda
                        WHERE DATE_FORMAT(p.pg_fecha,'%Y-%m') = '".$fecha."' AND p.pg_apartamento='".$id."')
                        UNION ALL(
                        select 4 As pg_tipo_deuda,d.du_id,d.du_saldo,
                               CASE
            WHEN d.du_saldo-COALESCE(aa_saldo,0) > 0 THEN d.du_saldo-COALESCE(aa_saldo,0)
                ELSE 0
            END
               As restado,COALESCE(aa_saldo,0) AS pagado from deuda As d
                        LEFT JOIN (
                            SELECT ab.pd_deuda,SUM(ab.pd_monto) As aa_saldo
                                     from deuda_pagos As ab
                             WHERE ab.pd_estado=3 and ab.pd_apartamento='".$id."'
                             group by ab.pd_deuda
                            ) As dx ON d.du_id=dx.pd_deuda
                        WHERE d.du_apartamento='".$id."' AND d.du_estado IN(1,2)
                        GROUP BY pg_tipo_deuda,d.du_id HAVING restado > 0
                            )");


                        $deuda_query = $this->general->query("select d.du_id,
       CASE
           WHEN  d.du_saldo > COALESCE(monto,0) THEN d.du_saldo-COALESCE(monto,0)
               ELSE COALESCE(monto,0)-d.du_saldo
                   END
     As restado from deuda As d
                        LEFT JOIN (
                            SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
                            ) As dp ON d.du_id=dp.pd_deuda
                        WHERE d.du_apartamento='".$id."'
                        #HAVING (restado) >= 0
                        ");


                       $idd = array_column($deuda_query,'du_id');
                       $deuda_id = ((isset($idd)) and (count($idd) >= 1)) ? implode(',',$idd) :" ''";



                        $comprobantes = $this->general->query("(
                        select dp.pg_tipo_deuda,dp.pg_saldo As saldo_pagar,
                               CASE dp.pg_tipo_deuda
            WHEN 1 THEN 'Dedua Agua'
            WHEN 2 THEN 'Dedua Condominal'
            WHEN 3 THEN 'Dedua Extraordinaria'
            ELSE ''
        END
        As titulo_deuda
        ,ab.as_id,ab.aa_monto,DATE_FORMAT(ab.aa_fecha,'%Y-%m-%d') As aa_atcreate,apa.aa_comprobante,ab.aa_estado from apartamento_pagos As dp
                        JOIN apartamento_pagos_abono apa on dp.pg_id = apa.aa_deuda
                        JOIN apartamento_pago_agregado As ab ON apa.aa_id=ab.as_agregado  AND  dp.pg_id=ab.as_deuda
                        WHERE dp.pg_apartamento='".$id."'  AND DATE_FORMAT(dp.pg_fecha,'%Y-%m') = '".$fecha."'
                        )
                        UNION ALL
                        (
                        select 4 As pg_tipo_deuda,d.du_saldo As saldo_pagar,d.du_nombre,dp.dp_id,dp.pd_monto,DATE_FORMAT(dp.pd_atcreate,'%Y-%m-%d') As aa_atcreate,dp.pd_comprobante,dp.pd_estado from deuda As d
                        JOIN deuda_pagos dp on d.du_id = dp.pd_deuda
                        WHERE d.du_id IN($deuda_id) AND d.du_apartamento='".$id."'
                        ) ");



                        $data = [];
                        foreach($this->class_data->comprobante As $com_id => $com_vl){
                            $data[$com_id] = [
                                'id' => $com_id,
                                'nombre' => $com_vl,
                                'monto' => 0,
                                'pagado' => 0,
                            ];


                            foreach($comprobantes As $comp){
                                if($com_id == $comp['pg_tipo_deuda']) {
                                    $data[$com_id]['comprobantes'][] = $comp;
                                }
                            }

                            foreach($clc_header As $dd) {

                                if ($com_id == $dd['pg_tipo_deuda']) {
                                    if (!isset($data[$com_id])) {
                                        $data[$com_id]['monto']  += $dd['restado'];
                                        $data[$com_id]['pagado'] += $dd['pagado'];

                                    } else {
                                        $data[$com_id]['monto']  += $dd['restado'];
                                        $data[$com_id]['pagado'] += $dd['pagado'];
                                    }
                                }
                            }
                        }
//                        print_r($data);
                        $this->load->view('modal/admin/pagos/v_comprobantes',array('page' => $page,'id' => $data1,'data' => $data,'filial' => $id,'fecha' => $fecha));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar si existen Comprobantes']);
                    }
                }



                /*
                 * MODULO EXCLUSIVO PARA FILIALES
                 * */

                //invitados
                elseif($page == 'f_invitados'){
                    $id = (strlen($data1) >= 7) ? desencriptar($data1) : '';
                    $datas = $this->general->get('propietarios_visitante',['pv_id' => $id],'array');
                    $this->load->view('modal/filial/v_invitados',array('page' => $page,'id' => $data1,'datas' => $datas));
                }
                elseif($page == 'f_condomino'){
                    $id = (strlen($data1) >= 7) ? desencriptar($data1) : '';
                    $datas = $this->general->get('usuarios',['u_id' => $id],'array');
                    $this->load->view('modal/filial/v_condomino',array('page' => $page,'id' => $data1,'datas' => $datas));
                }
                elseif($page == 'f_mascotas'){
                     $id = (strlen($data1) >= 7) ? desencriptar($data1) : '';
                     $apartamento = $this->user_data->u_apartamento;
                    if($this->general->exist('apartamento',['t_id' => $apartamento])){
                        $mascota = $this->general->get('propietarios_mascostas',['pm_id' => $id],'array');
                        $datas = $this->general->all_get('propietarios_mascostas',['pm_apartamento' => $apartamento],[],'array');
                        $this->load->view('modal/filial/v_mascota',array('page' => $page,'id' => $data1,'data' => $mascota,'datas' => $datas));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que el propietario exista']);
                    }
                }
                elseif($page == 'f_vehiculos'){
                    $id = (strlen($data1) >= 7) ? desencriptar($data1) : '';
                    $apt =  $this->user_data->u_apartamento;
                    $vehiculos = $this->general->get('propietarios_vehiculos',['pv_id' => $id,'pv_propietario' => $apt],'array');
                    $datas = $this->general->all_get('propietarios_vehiculos',['pv_propietario' => $apt],[],'array');
                    $this->load->view('modal/filial/v_vehiculos',array('page' => $page,'id' => $data1,'data' => $vehiculos,'datas' => $datas));

                }
                //Cliente deuda
                elseif($page == 'f_dueda_total'){


                    if(in_array($data1,[1,2,3,4])){
                        $apt_id = $this->user_data->u_apartamento;
                        $tipo   = $data1;
                        $data = [];

                        $favor = ($this->general->sum_data('saldo_favor',['sf_apartamento' => $apt_id],'sf_saldo')->suma);
                        $tipo_nombre = $this->class_security->array_data($tipo,$this->class_data->comprobante);

                        if(in_array($data1,[1,2,3])){

                            $all_data = $this->general->query("SELECT ap.pg_id As id,'' As du_nombre,ap.pg_fecha As fecha,ap.pg_anno,ap.pg_deuda,COALESCE(abono,0) As abono,ap.pg_deuda-COALESCE(abono,0) As saldado FROM apartamento_pagos AS ap
LEFT JOIN (
    SELECT pa.as_deuda,SUM(pa.aa_monto) As abono from apartamento_pago_agregado AS pa WHERE pa.aa_estado=3 GROUP BY pa.as_deuda
    ) As pa ON ap.pg_id=pa.as_deuda
WHERE ap.pg_apartamento='".$apt_id ."'  AND pg_tipo_deuda='".$tipo ."' AND ap.pg_estado IN(1,2)
GROUP BY ap.pg_id,ap.pg_mes,ap.pg_anno
HAVING saldado > 0",'array');

                        }else{
                            $all_data = $this->general->query("SELECT d.du_id As id,d.du_nombre,DATE_FORMAT(d.du_atcreate,'%Y-%m-%d') As fecha,d.du_saldo As deuda,COALESCE(monto,0) As abono,d.du_saldo-COALESCE(monto,0) As saldado FROM deuda AS d
    LEFT JOIN
    (
        SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3 group by dp.pd_deuda
    ) As dp ON d.du_id=dp.pd_deuda
     WHERE d.du_apartamento='".$apt_id ."' AND d.du_estado IN (1,2)
   GROUP BY d.du_id HAVING saldado > 0",'array');
                        }

                        $saldo_total = 0;

                        if(count($all_data) >= 1) {
                            foreach ($all_data as $dl) {

                                $deuda = $dl['saldado'];
                                $saldo_total += $deuda;

                                $data[] = array(
                                    'id' => encriptar($dl['id']),
                                    'nombre' =>  (in_array($data1,[1,2,3])) ? $this->class_security->array_data($data1,$this->class_data->comprobante,'') : $dl['du_nombre'],
                                    'fecha' =>  $dl['fecha'],
                                    'deuda' => $this->class_security->dinero($deuda),
                                    'abono' => $this->class_security->dinero($dl['abono']),
                                );
                            }
                        }
                      $this->load->view('modal/filial/deuda/v_deuda_total',array('page' => $page,'dataid' => encriptar($data1),'datas' => $data,'favor' => $favor,'tipo_nombre' => $tipo_nombre,'deuda_total' => '₡ '.$this->class_security->dinero($saldo_total)));

                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar el tipo de deuda']);
                    }


                }
                elseif($page == 'f_dueda'){
                    $suma_deuda     = $this->general->sum_data('deuda',array('du_apartamento' => $this->user_data->u_apartamento,'du_estado' => 1),'du_saldo');
                    $suma_pagado    = $this->general->sum_data('deuda_pagos',array('pd_apartamento' => $this->user_data->u_apartamento,'pd_estado' => 3),'pd_monto');
                    $datas  = $this->general->query("SELECT d.*,COALESCE((SELECT SUM(p.pd_monto) AS suma FROM deuda_pagos AS p WHERE p.pd_deuda=d.du_id AND p.pd_estado=3),0) AS saldo_pagado  FROM deuda AS d WHERE d.du_apartamento='".$this->user_data->u_apartamento."'",'obj');
                    $this->load->view('modal/filial/deuda/v_deuda',array('page' => $page,'saldo' => '₡ '.$this->class_security->dinero($suma_deuda->suma),'saldado' => '₡ '.$this->class_security->dinero($suma_pagado->suma),'id' => $data1,'datas' => $datas));
                }
                elseif($page == 'f_deuda_pago'){
                    $id = (strlen($data1) >= 7) ? desencriptar($data1) : '';
                    $apartamento_id = $this->user_data->u_apartamento;
                    if($this->general->exist('deuda',['du_id' => $id])){

                        //datos deuda
                        $deudas = $this->general->get('deuda',array('du_apartamento' => $apartamento_id,'du_id' => $id));

                        //saldos pagados
                        $pagos_saldar = $this->general->all_get('deuda_pagos',array('pd_apartamento' => $apartamento_id,'pd_deuda' => $id));

                        //suma de saldos agregados
                        $saldo_deuda = $this->general->sum_data('deuda_pagos',['pd_deuda' => $id,'pd_apartamento' => $apartamento_id,'pd_estado' => 3],'pd_monto');
                        $saldo_valor = (isset($saldo_deuda) AND ($saldo_deuda->suma >= 1)) ? ($saldo_deuda->suma) : 0;

                        $clc_favor = $this->general->sum_data('saldo_favor',['sf_apartamento' => $apartamento_id],'sf_saldo');
                         $favor = (isset($clc_favor) AND ($clc_favor->suma >= 1)) ? ($clc_favor->suma) : 0;

                        $this->load->view('modal/filial/deuda/v_deuda_pago',array('page' => $page,'id' => $data1,'favor' => $favor,'deuda' => $deudas,'saldo' => $saldo_valor,'datas' => $pagos_saldar));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que el propietario exista']);
                    }
                }
                //saldos filial
                elseif($page == 'f_saldo'){

                    $data = array();
                    $apartamento_id = $this->user_data->u_apartamento;
                    $year = date("Y");

                    $otrasd = $this->class_security->data_deudas($apartamento_id,$year);
                    foreach($this->class_data->meses as $me_id => $me_va){
                        $comprobante = '';
                        $m3 = '';
                        //variables
                        $agua = $this->general->get('lectura_agua',array('lt_mes' => $me_id,'lt_anno' => $year,'lt_apartamento' => $apartamento_id),'arr');
                        if(isset($agua)  AND (count($agua) >= 1)){
                            $comprobante = $agua['lt_imagen'];
                            $m3 =  $agua['lt_consumo'];
                        }


                        $deu1 =  $this->class_security->validar_deudas($otrasd,1,$me_id,$year);
                        $deu2 =  $this->class_security->validar_deudas($otrasd,2,$me_id,$year);
                        $deu3 =  $this->class_security->validar_deudas($otrasd,3,$me_id,$year);
                        $total = $this->class_security->solo_numerico($deu1['deuda'])+$this->class_security->solo_numerico($deu2['deuda'])+$this->class_security->solo_numerico($deu3['deuda']);

                        $data[] = array(
                            'nombre'    => $me_va,
                            'm3'        => $m3,
                            'comprobante' => $comprobante,
                            'deuda1'    => $deu1,
                            'deuda2'    => $deu2,
                            'deuda3'    => $deu3,
                            'total'     => $this->class_security->dinero($total)
                        );
                    }
                    $this->load->view('modal/filial/deuda/v_saldo',array('page' => $page,'datas' => $data));
                }
                elseif($page == 'f_saldo_abono'){
                    $id = (strlen($data1) >= 7) ? desencriptar($data1) : '';
                    $apartamento_id = $this->user_data->u_apartamento;
                    if($this->general->exist('apartamento_pagos',['pg_id' => $id,'pg_apartamento'=> $apartamento_id])){

                        //saldo cancelar
                        $clc_deuda = $this->general->get('apartamento_pagos',['pg_id' => $id,'pg_apartamento' => $apartamento_id]);

                        //abono
                        $clc_favor = $this->general->sum_data('saldo_favor',['sf_apartamento' => $apartamento_id],'sf_saldo');
                        $valor = (isset($clc_favor) AND ($clc_favor->suma > 0)) ? $this->class_security->dinero($clc_favor->suma) : 0;

                        //saldo sumado
                        $clc_cancelado = $this->general->sum_data('apartamento_pago_agregado',['as_apartamento' => $apartamento_id,'as_deuda' => $id,'aa_estado' => 3],'aa_monto');
                        $cancelado = (isset($clc_cancelado) AND ($clc_cancelado->suma >= 1)) ? ($clc_cancelado->suma) : 0;

                        $abonos = $this->general->query("select ap.aa_comprobante,apa.* from apartamento_pagos_abono As ap INNER JOIN apartamento_pago_agregado As apa ON ap.aa_id=apa.as_agregado WHERE ap.aa_deuda=$id AND ap.as_apartamento=$apartamento_id",'array');

                        $this->load->view('modal/filial/deuda/v_saldo_abono',array('page' => $page,'id' => $data1,'abonos' => $abonos,'deuda' => $clc_deuda,'favor' => $valor,'cancelado' => $cancelado));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que la deuda exista']);
                    }
                }


                //ticket usuario
                elseif($page == 'f_reserva'){

                    //validacion de datos
                    $f_inicial  = $data2;
                    $f_final    = $data3;

                        $rango_fechas = $this->class_security->rango_dias($f_inicial,$f_final);
                        $data = $this->general->all_get('propiedades',['p_estado' => 1],[],'array');
                        $this->load->view('modal/filial/reserva/v_reserva_propiedad',array('page' => $page,'dias' => $rango_fechas,'data1' => $f_inicial,'data2' => $f_final,'datas' => $data));
                }
                elseif($page == 'f_reserva_update'){
                    //validacion de datos
                    $id  = desencriptar($data1);
                    if($this->general->exist('reserva',['r_id' => $id]) ){
                        $data = $this->general->get('reserva',['r_id' => $id],'array');
                        $this->load->view('modal/filial/reserva/v_reserva_propiedad',array('id'=> $data1,'page' => $page,'dias' => $rango_fechas,'data1' => $f_inicial,'data2' => $f_final,'data' => $data));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'La propiedad no esta disponible o no se selecciono ningun dia para el prestamo']);
                    }
                }
                elseif($page == 'f_reserva_lista'){
                        $id = $this->user_data->u_apartamento;
                        $reservas = $this->general->query("select r.r_id,r.r_fecha_inicio,r.r_fecha_salida,r.r_atcreate,r.r_estado,p.p_imagen,p.p_nombre,COALESCE(count(rd.rd_id),0) As dias from reserva As r
JOIN propiedades p on r.r_propiedad = p.p_id JOIN reserva_dias As rd ON r.r_id = rd.rd_reserva WHERE r.r_apartamento='".$id."' 
GROUP BY r.r_id,r.r_fecha_inicio,r.r_fecha_salida,r.r_atcreate,r.r_estado,p.p_nombre",'array'
                        );
                        $this->load->view('modal/filial/reserva/v_lista',array('id'=> $data1,'page' => $page,'datas' => $reservas));

                }
                elseif($page == 'f_reserva_comprobante'){
                    $id = desencriptar($data1);
                    $apt = $this->user_data->u_apartamento;
                    if($this->general->exist('reserva',['r_id' => $id,'r_apartamento' => $apt])){
                        $reserva = $this->general->query("select * from reserva As r JOIN propiedades p on r.r_propiedad = p.p_id  WHERE  r.r_id='$id'",'array');
                        $reserva_dias    = $this->general->all_get('reserva_dias',['rd_reserva'=> $id]);
                        $this->load->view('modal/filial/reserva/v_comprobante',array('id'=> $data1,'page' => $page,'datas' => $reserva[0],'dias' => $reserva_dias,'id' => $data1));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar la Reserva']);
                    }
                }

                //ticket usuario
                elseif($page == 'ticket'){
                    $id = desencriptar($data1);
                    if($this->general->exist('ticket',['t_id' => $id])){
                        $ticket = $this->general->get('ticket',array('t_id' => $id),'array');
                        $ticket_mensajes = $this->general->all_get_join('usuarios As u',[['ticket_response As tr','u.u_id=tr.tr_usuario']],['tr.tr_ticket'=> $id]);
                        $this->load->view('modal/filial/ticket/v_ticket_response',array('id'=> $data1,'page' => $page,'datas' => $ticket,'mensajes' => $ticket_mensajes,'id' => $data1));
                    }else{
                        $this->load->view('modal/filial/ticket/v_ticket_filial_create');
                    }
                }

                //saldos filial
                elseif($page == 'lectura_medidor'){
                    $id = (strlen($data1) >= 7) ? desencriptar($data1) : '';
                    $fecha = date("Y-m");

                    if($this->general->exist('apartamento',['t_id' => $id])){
                        //lectura de agua
                        $lectura = $this->general->query("CALL proc_lectura('$id','$fecha');",'obj');
                        $this->load->view('modal/v_lectura',array('page' => $page,'id' => $data1,'lectura' => isset($lectura[0]) ? $lectura[0] : []));
                    }else{
                        $this->load->view('modal/v_error',['msg' => 'Validar que el propietario exista']);
                    }
                }


                //saldos filial
                elseif($page == 'vigilante_ingreso'){
                    $filiales  = $this->general->all_get('apartamento');
                    $this->load->view('modal/vigilante/v_registro_ingreso',array('page' => $page,'filiales' => $filiales));
                }
                elseif($page == 'vigilante_reporte'){
                    $this->load->view('modal/vigilante/v_registro_reporte',array('page' => $page));
                }


                else{
                    $this->load->view('modal/v_error',['msg' => 'No se encontro el models']);
                }

            }else{
                $this->load->view('modal/v_error',['msg' => 'No se encontro el models']);
            }

        }else{
            //no hay vistas que mostrar
            show_404();
        }

    }

    }