<?php $pager->setSurroundCount(2); ?>
<ul class="pagination pagination-sm m-0 float-end">
  <?php if ($pager->hasPrevious()): ?>
    <li class="page-item"><a class="page-link" href="<?= $pager->getPreviousPage(); ?>">«</a></li>
  <?php else: ?>
    <li class="page-item disabled"><span class="page-link">«</span></li>
  <?php endif; ?>

  <?php foreach ($pager->links() as $link): ?>
    <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
      <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
    </li>
  <?php endforeach; ?>

  <?php if ($pager->hasNext()): ?>
    <li class="page-item"><a class="page-link" href="<?= $pager->getNextPage(); ?>">»</a></li>
  <?php else: ?>
    <li class="page-item disabled"><span class="page-link">»</span></li>
  <?php endif; ?>
</ul>