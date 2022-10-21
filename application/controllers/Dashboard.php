<?php


class Dashboard extends CI_Controller
{
    //propiedades
    private $session_id;
    private $user_data;
    private $permission          = '';
    private $controllName        = '';
    private $controller          = 'afiliales/';
    private $controller_lector   = 'lector/';
    private $controller_servicio   = 'proveedor/';
    private $project;
    private $result = array();

    public function __construct(){
        parent::__construct();

        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();

        $this->user_data = $this->general->get('usuarios',array('u_id' => $this->session_id));

    }

    function index(){
        $data_head = array(
            'titulo'        => $this->project['tiulo'],
            'modulo'        => $this->controllName,
            'controller'    => 'Dashboard',
            'user'          => $this->user_data,
            'style_level'   => array('plugins/morris.js/morris.css')
        );


        //fecha del mes actual;
        if($this->user_data->u_perfil == 1 || $this->user_data->u_perfil == 5){

            $data_body = array(

                'crud' => array(
                    'url_modals'                 => base_url("modal/"),
                    'dasboard_data'                 => base_url("dashboard/data"),
                )
            );

            $data_foot = array('script_level' => array('cr/crud_data.js','plugins/raphael/raphael.min.js','plugins/morris.js/morris.min.js','cr/servicios.js','plugins/numero/AutoNumeric.js','cr/dashboard.js'));


            $this->load->view('template/v_header',$data_head);
            $this->load->view('template/v_menu');
            $this->load->view('dashboard/v_dashboard',$data_body);
            $this->load->view('template/v_footer',$data_foot);
        }else{

            //afiliales
            if($this->user_data->u_perfil == 2){



                $pantalla = 'dashboard/v_dashboard_afilial';

                $data_body = array(
                    'visitantes' => $this->general->all_get('propietarios_visitante',array('pv_apartamento' => $this->user_data->u_apartamento)),
                    'afiliales'  => $this->general->all_get('usuarios',array('u_apartamento' => $this->user_data->u_apartamento,'u_id != ' => $this->user_data->u_id)),
                    'vigilantes' => $this->general->all_get('usuarios',array('u_perfil' => 3,'u_estado' => 1)),
                    'lectura'     => $this->general->get('lectura_agua',array('lt_apartamento' => $this->user_data->u_apartamento,'lt_mes' => date('n'),'lt_anno' => date('Y'))),
                    'deudas'     => $this->validar_deudas(),
                    'crud' => array(
                        'tipo_usuario'             => 1,
                        'terminos'                 => $this->user_data->u_terminos,
                        'url_modals'               => base_url("modal/"),
                        'url_condiciones'     => base_url("{$this->controller}save_condiciones"),
                        'url_save_comprobante_reserva'     => base_url("{$this->controller}save_comprobante_reserva"),
                        'url_save_comprobante'     => base_url("{$this->controller}save_comprobante"),
                        'url_save_deuda_total'     => base_url("{$this->controller}save_deuda_total"),
                        'url_get'                  => base_url("{$this->controller}visitante_get"),
                        'url_save'                 => base_url("{$this->controller}visitante_save"),
                        'url_delete'               => base_url("{$this->controller}visitante_delete"),

                        'url_save_deuda'           => base_url("{$this->controller}save_deuda"),


                        'url_aflial_save'          => base_url("{$this->controller}condomino_save"),
                        'url_aflial_delete'        => base_url("{$this->controller}condomino_delete"),

                        //vehiculo
                        'url_save_vehiculo'        => base_url("{$this->controller}save_vehiculo"),
                        'url_dell_vehiculo'        => base_url("{$this->controller}dell_vehiculo"),

                        //mascotas
                        'url_save_mascotas'        => base_url("{$this->controller}save_mascotas"),
                        'url_get_mascotas_all'     => base_url("{$this->controller}get_all_mascotas"),
                        'url_get_mascotas'         => base_url("{$this->controller}get_mascotas"),
                        'url_dell_mascotas'        => base_url("{$this->controller}dell_mascotas"),
                    ),
                );
                $data_foot = array('script_level' => array('cr/crud_data.js','cr/all_scripts.js','cr/afiliales.js','cr/modals.js','plugins/numero/AutoNumeric.js'));
            }elseif($this->user_data->u_perfil == 4) {
                $pantalla = 'dashboard/v_dashboard_lector';
                $data_body = array(
                    'titulo'        => $this->project['tiulo'],
                    'modulo'        => $this->controllName,
                    'controller'    => 'Dashboard',
                    'crud' => array(
                        'url_modals'    => base_url("modal/"),
                        'url_search'    => base_url("{$this->controller_lector}search_propiedades"),
                        'url_save'      => base_url("{$this->controller_lector}save_lectura"),
                    )
                );
                $data_foot = array('script_level' => array('cr/crud_data.js','cr/recolector.js'));
            }elseif($this->user_data->u_perfil == 6) {
                $pantalla = 'dashboard/v_proveedores';
                $data_body = array(
                    'titulo'        => $this->project['tiulo'],
                    'modulo'        => $this->controllName,
                    'controller'    => 'Dashboard',
                    'crud' => array(
                        'url_modals'    => base_url("modal/"),
                        'url_datatable'    => base_url("{$this->controller_servicio}datatable"),
                        'url_comprobante_save'      => base_url("{$this->controller_servicio}save_comprobante"),
                    )
                );
                $data_foot = array('script_level' => array('cr/crud_data.js','cr/proveedores.js'));
            }else{

                $pantalla = 'dashboard/v_dashboard_vigilante';
                $data_body = array(
                    'titulo'        => $this->project['tiulo'],
                    'modulo'        => $this->controllName,
                    'controller'    => 'Dashboard',
                    'crud' => array(
                        'url_modals'    => base_url("modal/"),

                        'url_search'       => base_url("{$this->controller_lector}search_propiedades"),
                        'url_validar_acceso'       => base_url("{$this->controller_lector}valdiar_acceso"),
                        'url_buscar_usuario'       => base_url("{$this->controller_lector}buscar_usuario"),
                        'url_search_visitante'     => base_url("{$this->controller_lector}search_visitante"),
                        'url_get'                  => base_url("{$this->controller_lector}get_propiedades"),
                        'url_search_reporte'       => base_url("{$this->controller_lector}search_visitante_reporte"),
                        'url_data_filial'          => base_url("{$this->controller_lector}data_filial"),
                    )
                );
                $data_foot = array('script_level' => array('cr/crud_data.js','cr/pantalla.js'));
            }

            $this->load->view('template/alt/v_header',$data_head);
            $this->load->view($pantalla,$data_body);
            $this->load->view('template/alt/v_footer',$data_foot);

        }
    }

    private function validar_deudas(){

        $apt = $this->user_data->u_apartamento;
        $anno = date("Y");

        //obtener todos los registros
        $datas = $this->general->query("(select ap.pg_tipo_deuda as tipo_deuda,ap.pg_id,SUM(ap.pg_deuda-COALESCE(clc_pagado,0)) As pagado from apartamento_pagos As ap
    LEFT JOIN (
        SELECT ag.as_deuda,SUM(ag.aa_monto) As clc_pagado from apartamento_pago_agregado As ag WHERE ag.aa_estado=3 GROUP BY ag.as_deuda
    ) As t ON ap.pg_id=t.as_deuda
WHERE ap.pg_apartamento='".$apt."' AND ap.pg_estado IN(1,2) GROUP BY ap.pg_tipo_deuda,ap.pg_id HAVING pagado >= 0)
UNION ALL
(SELECT 4  as tipo_deuda,d.du_id,SUM(d.du_saldo-COALESCE(clc_pagado,0)) As pagado from deuda As d
 LEFT JOIN (
     SELECT dp.pd_deuda,SUM(dp.pd_monto) As clc_pagado from deuda_pagos As dp WHERE dp.pd_estado=3 GROUP BY dp.pd_deuda
    ) As t ON d.du_id=t.pd_deuda
WHERE d.du_apartamento='".$apt."' AND d.du_estado IN(1,2) GROUP BY d.du_id HAVING pagado >= 0)
",'obj');


        $d1_monto       = 0;
        $d2_monto       = 0;
        $d3_monto       = 0;
        $d4_monto       = 0;

        if(count($datas) >= 1){
            foreach($datas As $dd){

                if($dd->tipo_deuda == 1){
                    $d1_monto += $dd->pagado;
                }

                if($dd->tipo_deuda == 2){
                     $d2_monto += $dd->pagado;
                }

                if($dd->tipo_deuda == 3){
                    $d3_monto += $dd->pagado;
                }

                if($dd->tipo_deuda == 4){
                    $d4_monto += $dd->pagado;
                }

            }
        }


        $total = $d1_monto+$d2_monto+$d3_monto+$d4_monto;

        return array(
            'deuda1' =>['monto' =>  $this->class_security->dinero($d1_monto), 'valor'   => $this->formatear_dividir($d1_monto,$total)],
            'deuda2' => ['monto' =>  $this->class_security->dinero($d2_monto), 'valor'  => $this->formatear_dividir($d2_monto,$total)],
            'deuda3' => ['monto' =>  $this->class_security->dinero($d3_monto), 'valor'  => $this->formatear_dividir($d3_monto,$total)],
            'deuda4' => ['monto' =>  $this->class_security->dinero($d4_monto), 'valor'     => $this->formatear_dividir($d4_monto,$total)],
            'total' => $this->class_security->dinero($total),
            'favor' => $this->class_security->dinero($this->general->sum_data('saldo_favor',['sf_apartamento' => $this->user_data->u_apartamento],'sf_saldo')->suma),
        );
    }

    private function formatear_dividir($num1 = 0,$num2 = 0){
        $result = 0;
        if($num1 != 0 and $num2 != 0){
            $result = $this->class_security->dinero(($num1/$num2)*100,2);
        }
        return $result;
    }



    function data(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('token','fecha'))){
                    $anno = date("Y");
                    $fecha = $this->class_security->data_form('fecha');
//                    $fecha = '2022-04';
                    $rangos = $this->class_security->rango_fechas("$anno-01","$anno-12");
                    $datas  = [];
                    $i = 1;
                    $time=strtotime($fecha);

                    foreach($rangos As $rg){

                        $moroso_query = $this->general->query("SELECT DISTINCT a.t_id from apartamento As a  INNER JOIN deuda as d ON a.t_id=d.du_apartamento WHERE d.du_estado IN(1,2) AND DATE_FORMAT(d.du_fecha_inicio, '%Y-%m') = '$rg' ",'array');

                        $moroso = count($moroso_query);

                        $datas[] = array(
                            'mes'    => substr($this->class_data->meses[$i],0,3),
                            'agua'   =>$this->data_paga(1,$rg),
                            'cd'     =>$this->data_paga(2,$rg),
                            'ce'     =>$this->data_paga(3,$rg),
                            'deuda'  =>$this->data_paga(4,$rg),
                            'moroso' => $moroso,
                        );
                        $i++;
                    }

                    //obtener la informacion de la deuda tipo 2

                     //variables para la deuda tipo 2

                      $d_filial_deuda = 0;
                      $d_filial_pago  = 0;

                      $d_deuda_deuda = 0;
                      $d_deuda_pago  = 0;


                    $condominal_query = $this->general->query("
                    select  '1' As nada,SUM(COALESCE(ap.pg_deuda,0)) As d_deuda,SUM(COALESCE(pagado,0)) As d_pagado from  apartamento_pagos As ap
                        LEFT JOIN (
                            select ag.as_deuda,SUM(ag.aa_monto) As pagado from apartamento_pago_agregado As ag WHERE ag.aa_estado=3 group by ag.as_deuda
                        ) As t ON ap.pg_id=t.as_deuda
                        WHERE DATE_FORMAT(ap.pg_atcreate,'%Y-%m') = '".$fecha."' AND ap.pg_tipo_deuda=2
                    GROUP BY nada
                    ",'obj');


                    if(count($condominal_query) >= 1){
                        $data_dp = $condominal_query[0];
                        $d_filial_deuda = $data_dp->d_deuda;
                        $d_filial_pago  = $data_dp->d_pagado;
                        }



                    //informacion pago de deudas
                    $testdate=strtotime($fecha);
                        if (date('Ym')==date('Ym', $testdate)) {
//                            $deuda_anterior_fecha = $this->general->query("select '1' As fecha,SUM(du_saldo)  As rpagado from deuda as d  WHERE DATE_FORMAT(d.du_fecha_inicio, '%Y-%m') < '".$fecha."' AND d.du_estado IN (1,2) group by fecha",'array');

                            $deuda_anterior_fecha = $this->general->query("select '1' As fecha,SUM(du_saldo)-COALESCE(SUM(monto),0)  As rpagado from deuda as d
                                   LEFT JOIN (
                                        SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3   GROUP BY dp.pd_deuda
                                    ) As t ON d.du_id=t.pd_deuda
                          WHERE DATE_FORMAT(d.du_fecha_inicio, '%Y-%m') < '".$fecha."' AND d.du_estado IN (1,2)
                        group by fecha
                        ",'array');

                            $d_deuda_deuda += (count($deuda_anterior_fecha) >= 1) ? array_sum(array_column($deuda_anterior_fecha, 'rpagado')) : 0;
                    }

                        //deudas actuales por fecha
        $deudas_fecha = $this->general->query("select '1' As fecha,SUM(du_saldo)-COALESCE(SUM(monto),0)  As deuda,(SELECT COALESCE(SUM(dp.pd_monto),0) As monto from deuda_pagos As dp WHERE dp.pd_estado=3 AND DATE_FORMAT(dp.pd_atcreate, '%Y-%m') = '".$fecha."')  As pagado from deuda as d
                                   LEFT JOIN (
                                        SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3   GROUP BY dp.pd_deuda
                                    ) As t ON d.du_id=t.pd_deuda
                          WHERE DATE_FORMAT(d.du_fecha_inicio, '%Y-%m') = '".$fecha."' AND d.du_estado IN (1,2)
                        group by fecha",'obj')[0];

        $d_deuda_deuda += (isset($deudas_fecha->deuda)) ? $deudas_fecha->deuda : 0;
        $d_deuda_pago  += (isset($deudas_fecha->pagado)) ? $deudas_fecha->pagado : 0;


        $moroso_cantidad = count($this->general->query("SELECT DISTINCT a.t_id from apartamento As a  INNER JOIN deuda as d ON a.t_id=d.du_apartamento WHERE d.du_estado IN(1,2)"));



                $this->result = array('success' => 1,'data' => [
                    'condominal' => [
                        'deuda' =>  '₡ '.$this->class_security->dinero($d_filial_deuda),
                        'pagado' => '₡ '.$this->class_security->dinero($d_filial_pago),
                    ],
                    'deuda' => [
                        'deuda'  =>  '₡ '.$this->class_security->dinero($d_deuda_deuda),
                        'pagado' =>  '₡ '.$this->class_security->dinero($d_deuda_pago),
                    ],
                    'moroso_cantidad' => $moroso_cantidad,
                    'morosos' => $datas
                ]);


            }else{
                $this->result = array('success' => 2,'msg' => 'Que haces!');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);
    }

    private function data_paga($tipo,$fecha){

        if($tipo == 4){
            $dd = $this->general->query("select COALESCE(SUM(COALESCE(du_saldo,0))-COALESCE(SUM(monto),0),0)  As rpagado from deuda as d
           LEFT JOIN (
                SELECT dp.pd_deuda,SUM(dp.pd_monto) As monto from deuda_pagos As dp WHERE dp.pd_estado=3 AND DATE_FORMAT(dp.pd_atcreate, '%Y-%m') = '".$fecha."'  GROUP BY dp.pd_deuda
            ) As t ON d.du_id=t.pd_deuda
  WHERE DATE_FORMAT(d.du_fecha_inicio, '%Y-%m') = '".$fecha."'",'array')[0];
        }else{
            $dd = $this->general->query("select COALESCE(SUM(COALESCE(ap.pg_deuda,0))-COALESCE(SUM(monto),0),0)  As rpagado from apartamento_pagos As ap
 LEFT JOIN (
      SELECT ag.as_deuda,SUM(ag.aa_monto) As monto  from apartamento_pago_agregado As ag WHERE ag.aa_estado=3 AND DATE_FORMAT(ag.aa_fecha, '%Y-%m') =  '".$fecha."' GROUP BY ag.as_deuda
    ) As t ON ap.pg_id=t.as_deuda
WHERE DATE_FORMAT(ap.pg_fecha, '%Y-%m') =  '".$fecha."'  AND ap.pg_tipo_deuda='".$tipo."'",'array')[0];
        }


        return (count($dd) >=1) ? $dd['rpagado'] : 0;

    }

}