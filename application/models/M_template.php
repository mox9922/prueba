<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_template extends CI_Model
{

    //tables
    private $users = 'usuarios';
    private $perfiles = 'vias_perfiles';
    private $perfiles_permiso = 'vias_perfiles_permiso';
    private $menu  = 'vias_menu';

    function get_all_menu($permission = '',$type = ''){

        //query manual
            $consult = "SELECT * FROM vias_perfiles p 
    JOIN vias_perfiles_permiso AS pp ON p.p_id=pp.pp_perfil
    JOIN vias_menu AS vm ON pp.pp_permiso=vm.m_id
    WHERE p.p_id='".$permission."' AND vm.m_type='".$type."'";

       $result =  $this->db->query($consult)->result_array();
        return $result;
    }

}