<?php

/**
 * Contrôleur de la partie admin.
 */

namespace App\Controllers;

use App\Views\View;
use Exception;
use App\Models\Article;
use App\Models\ArticleManager;
use App\Models\UserManager;
use App\Utils\Utils;

class AdminController
{
    /**
     * Affiche la page d'administration.
     * @return void
     */
    public function showAdmin(): void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();
// On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticles();
// On affiche la page d'administration.
        $view = new View("Administration");
        $view->render("admin", [
            'articles' => $articles
        ]);
    }

    /**
     * Affiche le tableau de bord de l'administration.
     * @return void
     */
    public function showAdminMonitoring(): void
    {
        // On vérifie que l'utilisateur est connecté.
        $this->checkIfUserIsConnected();
//On détermine les critères de tri et l'ordre à partir de GET
        $sortBy = $_GET['sortBy'] ?? 'date_creation';
        $order = $_GET['order'] ?? 'desc';
// On récupère les articles.
        $articleManager = new ArticleManager();
        $articles = $articleManager->getAllArticlesOptions($sortBy, $order);
// On récupère la flèche de triage
        $adminController = new AdminController();
        $sortArrow = $adminController->getSortArrow($sortBy, $sortBy, $order);
// On affiche la page d'administration.
        $view = new View("AdministrationMonitoring");
        $view->render("adminMonitoring", [
            'articles' => $articles,
            'sortBy' => $sortBy,
            'order' => $order
        ]);
    }

    /**
     * Vérifie que l'utilisateur est connecté.
     * @return void
     */
    private function checkIfUserIsConnected(): void
    {
        // On vérifie que l'utilisateur est connecté.
        if (!isset($_SESSION['user'])) {
            Utils::redirect("connectionForm");
        }
    }

    /**
     * Affichage du formulaire de connexion.
     * @return void
     */
    public function displayConnectionForm(): void
    {
        $view = new View("Connexion");
        $view->render("connectionForm");
    }

    /**
     * Connexion de l'utilisateur.
     * @return void
     * @throws Exception
     */
    public function connectUser(): void
    {
        // On récupère les données du formulaire.
        $login = Utils::request("login");
        $password = Utils::request("password");
// On vérifie que les données sont valides.
        if (empty($login) || empty($password)) {
            throw new Exception("Tous les champs sont obligatoires. 1");
        }

        // On vérifie que l'utilisateur existe.
        $userManager = new UserManager();
        $user = $userManager->getUserByLogin($login);
        if (!$user) {
            throw new Exception("L'utilisateur demandé n'existe pas.");
        }

        // On vérifie que le mot de passe est correct.
        if (!password_verify($password, $user->getPassword())) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            throw new Exception("Le mot de passe est incorrect : $hash");
        }

        // On connecte l'utilisateur.
        $_SESSION['user'] = $user;
        $_SESSION['idUser'] = $user->getId();
// On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Déconnexion de l'utilisateur.
     * @return void
     */
    public function disconnectUser(): void
    {
        // On déconnecte l'utilisateur.
        unset($_SESSION['user']);
// On redirige vers la page d'accueil.
        Utils::redirect("home");
    }

    /**
     * Affichage du formulaire d'ajout d'un article.
     * @return void
     */
    public function showUpdateArticleForm(): void
    {
        $this->checkIfUserIsConnected();
// On récupère l'id de l'article s'il existe.
        $id = Utils::request("id", -1);
// On récupère l'article associé.
        $articleManager = new ArticleManager();
        $article = $articleManager->getArticleById($id);
// Si l'article n'existe pas, on en crée un vide.
        if (!$article) {
            $article = new Article();
        }

        // On affiche la page de modification de l'article.
        $view = new View("Edition d'un article");
        $view->render("updateArticleForm", [
            'article' => $article
        ]);
    }

    /**
     * Ajout et modification d'un article.
     * On sait si un article est ajouté car l'id vaut -1.
     * @return void
     */
    public function updateArticle(): void
    {
        $this->checkIfUserIsConnected();
// On récupère les données du formulaire.
        $id = Utils::request("id", -1);
        $title = Utils::request("title");
        $content = Utils::request("content");
// On vérifie que les données sont valides.
        if (empty($title) || empty($content)) {
            throw new Exception("Tous les champs sont obligatoires. 2");
        }

        // On crée l'objet Article.
        $article = new Article([
            'id' => $id, // Si l'id vaut -1, l'article sera ajouté. Sinon, il sera modifié.
            'title' => $title,
            'content' => $content,
            'id_user' => $_SESSION['idUser']
        ]);
// On ajoute l'article.
        $articleManager = new ArticleManager();
        $articleManager->addOrUpdateArticle($article);
// On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Suppression d'un article.
     * @return void
     */
    public function deleteArticle(): void
    {
        $this->checkIfUserIsConnected();
        $id = Utils::request("id", -1);
// On supprime l'article.
        $articleManager = new ArticleManager();
        $articleManager->deleteArticle($id);
// On redirige vers la page d'administration.
        Utils::redirect("admin");
    }

    /**
     * Détermine la flèche de tri à afficher en fonction de la colonne et de l’ordre de tri actuels.
     *
     * @param string $column La colonne en cours d’évaluation.
     * @param string $currentSort La colonne triée actuelle.
     * @param string $currentOrder L’ordre actuel de tri, soit 'asc' ou 'desc'.
     * @return string Renvoie '↓' si la colonne est la colonne triée actuelle et que l’ordre est croissant,
     *   '↑' si l’ordre est décroissant, ou une chaîne vide si la colonne n’est pas triée.
     */
    public static function getSortArrow($column, $currentSort, $currentOrder)
    {
        if ($column == $currentSort) {
            return $currentOrder == 'asc' ? '↓' : '↑';
        }
        return '';
    }
}
