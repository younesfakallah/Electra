<?php
    // Cette class servira à l'instanciation des différentes méthodes qui auront attrait à l'authentification
    class Auth{
        

        // Variable privée global à la class

        private $options = ['restriction_msg' => "Vous n'avez pas le droit d'accéder à cette page"];

        private $session;

        public function __construct($session, $options = []){
            $this->options = array_merge($this->options, $options);
            $this->session = $session;
        }
    
        // Cette fonction renverra un mot de passer hasher
        // Il suffira seulement de lui spécifier en argument le mot de passe à hasher
        public function hashPassword($password){
           return password_hash($password, PASSWORD_BCRYPT);
        }

        // Comme le nom de la fonction l'indique la cette fonctions permettra l'inscription
        // Elle prendra en parametre les informations de l'utilisateur (pseudo, mdp et l'email)
        // La gestion du token se fera directement ici avec l'appelle de la classe Str dans laquel est situer
        // La fonction qui vas générer le token
        public function register($db, $username, $password, $email) {
            $db = App::getDatabase();

            $password = $this->hashPassword($password);
            $token = Str::random(60);
            $role = 1;
            $db->query("INSERT INTO elec_users SET username = ?, password = ?, email = ?, confirmation_token = ?, id_elec_roles = ?", [$username, $password, $email, $token, $role]);
            $user_id = $db->lastInsertId();
            mail($email, 'Confirmation de votre compte', "Afin de valider votre compte Electra merci de cliquer sur ce lien\n\nhttp://localhost/confirm.php?id=$user_id&token=$token", 'From: youneslow60@gmail.com');
        }
        // Le parametre session servira à mettre à jour l'etat de la session
        // Si tout se passe bien loger l'utilisateur
        public function confirm($db, $user_id, $token){

            $user = $db->query('SELECT * FROM elec_users WHERE id = ?', [$user_id])->fetch();

            // Si le token a bien été récuperer et qu'il correspond au token de l'URL (paramétre)
            // Alors nous allons lui initialiser sa session (dans tous les cas une session sera initialiser)
            // mais je vais également lui vider son champ confirmation_token
            // de la db afin qu'il ne puissent plus accéder à cette page et enfin lui remplir son champs confirmed_at avec la date du jour

            if($user && $user->confirmation_token == $token) {      
                $db->query('UPDATE elec_users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
                $this->session->write('auth', $user);
                return true;
            }
            return false;
        }

        // Cette fonction nous servira de "verrou" pour les pages nécéssitant dêtre connecté à electra
        public function restrict(){
            if(!$this->session->read('auth')) {
                $this->session->setFlash('error', $this->options['restriction_msg']);
                header('Location: login.php');
                exit();
            }
        }
        // Cette fonction vérifie si l'utilisateur est connecté
        // Si oui renvoi ses infos
        public function user(){
            if(!$this->session->read('auth')) {
                return false;
            } else {
                return $this->session->read('auth');
            }
        }
        // Cette fonction permettra de créer une session (connecter un utilisateur) en mettant toute ses info
        // Daans la superglobal session
        // Elle prendra en parametre l'utilisateur à connecté
        public function connect($user){
            $this->session->write('auth', $user);
        }

        public function connectFromCookie($db){
                // Vérifier si le cookie de reco automatique a bien été initialiser
                // Si oui alors récupérer toute la row sql de l'utilisateur qui l'aura initialiser
                // Vérifier également si l'utilisateur n'a pas de connexion initialiser grace à SESSION
                if(isset($_COOKIE['remember']) && !$this->user()) {
                    $remember_token = $_COOKIE['remember'];
                    $parts = explode('==', $remember_token);
                    $user_id = $parts[0];
                    $user = $db->query('SELECT * FROM elec_users WHERE id = ?', [$user_id])->fetch();
                    if($user) {
                        // Vérifier si la valeur du cookie contient bien l'id et la valeur sql du champs remember_token de l'utilisateur
                        $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'younessss');
                        // Si la condition en dessous s'avére vrais cela voudras 
                        // qu'il peut encore garder sa connexion automatique (7 jours d'exp du cookie non dépasser)
                        if($expected == $remember_token) {
                            $this->connect($user);
                            setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                        } else {
                            setcookie('remember', NULL, -1);
                        }
                    }else {
                        setcookie('remember', NULL, -1);
                    }
                }
            }

        public function login($db, $username, $password, $remember = false){
                    // Renvoyer toute la row sql si le pseudonyme est dans la db; vérifier également que son champs de db confirmed_at
                    // a bien été rempli ce qui voudrais dire qu'il a confirmer son inscription avec le token
                    $user = $db->query('SELECT * FROM elec_users WHERE username = :username AND confirmed_at IS NOT NULL', ['username' => $username])->fetch();
                    // Vérifier si le mot de passe taper correspond au mot de passe de l'utilisateur sotcker en db
                    if(password_verify($password, $user->password)) {
                        $this->connect($user);
                        // Si l'utilisateur coche la checkbox "se souvenir de moi"
                        if($remember) {
                            $this->remember($db, $user->id);
                        }
                        return $user;
                    } else {
                        return false;
                    }
        }
        // se souvenir de la session de  l'utilisateur (insertion du cookie et de sa durée)
        public function remember($db, $user_id){
            $remember_token = Str::random(250);
            $db->query('UPDATE elec_users SET remember_token = ? WHERE id = ?', [$remember_token, $user_id]);
            // Faire en sorte de rendre la clé du cookie difficile à deviner et donc éviter qu'un utilisateur les regenére automatiquement
            // Ce cookie tiendra 7 jours
            setcookie('remember', $user_id . '==' . $remember_token . sha1($user_id . 'younessss'), time() + 60 * 60 * 24 * 7);
        }
        // Cette fonction supprimera la clé auth ce qui voudras dire que toute les info sql de l'utilisateur ne seront plus stocker
        // Il sera donc deconnecté
        public function logout() {
            setcookie('remember', NULL, -1);
            $this->session->delete('auth');
        }

        public function resetPassword($db, $email) {
                // Si l'adresse mail entré par l'utilisateur correspond à un champ mail de la db alors retourner la row de ce dernier
                // dans la variable user
                $user = $db->query('SELECT * FROM elec_users WHERE email = ? AND confirmed_at IS NOT NULL', [$email])->fetch();
                // Si l'utilisateur entre une adresse mail enregistré sur Electra alors remplir la conditon ci-dessous
                // Celle ci remplira le champ reset_token générer par notre fonction, et le champ reset_at par la date du jour
                // de la row sql de l'utilisateur
                var_dump($user);
                if($user){
    
                    $reset_token = Str::random(60);
                    $db->query('UPDATE elec_users SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);
                    mail($_POST['email'], 'Réinitialisation de votre mot de passe', "Afin de réinitialiser votre mot de passe Electra merci de cliquer sur ce lien\n\nhttp://localhost/reset.php?id={$user->id}&token=$reset_token", 'From: youneslow60@gmail.com');
                    return $user;
                }
                return false;
        }

        public function checkResetToken($db, $user_id, $token){
            // Récupérer toute la row quand l'id et le reset_token correspondent à ceux de l'URL
            // La deuxième partie de la requête sert à eviter que l'utilisateur spam la réinitialisation de compte
            return $db->query('SELECT * FROM elec_users WHERE id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)', [$user_id, $token])->fetch();
        }
    }
