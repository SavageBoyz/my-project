<!--
*
* Вывод информации о СМП
*
-->

<!-- Успешная сессия -->
<?php  $session = \Config\Services::session();
  ?>
  <?php if (isset($session->success_sbe)): ?>
  <div class="alert alert-success text-center alert-dismissible fade show mb-0" role="alert">
    <?= $session->success_sbe ?>
    <button  type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert">
    </button>
  </div>
  <?php endif; ?>
  <?php unset($_SESSION['success_sbe']); ?>
<!-- Информация по СМП -->
<div class="container">
  <div class="card">
    <div class="card-header">
      <div class="d-grid gap-2 d-md-flex justify-content-md-end" role="toolbar" aria-label="Toolbar with button groups">
         <div class="btn-group me-2" role="group" id="addButton" aria-label="First group">
           <a href="/create_sbe" type="button" class="btn btn-outline-secondary btn-sm">Добавить</a>
         </div>
       </div>
    </div>
  <div class="card-body">
<table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
          <tr>
              <th>ID</th>
              <th>Проверяемый СМП</th>
              <th>Действия</th>
          </tr>
      </thead>
      <tbody>

        <?php if($sbe) ?>
            <?php foreach ($sbe as $sbeItem):?>
            <tr>
              <th><?= $sbeItem['Id_sbe']?></th>
              <td><?= $sbeItem['TitleS']?></td>
              <td align="center" width="10">
                <a type="button" onclick="delete_data(<?=$sbeItem['Id_sbe']?>)" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i></a>
                <a href="<?php base_url()?>/edit_sbe/<?=$sbeItem['Id_sbe']?>" class="btn btn-outline-primary btn-sm" type="button"><i class="bi bi-pencil-square"></i></a>
             </td>
            </tr>
            <?php endforeach; ?>
      </tbody>
      <tfoot>
          <tr>
            <th>ID</th>
            <th>Проверяемый СМП</th>
            <th>Действия</th>
          </tr>
      </tfoot>
  </table>
</div>
  </div>
  </div>
<script type="text/javascript">
  $(document).ready(function() {
    $('#example').DataTable();
  });
  /*Удаление данных*/
  function delete_data(Id_au)
  {
      if(confirm("Вы точно хотите это удалить?"))
      {
          window.location.href="<?php echo base_url();?>/sbe/delete/" + Id_au;
      }
      return false;
  }
  /**********************************/
</script>
