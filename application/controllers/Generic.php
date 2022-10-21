<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Generic extends  CI_Controller
{
    private $session_id;
    private $session_token;
    private $user_data;
    private $project;
    private $result = array();

    public function __construct(){
        parent::__construct();


        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso
        auth();

        //validar y hacer el llamado de todo
        $this->user_data = $this->general->get('usuarios',array('u_id' => $this->session_id));
        //load
        $this->load->model('m_datatable','datatable');
        $this->load->model('generico');
        $this->load->helper(array('website','encriptar'));

    }

    function delete(){
        if($this->input->post()){
            if($this->class_security->validate_post(array('id','table','columns'))){
                $id     = $this->class_security->data_form('id','decrypt_int');
                $tabla  = $this->class_security->data_form('id','letras_numeros_espace');
                $column = $this->class_security->data_form('id','letras_numeros_espace');
                if(strlen($id) >= 1 AND (strlen($tabla) >= 4) AND (strlen($column) >= 4)){

                    if($this->general->exist($tabla,array($column => $id))){
                        $this->result =  $this->general->delete($tabla,array($column => $id));
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


}