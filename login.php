<?php
    require 'inc/bootstrap.php';
    $auth = App::getAuth();
    $db = App::getDatabase();
    $auth->connectFromCookie($db);
    // Si l'utilisateur est déjà connecté alors le rediriger vers son compte
    if($auth->user()) {
        App::redirect('accueil.php');
    }

    if(isset(($_POST['submit']))) {
        $user = $auth->login($db, $_POST['pseudonyme'], $_POST['password'], isset($_POST['remember']));
        $session = Session::getInstance();
        // Si l'utilisateur est connecté
        if($user) {
            $session->setFlash('success', 'Vous êtes maintenant connecté');
            App::redirect('accueil.php');
            exit();
        } else {
            $session->setFlash('error', 'Pseudonyme ou mot de passe incorrect');
        }
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
    <title>Electra - Inscrivez-vous</title>
</head>
<body>
    <main>
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
                    <div id="bfinput">
                        <input id="finput" type="text" name="pseudonyme" placeholder="votre pseudonyme">
                    </div>
                    <div id="btinput">
                        <input id="tinput" type="password" name="password" placeholder="votre mot de passe">
                    </div>
                    <div class="remember">
                            <label class="checkbox">
                                <span>Se souvenir de moi</span><input type="checkbox" name="remember">
                            </label>
                    </div>
                    <div class="instruction">
                        <p class="question">pas de compte?</p>
                        <a href="/register.php" class="btn-sub btn-return-log">inscris-toi</a>
                    </div>
                    <div class="forgotpwd">
                        <a href="forget.php" class="forgottxt">mot de passe oublié?</a>
                    </div>
                    <div class="check">
                        <input class="btn-sub register-btn" type="submit" id="submitt" name="submit" value="connexion">
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="./js/login.js"></script>
</body>
</html>