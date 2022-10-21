<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- -------------------------------------------------------------- -->
<!-- Container fluid  -->
<!-- -------------------------------------------------------------- -->
<div class="container-fluid page-content">
    <!-- -------------------------------------------------------------- -->
    <!-- Start Page Content -->
    <!-- -------------------------------------------------------------- -->
    <!-- basic table -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card sin_fondo">
                <div class="card-body text-center" style="padding:0 !important;">
                    <h1 class="card-title" style="font-size: 2.5rem">Lista de Filiales</h1>
                  <div class="row">
                      <div class="col-md-10">
                          <div class="element-search autosuggest-search-activator ">
                              <input class="text-center display-3 text-uppercase" placeholder="Buscar..."  id="buscador">
                          </div>
                      </div>
                      <div class="col-md-1">
                          <button type="button" onclick='$(this).forms_modal({"page" : "vigilante_reporte","title" : "Reporte Ingreso"})' class="btn btn-dark btn-lg btn-block w-100" style="height: 93px;width: 100%;font-size: 50px;"><i class=" fab fa-rendact"></i></button>
                      </div>
                      <div class="col-md-1">
                          <button type="button" onclick='$(this).forms_modal({"page" : "vigilante_ingreso","title" : "Registro Ingreso"})' class="btn btn-dark btn-lg btn-block w-100" style="height: 93px;width: 100%;font-size: 50px;"><i class="fas fa-user"></i></button>
                      </div>
                  </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row d-block mb-5">
      <div class="table-responsive">
          <table id="tabla_visitante" class="table  customize-table table-hover mb-0 v-middle text-center ">
              <thead>
              <tr>
                  <th>Cedula</th>
                  <th>Nombre</th>
                  <th>Tipo</th>
                  <th>Placa</th>
                  <th>Tipo Ingreso</th>
                  <th>Cono</th>
                  <th>Filial</th>
                  <th >Hora Ingreso</th>
                  <th >Hora Salida</th>
                  <th >Tiempo Transcurrido</th>
                  <th >Salida</th>
              </tr>
              </thead>
              <tbody>
              </tbody>

          </table>
      </div>
    </div>

    <div class="row"  id="lista_casas"> </div>
</div>
<!-- -------------------------------------------------------------- -->
<!-- End Container fluid  -->
<!-- -------------------------------------------------------------- -->
<!-- -------------------------------------------------------------- -->
<!--</div>-->
<div aria-hidden="true" class="onboarding-modal modal fade animated" id="modal_data" role="dialog" tabindex="-1">
    <div class="modal-dialog  modal-1400 modal-centered" role="document">
        <div class="modal-content text-center">
            <button aria-label="Close" class="close btn-close"   data-bs-dismiss="modal" aria-label="Close" type="button"></button>

            <div class="onboarding-content with-gradient">

                <div class="row mb-3">
                    <div class="col-md-7">

                        <h2 class="onboarding-title">
                            Lista de Propietarios
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded table-striped text-center">
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
                                    <th>
                                        Contacto
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="tabla_propietario"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-5">

                        <h2 class="onboarding-title">
                            Vehiculos
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded table-striped text-center">
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

                <div class="row  mb-3">
                    <div class="col-md-6">

                        <h2 class="onboarding-title">
                            Lista de Mascotas
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded table-striped text-center">
                                <thead>
                                <tr>
                                    <th style="width:50px">
                                        #
                                    </th>
                                    <th>
                                        Nombre Mascota
                                    </th>
                                    <th>
                                       Tipo
                                    </th>
                                    <th>
                                        Color
                                    </th>
                                    <th>
                                        Raza
                                    </th>

                                </tr>
                                </thead>
                                <tbody id="tabla_mascotas"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">

                        <h2 class="onboarding-title">
                            Lista de visitantes
                        </h2>

                        <div class="table-responsive">
                            <table class="table table-padded table-striped text-center">
                                <thead>
                                <tr>
                                    <th>
                                        #
                                    </th>
                                    <th>
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
