<!DOCTYPE html>
<html lang="id">
  <?= $this->include('layout/header_home') ?>
  <body class="<?= esc($bodyClass ?? '') ?>">
    <header id="header" class="header d-flex align-items-center sticky-top">
      <?= $this->include('layout/navbar_home') ?>
    </header>

    <?= isset($content) ? $content : $this->renderSection('content') ?>

    <?= $this->include('layout/footer_home') ?>
  </body>
</html>