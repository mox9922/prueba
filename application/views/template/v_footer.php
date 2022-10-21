<?php
defined('BASEPATH') or exit('No direct script access allowed');


if(isset($crud) AND count($crud) >= 1){
    foreach($crud As $crud_key => $crud_value){
        $hidden_name = $crud_key;
        $hidden_url  = $crud_value;
        echo "<input type='hidden' id='{$hidden_name}' value='{$hidden_url}'>\n";
    }
}
?>


</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<div id="root-picker-outlet"></div>
<!-- Modal Principal -->
<div id="modal_principal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog in_modal_primario modal-800">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?>  - <b id="label_modal"></b></h4>
                <button type="button" class="close btn-close"  data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body-view"></div>

        </div>
    </div>
</div>

<div id="modal_principal2" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog in_modal_secundario  modal-800">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?>  - <b id="label_modal2"></b></h4>
                <button type="button" class="close btn-close"  data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body-view2"></div>

        </div>
    </div>
</div>


<!-- -------------------------------------------------------------- -->
<!-- All Jquery -->
<!-- -------------------------------------------------------------- -->
<script src="<?=base_url('assets/'); ?>libs/jquery/dist/jquery.min.js"></script>
<script src="<?=base_url('assets/'); ?>extra-libs/taskboard/js/jquery-ui.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="<?=base_url('assets/'); ?>libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<!-- apps -->
<script src="<?=base_url('assets/'); ?>libs/moment/moment.js"></script>
<script src="<?=base_url('assets/'); ?>dist/js/app.min.js"></script>
<script src="<?=base_url('assets/'); ?>dist/js/app.init.sidebar.js"></script>
<script src="<?=base_url('assets/'); ?>dist/js/app-style-switcher.js"></script>
<script src="<?=base_url('assets/'); ?>libs/select2/dist/js/select2.full.min.js"></script>
<script src="<?=base_url('assets/'); ?>libs/select2/dist/js/select2.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="<?=base_url('assets/'); ?>libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
<script src="<?=base_url('assets/'); ?>extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<script src="<?=base_url('assets/'); ?>dist/js/waves.js"></script>
<!--Menu sidebar -->
<script src="<?=base_url('assets/'); ?>dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="<?=base_url('assets/'); ?>dist/js/feather.min.js"></script>
<script src="<?=base_url('assets/'); ?>dist/js/custom.min.js"></script>





<!--This page plugins -->
<script src="<?php echo base_url('assets/'); ?>plugins/pickadate/lib/compressed/picker.js"></script>
<script src="<?php echo base_url('assets/'); ?>plugins/pickadate/lib/compressed/picker.date.js"></script>
<script src="<?php echo base_url('assets/'); ?>plugins/pickadate/lib/compressed/picker.time.js"></script>
<script src="<?php echo base_url('assets/'); ?>plugins/pickadate/lib/compressed/legacy.js"></script>
<script src="<?php echo base_url('assets/'); ?>libs/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url('assets/'); ?>libs/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo base_url('assets/'); ?>libs/select2/dist/js/select2.min.js"></script>

<script src="<?=base_url('assets/'); ?>extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
<!--<script src="--><?//=base_url('assets/'); ?><!--extra-libs/datatables.net-bs4/js/dataTables.responsive.min.js"></script>-->

<!-- start - This is for export functionality only -->
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>-->


<!-- This Page JS -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>-->
<!--<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>-->

<script src="<?=base_url('assets/'); ?>js/validator.min.js"></script>
<script src="<?=base_url('assets/'); ?>plugins/lobibox/js/lobibox.js"></script>
<script src="<?=base_url('assets/'); ?>plugins/table2excel/table2excel.js"></script>
<script src="<?=base_url('assets/'); ?>libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script src="<?=base_url('assets/'); ?>cr/all_scripts.js"></script>
<script src="<?=base_url('assets/'); ?>cr/modals.js"></script>

<?php
if(!empty($script_level))
{
    foreach($script_level As $script_lv):
        $url_script_level = base_url('assets/').$script_lv;
        echo "<script src='$url_script_level'></script>
";
    endforeach;
}
?>
</html>
