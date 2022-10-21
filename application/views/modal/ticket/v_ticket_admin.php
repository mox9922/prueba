<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


    <form role="form" data-toggle="validator" method="POST" class="frm_data" id="frm_data">

        <input type="hidden" name="send" value="data">
        <input type="hidden" name="data_id" id="data_id" value="<?=$id?>">
        <input type="hidden" id="url_accion">

        <div class="modal-body">

            <div class="row">
                <div class="form-group col-md-7">
                    <label>Titulo Del ticket</label>
                    <input type="text" value="<?=$ticket->t_titulo?>" readonly placeholder="Titulo ticket" autofocus required class="form-control imput_reset" autocomplete="off">
                </div>

                <div class="form-group col-md-2">
                    <label>Estado</label>
                        <?php
                        if($ticket->t_estado  != 3):
                            echo '<select  name="estado"  id="estado" required autofocus  class="form-control imput_reset" autocomplete="off"> <option value=""> [SELEC] </option>';
                            foreach($this->class_data->ticket_estado As $pri_id => $pri_val){
                            if($ticket->t_estado == $pri_id){
                                echo "<option value=\"{$pri_id}\" selected>{$pri_val['title']}</option>";
                            }else{
                                echo "<option value=\"{$pri_id}\">{$pri_val['title']}</option>";
                            }
                        }
                            echo ' </select>';
                        else:
                               echo " <input type='text' value='{$this->class_security->array_data($ticket->t_estado,$this->class_data->ticket_estado)['title']}' disabled  class='form-control text-center'>";
                        endif;
                        ?>

                </div>

                <div class="form-group col-md-3">
                    <label>Prioridad</label>
                    <input type="text" value="<?=$this->class_security->array_data($ticket->t_prioridad,$this->class_data->ticket_prioridad)['title'];?>" disabled  class="form-control text-center imput_reset" autocomplete="off">

                </div>

                <div class="form-group col-md-6">
                    <label>Documento 1</label>
                    <?=$this->class_security->url_documento($ticket->t_documento1)?>
                </div>

                <div class="form-group col-md-6">
                    <label>Documento 2</label>
                    <?=$this->class_security->url_documento($ticket->t_documento2)?>
                </div>

                <div class="form-group col-md-6">
                    <label>Documento 3</label>
                    <?=$this->class_security->url_documento($ticket->t_documento3)?>
                </div>

                <div class="form-group col-md-6">
                    <label>Documento 4</label>
                    <?=$this->class_security->url_documento($ticket->t_documento4)?>
                </div>

                <div class="form-group col-md-12">
                    <label>Descripcion ticket</label>
                    <textarea id="descripcion" readonly rows="8" autofocus required class="form-control imput_reset" autocomplete="off"><?=$ticket->t_descripcion?></textarea>
                </div>

                <div class="support-index" style="width:100%">
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
                                                          <span class='info-data'><span class='label'>respondida en </span><span class='value'>{$msg->tr_atcreate}</span></span>
                                                          
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


                <div class="row">
                    <?php
                        if($ticket->t_estado  != 3):
                            echo "  <div class='form-group col-md-12'>
                                            <label>Respuesta Ticket</label>
                                            <textarea id='respuesta' name='respuesta'  rows='8' autofocus required class='form-control imput_reset' autocomplete='off'></textarea>
                                        </div>
                        
                                        <div class='form-group col-md-6'>
                                            <label>Documento 1</label>
                                            <div class='custom-file d-flex flex-row-reverse'>
                                                <input type='file' name='documento_1' accept='.gif,.jpg,.jpeg,.png,.doc,.docx, .pdf' class='custom-file-input' id='customFile' >
                                                <label class='custom-file-label text-right' for='customFile'>Seleccionar archivos</label>
                                            </div>
                                        </div>
                        
                                        <div class='form-group col-md-6'>
                                            <label>Documento 2</label>
                                            <div class='custom-file d-flex flex-row-reverse'>
                                                <input type='file' name='documento_2' accept='.gif,.jpg,.jpeg,.png,.doc,.docx, .pdf' class='custom-file-input' id='customFile' >
                                                <label class='custom-file-label text-right' for='customFile'>Seleccionar archivos</label>
                                            </div>
                                        </div>
                                        
                                        <div class='form-group col-md-12'>
                                            <div class='progress' style='height: 26px;'>
                                                <div class='progress-bar  progress-bar-striped  progress-bar-animated' role='progressbar' id='progressbar_invitado' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100'>Barra de progreso</div>
                                            </div>
                                        </div>";
                        endif;
                    ?>

                </div>



            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Cancelar Proceso</button>
           <?php if($ticket->t_estado  != 3) echo '<button type="submit" class="btn btn-success waves-effect waves-light">Guardar</button>';?>
        </div>
    </form>

<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-900');

        // $(this).fecha_func('#fecha');
        // $(this).dinero_func('#monto');


        $('#frm_data').validator().on('submit', function(e) {
            if (e.isDefaultPrevented()) {
                $(this).mensaje_alerta(1, "El campo es obligatorio");
                return false;
            } else {

                $(this).simple_call('frm_data','url_save',false,() => {
                    $(this).forms_modal({"page" : "ticket_admin","data1" : "<?=$id?>","title" : "Tickets"})
                });
                return false;
            }
        })

    })
</script>
