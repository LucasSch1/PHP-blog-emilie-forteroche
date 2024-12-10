<?php

/**
 * Système d'autoload. 
 * A chaque fois que PHP va avoir besoin d'une classe, il va appeler cette fonction 
 * et chercher dnas les divers dossiers (ici Models, Controllers, Views, services) s'il trouve
 * un fichier avec le bon nom. Si c'est le cas, il l'inclut avec require_once.
 */
spl_autoload_register(function($className) {
    // On va voir dans le dossier Service si la classe existe.
    if (file_exists('services/' . $className . '.php')) {
        require_once 'services/' . $className . '.php';
    }

    // On va voir dans le dossier Model si la classe existe.
    if (file_exists('Models/' . $className . '.php')) {
        require_once 'Models/' . $className . '.php';
    }

    // On va voir dans le dossier Controller si la classe existe.
    if (file_exists('Controllers/' . $className . '.php')) {
        require_once 'Controllers/' . $className . '.php';
    }

    // On va voir dans le dossier View si la classe existe.
    if (file_exists('Views/' . $className . '.php')) {
        require_once 'Views/' . $className . '.php';
    }
    
});