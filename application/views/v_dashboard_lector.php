<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!--<div class="content-w">-->

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

                    <div class="element-content mb-3">
                        <div class="tablo-with-chart">
                            <div class="row">
                                <div class="col-sm-12 col-xxl-12 ">
                                    <div class="element-search autosuggest-search-activator "><input class="text-center display-3 text-uppercase" placeholder="Buscar..."  onkeyup="$(this).buscar_apartamentos()"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="element-content">
                        <div class="tablo-with-chart">
                            <div class="row">
                                <div class="col-sm-12 col-xxl-12">
                                    <div class="tablos">
                                        <div class="row mb-xl-2 mb-xxl-3" id="lista_casas">

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
<!--</div>-->
<div id="modal_principal" class="modal fade  modal fade animated" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-800">
        <div class="modal-content">
            <div class="modal-header header_modal" style="background: #2f323e;border-color: #2f323e;">
                <h4 class="modal-title" id="myModalLabel" style="color: white;"><?php echo $titulo; ?>  - <b id="label_modal"></b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        style="float: right;font-size: 30px;font-weight: 700;line-height: 1;color: #FFF;text-shadow: 0 1px 0 #fff;opacity:1;">
                    ×
                </button>
            </div>

            <div class="modal-body-view"></div>

        </div>
    </div>
</div>

