// Permet de déterminer quel page html execute le script afin de partitionner chaque bout de code
// avec la page html qui lui correspond ce qui me permettra de ne pas recréer un fichier javascript
// pour chaque page html
let filename = location.pathname.split('/').pop();


if(filename == "login.php" || filename == "register.php") {
    // Création d'une balise style qui sera utiliser dynamiquement

        let styleElem = document.head.appendChild(document.createElement("style"));

        // element dom de la page inscription

        let errorPass = window.getComputedStyle(document.getElementById('btinput'), '::before');
        let errorMail = window.getComputedStyle(document.getElementById('bsinput'), '::before');
        let errorUser = window.getComputedStyle(document.getElementById('bfinput'), '::before');

        let firstInput = document.getElementById('finput');
        let mailInput = document.getElementById('sinput');
        let passInput = document.getElementById('tinput');

        // Initialisation des variables global qui permettront la vérification des erreurs au submit/ 1 = erreur 0 = aucune erreur
        let errorAccname;
        let errorAccmail;
        let errorAccpass;

        // Les deux prochaines fonctions ont nécessiter AJAX car je souhaite vérifier des données php en javascript
        // Deux vérifications ont été faites: La regex et la vérification de donnée déjà existante

        const checkexistName = () => {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                            if(this.responseText != "false") {
                                errorAccname = 1;
                                firstInput.style.border = "solid 1px #C42021";
                                styleElem.innerHTML += '#bfinput::before {position: absolute; font-family: FontAwesome; content: "\\f06a"; color: #E20203; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                            } else if(!/^[A-Za-z]{5,10}.{0,4}$/.test(firstInput.value)) {
                                errorAccname = 1;
                                firstInput.style.border = "solid 1px #E20203";
                                styleElem.innerHTML += '#bfinput::before {position: absolute; font-family: FontAwesome; content: "\\f06a"; color: #C42021; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                            } else {
                                errorAccname = 0;
                                firstInput.style.border = "solid 1px #5AFF15";
                                styleElem.innerHTML += '#bfinput::before {position: absolute; font-family: FontAwesome; content: "\\f058"; color: #5AFF15; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                            }
                        }
                        }
                    xmlhttp.open("GET", "controllers/indexController.php?pseudonyme=" + firstInput.value, true)
                    xmlhttp.send();
                };

                const checkexistMail = () => {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                                errorAccmail = 1;
                                console.log(this.responseText)
                                if(this.responseText != "false") {
                                    mailInput.style.border = "solid 1px #C42021";
                                    styleElem.innerHTML += '#bsinput::before {position: absolute; font-family: FontAwesome; content: "\\f06a"; color: #E20203; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                                } else if(!/(.+)@(.+){2,}\.(.+){2,}/.test(mailInput.value)) {
                                    errorAccmail = 1;
                                    mailInput.style.border = "solid 1px #E20203";
                                    styleElem.innerHTML += '#bsinput::before {position: absolute; font-family: FontAwesome; content: "\\f06a"; color: #C42021; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                                } else {
                                    errorAccmail = 0;
                                    mailInput.style.border = "solid 1px #5AFF15";
                                    styleElem.innerHTML += '#bsinput::before {position: absolute; font-family: FontAwesome; content: "\\f058"; color: #5AFF15; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                                }
                            }
                            }
                        xmlhttp.open("GET", "controllers/indexController.php?email=" + mailInput.value, true)
                        xmlhttp.send();
                    };

                firstInput.onkeyup = () => {
                    checkexistName();
                }

                mailInput.onkeyup = () => {
                    checkexistMail();
                }

        // Cette fonction ne nécessite aucune données PHP car nous ne devons et pouvons pas vérifier ce genre de données SQL

        const checkRegex = (input) => {
            if(input.getAttribute('type') === "password") {
                    if(/[A-Z]{1,5}[a-z]{1,5}(?=.*[0-9]{1,5})(?=.*[^\w\d]{1,5})/.test(input.value)) {
                        errorAccpass = 0;
                        passInput.style.border = "solid 1px #5AFF15";
                        styleElem.innerHTML += '#btinput::before {position: absolute; font-family: FontAwesome; content: "\\f058"; color: #5AFF15; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                    } else {
                        errorAccpass = 1;
                        passInput.style.border = "solid 1px #C42021";
                        styleElem.innerHTML += '#btinput::before {position: absolute; font-family: FontAwesome; content: "\\f06a"; color: #C42021; font-size: 1.5rem; top: 0.45rem; right: .6rem;}'
                        
                    }
            }
        }

        passInput.onkeyup = () => {
            checkRegex(passInput);
        };

        // Dès lors que l'utilisateur appuiera sur le bouton de validation la fonction ci-dessous viendras vérifier les couleurs de bordures
        // Si toutes les bordures sont vertes le formulaire sera envoyés 
        // Une refactorisation sera faites car en terme de securité c'est assez desastreux

        document.getElementById('submit-action').addEventListener('click', (e) => {
            if(errorAccname === 0 && errorAccmail === 0 && errorAccpass === 0) {
                
            } else {
                e.preventDefault();

                let error = document.getElementById('notif');
                let errArr = [passInput, mailInput, firstInput];
                for(let i=0; i < errArr.length; i++) {
                    if(errArr[i].style.border === "" && errArr[i].getAttribute('type') === "text") {
                            error.innerHTML = "<div class='errormsg'>Veuillez entrer un nom d'utilisateur</div>"
                    } else if(errArr[i].style.border === "" && errArr[i].getAttribute('type') === "email") {
                            error.innerHTML = "<div class='errormsg'>Veuillez entrer une adresse mail</div>"
                    } else if(errArr[i].style.border === "" && errArr[i].getAttribute('type') === "password") {
                            error.innerHTML = "<div class='errormsg'>Veuillez entrer un mot de passe</div>"
                    } else if(errArr[i].style.border === "1px solid rgb(196, 32, 33)"
                    && errArr[i].getAttribute('type') === "text") {
                    error.innerHTML = "<div class='errormsg'>Ce pseudonyme est déjà utilisé</div>"
                    }
                        else if(errArr[i].style.border === "1px solid rgb(226, 2, 3)"
                                && errArr[i].getAttribute('type') === "text") {
                            error.innerHTML = "<div class='errormsg'>Le pseudonyme doit comporter 14 caractères maximum et commencer par 5 lettres minimum</div>"
                    } else if(errArr[i].style.border === "1px solid rgb(196, 32, 33)"
                                && errArr[i].getAttribute('type') === "email") {
                            error.innerHTML = "<div class='errormsg'>Cette adresse mail est déjà utilisée</div>"
                    }    
                    else if(errArr[i].style.border === "1px solid rgb(226, 2, 3)"
                                && errArr[i].getAttribute('type') === "email") {
                            error.innerHTML = "<div class='errormsg'>Le format de l'adresse mail est incorrecte</div>"
                    } else if(errArr[i].style.border === "1px solid rgb(196, 32, 33)"
                                && errArr[i].getAttribute('type') === "password") {
                            error.innerHTML = "<div class='errormsg'>Le mot de passe doit comporter 8 caractères minimum (majuscule, minuscule, caractères spécial, nombre)</div>"
                    }
                }
                error.style.display = "flex";
                setTimeout(() => {
                    error.style.display = "none";
                }, 5000);
            }
            })
} else if(filename == "accueil.php") {
    const searchValue = document.getElementById('search_bar');
    const showContainer = document.getElementById('show');

    const checkexistShow = () => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                    // Je parse les données recu pour les transformer de chaines de charactères
                    // en objets ce qui me permettra d'itérer decu
                    let showResult = JSON.parse(this.responseText);
                    console.log(showResult.name.trim());
                    showContainer.innerHTML = `<div id="top_show" class="like_scope" style="background-image: url(${showResult.image.trim()}); background-size: cover; background-position: center;">
                                                    <div class="top_showcard-content">
                                                        <p class="showcard-title">${showResult.name.trim()}</p>
                                                        <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                                                        <button class="top_resume_btn resume_btn">résumé | <i class="fa-solid fa-plus"></i></button>
                                                        <p class="synopsis">${showResult.synopsis.trim()}</p>
                                                        <a target="_BLANK" href="${showResult.buy.trim()}" class="buy_btn">Achetez <i class="fa-solid fa-cart-shopping"></i></a>
                                                    </div>
                                                </div>`
                    
                    allowAsync()
                }
        }
            xmlhttp.open("GET", "controllers/indexController.php?search=" + searchValue.value, true)
            xmlhttp.send();
        };

        searchValue.onkeyup = () => {
            checkexistShow();
        }

    //top_one_badge = CSSRulePlugin.getRule("#top_show:after");
    const allowAsync = () => {

        let resumeBtn = document.querySelectorAll('.resume_btn');

        let nodes = document.getElementById('show').childNodes;
        console.log(nodes)
        // Cibler les pseudo element coeur afin de pouvoir changer leurs couleurs au clique
        let element = document.getElementById('top_show');
        let styles = window.getComputedStyle(element,':after').getPropertyValue('content');
        console.log(styles);
        let top_badge = styles['display'];
        let toggleChecker = [true, true, true, true, true, true, true, true, true, true, true, true];
        let stars = document.querySelectorAll('.stars');
        let plus = document.querySelectorAll('.fa-plus');
        let titleCard = document.querySelectorAll('.showcard-title');
        let buyBtn = document.querySelectorAll('.buy_btn');
        let synopsis = document.querySelectorAll('.synopsis');

        for(let i = 0; i < resumeBtn.length; i++) {
            resumeBtn[i].addEventListener('click', () => {
                console.log('ok')
                    if(i == 0 && toggleChecker[i] == true) {
                        if(window.matchMedia("(max-width: 1230px)").matches) {
                            gsap.to(synopsis[i], 1, {position: "static",display: "block",color:"#F1D302",fontSize:"1.5rem"});
                            gsap.to(buyBtn[i], 1, {display: "block",bottom:"auto",left:"12rem",fontSize:"2rem",padding: "0.3rem 0.8rem"});
                        } else {
                            gsap.to(synopsis[i], 1, {position: "absolute",top:"7rem",display: "block",color:"#F1D302",fontSize:"1.5rem"});
                            gsap.to(buyBtn[i], 1, {display: "block"});
                        }
                        document.documentElement.style.setProperty("--dynamic-visibility", "hidden");
                        gsap.to("#top_show", 1, {paddingTop:"0"});
                        gsap.to(plus[i], 1, {rotation:45});
                        gsap.to(stars[i], 0, {display:"none"});
                        gsap.to(titleCard[i], 0, {display:"none"});
                        top_badge = "none";
                        toggleChecker[i] = false;
                } else if(i == 0) {
                    if(window.matchMedia("(max-width: 1230px)").matches) {
                        gsap.to("#top_show", 1, {paddingTop:"8.5rem"});
                    } else {
                        gsap.to("#top_show", 1, {paddingTop:"20rem"});
                    }
                    gsap.to(plus[i], 1, {rotation:0});
                    gsap.fromTo(stars[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                    gsap.fromTo(titleCard[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                    gsap.to(synopsis[i], 0, {display:"none"});
                    toggleChecker[i] = true;
                    document.documentElement.style.setProperty("--dynamic-visibility", "unset");
                    gsap.to(buyBtn[i], 0, {display: "none"});
                } else if(toggleChecker[i] == true) {
                        toggleChecker[i] = false;
                        document.documentElement.style.setProperty(`--${i}`, "hidden");
                        gsap.to(`#show_${[i]}`, 1, {paddingTop:"0"});
                        gsap.to(plus[i], 1, {rotation:45});
                        gsap.to(stars[i], 0, {display:"none"});
                        gsap.to(titleCard[i], 0, {display:"none"});
                        gsap.to(synopsis[i], 0, {display:"block"});
                        gsap.to(buyBtn[i], 0, {display:"block"});
                        if (i == 11 && window.matchMedia("(max-width: 1230px)").matches) {
                            gsap.to(`#show_${[i]}`, 1, {height:"15rem"});
                        }
                } else {
                        toggleChecker[i] = true;
                        document.documentElement.style.setProperty(`--${i}`, "unset");
                        gsap.to(`#show_${[i]}`, 1, {paddingTop:"8rem"});
                        gsap.to(plus[i], 1, {rotation:0});
                        gsap.fromTo(stars[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                        gsap.fromTo(titleCard[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                        gsap.to(synopsis[i], 0, {display:"none"});
                        gsap.to(buyBtn[i], 0, {display:"none"});
                        if (i == 11 && window.matchMedia("(max-width: 1230px)").matches) {
                            gsap.to(`#show_${[i]}`, 1, {height:"auto"});
                        }
                        
                }
            });
        }

        let toggleLike = [false, false, false, false, false, false, false, false, false, false, false];
        let like_scope = document.querySelectorAll('.like_scope');

        for(let i = 0; i < resumeBtn.length; i++) {
            like_scope[i].addEventListener("click", (e) => {
                e.stopPropagation();
                    if(toggleLike[i] == false && i == 0) {
                    document.documentElement.style.setProperty("--dynamic-color", "red");
                    toggleLike[i] = true;
                    } else if(i == 0) {
                        document.documentElement.style.setProperty("--dynamic-color", "black");
                        toggleLike[i] = false;
                    } else if(toggleLike[i] == false) {
                        document.documentElement.style.setProperty(`--${i}${i}`, "red");
                        toggleLike[i] = true;
                    } else {
                        document.documentElement.style.setProperty(`--${i}${i}`, "black");
                        toggleLike[i] = false;
                    }
            })
            
            
        };

        window.addEventListener('resize', () => {
            for(let i = 0; i < resumeBtn.length; i++) {
                    if(window.matchMedia("(max-width: 1230px)").matches && toggleChecker[0] == false && i == 0) {
                    gsap.to("#top_show", 1, {paddingTop:"8.5rem"});
                    gsap.to(buyBtn[i], 1, {display: "block",bottom:"auto",left:"12rem",fontSize:"2rem",padding: "0.3rem 0.8rem"});
                    toggleChecker[0] = true;
                } else if(toggleChecker[0] == false && i == 0) {
                    gsap.to("#top_show", 1, {paddingTop:"20rem"});
                    gsap.to(buyBtn[i], 1, {display: "block",bottom:"6rem",left:"4.5rem",fontSize:"1.5rem",padding: "0.5rem 1rem"});
                    toggleChecker[0] = true;
                } else if(window.matchMedia("(max-width: 1230px)").matches && i == 0) {
                    gsap.to(buyBtn[i], 1, {display: "none"});
                    gsap.to("#top_show", 1, {paddingTop:"8.5rem"});
                    gsap.to(plus[i], 1, {rotation:0});
                    gsap.fromTo(stars[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                    gsap.fromTo(titleCard[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                    gsap.to(synopsis[i], 0, {display:"none"});
                } else if(i == 0) {
                    gsap.to(buyBtn[i], 1, {display: "none"});
                    gsap.to("#top_show", 1, {paddingTop:"20rem"});
                    gsap.to(plus[i], 1, {rotation:0});
                    gsap.fromTo(stars[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                    gsap.fromTo(titleCard[i], {display: "block", opacity: 0}, {opacity: 1, duration: 1});
                    gsap.to(synopsis[i], 0, {display:"none"});
                }
            }
            
        });
    }
    
    allowAsync();

    
} else {
    gsap.set(".litem", {x:900})

    gsap.to(".spidermanbox", {x: -900, duration: 1});
    gsap.to(".womenbox", {x: -900, duration: 1, delay: 1});
    gsap.to(".communitybox", {x: -900, duration: 1, delay: 2});
}


    