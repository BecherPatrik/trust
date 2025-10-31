<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$navItems = [
        'index.php' => 'iSoupis',
];
?>

<nav>
    <div class="logo">
        <img src="../icon.png" alt="">
    </div>

    <ul>
        <?php foreach ($navItems as $file => $title): ?>
            <li class="<?= $currentPage === $file ? 'active' : '' ?>">
                <a href="<?= $file ?>"><?= e($title) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="trust-text">Trust.</div>
</nav>