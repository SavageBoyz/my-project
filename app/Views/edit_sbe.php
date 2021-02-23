<!--
*
* Редактирование информации о СМП
*
-->
<!-- Сессия warning -->
<?php  $session = \Config\Services::session();
  ?>
<?php if (isset($session->warning)): ?>
<div class="alert alert-warning text-center alert-dismissible fade show mb-0" role="alert">
  <?= $session->warning ?>
  <button  type="button" class="btn-close" aria-label="Close" data-bs-dismiss="alert">
  </button>
</div>
<?php endif; ?>
<?php unset($_SESSION['warning']); ?>

<section>
  <div class="container">
    <form class="row g-3" action="/sbe/edit_validation/<?=$sbe['Id_sbe']?>" method="post">
    <!-- 1 --->
    <div class="form-group col-md-4">
      <label for="title" class="form-label">Плановая длительность проверки: </label>
      <input type="text" class="form-control" name="title" id="title" autocomplete="off"
      value= '<?= $sbe['TitleS']; ?>' required>
    </div>
    <!-- Кнопка submit -->
    <div class="form-group col-12">
      <button class="btn btn-primary" type="submit">Сохранить</button>
    </div>
    </form>
  </div>
</section>
