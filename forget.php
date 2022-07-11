<?php
    require 'inc/bootstrap.php';
    if(!empty($_POST) && !empty($_POST['email'])) {
        $db = App::getDatabase();
        $auth = App::getAuth();
        $session = Session::getInstance();
        if($auth->resetPassword($db, $_POST['email'])){
            $session->setFlash('success', 'Les instructions du rappel de mot de passe vous ont été envoyées par emails');
            App::redirect('login.php');
        } else {
            $session->setFlash('error', 'Aucun compte ne correspond à cette adresse');
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
    <title>Electra - Mot de passe oublié</title>
</head>
<body>
    <main>
        <div class="ocontainer">
            <div class="bg-form">
                <img class="login-logo" src="./img/logo_signe.svg" alt="Logo d'Electra">
                <form action="" method="POST">
                    <div id="bsinput">
                        <input id="sinput" type="email" name="email" placeholder="votre adresse mail">
                    </div>
                    <div class="check">
                        <input class="btn-sub register-btn" type="submit" id="submitt" name="submit" value="envoyer un code">
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script src="./js/main.js"></script>
</body>
</html>