<?php

/**
 * Template du formulaire d'update/creation d'un article.
 */

?>

<form action="index.php" method="post" class="foldedCorner">
    <h2><?= $article->getId() == -1 ? "Création d'un article" : "Modification de l'article " . htmlspecialchars($article->getTitle()) ?></h2>
    <div class="formGrid">
        <label for="title">Titre</label>
        <input type="text" name="title" id="title" value="<?= htmlspecialchars($article->getTitle()) ?>" required>
        <label for="content">Contenu</label>
        <textarea name="content" id="content" cols="30" rows="10" required><?= htmlspecialchars($article->getContent()) ?></textarea>
        <input type="hidden" name="action" value="updateArticle">
        <input type="hidden" name="id" value="<?= $article->getId() ?>">
        <button class="submit"><?= $article->getId() == -1 ? "Ajouter" : "Modifier" ?></button>
    </div>
</form>

<script>
    // Aucun script n'a été modifié ici.
</script>
