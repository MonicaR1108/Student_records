<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */
$pager->setSurroundCount(2);

if ($pager->getPageCount() <= 1) {
    return;
}
?>
<nav aria-label="Student pagination">
    <ul class="pagination mb-0">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Previous">Previous</a>
            </li>
        <?php else : ?>
            <li class="page-item disabled"><span class="page-link">Previous</span></li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <?php if ($link['active']) : ?>
                    <span class="page-link"><?= $link['title'] ?></span>
                <?php else : ?>
                    <a class="page-link" href="<?= $link['uri'] ?>"><?= $link['title'] ?></a>
                <?php endif ?>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Next">Next</a>
            </li>
        <?php else : ?>
            <li class="page-item disabled"><span class="page-link">Next</span></li>
        <?php endif ?>
    </ul>
</nav>
