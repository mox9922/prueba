
<?php
defined('BASEPATH') OR exit('No direct script access allowed');


$route['default_controller'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//perfiles de usuarios
$route['usuarios']     = 'usuarios/1';
$route['afiliales']    = 'usuarios/2';
$route['ticket_admin']      = 'ticket/ticket_admin';


/*Route Asigne*/
$route['auth']       = 'login/auth';
$route['recovery']   = 'login/recovery';
$route['logout']     = 'login/logout';
