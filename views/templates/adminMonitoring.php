<?php
/**
 * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun.
 * Et un formulaire pour ajouter un article.
 */
?>

<h2>Page de monitoring</h2>

<div class="adminArticle">

    <table style="width: 100%; border-collapse: collapse; text-align: center;">
        <thead>
        <tr>
            <th>Nom de l'article</th>
            <th>Nombre de vues</th>
            <th>Nombre de commentaires</th>
            <th>Date de publication</th>
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