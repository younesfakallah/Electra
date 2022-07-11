<?php 

    require 'inc/bootstrap.php';
    App::getAuth()->restrict();

    

    // Si le mot de passe peut être modifier (vérif fais cote JS) récuperer l'id de l'utilisateur de la db
    // Egalement hasher son nouveau mot de passe pour pouvoir l'envoyer dans sa row sql
    //Initialiser une connexion à la db
    // Et enfin mettre à jour son champs sql password avec son nouveau mot de passe
    if(isset($_POST['submit'])){
        $auth = App::getAuth();
        $db = App::getDatabase();
        $user_id = $auth->user();
        $password = $auth->hashPassword($_POST['password']);
        $db->query('UPDATE elec_users SET password = ? WHERE id = ?', [$password, $user_id]);
        Session::getInstance()->setFlash('success', "Votre mot de passe a bien été mis à jour");
    }

?>
<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/styles/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <title>Electra - Mon compte</title>
</head>
<body class="forced_no_overflow">
    <?php if(App::getAuth()->user()): ?>
        <li class="logout"><a href="logout.php">Déconnexion <i class="fa-solid fa-arrow-up-left-from-circle"></i></a></li>
    <?php endif; ?>

    <!-- Afficher chaque message flash (le status de l'utilisateur) si il y en a -->
    <?php if(Session::getInstance()->hasFlashes()): ?>
            <?php foreach(Session::getInstance()->getFlashes() as $key => $message): ?>
                <?php if($key == 'error'): ?>
                    <div id="notif">
                        <p class="errormsg"> <?= $message; ?> </p>
                    </div>
                <?php else: ?>
                    <div id="notif">
                        <p class="successmsg"> <?= $message; ?> </p>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>

    <div class="ocontainer">
        <div class="bg-form">
            <img class="login-logo" src="./img/logo_signe.svg" alt="Logo d'Electra">
                <form action="" method="POST">
                    <div id="btinput">
                        <input id="tinput" type="password" name="password" placeholder="votre nouveau mot de passe">
                        <i class="fa-solid fa-arrows-to-eye eye" id="togglePassword"></i>
                    </div>
                    <div class="check">
                        <input class="btn-sub register-btn" type="submit" id="submitt" name="submit" value="modifier">
                    </div>
                </form>
        </div>
    </div>
    <script src="./js/account.js"></script>
</body>
</html>