<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Class_security{

    private $CI;
    private $result;

    function __construct(){
        $this->CI = &get_instance();
    }

    function letras_numeros($limpiardato){
        return preg_replace('#[^a-zA-Z0-9]#i', '', strip_tags(trim($limpiardato)));
    }

    function letras_numeros_espace($limpiardato){
        return preg_replace('#[^a-zA-Z0-9-=._, ]#i', '', strip_tags(trim($limpiardato)));
    }

    function letras_numeros_mas($datos_filtrar)
    {
        $limpiar1 = strip_tags(trim($datos_filtrar));
        $limpiar2 = preg_replace('#[^a-zA-Z0-9-=._]#i', '', $limpiar1);
        return $limpiar2;
    }

function solo_numerico($datos_filtrar) {
        $limpiar1 = strip_tags(trim($datos_filtrar));
        $limpiar2 = preg_replace('#[^0-9.]#i', '', $limpiar1);
        return $limpiar2;
    }

    function limpiar_form($limpiardato){
        return $limpiardato = strip_tags(trim($limpiardato));
    }

    function Int($datos_filtrar){
        return  preg_replace('#[^0-9]#i', '', strip_tags(trim($datos_filtrar)));
    }

    function capitalizar($limpiar){
        return htmlentities(ucfirst(trim($limpiar)), ENT_QUOTES, 'UTF-8');
    }

    function codificar($limpiar)  {
        return htmlentities(trim($limpiar), ENT_QUOTES, 'UTF-8');
    }

    function decodificar($decodificar){
        return html_entity_decode($decodificar, ENT_QUOTES, 'UTF-8');
    }

    function validate_post($post_information){
        $response=true;

        foreach($post_information as $post_input) {
            //explode

            //validar si se envia la ,
            if(strpos($post_input,',')){


                $post_ex = explode(',',$post_input);
                $post_ex_input = $post_ex['0'];
                $post_ex_type  = $post_ex['1'];

                //validate type data
                if($post_ex_type == 'str'){
                    $input_data = isset($_POST[$post_ex_input]) ?  $this->limpiar_form($_POST[$post_ex_input]) : '';
                }else{
                    $input_data = isset($_POST[$post_ex_input]) ?  $this->Int($_POST[$post_ex_input]) : '';
                }

            }else{
                $input_data = isset($_POST[$post_input]) ?  $this->limpiar_form($_POST[$post_input]) : '';
            }

            if (strlen($input_data) == 0) {
                $response = false;
            }
        }
        return $response;
    }

    function data_form($input,$type = 'str',$default = ''){

        if($type == 'str'){
            $this->result = $this->limpiar_form($this->CI->input->post($input));
        }elseif($type == 'str2'){
            $this->result = $this->letras_numeros_espace($this->CI->input->post($input));
        }elseif($type == 'clean'){
            $this->result = $this->letras_numeros($this->CI->input->post($input));
        }elseif($type == 'decrypt_int'){
            $this->result = ($this->Int(desencriptar($this->CI->input->post($input))));
        }elseif($type == 'decrypt_str'){
            $this->result = ($this->letras_numeros(desencriptar($this->CI->input->post($input))));
        }elseif($type == 'uft8'){
            $this->result = $this->codificar_acentos($this->CI->input->post($input));
        }elseif($type == 'saldo'){
            $this->result = $this->solo_numerico($this->CI->input->post($input));
        }elseif($type == 'alone'){
            $this->result = $this->CI->input->post($input);
        }else{
            $this->result = $this->Int($this->CI->input->post($input));
        }


        //clean data
        if($type == 'alone'){
            $this->result = $this->result;
        }else{
            $this->result =  (strlen($this->result) >= 1) ? $this->result :  $default;
        }


        return ($this->result);
    }

    //IP USER ADdRESS
    function get_user_ip_address(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }
        return $user_ip;
    }

    function generate_password() {
        $data = '1234567890abcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($data), 0, 7);
    }

    function array_data($key,$dataAr,$default = ''){

        $result =  '';
        if(count($dataAr) >= 1){
            if(isset($dataAr[$key])){
                $result = $dataAr[$key];
            }else{
                if(is_array($default)){
                    if(count($default) >= 1){
                        $result = $default[1];
                    }
                }

            }
        }
        return $result;
    }

    function sumar_dias($dias = 0){
        return date("Y-m-d",strtotime(date('Y-m-d')."+ {$dias} days"));
    }

    function sumar_meses($dias = 0,$fecha = "Y-m-d"){
        return date($fecha,strtotime(date($fecha)."+ {$dias} month"));
    }

    function restar_dias($mes = "Y-m-d",$dias = 0){
        return date($mes,strtotime(date($mes)."+ {$dias} days"));
    }

    function sumar_segundos($fecha = 0){
        $newDate = strtotime ( '+3 second' , strtotime ($fecha) ) ;
        $newDate = date ( 'Y-m-j H:i:s' , $newDate);
        return $newDate;
    }

    function random(){
        return md5(date("Y-m-d h:m:s".mt_rand(0,9999).md5(uniqid())));
    }

    function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shuffle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),
            0, $length_of_string);
    }

    function eliminar_archivo($directorio,$documento){
        $documento_folder = $directorio.$documento;
        if ((strlen($documento) > 3) && file_exists($documento_folder)) {
            unlink($documento_folder);
        }
    }


    function upload_document($name,$directory,$extencion){
        $CI = &get_instance();
        $config['upload_path'] = $directory;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $config['allowed_types'] = $extencion;
        $CI->load->library('upload', $config);

        if ($CI->upload->do_upload($name)) {
            $data = $CI->upload->data();
            $result_documento = $data['file_name'];
        } else {
            $result_documento = '';
        }
        return $result_documento;
    }

    function upload_image($imagen,$directory){
        $CI = &get_instance();

        $_FILES['file']['name']      = $imagen['name'];
        $_FILES['file']['type']      = $imagen['type'];
        $_FILES['file']['tmp_name']  = $imagen['tmp_name'];
        $_FILES['file']['error']     = $imagen['error'];
        $_FILES['file']['size']      = $imagen['size'];


        $config['upload_path'] = $directory;
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $config['allowed_types'] = 'gif|jpg|jpeg|png';
//        $config['file_name'] = $imagen;
        $CI->load->library('upload', $config);

        if ($CI->upload->do_upload('file')) {
            $data = $CI->upload->data();
            $result_documento = $data['file_name'];
        } else {
            $result_documento = '';
        }
        return $result_documento;
    }

    function imageresize($imagen_source, $directory_new, $width, $height){
        $this->CI = &get_instance();
        $this->CI->load->library('image_lib');
        $this->CI->image_lib->clear();
        $config['image_library']  = 'gd2';
        $config['source_image']   = $imagen_source;
        $config['new_image']      = $directory_new;
        $config['create_thumb']   = TRUE;
        $config['maintain_ratio'] = FALSE;
        $config['width']          = $width;
        $config['height']         = $height;
        $config['thumb_marker']    = '';

        $this->CI->image_lib->initialize($config);
        return  $this->CI->image_lib->resize();
    }


    //sacar la cantidad de cuotas

    function sacar_cuotas($date1,$date2){
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $diff = $date1->diff($date2);
        return $diff->m;
    }

    function validar_existe_archivo($directorio,$documento){
        $documento_folder = $directorio.$documento;
        $result = false;
        if ((strlen($documento) > 3) && file_exists($documento_folder)) {
            $result = true;
        }
        return $result;
    }


    function mover_archivo($foler1,$folder2,$archivo){

        $archivo_old = "{$foler1}{$archivo}";
        $archivo_move = "{$folder2}{$archivo}";
        if($this->validar_existe_archivo($foler1,$archivo)){
            rename($archivo_old, $archivo_move);
        }
    }

    function generar_codigo(){
        $timestamp = mt_rand(5, time());
        $randomDate = date("Ydms", $timestamp);
        return $randomDate;
    }

    function rango_fechas($startDate, $endDate) {
        $months = array();
        while (strtotime($startDate) <= strtotime($endDate)) {
            $months[] = date('Y-m', strtotime($startDate));
            $startDate = date('01 M Y', strtotime($startDate.
                '+ 1 month')); // Set date to 1 so that new month is returned as the month changes.
        }

        return $months;
    }
    function rango_dias($startDate, $endDate) {
        $dias = [];
        while (strtotime($startDate) <= strtotime($endDate)) {
            $dias[] = $startDate;
            $startDate = date("Y-m-d", strtotime("+1 day", strtotime($startDate)));
        }
        return $dias;
    }

    function img_avatar($imagen = '',$tipo = 1){
        if(strlen($imagen) >= 10){
            $img2 = base_url("_files/{$imagen}");
            if($tipo == 1){
                $img = "<div class='user-with-avatar'><img class='rounded-circle' style='height: 40px;width: 40px;' src='$img2'></div>";
            }else{
                $img = "<a href='$img2' class='single_image'><img class='rounded-circle' style='height: 40px;width: 40px;' src='$img2'></a>";

            }
         }else{
            $img2 = base_url("_files/default.jpg");
            $img = "<div class='user-with-avatar'><img class='rounded-circle' style='height: 40px;width: 40px;' src='$img2'></div>";
        }
        return $img;
    }


     function url_documento($imagen = '',$tipo = 1,$class = ''){
        $title = ($tipo == 1 ) ? 'Sin Documento' : '-';
        $img = "<a href='javascript:void(0)'  class='btn  btn-sm btn-dark {$class}'>{$title}</a>";
        if(strlen($imagen) >= 10){
            $img2 = base_url("_files/{$imagen}");
            if($tipo == 1){
                $img = "<a href='$img2' target='_blank' class='btn  btn-sm btn-info btn-block {$class}'><i class='fa fa-download text-white'></i> Descargar</a>";
            }else{
                $img = "<a href='$img2' target='_blank' class='btn  btn-sm btn-info text-white {$class}'><i class='fa fa-download text-white'></i></a>";
            }
        }
        return $img;
    }

     function select_pago_valor($cp_id = '',$array_data = [],$estaddo = '',$function_txt = '',$delete = '',$modulo = ''){
         $i = $this->random();
         $name = "name_{$i}";
        $estado_select = "<select id='$name' class='form-control text-dark'  onchange='$(this).{$function_txt}(\"{$cp_id}\",\"{$modulo}\")'>";

         if($delete != ''){ unset($array_data[$delete]);}
        foreach ($array_data As $est_id => $est_vl){
            if($est_id == $estaddo){
                $estado_select .= "<option value='{$est_id}' selected>{$est_vl['title']}</option>";
            }else{
                $estado_select .= "<option value='{$est_id}'>{$est_vl['title']}</option>";
            }
        }
         $estado_select .= "</select>";
        echo "<script>
            setTimeout(() => {
                $(\"#{$name}\").val('$estaddo').attr('change');
            },400)
         
        </script>";

        return $estado_select;
    }

     function asertarMoneda($valor,$x = ''){
        $result = $valor;
        if($valor != '-'){
            $result = ($x != '') ? '₡ ' : ''. $this->dinero($valor);
        }
        return $result;
    }

    function saldo_validar($deuda,$agregado){
       $res = 0;
       $clen_deuda = ($deuda != '-') ? $deuda : 0;
       $clen_pago = ($agregado != '-') ? $agregado : 0;
        if($clen_pago > $clen_deuda){
            //hay saldo a favor
            $res = $clen_pago-$clen_deuda;
        }else{
            //saldo descuento normal
            $res = $clen_deuda-$clen_pago;
        }
        return $this->asertarMoneda($res);
    }

     function data_deudas($apartamento_id,$year){
        $CI = &get_instance();
         $CI->load->model('general');
//         $this->CI->load->model('general');
        $data = [];
        $rest =  $this->CI->general->query("SELECT pg_id,pg_deuda,pg_tipo_deuda,pg_mes,pg_estado,SUM(ad.aa_monto) As abono FROM apartamento_pagos AS ap 
LEFT JOIN apartamento_pago_agregado AS  ad ON ap.pg_id=ad.as_deuda AND ad.aa_estado=3
WHERE ap.pg_apartamento = '$apartamento_id' AND ap.pg_anno = '$year'
GROUP BY pg_id,pg_deuda,pg_tipo_deuda,pg_mes,pg_estado",'obj');
        foreach($rest As $dd){
            $id = $this->cryptedeuda($dd->pg_tipo_deuda,$dd->pg_mes);
            $data[$id] = $dd;
        }
        return $data;
    }

     function cryptedeuda($tipo,$mes){
        return md5("{$tipo}_{$mes}");
    }

     function validar_deudas($data = array(),$tt,$mes,$anno){
        $CI = &get_instance();
        $CI->load->model('general');
        $CI->load->library('class_data');

        $idx = $this->cryptedeuda($tt,$mes);
        $abono = 0;
        $deuda = 0;
        $btn = "<button type='button' disabled class='btn  btn-sm btn-dark'>Sin Registro</button>";
        if(isset($data[$idx])){
            $dxd = $data[$idx];
            $abono = $this->dinero(($dxd->abono >= 1) ? $dxd->abono : 0);
            $deuda = $this->dinero($dxd->pg_deuda);
            $id = encriptar($dxd->pg_id);
            $estado  = $CI->class_data->estado_pago[$dxd->pg_estado];
            $btn = "<button type='button' class='btn {$estado['class']}'  onclick='$(this).forms_modal2({\"page\" : \"f_saldo_abono\",\"data1\" : \"{$id}\",\"title\" : \"Saldo abono\"})'>{$estado['title']}   </button>";
//            $btn = "<button type='button' class='btn {$estado['class']}' onclick='$(this).clear_form();$(this).asignar_pagos_facturas(\"{$id}\",\"{$mes}\",\"{$anno}\")'>{$estado['title']}   </button>";

        }
        return array(
            'deuda' => $deuda,
            'abono' => $abono,
            'estado' => $btn,
        );
    }

    function simplificar_deudas($apt,$tipo,$fecha){

        $CI = &get_instance();
        $CI->load->model('general');

        $deuda = 0;
        $agregado = 0;
        $reciduo = 0;

        $consulta = "SELECT COALESCE(a.pg_deuda,0) AS debe,COALESCE(montox,0) AS monto FROM apartamento_pagos AS a
LEFT JOIN (
    SELECT ap.as_deuda,SUM(ap.aa_monto) As montox from apartamento_pago_agregado As ap WHERE ap.aa_estado=3 and DATE_FORMAT(ap.aa_fecha, '%Y-%m') = '".$fecha."' group by ap.as_deuda
    ) As t ON a.pg_id=t.as_deuda
WHERE a.pg_apartamento='".$apt."' AND  a.pg_tipo_deuda='".$tipo."' AND DATE_FORMAT(a.pg_fecha, '%Y-%m') = '".$fecha."'";

        $query_data = $CI->general->query($consulta,'array');

        if(count($query_data) >= 1){
            $deuda      = $query_data[0]['debe'];
            $agregado   = $query_data[0]['monto'];
            $reciduo    = ($deuda >= $agregado) ? $deuda-$agregado : 0;
        }

        return [
            'deuda' => $deuda,
            'agregado' => $agregado,
            'reciduo' => $reciduo,
        ];
    }

    function favor_reporte($id,$tipo,$fecha){
        $CI = &get_instance();
        $CI->load->model('general');
        $consulta = "SELECT COALESCE(SUM(ag.aa_monto),0)-COALESCE(SUM(ap.pg_deuda),0) AS favor_x FROM apartamento AS a
INNER JOIN apartamento_pagos AS ap ON a.t_id=ap.pg_apartamento
LEFT JOIN apartamento_pago_agregado AS ag ON ap.pg_id=ag.as_deuda AND ag.aa_estado=3  AND DATE_FORMAT(ag.aa_fecha, '%Y-%m') = '$fecha' 
WHERE a.t_id=$id AND DATE_FORMAT(ap.pg_fecha, '%Y-%m') = '$fecha' AND ap.pg_tipo_deuda=$tipo
HAVING favor_x >= 1";

        $response = $CI->general->query($consulta,'array');
        $favor = 0;
        if(count($response) >= 1 AND isset($response)){
            $favor = $response[0]['favor_x'];
        }
        return $favor;
    }

    function abonos_saldado($id,$monto = 0,$tipo = 0){
        $CI = &get_instance();
        $CI->load->model('general');
        $id_crypt = encriptar($id);
        $cl_monto = $this->solo_numerico($monto);

        $saldo  = 0;
        $pago  = 0;
        $favor  = 0;

        if($CI->general->exist('apartamento_pagos',['pg_id' => $id])){


            $ddata = $CI->general->get('apartamento_pagos',['pg_id' => $id]);
            $apt = $ddata->pg_apartamento;
            $apt_id = encriptar($apt);
            $tipo_id = encriptar($tipo);

            $ge_sa = $CI->general->sum_data('apartamento_pago_agregado',['as_deuda' => $id,'aa_estado' => 3],'aa_monto');
            $pago = $ge_sa->suma;


            $ge_fa = $CI->general->query("SELECT a.t_id,a.t_apartamento,ap.pg_id,COALESCE(SUM(ag.aa_monto),0)-(ap.pg_deuda) AS deuda_x FROM apartamento AS a
JOIN apartamento_pagos AS ap ON a.t_id=ap.pg_apartamento
LEFT JOIN apartamento_pago_agregado AS ag ON ap.pg_id=ag.as_deuda AND ag.aa_estado=3 AND ag.aa_favor=1
WHERE a.t_id=$apt AND ap.pg_tipo_deuda=$tipo  GROUP BY a.t_id,a.t_apartamento,ap.pg_id HAVING deuda_x > 0");

        }

        if(count($ge_fa) and isset($ge_fa)){
            foreach($ge_fa As $ff){
                $favor += $ff['deuda_x'];
            }
//            $favor = array_sum($ge_fa['deuda_x']);
        }


        //validar si el saldo supera el valor
        if($pago > $cl_monto){
//            $favor = ($ge_fa >= 1) ? $ge_fa-$cl_monto : 0;
        }else{
            $saldo = $cl_monto-$pago;
        }


        return [
            'pago'  => $pago,
            'saldo' => ($saldo),
            'favor' =>   "<a href='javascript:void(0)' class='text-success' onclick='$(this).asinar_saldo_favor(\"$apt_id\",\"$tipo_id\")'><b>₡ ".$this->dinero($favor)."</b></a>",
            'favorn' => $favor
        ];

    }

    function saldo_favor_clc($at,$favor,$saldo){
        $CI = &get_instance();
        $CI->load->model('general');

        $clc_favor = $CI->general->get('saldo_favor',['sf_apartamento' => $at]);
        $m_favor = (isset($clc_favor->sf_saldo)) ? ($clc_favor->sf_saldo >= 1) ? $clc_favor->sf_saldo : 0 : 0;

        $money_favor  = 0;
        $money_favor_clc  = 0;
        if($favor >= 1 and $m_favor >= 1){
            if($m_favor >= $favor){
                $money_favor = $favor;//se puede tomar todo el saldo que se ingreso ya que este es superado por el guardado
            }else{
                $money_favor = $m_favor;//se valida que el saldo no supera la cuota y se toma la todalidad del saldo a favor
            }
        }

        if($money_favor >= 1){
            if($money_favor > $saldo){
                $money_favor_clc = $saldo;
            }else{
                $money_favor_clc = $money_favor;
            }



            $clc_favor = $m_favor-$money_favor_clc;

            //descontar el valor del saldo a favor directamente
            $CI->general->update('saldo_favor',['sf_apartamento' => $at],['sf_saldo' => $clc_favor]);
        }

        return $money_favor_clc;
    }

    function saldo_normal_clc($at,$favor,$saldo){
        $CI = &get_instance();
        $CI->load->model('general');

        $clc_favor = $CI->general->get('saldo_favor',['sf_apartamento' => $at]);
        $m_favor = (isset($clc_favor->sf_saldo)) ? ($clc_favor->sf_saldo >= 1) ? $clc_favor->sf_saldo : 0 : 0;

        $money_favor  = 0;
        $money_favor_clc  = 0;
        if($favor >= 1 and $m_favor >= 1){
            if($m_favor >= $favor){
                $money_favor = $favor;//se puede tomar todo el saldo que se ingreso ya que este es superado por el guardado
            }else{
                $money_favor = $m_favor;//se valida que el saldo no supera la cuota y se toma la todalidad del saldo a favor
            }
        }

        if($money_favor >= 1){
            if($money_favor > $saldo){
                $money_favor_clc = $saldo;
            }else{
                $money_favor_clc = $money_favor;
            }



            $clc_favor = $m_favor-$money_favor_clc;

            //descontar el valor del saldo a favor directamente
            $CI->general->update('saldo_favor',['sf_apartamento' => $at],['sf_saldo' => $clc_favor]);
        }

        return $money_favor_clc;
    }

    function validate_var($arr,$dato){
        return (isset($arr[$dato]) and $dato != '') ? $arr[$dato] : '';
    }

    function substr($palabra,$inicio,$final){
            return substr($palabra,$inicio,$final);
    }

    function fecha_formato($fecha = '',$formato = ''){
        $date = date_create($fecha);
        return date_format($date,$formato);
    }


     function calculo_validar_total($mul = 0,$anterior = 0,$actual = 0,$consumo = 0){
        if($anterior == $actual){
            $res = 0;
        }elseif ($anterior > $actual){
            $res = 0;
        }elseif ($anterior == 0){
            $res = 0;
        }else{
            $res = ($actual-$anterior);
        }
        return array(
            'res' => $res,
            'mul' => round(($consumo) >= 1 ?  $mul*$consumo : $mul*$res, 2, PHP_ROUND_HALF_DOWN)
        );
    }

    function dinero($saldo,$decimal = 2){
        return number_format(round($saldo,$decimal,PHP_ROUND_HALF_DOWN),$decimal);
    }

}