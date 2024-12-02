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


    /**
     * Récupère une liste de tous les articles disponibles triés selon les options spécifiées.
     *
     * @param string $sortBy Le champ par lequel trier les articles, 'title', 'views', ou 'date_creation'. Par défaut 'date_creation'.
     * @param string $order L'ordre de tri, 'asc' pour ascendant ou 'desc' pour descendant. Par défaut 'desc'.
     * @return array Un tableau d'objets Article contenant la liste des articles triés.
     */
    public function getAllArticlesOptions(string $sortBy = 'date_creation', string $order = 'desc') : array
    {

        //Vérifie si le triage donc la variable $sortBy contient une des valeurs situé dans la tableau sinon reset un tri sur la date de création
        $validSortBy = ['title', 'views', 'date_creation'];
        if (!in_array($sortBy, $validSortBy)) {
            $sortBy = 'date_creation';
        }

        //Transformation de l'ordre de tri en majuscule pour la requete SQL
        $order = strtolower($order) === 'asc' ? 'ASC' : 'DESC';

        $sql = "SELECT id, title, date_creation, views  FROM article ORDER BY $sortBy $order";
        $result = $this->db->query($sql);
        $articles = [];

        while ($articleData = $result->fetch()) {
            $article = new Article($articleData);

            // Récupère le nombre de commentaire pour cet article
            $commentsCount = $this->getCommentsCountByArticleId($article->getId());

            // Défini le nombre de commentaires dans l'objet Article
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

    /**
     * Récupère le nombre de commentaires associés à un article par son ID
     *
     * @param int $articleId : l'identifiant unique de l'article dont on veut compter les commentaires
     * @return int : le nombre de commentaires pour l'article spécifié
     */
    public function getCommentsCountByArticleId(int $articleId): int
    {
        $sql = "SELECT COUNT(*) as count FROM comment WHERE id_article = :articleId";
        $stmt = $this->db->query($sql, ['articleId' => $articleId]);
        $result = $stmt->fetch();
        return (int)($result['count'] ?? 0);
    }


}