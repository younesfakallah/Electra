<?php

// Cette fonction permet d'avoir des informations sur une variable

function debug($variable) {
    echo '<pre>' . print_r($variable, true) . '</pre>';
}


// Cette fonction nous servira de "verrou" pour les pages nécéssitant dêtre connecté à electra
function logged_only() {
    // Vérifie si une session est déjà demarrer sinon en démarrer une
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    if(!isset($_SESSION['auth'])) {
        $_SESSION['flash']['error'] = 'Vous devez être connecté pour utiliser Electra';
        header('Location: login.php');
        exit();
    }
}

function reconnect_cookie() {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Vérifier si le cookie de reco automatique a bien été initialiser
    // Si oui alors récupérer toute la row sql de l'utilisateur qui l'aura initialiser
    // Vérifier également si l'utilisateur n'a pas de connexion initialiser grace à SESSION
    if(isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
        require_once 'db.php';
        // Si jamais il ne trouve pas la connexion a la db (db.php) via le require_once alors charger pdo depuis global
        if(!isset($pdo)) {
            global $pdo; 
        }
        $remember_token = $_COOKIE['remember'];
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $req = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $req->execute([$user_id]);
        $user = $req->fetch();
        if($user) {
            // Vérifier si la valeur du cookie contient bien l'id et la valeur sql du champs remember_token de l'utilisateur
            $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'younessss');
            // Si la condition en dessous s'avére vrais cela voudras 
            // qu'il peut encore garder sa connexion automatique (7 jours d'exp du cookie non dépasser)
            if($expected == $remember_token) {
                session_start();
                $_SESSION['auth'] = $user;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
            } else {
                setcookie('remember', NULL, -1);
            }
        }else {
            setcookie('remember', NULL, -1);
        }
    }
}