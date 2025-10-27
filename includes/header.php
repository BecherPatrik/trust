<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav>
    <ul>
        <li class="<?= $currentPage === 'index.php' ? 'active' : '' ?>"><a href="../index.php">Domů</a></li>
        <li class="<?= $currentPage === 'new-version.php' ? 'active' : '' ?>"><a href="../new-version.php">Nová verze</a></li>
        <li class="<?= $currentPage === 'administration.php' ? 'active' : '' ?>"><a href="../administration.php">Administrace</a></li>
    </ul>
</nav>

