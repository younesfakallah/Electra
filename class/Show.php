<?php

class Show{
    // Cette class servira à l'instanciation des différentes méthodes qui auront attrait à la gestion des films

    // Cette fonction renvoi un tableau de tous les films stocker en db dans l'ordre suivant: du film le plus récent au plus anciens.
    static function showRetrieve(){
        $db = App::getDatabase();  
        $shows = $db->query('SELECT name, id_elec_genres, image, synopsis, buy FROM elec_shows')->fetchAll();
        return $shows;
    }
}