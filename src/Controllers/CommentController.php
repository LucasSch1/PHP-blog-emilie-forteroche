<?php

namespace App\src\Controllers;
use App\src\models\ArticleManager;
use App\src\models\Comment;
use App\src\models\CommentManager;
use App\src\Utils\Utils;

class CommentController
{
    /**
     * Ajoute un commentaire.
     * @return void
     */
    public function addComment(): void
    {
        // Récupération des données du formulaire.
        $pseudo = Utils::request("pseudo");
        $content = Utils::request("content");
        $idArticle = Utils::request("idArticle");

        // On vérifie que les données sont valides.
        if (empty($pseudo) || empty($content) || empty($idArticle)) {
            throw new Exception("Tous les champs sont obligatoires. 3");
        }

        // On vérifie que l'article existe.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($idArticle);
        if (!$article) {
            throw new Exception("L'article demandé n'existe pas.");
        }

        // On crée l'objet Comment.
        $comment = new Comment([
            'pseudo' => $pseudo,
            'content' => $content,
            'idArticle' => $idArticle
        ]);

        // On ajoute le commentaire.
        $commentManager = new CommentManager();
        $result = $commentManager->addComment($comment);

        // On vérifie que l'ajout a bien fonctionné.
        if (!$result) {
            throw new Exception("Une erreur est survenue lors de l'ajout du commentaire.");
        }

        // On redirige vers la page de l'article.
        Utils::redirect("showArticle", ['id' => $idArticle]);
    }


    /**
     * Supprime un commentaire en fonction de l’ID de commentaire fourni.
     *
     * Cette méthode vérifie si la demande entrante est une demande POST et si le 'commentId’
     * paramètre est défini. Il convertit ensuite le 'commentId' en un entier pour des raisons de sécurité
     * avant de tenter de supprimer le commentaire correspondant à l’aide du CommentManager.
     *
     * Si la demande est acceptée, l’application renvoie à la page de renvoi pour rafraîchir l’affichage.
     * En cas d’erreur pendant le processus de suppression, un message d’erreur s’affiche.
     *
     * @return void
     */
    public function deleteComment(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentId'])) {
            $commentId = intval($_POST['commentId']); // Convertir en entier pour la sécurité

            try {
                $commentManager = new CommentManager();
                $commentManager->deleteCommentById($commentId);
                // Redirige vers la même page pour rafraîchir
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit();
            } catch (Exception $e) {
                // Afficher un message d'erreur approprié
                echo "Erreur : " . $e->getMessage();
            }
        }

    }


}