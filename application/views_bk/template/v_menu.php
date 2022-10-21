<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header border-end">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
            <a class="navbar-brand" href="<?=base_url(); ?>">
                <!-- Logo icon -->
                <b class="logo-icon">
                    <!-- Dark Logo icon -->
                    <img src="<?=base_url('assets/'); ?>images/logos/logo-icon.png" alt="homepage" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="<?=base_url('assets/'); ?>images/logos/logo-light-icon.png" alt="homepage" class="light-logo" />
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                             <!-- dark Logo text -->
                             <img src="<?=base_url('assets/'); ?>images/logos/logo-text.png" alt="homepage" class="dark-logo" />
                    <!-- Light Logo text -->
                             <img src="<?=base_url('assets/'); ?>images/logos/logo-light-text.png" class="light-logo" alt="homepage" />
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
                                <p class=" mb-0"><?=$this->class_data->perfiles[$user->u_perfil];?></p>
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
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect sidebar-link" href="<?=base_url()?>" aria-expanded="false">
                        <i class="mdi mdi-home text-info"></i>
                        <span class="hide-menu">Inicio</span>
                    </a>
                </li>
                <?php
                if($user->u_perfil == 1 || $user->u_perfil == 5){
                    foreach($this->class_data->menu As $menu){

                        $titulo = $menu['title'];
                        $href = base_url($menu['href']);

                        echo "	<li class='sidebar-item'>
                                    <a class='sidebar-link waves-effect  sidebar-link' href='{$href}' aria-expanded='false'>
                                        <i class='mdi mdi-adjust text-danger'></i>
                                        <span class='hide-menu'>{$titulo}</span>
                                    </a>
                                </li>
                         ";
                    }
                }else{
                    echo "	
	                            <li class='sidebar-item'>
                                    <a class='sidebar-link waves-effect  sidebar-link' href='".base_url('perfil')."' aria-expanded='false'>
                                        <i class='mdi mdi-adjust text-danger'></i>
                                        <span class='hide-menu'>Mi perfil</span>
                                    </a>
                                </li>
                                
                         ";
                }
                ?>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="<?=base_url('logout')?>" aria-expanded="false">
                        <i class="mdi mdi-adjust text-info"></i>
                        <span class="hide-menu">Desconectar</span>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<!-- ============================================================== -->


