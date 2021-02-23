<!--
*
* Вывод информации о контр. органах
*
-->

<!-- Успешная сессия -->
<?php  $session = \Config\Services::session();
  ?>
  <?php if (isset($session->success_au)): ?>
  <div class="alert alert-success text-center alert-dismissible fade show mb-0" role="alert">
    <?= $session->success_au ?>
    <button  type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert">
    </button>
  </div>
  <?php endif; ?>
  <?php unset($_SESSION['success_au']); ?>
<!-- Информация по контр. органам -->
<div class="container">
  <div class="card">
    <div class="card-header">
      <div class="d-grid gap-2 d-md-flex justify-content-md-end" role="toolbar" aria-label="Toolbar with button groups">
         <div class="btn-group me-2" role="group" id="addButton" aria-label="First group">
           <a href="/create_au" type="button" class="btn btn-outline-secondary btn-sm">Добавить</a>
         </div>
       </div>
    </div>
  <div class="card-body">
<table id="example" class="table table-striped table-bordered" style="width:100%">
      <thead>
          <tr>
              <th>ID</th>
              <th>Контролирующий орган</th>
              <th>Действия</th>
          </tr>
      </thead>
      <tbody>
        <?php if($authority) ?>
            <?php foreach ($authority as $authorityItem):?>
            <tr>
              <th><?= $authorityItem['Id_au']?></th>
              <td><?= $authorityItem['TitleA']?></td>
              <td align="center" width="10">
                <a type="button" onclick="delete_data(<?=$authorityItem['Id_au']?>)" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i></a>
                <a href="<?= base_url()?>/edit_au/<?=$authorityItem['Id_au']?>" class="btn btn-outline-primary btn-sm" type="button"><i class="bi bi-pencil-square"></i></a>
             </td>
            </tr>
            <?php endforeach; ?>
      </tbody>
      <tfoot>
          <tr>
            <th>ID</th>
            <th>Контролирующий орган</th>
            <th>Действия</th>
          </tr>
      </tfoot>
  </table>
</div>
  </div>
  </div>

<script type="text/javascript">
  /*DataTables функция вывода*/
  $(document).ready(function() {
    $('#example').DataTable();
  });
  /*Удаление данных*/
  function delete_data(Id_au)
  {
      if(confirm("Вы точно хотите это удалить?"))
      {
          window.location.href="<?php echo base_url();?>/authority/delete/" + Id_au;
      }
      return false;
  }
  /**********************************/
</script>
