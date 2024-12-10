<?php
/**
 * Affichage de la partie adminMonitoring : Affichage d'un tableau contenant le nom de l'article, suivi du nombre de vues , nombre de commentaires et de la date de publication */

use App\src\Controllers\AdminController;

?>

<h2>Page de monitoring</h2>

<div class="adminArticle">
    <table class="adminMonitoringTable" ">
        <thead>
        <tr>
            <th>
                <!-- permet à l'utilisateur de cliquer pour changer l'ordre de tri des articles par titre et affiche une indication visuelle de l'ordre actuel à l'aide d'une flèche.-->
                <a href="?action=showAdminMonitoring&sortBy=title&order=<?= $sortBy === 'title' && $order === 'asc' ? 'desc' : 'asc' ?>">
                    Titre de l'article <?= AdminController::getSortArrow('title', $sortBy, $order) ?>
                </a>
            </th>
            <th>
                <a href="?action=showAdminMonitoring&sortBy=views&order=<?= $sortBy === 'Views' && $order === 'asc' ? 'desc' : 'asc' ?>">
                    Nombre de vues <?= AdminController::getSortArrow('Views', $sortBy, $order) ?>
                </a>
            </th>
            <th>
                <a href="?action=showAdminMonitoring&sortBy=comments_count&order=<?= $sortBy === 'comments_count' && $order === 'asc' ? 'desc' : 'asc' ?>">
                    Nombre de commentaires <?= AdminController::getSortArrow('comments_count', $sortBy, $order) ?>
                </a>
            </th>
            <th>
                <a href="?action=showAdminMonitoring&sortBy=date_creation&order=<?= $sortBy === 'date_creation' && $order === 'asc' ? 'desc' : 'asc' ?>">
                    Date de publication <?= AdminController::getSortArrow('date_creation', $sortBy, $order) ?>
                </a>
            </th>
        </tr>
        </thead>
        <tbody>
            <?php
            foreach ($articles as $article) { ?>
                <tr>
                    <td><?= htmlspecialchars($article->getTitle()) ?></td>
                    <td><?= htmlspecialchars($article->getViews()) ?></td>
                    <td><?= htmlspecialchars($article->getCommentsCount()) ?></td>
                    <td><?= htmlspecialchars($article->getDateCreation()->format('Y-m-d')) ?></td>
                </tr>
            <?php } ?>
        </tbody>
</div>
