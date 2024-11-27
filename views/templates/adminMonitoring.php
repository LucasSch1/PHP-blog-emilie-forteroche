<?php
/**
 * Affichage de la partie admin : liste des articles avec un bouton "modifier" pour chacun.
 * Et un formulaire pour ajouter un article.
 */
?>

<h2>Page de monitoring</h2>

<div class="adminArticle">
    <?php foreach ($articles as $article) { ?>
        <div class="articleLine">
            <div class="title"><?= $article->getTitle() ?></div>
            <div class="view"><?= $article->getViews() ?></div>
            <div class="commentsCounts"><?= $article->getCommentsCount() ?></div>
            <div class="publishedDate"><?= $article->getDateCreation()->format('Y-m-d') ?></div>
        </div>
    <?php } ?>
</div>

<a class="submit" href="index.php?action=showUpdateArticleForm">Ajouter un article</a>