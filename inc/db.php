<?php
    // Connexion à la DB
    $pdo = new PDO ('mysql:dbname=electra;host=localhost', 'root', '');
    // Lorsque PDO rencontrera une erreur il enverra une exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Défini comment PDO nous renvoi les information (type de donnée) par défaut PDO renvoi un tableau associatif
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    