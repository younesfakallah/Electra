<?php
// Le "problème" qu'on aura quand on voudras créer une instance de Database.php c'est qu'on devras toujours mettre en parametre
// Les informations de connexion à la base de donnée (nom d'hôte mot de passe...)
// Cette class nous évitera de le faire

// La variable static db me permettra de vérifier l'état de ma connexion à la db
// Ce qui m'évitera de me connecter à la db si je le suis déjà

// La méthode self me permettra de changer la variable static (son état)
class App{

    static $db = null;

    static function getDatabase(){
        if(!self::$db){
            self::$db = new Database('root', 'KB6wp93f7WZbv*_6', 'electra');
        }
        return self::$db;
    }

    static function getAuth(){
        return new Auth(Session::getInstance(), ['restriction_msg' => "Tu dois t'identifier pour accèder à cette page"]);
    }

    static function redirect($page){
        header("Location: $page");
    }

}

// à savoir : on utiliser ici une class static car on pourra l'appeller depuis n'importe où