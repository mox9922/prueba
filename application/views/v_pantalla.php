<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<html><head>
    <title>Admin Dashboard HTML Template</title>
    <meta charset="utf-8">
    <meta content="ie=edge" http-equiv="x-ua-compatible">
    <meta content="template language" name="keywords">
    <meta content="Tamerlan Soziev" name="author">
    <meta content="Admin dashboard html template" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="favicon.png" rel="shortcut icon">
    <link href="apple-touch-icon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500" rel="stylesheet" type="text/css">
    <link href="<?=base_url('assets/')?>bower_components/perfect-scrollbar/css/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="<?=base_url('assets/')?>bower_components/slick-carousel/slick/slick.css" rel="stylesheet">
    <link href="<?=base_url('assets/')?>css/main.css?version=4.4.0" rel="stylesheet">
    <style type="text/css">/* Chart.js */
        @-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}</style><style>.cke{visibility:hidden;}</style>

    <link href="<?php echo base_url('assets/'); ?>css/all_style.css" rel="stylesheet">
    <link href="<?php echo base_url('assets/'); ?>plugins/lobibox/css/lobibox.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" rel="stylesheet">
    <script>
        var project_title = "<?php echo $titulo; ?>";
        var url_sitio = "<?php echo base_url(); ?>";
    </script>
</head>
<body class="menu-position-side menu-side-left full-screen">

<div class="all-wrapper with-side-panel solid-bg-all">
    <div class="layout-w">
        <!--------------------
        START - Mobile Menu
        -------------------->
        <!--------------------
        END - Mobile Menu
        --------------------><!--------------------
        START - Main Menu
        -------------------->

        <!--------------------
        END - Main Menu
        -------------------->
        <div class="content-w">

            <div class="content-i">
                <div class="content-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <!--START - Grid of tablo statistics-->
                            <div class="element-wrapper">

                                <div class="section-heading centered">
                                    <h1>
                                        Lista de Filiales
                                    </h1>

                                </div>
                                <div class="element-content">
                                    <div class="tablo-with-chart">
                                        <div class="row">
                                            <div class="col-sm-12 col-xxl-12">
                                                <div class="tablos">
                                                    <div class="row mb-xl-2 mb-xxl-3">

                                                        <?php
                                                            foreach($apartamentos As $apt){

                                                                $id = encriptar($apt['t_id']);

                                                                $letra_apartamento = "{$apt['t_torre']}-{$apt['t_apartamento']}";

                                                                echo "
                                                                          <div class='col-sm-3'>
                                                                              <a class='element-box el-tablo centered trend-in-corner padded bold-label' href='javascript:void(0)'  onclick='$(this).data_propiedad(\"$id\")' >
                                                                                  <div class='avatar'>
                                                                                      <img src='".site_url('assets/')."img/casa.jpg' class='img-responsive' style='border-radius: 50px; width: 100px; height: auto;' />
                                                                                  </div>
                                                                                  <div class='value'>
                                                                                      {$letra_apartamento}
                                                                                  </div>
                                                                              </a>
                                                                          </div>
                                                                ";
                                                            }
                                                        ?>



                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--END - Grid of tablo statistics-->
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="display-type"></div>
</div>

<div aria-hidden="true" class="onboarding-modal modal fade animated" id="modal_data" role="dialog" tabindex="-1">
    <div class="modal-dialog  modal-1100 modal-centered" role="document">
        <div class="modal-content text-center">
            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span class="os-icon os-icon-close"></span></button>

            <div class="onboarding-content with-gradient">

                <div class="row">
                    <div class="col-sm-12">

                        <h2 class="onboarding-title">
                            Lista de Propietarios
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded text-center">
                                <thead>
                                <tr>
                                     <th>
                                        Nombre Visitante
                                    </th>
                                    <th>
                                        Telefono 1
                                    </th>
                                    <th>
                                        Telefono 2
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="tabla_propietario"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">

                        <h2 class="onboarding-title">
                            Lista de visitantes
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded text-center">
                                <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>  <th>
                                        Nombre Visitante
                                    </th>
                                    <th>
                                        Cedula
                                    </th>
                                    <th>
                                        Tipo Vehiculo
                                    </th>
                                    <th>
                                        Placas
                                    </th>
                                    <th>
                                        Estado
                                    </th>

                                </tr>
                                </thead>
                                <tbody id="tabla_invitados"> </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>

<script src="<?=base_url('assets/')?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?=base_url('assets/')?>bower_components/popper.js/dist/umd/popper.min.js"></script>
<script src="<?=base_url('assets/')?>bower_components/tether/dist/js/tether.min.js"></script>

<script src="<?=base_url('assets/')?>bower_components/slick-carousel/slick/slick.min.js"></script>
<script src="<?=base_url('assets/')?>bower_components/bootstrap/js/dist/util.js"></script>
<script src="<?=base_url('assets/')?>bower_components/bootstrap/js/dist/modal.js"></script>
<script src="<?=base_url('assets/')?>bower_components/bootstrap/js/dist/tooltip.js"></script>
<script src="<?=base_url('assets/')?>bower_components/bootstrap/js/dist/popover.js"></script>
<script src="<?=base_url('assets/')?>js/demo_customizer.js?version=4.4.0"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

<script src="<?php echo base_url('assets/'); ?>plugins/lobibox/js/lobibox.js"></script>
<script src="<?php echo base_url('assets/'); ?>cr/all_scripts.js"></script>
<script src="<?php echo base_url('assets/'); ?>cr/pantalla.js"></script>
<script>
    $(document).ready(function() {
        $(".single_image").click(function () {
            alert(22);
            return;
        })
        // $(".single_image").fancybox();
    });
</script>


</body>
</html>
