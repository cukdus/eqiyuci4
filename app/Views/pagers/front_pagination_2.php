<?php $pager->setSurroundCount(2); ?>
<section id="pagination-2" class="pagination-2 section">
  <div class="container">
    <nav class="d-flex justify-content-center" aria-label="Page navigation">
      <ul>
        <?php if ($pager->hasPrevious()): ?>
          <li>
            <a href="<?= $pager->getPreviousPage(); ?>" aria-label="Previous page">
              <i class="bi bi-arrow-left"></i>
              <span class="d-none d-sm-inline"></span>
            </a>
          </li>
        <?php else: ?>
          <li class="disabled">
            <span aria-label="Previous page">
              <i class="bi bi-arrow-left"></i>
              <span class="d-none d-sm-inline"></span>
            </span>
          </li>
        <?php endif; ?>

        <?php foreach ($pager->links() as $link): ?>
          <li>
            <a href="<?= $link['uri']; ?>" class="<?= $link['active'] ? 'active' : '' ?>"><?= $link['title']; ?></a>
          </li>
        <?php endforeach; ?>

        <?php if ($pager->hasNext()): ?>
          <li>
            <a href="<?= $pager->getNextPage(); ?>" aria-label="Next page">
              <span class="d-none d-sm-inline"></span>
              <i class="bi bi-arrow-right"></i>
            </a>
          </li>
        <?php else: ?>
          <li class="disabled">
            <span aria-label="Next page">
              <span class="d-none d-sm-inline"></span>
              <i class="bi bi-arrow-right"></i>
            </span>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</section>