<!--
*
* Добавление информации о СМП
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
    <form class="row g-3" action="/sbe/create" method="post">
    <!-- 1 --->
    <div class="form-group col-md-3">
        <label for="title" class="form-label">Проверяемый СМП:</label>
        <input type="text" name="title" id="title" class="form-control" required autocomplete="off">
    </div>
    <!-- Кнопка submit -->
    <div class="form-group col-12">
      <button class="btn btn-primary" type="submit">Добавить</button>
    </div>
    </form>
  </div>
</section>
