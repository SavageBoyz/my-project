<!--
*
* Добавление информации о проверках
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
    <form class="row g-3" action="/inspectors/create" method="post">
    <!-- Ввод СМП --->
    <div class="form-group col-md-4">
      <label for="sbe" class="form-label">Название СМП: </label>
      <select class="form-select" name="sbe" id='sbe' aria-label="Default select example">

        <?php if($sbe) ?>
          <?php foreach ($sbe as $value):?>
            <option value="<?=$value['Id_sbe']?>"><?=$value['TitleS']?></option>
              <?php endforeach; ?>
      </select>
    </div>
    <!-- 2 --->
    <div class="form-group col-md-4">
      <label for="authority" class="form-label">Контролирующий орган: </label>
      <select class="form-select" name="authority" id='authority' aria-label="Default select example">
        <?php if($authority) ?>
          <?php foreach ($authority as $value):?>
            <option value="<?=$value['Id_au']?>"><?=$value['TitleA']?></option>
              <?php endforeach; ?>
      </select>
    </div>
    <!-- 3 --->
    <div class="form-group col-md-4">
      <label for="duration" class="form-label">Плановая длительность проверки: </label>
      <input type="text" class="form-control" name="duration" id="duration" placeholder="3" autocomplete="off" required>
    </div>
    <!-- 4 --->
    <div class="form-group col-md-3">
        <label for="dateFrom"  class="form-label">Плановая длительность проверки от: </label>
        <input type="date" name="dateFrom" id="dateFrom" class="form-control" required>
    </div>
    <!-- 5 --->
    <div class="form-group col-md-3">
        <label for="dateTo" class="form-label">Плановая длительность проверки до: </label>
        <input type="date" name="dateTo" id="dateTo" class="form-control" required>
    </div>
    <!-- Кнопка submit -->
    <div class="form-group col-12">
      <button class="btn btn-primary" type="submit">Добавить</button>
    </div>
    </form>
  </div>
</section>
