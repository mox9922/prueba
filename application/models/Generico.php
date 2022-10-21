<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Generico extends CI_Model
{

    function permisos_usuario($id,$permisos = array()){
        $this->general->delete('vias_usuarios_clinicas',array('uc_usuario' => $id));

        //asignar permisos
        if(isset($permisos) AND count($permisos) >= 1){
            foreach($permisos As $permiso){
                $this->general->create('vias_usuarios_clinicas',array('uc_usuario' => $id,'uc_clinica' => $permiso));
            }
        }
    }

    function create_mail($id,$cola,$tipo){
        $this->general->create(
            'correos_task',
            [
                'cr_registro'    => $id,
                'cr_cola'        => $cola,
                'cr_tipo'        => $tipo,
                'cr_atcreate'    => fecha(),
            ]
        );
    }

}