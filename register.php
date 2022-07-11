<?php
    require_once 'inc/bootstrap.php';
    // Si les regex et verifications de donnée utilisateurs déjà existant fait en javascript s'avére retourner aucune
    // erreur, alors executer la requete d'insertion à la table users. Dans le cas contraire le javascript appliquera
    // la méthode preventDefault qui empechera la condition ci-dessous de retourner true
    // Un token aléatoire sera attribuer à chaque utilisateur tout justement enregistrer afin de permettre partiellement la confirmation
    // d'inscription par email. Chaque nouvelle utilisateur recevra un mail dans lequel sera indiquer un lien qui les redirigera vers
    // la page confirm.php avec comme parametres query leur ID et token

    if(isset($_POST['submit'])) {
        $db = App::getDatabase();
        App::getAuth()->register($db, $_POST['pseudonyme'], $_POST['password'], $_POST['email']);
        Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte');
        App::redirect('login.php');
        exit(); 
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
        <div id="notif"></div>
        
        <div class="ocontainer">
            <div class="bg-form">
                <img class="login-logo" src="./img/logo_signe.svg" alt="Logo d'Electra">
                <form action="" method="POST" id="myform">
                    <div id="bfinput">
                        <input id="finput" type="text" name="pseudonyme" placeholder="votre pseudonyme">
                    </div>
                    <div id="bsinput">
                        <input id="sinput" type="email" name="email" placeholder="votre adresse mail">
                    </div>
                    <div id="btinput">
                        <input id="tinput" type="password" name="password" placeholder="votre mot de passe">
                    </div>
                    <div class="instruction">
                        <p class="question">déjà inscrit ?</p>
                        <a href="/login.php" class="btn-sub btn-return-log">connexion</a>
                    </div>
                    <div class="check">
                        <input class="btn-sub register-btn" id="submit-action" type="submit" name="submit" value="s'inscrire">
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script src="./js/main.js"></script>
</body>
</html>