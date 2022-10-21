<?php
defined('BASEPATH') or exit('No direct script access allowed');
$codigo      =  $this->class_security->validate_var($datas,'t_codigo');
$nombre      =  $this->class_security->validate_var($datas,'t_titulo');
$tipo        =  $this->class_security->array_data($this->class_security->validate_var($datas,'t_prioridad'),$this->class_data->ticket_prioridad);
$estado_uid  =  $this->class_security->validate_var($datas,'t_estado');
$estado        =  $this->class_security->array_data($estado_uid,$this->class_data->ticket_estado);
$documento1 =  $this->class_security->validate_var($datas,'t_documento1');
$documento2 =  $this->class_security->validate_var($datas,'t_documento2');
$documento3 =  $this->class_security->validate_var($datas,'t_documento3');
$documento4 =  $this->class_security->validate_var($datas,'t_documento4');
$descripcion =  $this->class_security->validate_var($datas,'t_descripcion');
?>


<form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

    <input type="hidden" name="send" value="data">
    <?php if($datas['t_estado']  != 3) echo "<input type='hidden' name='data_id' id='data_id' value='{$id}'>";?>


    <div class="modal-body">
        <div class="row mb-3">
            <div class="form-group col-md-7">
                <label>Titulo Ticket</label>
                <input type="text" readonly autofocus value="<?=$nombre?>" class="form-control imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-3">
                <label>Prioridad</label>
                <input type="text" readonly value="<?=$tipo['title']?>" class="form-control text-center imput_reset" autocomplete="off">
            </div>

            <div class="form-group col-md-2">
                <label>Estado</label>
                <?php
                    if($this->user_data->u_perfil == 1 AND $estado_uid != 3){
                        ?>
                        <select class="form-control text-center imput_reset" required name="estado" id="estado" autocomplete="off">
                            <option value=""> [ SELECCIONAR ] </option>
                            <?php
                            foreach ($this->class_data->ticket_estado as $e_id => $e_val){
                                if($estado_uid == $e_id){
                                    echo "<option value='{$e_id}' selected>{$e_val[title]}</option>";
                                }else{
                                    echo "<option value='{$e_id}'>{$e_val[title]}</option>";
                                }
                            }
                            ?>
                        </select>
                       <?php

                    }else{
                        ?>
                        <input type="text" readonly value="<?=$estado['title']?>" class="form-control text-center imput_reset" autocomplete="off">
                        <?php
                    }
                ?>
            </div>

        </div>

        <div class="row mb-3">

            <div class="form-group col-md-12">
                <label>Descripción</label>
                <textarea readonly rows="5" placeholder="descripcion" autofocus  class="form-control imput_reset" autocomplete="off"><?=$descripcion?></textarea>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label class="d-block">Evidencia 1</label>
                    <?=$this->class_security->url_documento($documento1,1,'w-100')?>
            </div>

            <div class="form-group col-md-6">
                <label class="d-block">Evidencia 2</label>
                    <?=$this->class_security->url_documento($documento2,1,'w-100')?>
            </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-6">
                <label class="d-block">Evidencia 3</label>
                    <?=$this->class_security->url_documento($documento3,1,'w-100')?>
            </div>

            <div class="form-group col-md-6">
                <label class="d-block">Evidencia 4</label>
                    <?=$this->class_security->url_documento($documento4,1,'w-100')?>
          </div>
        </div>

        <div class="row mb-3">
            <div class="form-group col-md-12">
                <div class="progress" style="height: 26px;">
                    <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar_invitado" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                </div>
            </div>
        </div>


    <div class="support-index w-100">
        <div class="support-ticket-content-w">
            <div class="support-ticket-content">
                <div class="support-ticket-content-header">
                    <h3 class="ticket-header">Respuesta de Ticket</h3>
                    <a class="back-to-index" href="#"><i class="os-icon os-icon-arrow-left5"></i><span>Back</span></a><a class="show-ticket-info" href="#"><span>Show Details</span><i class="os-icon os-icon-documents-03"></i></a>
                </div>
                <div class="ticket-thread" id="mensajes">

                    <?php
                    $data = '';
                    foreach($mensajes AS $msg){

                        $usu = '';
                        $name = '';
                        if($msg->u_perfil == 1){
                            $usu = 'highlight';
                            $name = 'text-right';
                        }
                        $img = base_url("_files/avatar.jpg");

                        $archivo1 = (strlen($msg->tr_documento1) >= 10) ? "<a class='attachment' href='".base_url("_files/{$msg->tr_documento1}")."' target='_blank'><i class='os-icon os-icon-ui-51'></i><span>Archivo 1</span></a>" : '';
                        $archivo2 = (strlen($msg->tr_document2) >= 10) ? "<a class='attachment' href='".base_url("_files/{$msg->tr_document2}")."' target='_blank'><i class='os-icon os-icon-ui-51'></i><span>Archivo 2</span></a>" : '';
                        $str = str_replace(PHP_EOL, '<p>', $msg->tr_descripcion);
                        $data .= "
                                                <div class='ticket-reply {$usu}'>
                                                      <div class='ticket-reply-info {$name}'>
                                                          <b class='author with-avatar' ><img alt='' src='{$img}' /><span>{$msg->u_nombre}</span></b>
                                                          <span class='info-data'><span class='label'>Respuesta en </span><span class='value'>{$msg->tr_atcreate}</span></span>
                                                          
                                                      </div>
                                                      <div class='ticket-reply-content'>
                                                          <p>{$str}</p>
                                                      </div>
                                                      <div class='ticket-attachments'>
                                                         {$archivo1}
                                                         {$archivo2}
                                                     </div>
                            
                                                </div>
                                     ";
                    }
                    echo $data;
                    ?>


                </div>
            </div>
        </div>
    </div>


        <?php
        if($datas['t_estado']  != 3):
            ?>
            <div class="row mb-3">

                <div class='form-group col-md-12'>
                    <label>Respuesta Ticket</label>
                    <textarea id='respuesta' name='respuesta'  rows='8' autofocus required class='form-control imput_reset' autocomplete='off'></textarea>
                </div>

            </div>

            <div class="row mb-3">
                <div class="form-group col-md-6">
                    <label>Evidencia 1</label>
                    <input type="file" name="documento_1"  accept=".gif,.jpg,.jpeg,.png,.xls,.doc,.docx,.xlsx,.xls,doc,docx,xlsx" class="form-control imput_reset" id="customFile" >
                </div>

                <div class="form-group col-md-6">
                    <label>Evidencia 2</label>
                    <input type="file" name="documento_2" accept=".gif,.jpg,.jpeg,.png,.xls,.doc,.docx,.xlsx" class="form-control imput_reset" id="customFile" >
                </div>
            </div>

            <div class="row mb-3">
                <div class="form-group col-md-12">
                    <div class="progress" style="height: 26px;">
                        <div class="progress-bar  progress-bar-striped  progress-bar-animated" role="progressbar" id="progressbar_invitado" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">Barra de progreso </div>
                    </div>
                </div>
            </div>

        <?php
        endif;
        ?>


    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">Cancelar Proceso</button>
        <?php if($datas['t_estado']  != 3) echo '<button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>';?>
    </div>

</form>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900');



        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {
                e.preventDefault();
                $(this).simple_call('frm_data','url_respuesta',false,function(){
                    let estado = $("#estado").val();
                    if(estado == 3){
                        window.location.reload();
                    }else{
                        $(this).forms_modal({"page" : "ticket","data1" : "<?=$id?>","title" : "Tickets"});
                    }
                });
                return false;
            }
        })

    })
</script>