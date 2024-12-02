<?php
/**
 * Affichage de la partie adminMonitoring : Affichage d'un tableau contenant le nom de l'article, suivi du nombre de vues , nombre de commentaires et de la date de publication */
?>

<h2>Page de monitoring</h2>

<div class="adminArticle">

    <table style="width: 100%; border-collapse: collapse; text-align: center;">
        <thead>
        <tr>
            <th><a href="?action=showAdminMonitoring&sortBy=title&order=asc">Titre de l'article ↓ | <a href="?action=showAdminMonitoring&sortBy=title&order=desc">↑</a></th>
            <th><a href="?action=showAdminMonitoring&sortBy=views&order=asc">Nombre de vues ↓ | <a href="?action=showAdminMonitoring&sortBy=views&order=desc">↑</a></th>
            <th><a href="?action=showAdminMonitoring&sortBy=comments_count&order=asc">Nombre de commentaires ↓ | <a href="?action=showAdminMonitoring&sortBy=comments_count&order=desc">↑</a> </th>
            <th><a href="?action=showAdminMonitoring&sortBy=date_creation&order=asc">Date de publication ↓ | <a href="?action=showAdminMonitoring&sortBy=date_creation&order=desc">↑</a> </th>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article) { ?>
                <tr>
                    <td><?= htmlspecialchars($article->getTitle()) ?></td>
                    <td><?= htmlspecialchars($article->getViews()) ?></td>
                    <td><?= htmlspecialchars($article->getCommentsCount()) ?></td>
                    <td><?= htmlspecialchars($article->getDateCreation()->format('Y-m-d')) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>