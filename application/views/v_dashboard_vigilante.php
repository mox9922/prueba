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

<div aria-hidden="true" class="onboarding-modal modal fade animated" id="modal_data" role="dialog" tabindex="-1">
    <div class="modal-dialog  modal-1200 modal-centered" role="document">
        <div class="modal-content text-center">
            <button aria-label="Close" class="close" data-dismiss="modal" type="button">

                <span class="os-icon os-icon-close"></span></button>

            <div class="onboarding-content with-gradient">

                <div class="row">
                    <div class="col-sm-7">

                        <h2 class="onboarding-title">
                            Lista de Propietarios
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded text-center">
                                <thead>
                                <tr>
                                    <th>
                                        Nombre Propietario
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
                    <div class="col-sm-5">

                        <h2 class="onboarding-title">
                           Vehiculos
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded text-center">
                                <thead>
                                <tr>

                                    <th>
                                        Tipo Vehiculo
                                    </th>
                                    <th>
                                        Placas
                                    </th>
                                    <th>
                                        Color
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="tabla_propietario_vehiculo"></tbody>
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

