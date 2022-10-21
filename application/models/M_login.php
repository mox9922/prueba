<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_login extends CI_Model{

    //tables
    private $user    = 'usuarios';

    function validate_login($username,$password){
        //validar existencia de usuario
        if($this->general->exist($this->user,array('u_username' => $username))){
            //validar cuenta estado
            if($this->general->exist($this->user,array('u_username' => $username,'u_estado' => 1))){
                //validar cuenta
                if($this->general->exist($this->user,array('u_username' => $username,'u_password' => $password))){

                    $data_q = $this->general->query("select COALESCE(a.t_id,'') As apt,u.* from usuarios As u
                LEFT JOIN apartamento As a ON u.u_apartamento=a.t_id WHERE u.u_username='".$username."' AND u.u_password='".$password."'",'obj')[0];

                    if($data_q->u_perfil == 2 AND $data_q->apt == ''){
                        $result = array('success' => 2,'msg' => 'Lo siento debes tener una filial registada');
                    }else{
                        $data = $this->general->get($this->user,array('u_username' => $username,'u_password' => $password));
                        $result = array('success' => 1,'data' => $data);
                    }

                }else{
                    $result = array('success' => 2,'msg' => 'Usuario / Contraseña incorrectos');
                }
            }else{
                $result = array('success' => 2,'msg' => 'Validar la cuenta con soporte');
            }
        }else{
            //el username no existe
            $result = array('success' => 2,'msg' => 'El usuario no existe');
        }
        return $result;
    }

    function validate_login_document($username){
        //validar existencia de usuario
        if($this->general->exist($this->user,array('u_username' => $username))){
            //validar cuenta estado
            if($this->general->exist($this->user,array('u_username' => $username,'u_estado' => 1))){
                //validar cuenta
                    $data = $this->general->get($this->user,array('u_username' => $username));
                    $result = array('success' => 1,'data' => $data);

            }else{
                $result = array('success' => 2,'msg' => 'Validar la cuenta con soporte');
            }
        }else{
            //el username no existe
            $result = array('success' => 2,'msg' => 'El usuario no existe');
        }
        return $result;
    }

    function save_token($id = '',$token){
        if(strlen($id) >= 1){
            $this->general->update($this->user,array('u_id' => $id),array('u_token' => $token));
        }
    }

    function validate_user($username){
        //validar existencia de usuario
        if($this->general->exist($this->user,array('u_username' => $username))){
            //validar cuenta estado
            if($this->general->exist($this->user,array('u_username' => $username,'u_estado' => 1))){
                //validar cuenta
                if($this->general->exist($this->user,array('u_username' => $username))){
                    $data = $this->general->get($this->user,array('u_username' => $username));
                    $result = array('success' => 1,'data' => $data);
                }else{
                    $result = array('success' => 2,'msg' => 'Usuario  incorrectos');
                }
            }else{
                $result = array('success' => 2,'msg' => 'Validar la cuenta con soporte');
            }
        }else{
            //el username no existe
            $result = array('success' => 2,'msg' => 'El usuario no existe');
        }
        return $result;
    }

    function validate_token($id,$token){
       $user_login =  $this->db->from($this->user)
            ->where(
                array(
                    'u_id'      => $id,
                    'u_token'   => $token,
                    'u_estado'  => 1,
                    'u_alta'    => 1
                )
            )
            ->count_all_results();

       if($user_login == 1):
           $this->estado = true;
        else:
            $this->estado = false;
       endif;
       return $this->estado;
    }
}