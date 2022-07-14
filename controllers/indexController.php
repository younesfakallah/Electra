<?php
    // Vérification du nom d'utilisateur
    // Récupération de la row username en PHP afin de fournir la donnée à l'Ajax et enfin pouvoir faire nos vérifications
    // D'utilisateur déjà existant en Javascript 

    // Je n'ai pas trouver de moyen de faire fonctionner l'auto loader ici
    // L'inclusion se fait donc manuellement sur ce fichier
    require_once '../class/Database.php';
    require_once '../class/App.php';
    $db = App::getDatabase();

    if(isset($_GET['pseudonyme'])) {
        $userName = $db->query('SELECT id FROM elec_users WHERE username = ?', [$_GET['pseudonyme']])->fetch();
        echo json_encode($userName);
    } else if(isset($_GET['email'])) {
        $userMail = $db->query('SELECT id FROM elec_users WHERE email = ?', [$_GET['email']])->fetch();
        echo json_encode($userMail);
    } else if(isset($_GET['search'])) {
        $searchResult = $db->query('SELECT name, buy, image, synopsis FROM elec_shows WHERE TRIM(name) = ?', [$_GET['search']])->fetch();
        echo json_encode($searchResult);
    }