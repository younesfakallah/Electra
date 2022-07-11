<?php
    require 'inc/bootstrap.php';
    // Une fois que l'utilisateur aura cliquer sur le lien (contenant son ID et son token) initialement envoyer par mail
    // On récupérera ses deux paramétres dans la fonction confirm qui se trouve dans la class Auth
   $db = App::getDatabase();

    // Si le token a bien été récuperer et qu'il correspond au token de l'URL (paramétre)
    // Alors nous allons lui initialiser sa session (dans tous les cas une session sera initialiser)
    // mais je vais également lui vider son champ confirmation_token
    // de la db afin qu'il ne puissent plus accéder à cette page et enfin lui remplir son champs confirmed_at avec la date du jour
    // Et pour terminer je vais le rediriger vers son espace perso

    if(App::getAuth()->confirm($db, $_GET['id'], $_GET['token'], Session::getInstance())) {
        Session::getInstance()->setFlash('success', "Votre compte a bien été activé, bienvenue sur Electra");
        App::redirect('account.php');
    }else{
        Session::getInstance()->setFlash('error', "Ce code d'activation n'est plus valide");
        App::redirect('login.php');
    }




