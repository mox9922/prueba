<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="modal-body">


    <div class="row mt-4">
        <div class="table-responsive">
            <table id="modal_table" class="table table-striped table-padded text-center">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Filial</th>
                    <th>Deuda</th>
                    <th>Pago</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if(isset($datas)){
                    $i = 1;
                    foreach($datas as $pg){
                        echo "<tr> 
                                        <td>{$i}</td>
                                        <td>$pg[t_apartamento]</td>
                                        <td class='bolder nowrap'><span class='text-success'>₡ ".number_format($pg['rdeuda'])."</span></td>
                                        <td class='bolder nowrap'><span class='text-success'>₡ ".number_format($pg['rpagado'])."</span></td>
                                  </tr>";
                        $i++;
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        //remove size modal
        $(this).clear_modal_view('modal-700');
        $(this).datatable_func("#modal_table");


    })
</script>