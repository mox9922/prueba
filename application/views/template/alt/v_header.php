<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$titulo; ?></title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('assets/'); ?>assets/images/favicon.png">
    <!-- Custom CSS -->
    <link href="<?=base_url('assets/'); ?>dist/css/style.min.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>css/all_style.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>css/ligth.css" rel="stylesheet">
    <link href="<?=base_url('assets/'); ?>plugins/lobibox/css/lobibox.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/'); ?>libs/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/'); ?>plugins/pickadate/lib/themes/default.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/'); ?>plugins/pickadate/lib/themes/default.date.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/'); ?>plugins/pickadate/lib/themes/default.time.css">
    <link href="<?php echo base_url('assets/'); ?>libs/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">


    <!-- cdn External -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">

    <link rel="stylesheet" type="text/css" href="<?=base_url('assets/'); ?>extra-libs/datatables.net-bs4/css/responsive.dataTables.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->


    <script>
        var project_title = "<?php echo $titulo; ?>";
        var url_sitio = "<?php echo base_url(); ?>";
    </script>
    <?php
    if((isset($style_level)) AND (!empty($style_level)))
    {
        foreach($style_level As $style_lv):
            $url_style_level = base_url('assets/').$style_lv;
            echo "<link  rel='stylesheet' type='text/css'  href='$url_style_level'>
";
        endforeach;
    }
    ?>
</head>

<body>
<!-- -------------------------------------------------------------- -->
<!-- Preloader - style you can find in spinners.css -->
<!-- -------------------------------------------------------------- -->
<div class="preloader">
    <svg class="tea lds-ripple" width="37" height="48" viewbox="0 0 37 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z" stroke="#20222a" stroke-width="2"></path>
        <path d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34" stroke="#20222a" stroke-width="2"></path>
        <path id="teabag" fill="#20222a" fill-rule="evenodd" clip-rule="evenodd" d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z"></path>
        <path id="steamL" d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke="#20222a"></path>
        <path id="steamR" d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13" stroke="#20222a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
</div>

<!-- -------------------------------------------------------------- -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- -------------------------------------------------------------- -->
<div id="main-wrapper">
    <!-- -------------------------------------------------------------- -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- -------------------------------------------------------------- -->
    <header class="topbar" >
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <div class="navbar-header border-end">
                <!-- This is for the sidebar toggle which is visible on mobile only -->
                <a class="nav-toggler waves-effect waves-light d-block d-md-none text-white" href="javascript:void(0)">0</a>
                <a class="navbar-brand" href="<?=base_url();?>">
                    <!-- Logo icon -->
                    <b class="logo-icon">
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <img src="<?=base_url('assets/'); ?>/images/logos/logo-icon.png" alt="homepage" class="dark-logo" />
                        <!-- Light Logo icon -->
                        <img src="<?=base_url('assets/'); ?>/images/logos/logo-light-icon.png" alt="homepage" class="light-logo" />
                    </b>
                    <!--End Logo icon -->
                    <!-- Logo text -->
                    <span class="logo-text">
                             <!-- dark Logo text -->
                             <img src="<?=base_url('assets/'); ?>/images/logos/logo-text.png" alt="homepage" class="dark-logo" />
                        <!-- Light Logo text -->
                             <img src="<?=base_url('assets/'); ?>/images/logos/logo-light-text.png" class="light-logo" alt="homepage" />
                        </span>
                </a>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Toggle which is visible on mobile only -->
                <!-- ============================================================== -->
                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu fs-5"></i></a></li>
                    <!-- ============================================================== -->
                    <!-- Messages -->
                    <!-- ============================================================== -->

                </ul>
                <!-- ============================================================== -->
                <!-- Right side toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav">

                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="<?=base_url('assets/'); ?>/images/users/1.jpg" alt="user" class="rounded-circle" width="36">
                            <span class="ms-2 font-weight-medium"> <?php echo $user->u_nombre; ?></span><span class="fas fa-angle-down ms-2"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end user-dd animated flipInY">
                            <div class="d-flex no-block align-items-center p-3 bg-dark text-white mb-2">
                                <div class=""><img src="<?=base_url('assets/'); ?>/images/users/1.jpg" alt="user" class="rounded-circle" width="60"></div>
                                <div class="ms-2">
                                    <h4 class="mb-0 text-white"><?php echo $user->u_nombre; ?></h4>
                                    <p class=" mb-0"><?=$this->class_security->array_data($user->u_perfil,$this->class_data->perfiles);?></p>
                                </div>
                            </div>
                            <a class="dropdown-item" href="<?=base_url('perfil')?>"><i data-feather="user" class="feather-sm text-info me-1 ms-1"></i> Mi perfil</a>

                            <div class="dropdown-divider"></div>
                            <div class="ps-4 p-2"><a href="<?=base_url('logout')?>"  class="btn d-block w-100 btn-dark rounded-pill">Desconectarse</a></div>
                        </div>
                    </li>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                </ul>
            </div>
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->

    <!-- -------------------------------------------------------------- -->
    <!-- Page wrapper  -->
    <!-- -------------------------------------------------------------- -->
    <div class="page-wrapper" style="padding-top:60px !important;">

        <div class="page-breadcrumb border-bottom  bg-dark">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 text-center">
                    <h5 class="font-weight-medium text-uppercase mb-0 text-white"><?=$titulo; ?></h5>
                </div>
            </div>
        </div>