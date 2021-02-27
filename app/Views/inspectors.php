<!--
*
* Вывод информации о проверках
*
-->

<!-- Успешная сессия -->
<?php  $session = \Config\Services::session();
  ?>
  <?php if (isset($session->success_ins)): ?>
  <div class="alert alert-success text-center alert-dismissible fade show mb-0" role="alert">
    <?= $session->success_ins?>
    <button  type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert">
    </button>
  </div>
  <?php endif; ?>
  <?php unset($_SESSION['success_ins']); ?>

<!-- Информация по проверкам-->
<div class="container">
  <div class="card">
    <div class="card-header">
      <div class="d-grid gap-2 d-md-flex justify-content-md-end " role="toolbar" aria-label="Toolbar with button groups">
        <?php echo form_open_multipart('inspectors/import');?>
        <div class="input-group input-group-sm mb-1">
          <input type="file" class="form-control" name="file_excel" accept=".xls,.xlsx" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
          <button class="btn btn-outline-secondary btn-sm" type="submit" id="inputGroupFileAddon04"  >Загрузить .xlsx</button>
        </div>
        <?php echo form_close(); ?>
         <div class="btn-group-sm me-1" role="group" id="addButton" aria-label="First group">
           <a href="/create_ins" type="button" class="btn btn-outline-secondary btn-sm">Добавить</a>
         </div>
       </div>
    </div>
  <div class="card-body">
<table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
          <tr>
              <th>ID</th>
              <th>Проверяемый СМП</th>
              <th>Контролирующий орган</th>
              <th>Плановый период проверки от</th>
              <th>Плановый период проверки до</th>
              <th>Плановая длительность</th>
              <th>Действия</th>
          </tr>
      </thead>
      <tfoot>
            <tr>
              <th>ID</th>
              <th>Проверяемый СМП</th>
              <th>Контролирующий орган</th>
              <th>Плановый период проверки от</th>
              <th>Плановый период проверки до</th>
              <th>Плановая длительность</th>
              <th>Действия</th>
            </tr>
        </tfoot>
  </table>
</div>
  </div>
  </div>
<script type="text/javascript">
/* Вывод таблицы*/
$(document).ready(function(){

  $('#example thead tr').clone(true).appendTo( '#example thead' );
    $('#example thead tr:eq(1) th').each( function (i) {

        var title = $(this).text();

        if(i == 6){
          $(this).html( 
              '<div class="clearCon">'+
                '<input id="Filter'+ i +'" class="clearInput" type="text" placeholder="'+ title +'" disabled/>'+
                '<a id="ClearIcon'+ i +'" class="clearIcon" type="button"><i class="bi bi-x-square"></i></a>'+
              '</div>'
            ); 
        }else if((i == 3) || (i == 4)){
          $(this).html( 
            '<div class="clearCon">'+
                '<input id="Filter'+ i +'" class="clearInput" type="date"/>'+
                '<a id="ClearIcon'+ i +'" class="clearIcon" type="button"><i class="bi bi-x-square"></i></a>'+
              '</div>'
          );
        }else{
          $(this).html( 
            '<div class="clearCon">'+
                '<input id="Filter'+ i +'" class="clearInput" type="text" placeholder="'+ title +'" />'+
                '<a id="ClearIcon'+ i +'" class="clearIcon" type="button"><i class="bi bi-x-square"></i></a>'+
              '</div>');
        }
    } );

var table = $('#example').DataTable({
            "serverSide":true,
            "searching": false,
            "processing": true,
            "lengthChange":false,
            "orderCellsTop": true,
            "fixedHeader": true,
            "ajax":{
                url:"<?=base_url('inspectors/table_data')?>",
                type:'POST',
                data: function(data){
                  data.idF = $('#Filter0').val();
                  data.sbe_nameF = $('#Filter1').val();
                  data.au_nameF = $('#Filter2').val();
                  data.date_frF = $('#Filter3').val();
                  data.date_toF = $('#Filter4').val();
                  data.durationF = $('#Filter5').val();
                }
                },
                'dom': 'Bfrtip',
                "buttons": [ {
                  text:      '<i class="bi bi-file-earmark-spreadsheet"></i>',
                  className: 'btn btn-outline-secondary Excel btn-sm',
                  extend: 'excelHtml5',
                  exportOptions: {columns: [0,1,2,3,4,5]}, 
                  }],
                "columnDefs": [{
                  "targets": [6],
                  "orderable": false
                }],
                initComplete: function () {
                table.buttons().container()
                  .appendTo( $('.dataTables_filter', table.table().container() ) );
              }
          });

          $('.clearInput').on('input keyup', function(e) {
              table.draw();
            });

          document.getElementById("ClearIcon0").onclick = function(e) {
             document.getElementById("Filter0").value = "";
             table.draw();
            }
          document.getElementById("ClearIcon1").onclick = function(e) {
             document.getElementById("Filter1").value = "";
             table.draw();
            }  
          document.getElementById("ClearIcon2").onclick = function(e) {
             document.getElementById("Filter2").value = "";
             table.draw();
            }  
          document.getElementById("ClearIcon3").onclick = function(e) {
             document.getElementById("Filter3").value = "";
             table.draw();
            }
          document.getElementById("ClearIcon4").onclick = function(e) {
             document.getElementById("Filter4").value = "";
             table.draw();
            }
          document.getElementById("ClearIcon5").onclick = function(e) {
             document.getElementById("Filter5").value = "";
             table.draw();
            }
}); 
/**********************************/

/*Удаление данных*/
function delete_data(Id_ins)
{
    if(confirm("Вы точно хотите это удалить?"))
    {
        window.location.href="<?php echo base_url();?>/inspectors/delete/" + Id_ins;
    }
    return false;
}
/**********************************/
</script>
