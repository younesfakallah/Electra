<?php 

    require 'inc/bootstrap.php';
    App::getAuth()->restrict();

    // Récupération des films
    $shows = Show::showRetrieve();

    $showid = 1;
       

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
    <title>Electra - Tous les films</title>
</head>
<body class="forced_no_overflow">
    <!-- Header de la page d'accueil -->
    <header>
        <ul id="navbar">
            <li class="nav_list"><a class="nav_link" href="#">MA SHOWLIST</a></li>
            <li class="nav_list"><a class="nav_link" href="#">TIMELINE</a></li>
            <li id="logo"><a id="nav_logo" href="#"><h1>ELECTRA</h1></a></li>
            <li class="nav_list"><a class="nav_link" href="account.php">MON COMPTE</a></li>
            <li class="nav_list"><a class="nav_link" href="logout.php">DECONNEXION</a></li>
        </ul>
    </header>

    <main id="vertical_display">
        <div id="input_container">
            <input type="text" id="search_bar" name="search">
        </div>
        <!-- Affichage des films -->
        <div id="show">
            
        <?php for($i = 0; $i < 12; $i++): ?>
            <?php if($i == 0): ?>
                <div id="top_show" class="like_scope" style="background-image: url(<?= $shows[$i]->image; ?>); background-size: cover; background-position: center;">
                    <div class="top_showcard-content">
                        <p class="showcard-title"><?= $shows[$i]->name; ?></p>
                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                        <button class="top_resume_btn resume_btn">résumé | <i class="fa-solid fa-plus"></i></button>
                        <p class="synopsis"><?= $shows[$i]->synopsis; ?></p>
                        <a target="_BLANK" href="<?= $shows[$i]->buy; ?>" class="buy_btn">Achetez <i class="fa-solid fa-cart-shopping"></i></a>
                    </div>
                </div>
            <?php else: ?>
            <div id="show_<?= $showid; ?>" class="like_scope" style="background-image: url(<?= $shows[$i]->image; ?>); background-size: cover; background-position: center;">
                <div class="showcard-content">
                    <p class="showcard-title"><?= $shows[$i]->name; ?></p>
                    <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                    <button class="resume_btn">résumé | <i class="fa-solid fa-plus"></i></button>
                    <p class="synopsis"><?= $shows[$i]->synopsis; ?></p>
                    <a target="_BLANK" href="<?= $shows[$i]->buy; ?>" class="buy_btn">Achetez <i class="fa-solid fa-cart-shopping"></i></a>
                </div>
            </div>
            <?php $showid++; ?>
            <?php endif; ?>
        <?php endfor; ?>
        </div>
    </main>

    <footer>
        <p>Electra 2022 - Tout droit réservé</p>
    </footer>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
                <script src="./js/main.js"></script>
</body>
</html>