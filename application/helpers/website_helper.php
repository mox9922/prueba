<?php
defined('BASEPATH') or exit('No direct script access allowed');

if(!function_exists('dashboard')){
    function dashboard($type){
//        $CI = &get_instance();
            $result = 'v_default';
            if($type == 1){
                //adminstrador
                $result = 'declaranet/dashboard/v_admin';
            }elseif($type == 2){
             //editor
                $result = 'declaranet/dashboard/v_editor';
            }else{
                //declarante
                $result = 'declaranet/dashboard/v_declarante';
            }
            return $result;
    }
}

if(!function_exists('fecha')){
    function fecha($tipo = 2){
        return $tipo == 1 ? date('Y-m-d') : date('Y-m-d G:i:s');
    }
}


if(!function_exists('hora')){
    function hora(){
        return date('G:i:s');
    }
}

if(!function_exists('limitar_texto')){
    function limitar_texto($palabra = '',$cantidad = 1){
            return substr($palabra,0,$cantidad);
    }
}

if(!function_exists('array_data')){
    function array_data($id = '',$data = array()){
        $result  = '';
        if(count($data) >= 1){
            if(isset($data[$id])){
                $result = $data[$id];
            }
        }
        return $result;
    }
}

if(!function_exists('validate_array')){
    function validate_array($id = '',$data = array()){
        $result  = false;
        if(count($data) >= 1){
            if(isset($data[$id])){
                $result = true;
            }
        }
        return $result;
    }
}

if(!function_exists('validate_array_permissions')){
    function validate_array_permissions($permiso,$id = ''){
        $CI = & get_instance();
        $CI->load->model('general');
        if($CI->general->exist('permisos',array('p_perfil' => $permiso,'p_modulo' => $id)) ==  false){
            redirect('declaracion/');
        }
    }
}

if(!function_exists('generate_menu')){
    function generate_menu($type = ''){
        $CI = & get_instance();
        $data = array();
        $CI->load->model('m_template','template');

//        $menu_principal = $CI->template->get_all_menu($type,0);
//
//        foreach($menu_principal As $menu1){
//            $data[] = array($menu1);
//        }
//
//
//
//
//
//
//        print_r($data);
//        exit;
        //get_all_menu
//            if(count($menusAr) >= 1){ }


    }
}

?>

