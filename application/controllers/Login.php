<?php


class Login extends CI_Controller{
    private $session_id;
    private $session_token;
    private $user_data;
    private $controllName = 'Cdm';
    private $controller   = 'cdm/';
    private $project;
    private $result = array();

    function __construct(){
        parent::__construct();
        //variable global
        $this->session_id     = $this->session->userdata('user_id');
        $this->session_token  = $this->session->userdata('user_token');
        $this->project        = $this->config->config['project'];

        //validacion de acceso

        $this->user_data = $this->general->get('usuarios',array('u_id' => $this->session_id));

        //call models
        $this->load->model('m_login','login');


    }

    function index(){
        $this->accesso =  auth('login');
        $data_head = array(
            'titulo' => $this->project['tiulo'],
            'crud' => array(
                'url_login'       =>  base_url().'auth',
                'url_recovery'    =>  base_url().'recovery'
            )
        );

        $this->load->view('v_login',$data_head);
    }

    //method
    function auth(){
        if($this->input->post()){
            //validate method post
            if($this->class_security->validate_post(array('username','password'))){
                //validar
                $username   = $this->class_security->data_form('username','str');
                $password = encriptar_password($this->class_security->data_form('password','str'));
                $result_in =  $this->login->validate_login($username,$password);
                if($result_in['success'] == 1){
                    $data = $result_in['data'];
                    //generate token
                    $token = generate_token($data->u_id);

                    //save token
                    $this->login->save_token($data->u_id,$token);

                    //save session
                    $this->session->set_userdata('user_id', $data->u_id);
                    $this->session->set_userdata('user_token', $token);

                    $result = array(
                        'success' => $result_in['success'],
                        'data' => base_url("/")
                    );
                }else{
                    $result = $result_in;
                }

            }else{
                $result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($result);
    }

    function recovery(){
        if($this->input->post()){
            //validate method post
            if($this->class_security->validate_post(array('username'))){
                //validar
                $username   = $this->class_security->data_form('username','str');
                $result_in =  $this->login->validate_user($username);
                if($result_in['success'] == 1){
                    $data = $result_in['data'];
                    //generate token
                    $password = $this->class_security->generate_password();
                    $pass_crypt = encriptar_password($password);

                    //save password
                    $this->general->update('usuarios',array('u_id' => $data->u_id),array('u_password' => $pass_crypt));

                    $email_parameters=array(
                        "to"        => $data->u_correo1,
                        'email'     => $this->config->config['project']['email']['ticket'],
                        "subject"   =>'restablecer la contraseña en ' .  $this->project['tiulo'],
                        "template"  => 'correos/v_reset',
                        "data"      => array(
                            'password' => $password,
                            "nombre"    => $data->u_nombre
                        ),
                    );
                    $this->load->library('Mailer');
                    $this->mailer->initialize($email_parameters);
                    $this->mailer->send_mail();

                    $this->result = array('success' => $result_in['success']);
                }else{
                    $this->result = $result_in;
                }

            }else{
                $this->result = array('success' => 2,'msg' => 'Campos Obligatorios');
            }
        }else{
            $this->result = array('success' => 2,'msg' => 'Que haces!');
        }
        api($this->result);

    }

    function logout() {
        unset($_SESSION);
        $this->session->sess_destroy();
        redirect('login');
    }

}