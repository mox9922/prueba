<?php



if(!function_exists('auth')){

    //validate user auth
    function auth($page = ''){
        //sessions
        $CI = & get_instance();
        $in_token    = $CI->session->userdata('user_token');
        $token  = $CI->class_security->limpiar_form($in_token) ?: '';

        if(strlen($token) >= 1){
            //validate security
            $data =   $CI->db->from('usuarios')->where(
                array(
                    'u_token' => $token,
                    'u_estado' => 1
                )
            )->get()->result();

          $count = count($data);

            if($count == 0){

              if($page != 'login'){
                redirect(base_url('login'));
              }else{
                  //validate permissions
//                  echo $page;
                  if($page == 'login'){
                     // redirect(base_url());
                  }
              }
          }else{

             if($page == 'login'){
                 redirect(base_url());
             }

             $u = $data[0];
             if($u->u_perfil == 2 AND $u->u_terminos == 1){

                 if($page == 'filial'){
                     redirect(base_url());
                 }
             }

          }
        }else{
            if($page != 'login'){
                redirect(base_url('login'));
            }
        }
    }

}


if(!function_exists('permissions')){

    //validate user auth
    function permissions($permissions = ''){
        //sessions
        $CI = & get_instance();
        $in_token    = $CI->session->userdata('user_token');
        $token  = $CI->class_security->limpiar_form($in_token) ?: '';

        if(strlen($token) >= 1){
            //validate security
            $data =   $CI->db->from('usuarios')->where(
                array(
                    'u_token' => $token,
                    'u_estado' => 1
                )
            )->get()->row();
            if($permissions != $data->u_perfil){
                redirect(base_url());
            }

        }else{
            redirect(base_url('login'));
        }
    }

}