<?php
defined('BASEPATH') or exit('No direct script access allowed');


?>

<div class="onboarding-media text-center"><img alt="" src="<?=base_url('assets/');?>img/seguridad.jpg" width="200px"></div>
<div class="onboarding-content with-gradient">
    <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">
        <input type="hidden" name="id" id="s_id">
        <input type="hidden" name="tipo" id="s_tipo">
        <div class="modal-body">

            <div class="row mb-3 text-center">
                <div class="form-group col-md-5">
                    <h4 class="d-block">Nombre Visitante</h4>
                    <input type="text" id="s_nombre" required name="nombre"  readonly  class="form-control  text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-4">
                    <h4 class="d-block">Documento</h4>
                    <input type="text" name="documento" required id="cedula_search"  class="form-control  text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <h4 class="d-block">Filial</h4>
                    <select name="filial" class="form-control" onchange="$(this).data_filial()" required id="s_filial"  style="width: 100%">
                        <option value="">[ FILTRAR ]</option>
                    <?php
                    if(isset($filiales) AND count($filiales) >= 1){
                        foreach($filiales As $fl){
                            echo "<option value='{$fl->t_id}'>{$fl->t_apartamento}</option>";
                        }
                    }
                    ?></select>
                </div>
                <div id="myModal"></div>
            </div>

            <div class="row mb-3 text-center">

                <div class="form-group col-md-3">
                    <h4 class="d-block">Tipo Invitado</h4>
                    <input type="text" id="s_tipo_invitado" disabled  class="form-control  text-center imput_reset" autocomplete="off">
                </div>


                <div class="form-group col-md-3">
                    <h4 class="d-block">Placa</h4>
                    <input type="text" id="s_placa" name="placa"  placeholder="Placa"  class="form-control  text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <h4 class="d-block">Tipo Ingreso</h4>
                    <input type="text" id="s_tipo_ingreso" name="tipo_ingreso"   placeholder="Tipo"  class="form-control  text-center imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <h4 class="d-block">Cono</h4>
                    <input type="text" id="s_cono" name="cono"  placeholder="Cono"  class="form-control  text-center imput_reset" autocomplete="off">
                </div>
            </div>

            <div class="row mb-3 text-center">

                <div class="form-group col-md-3">
                    <h4 class="d-block">Conacto Primario</h4>
                    <input type="text" id="c_data_nombre_1" disabled  class="form-control  text-center reset_all" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <h4 class="d-block">Telefono Primario</h4>
                    <input type="text" id="c_data_telefono_1" disabled  class="form-control  text-center reset_all" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <h4 class="d-block">Conacto Secundario</h4>
                    <input type="text" id="c_data_nombre_2" disabled  class="form-control  text-center reset_all" autocomplete="off">
                </div>

                <div class="form-group col-md-3">
                    <h4 class="d-block">Telefono Secund.</h4>
                    <input type="text" id="c_data_telefono_2" disabled  class="form-control  text-center reset_all" autocomplete="off">
                </div>


            </div>

            <div class="row  mb-3 text-center">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success btn-block btn-lg waves-effect">Guardar Ingreso</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900','.in_modal_primario ');
        $(this).select2_func('#s_filial');

        $('#cedula_search').keyup(debounce(function(){
            var $this=$(this);
            var n1 = $this.val();
            $(this).buscar_usuario(n1);
        },500));


        function debounce(func, wait, immediate) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                var later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        };

        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_validar_acceso');
                return false;
            }
        })

    })
</script>