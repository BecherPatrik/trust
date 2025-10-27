<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$navItems = [
        'index.php' => 'Domů',
        'new-version.php' => 'iSoupis',
        'administration.php' => 'Administrace'
];
?>

<nav>
    <ul>
        <?php foreach ($navItems as $file => $title): ?>
            <li class="<?= $currentPage === $file ? 'active' : '' ?>">
                <a href="<?= $file ?>"><?= e($title) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

