<?php

/**
 * Classe qui gère les articles.
 */
class ArticleManager extends AbstractEntityManager 
{
    /**
     * Récupère tous les articles.
     * @return array : un tableau d'objets Article.
     */
    public function getAllArticles() : array
    {
        $sql = "SELECT * FROM article";
        $result = $this->db->query($sql);
        $articles = [];

        while ($article = $result->fetch()) {
            $articles[] = new Article($article);
        }
        return $articles;
    }


    public function getAllArticlesOptions() : array
    {
        $sql = "SELECT id, title, date_creation, views  FROM article";
        $result = $this->db->query($sql);
        $articles = [];

        while ($articleData = $result->fetch()) {
            $article = new Article($articleData);

            // Récupère le nombre de commentaires pour cet article
            $commentsCount = $this->getCommentsCountByArticleId($article->getId());

            // Définit le nombre de commentaires dans l'objet Article
            $article->setCommentsCount($commentsCount);
            // Ajoute l'article à la liste
            $articles[] = $article;

        }
        return $articles;
    }
    
    /**
     * Récupère un article par son id.
     * @param int $id : l'id de l'article.
     * @return Article|null : un objet Article ou null si l'article n'existe pas.
     */
    public function getArticleById(int $id) : ?Article
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $result = $this->db->query($sql, ['id' => $id]);
        $article = $result->fetch();
        if ($article) {
            return new Article($article);
        }
        return null;
    }

    /**
     * Ajoute ou modifie un article.
     * On sait si l'article est un nouvel article car son id sera -1.
     * @param Article $article : l'article à ajouter ou modifier.
     * @return void
     */
    public function addOrUpdateArticle(Article $article) : void 
    {
        if ($article->getId() == -1) {
            $this->addArticle($article);
        } else {
            $this->updateArticle($article);
        }
    }

    /**
     * Ajoute un article.
     * @param Article $article : l'article à ajouter.
     * @return void
     */
    public function addArticle(Article $article) : void
    {
        $sql = "INSERT INTO article (id_user, title, content, date_creation) VALUES (:id_user, :title, :content, NOW())";
        $this->db->query($sql, [
            'id_user' => $article->getIdUser(),
            'title' => $article->getTitle(),
            'content' => $article->getContent()
        ]);
    }

    /**
     * Modifie un article.
     * @param Article $article : l'article à modifier.
     * @return void
     */
    public function updateArticle(Article $article) : void
    {
        $sql = "UPDATE article SET title = :title, content = :content, date_update = NOW() WHERE id = :id";
        $this->db->query($sql, [
            'title' => $article->getTitle(),
            'content' => $article->getContent(),
            'id' => $article->getId()
        ]);
    }

    /**
     * Supprime un article.
     * @param int $id : l'id de l'article à supprimer.
     * @return void
     */
    public function deleteArticle(int $id) : void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $this->db->query($sql, ['id' => $id]);
    }


    /**
     * Incrémente le nombre de vues d’un article par son ID
     *
     * @param int $id : l’identifiant unique de l’article à mettre à jour
     * @return void
     */
    public function incrementViews($id) : void
    {
        $sql = "UPDATE article SET views = views + 1 WHERE id = :id";
        $stmt = $this->db->getPDO()->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function getCommentsCountByArticleId(int $articleId): int
    {
        $sql = "SELECT COUNT(*) as count FROM comment WHERE id_article = :articleId";
        $stmt = $this->db->query($sql, ['articleId' => $articleId]);
        // Récupère le résultat
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }



    public function getViewsByArticleId(int $id): int
    {
        $sql = "SELECT views FROM article WHERE id = :idArticle";
        $result = $this->db->query($sql, ['idArticle' => $id]);

        return (int) $result[0]['views'];
    }

}